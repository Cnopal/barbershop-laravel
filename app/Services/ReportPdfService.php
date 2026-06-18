<?php

namespace App\Services;

class ReportPdfService
{
    private const PAGE_WIDTH = 842.0;
    private const PAGE_HEIGHT = 595.0;
    private const MARGIN = 36.0;
    private const FOOTER_HEIGHT = 24.0;

    private array $pages = [];
    private array $payload = [];
    private int $pageNumber = 0;
    private float $y = 0.0;

    public function render(array $payload): string
    {
        $this->pages = [];
        $this->payload = $payload;
        $this->pageNumber = 0;

        $this->newPage();
        $this->drawReportHeader();
        $this->drawSummary();
        $this->drawTable();

        return $this->buildPdf();
    }

    private function newPage(): void
    {
        $this->pages[] = [];
        $this->pageNumber++;
        $this->y = self::PAGE_HEIGHT - self::MARGIN;

        if ($this->pageNumber > 1) {
            $this->text(self::MARGIN, $this->y, $this->payload['title'] ?? 'Report', 12, 'F2');
            $this->text(
                self::PAGE_WIDTH - self::MARGIN - 240,
                $this->y,
                $this->periodLabel(),
                9,
                'F1',
                'right',
                240
            );
            $this->y -= 18;
            $this->line(self::MARGIN, $this->y, self::PAGE_WIDTH - self::MARGIN, $this->y, [0.82, 0.86, 0.90]);
            $this->y -= 16;
        }
    }

    private function drawReportHeader(): void
    {
        $generatedAt = now('Asia/Kuala_Lumpur')->format('d M Y, h:i A');

        $this->text(self::MARGIN, $this->y, $this->payload['title'] ?? 'Report', 22, 'F2', 'left', 500, [0.10, 0.13, 0.23]);
        $this->y -= 22;
        $this->text(self::MARGIN, $this->y, $this->payload['description'] ?? '', 10, 'F1', 'left', 560, [0.32, 0.36, 0.43]);
        $this->y -= 18;
        $this->text(self::MARGIN, $this->y, 'Period: ' . $this->periodLabel(), 10, 'F2');
        $this->text(self::PAGE_WIDTH - self::MARGIN - 260, $this->y, 'Generated: ' . $generatedAt, 10, 'F1', 'right', 260);
        $this->y -= 18;
        $this->line(self::MARGIN, $this->y, self::PAGE_WIDTH - self::MARGIN, $this->y, [0.82, 0.86, 0.90]);
        $this->y -= 22;
    }

    private function drawSummary(): void
    {
        $summary = $this->payload['summary'] ?? [];

        if (count($summary) === 0) {
            return;
        }

        $items = array_map(
            fn ($label, $value) => ['label' => (string) $label, 'value' => (string) $value],
            array_keys($summary),
            array_values($summary)
        );

        $columns = min(3, max(1, count($items)));
        $gap = 10.0;
        $boxWidth = ($this->contentWidth() - ($gap * ($columns - 1))) / $columns;
        $boxHeight = 44.0;

        foreach (array_chunk($items, $columns) as $chunk) {
            $this->ensureSpace($boxHeight + 10);
            $x = self::MARGIN;

            foreach ($chunk as $item) {
                $this->rect($x, $this->y - $boxHeight, $boxWidth, $boxHeight, [0.96, 0.97, 0.98], [0.86, 0.89, 0.93]);
                $this->text($x + 10, $this->y - 17, $item['label'], 8, 'F2', 'left', $boxWidth - 20, [0.38, 0.43, 0.50]);
                $this->text($x + 10, $this->y - 34, $item['value'], 14, 'F2', 'left', $boxWidth - 20, [0.10, 0.13, 0.23]);
                $x += $boxWidth + $gap;
            }

            $this->y -= $boxHeight + 12;
        }
    }

    private function drawTable(): void
    {
        $columns = array_values($this->payload['columns'] ?? []);
        $rows = collect($this->payload['rows'] ?? [])->values();
        $widths = $this->columnWidths($columns);

        $this->ensureSpace(56);
        $this->text(self::MARGIN, $this->y, 'Report Data', 13, 'F2', 'left', 220, [0.10, 0.13, 0.23]);
        $this->y -= 18;
        $this->drawTableHeader($columns, $widths);

        if ($rows->isEmpty()) {
            $this->ensureSpace(30);
            $this->rect(self::MARGIN, $this->y - 28, $this->contentWidth(), 28, [1, 1, 1], [0.88, 0.90, 0.93]);
            $this->text(self::MARGIN + 12, $this->y - 18, 'No report data for this period.', 10, 'F1', 'left', $this->contentWidth() - 24, [0.38, 0.43, 0.50]);
            $this->y -= 28;
            return;
        }

        foreach ($rows as $index => $row) {
            $this->ensureSpace(24, fn () => $this->drawTableHeader($columns, $widths));
            $this->drawTableRow((array) $row, $columns, $widths, $index % 2 === 1);
        }
    }

    private function drawTableHeader(array $columns, array $widths): void
    {
        $height = 24.0;
        $x = self::MARGIN;

        $this->rect($x, $this->y - $height, $this->contentWidth(), $height, [0.10, 0.13, 0.23]);

        foreach ($columns as $index => $column) {
            $this->text($x + 6, $this->y - 15, $column['label'] ?? '', 8, 'F2', 'left', $widths[$index] - 12, [1, 1, 1]);
            $x += $widths[$index];
        }

        $this->y -= $height;
    }

    private function drawTableRow(array $row, array $columns, array $widths, bool $muted): void
    {
        $height = 24.0;
        $x = self::MARGIN;
        $background = $muted ? [0.98, 0.99, 1] : [1, 1, 1];

        $this->rect($x, $this->y - $height, $this->contentWidth(), $height, $background, [0.90, 0.92, 0.95]);

        foreach ($columns as $index => $column) {
            $key = $column['key'] ?? '';
            $align = ($column['align'] ?? '') === 'right' ? 'right' : 'left';
            $value = (string) ($row[$key] ?? '-');
            $cellWidth = $widths[$index];
            $textX = $align === 'right' ? $x + 6 : $x + 6;

            $this->text($textX, $this->y - 15, $value, 8.5, 'F1', $align, $cellWidth - 12, [0.12, 0.15, 0.22]);
            $x += $cellWidth;
        }

        $this->y -= $height;
    }

    private function ensureSpace(float $height, ?callable $afterNewPage = null): void
    {
        if ($this->y - $height >= self::MARGIN + self::FOOTER_HEIGHT) {
            return;
        }

        $this->newPage();

        if ($afterNewPage) {
            $afterNewPage();
        }
    }

    private function columnWidths(array $columns): array
    {
        $weights = array_map(function ($column) {
            return ($column['align'] ?? '') === 'right' ? 1.0 : 1.7;
        }, $columns);

        $totalWeight = array_sum($weights) ?: 1;

        return array_map(fn ($weight) => $this->contentWidth() * ($weight / $totalWeight), $weights);
    }

    private function periodLabel(): string
    {
        $start = isset($this->payload['startDate']) ? date('d M Y', strtotime($this->payload['startDate'])) : '-';
        $end = isset($this->payload['endDate']) ? date('d M Y', strtotime($this->payload['endDate'])) : '-';

        return $start . ' to ' . $end;
    }

    private function contentWidth(): float
    {
        return self::PAGE_WIDTH - (self::MARGIN * 2);
    }

    private function rect(float $x, float $y, float $width, float $height, array $fill, ?array $stroke = null): void
    {
        $command = 'q ' . $this->rgb($fill, 'rg') . ' ' .
            $this->n($x) . ' ' . $this->n($y) . ' ' . $this->n($width) . ' ' . $this->n($height) . ' re f Q';

        $this->add($command);

        if ($stroke) {
            $this->add('q ' . $this->rgb($stroke, 'RG') . ' ' .
                $this->n($x) . ' ' . $this->n($y) . ' ' . $this->n($width) . ' ' . $this->n($height) . ' re S Q');
        }
    }

    private function line(float $x1, float $y1, float $x2, float $y2, array $stroke): void
    {
        $this->add('q ' . $this->rgb($stroke, 'RG') . ' 0.8 w ' .
            $this->n($x1) . ' ' . $this->n($y1) . ' m ' .
            $this->n($x2) . ' ' . $this->n($y2) . ' l S Q');
    }

    private function text(
        float $x,
        float $y,
        string $text,
        float $size = 10,
        string $font = 'F1',
        string $align = 'left',
        ?float $maxWidth = null,
        array $color = [0, 0, 0]
    ): void {
        $text = $this->fitText($this->cleanText($text), $maxWidth, $size);

        if ($align === 'right' && $maxWidth !== null) {
            $x += max(0, $maxWidth - $this->estimateTextWidth($text, $size));
        }

        $this->add('BT /' . $font . ' ' . $this->n($size) . ' Tf ' .
            $this->rgb($color, 'rg') . ' 1 0 0 1 ' .
            $this->n($x) . ' ' . $this->n($y) . ' Tm (' . $this->escapeText($text) . ') Tj ET');
    }

    private function fitText(string $text, ?float $maxWidth, float $size): string
    {
        if ($maxWidth === null || $this->estimateTextWidth($text, $size) <= $maxWidth) {
            return $text;
        }

        $ellipsis = '...';

        while ($text !== '' && $this->estimateTextWidth($text . $ellipsis, $size) > $maxWidth) {
            $text = substr($text, 0, -1);
        }

        return rtrim($text) . $ellipsis;
    }

    private function estimateTextWidth(string $text, float $size): float
    {
        return strlen($text) * $size * 0.48;
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? '';
        $text = str_replace(['–', '—'], '-', $text);
        $text = preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';

        return $text;
    }

    private function escapeText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function rgb(array $color, string $operator): string
    {
        return $this->n((float) ($color[0] ?? 0)) . ' ' .
            $this->n((float) ($color[1] ?? 0)) . ' ' .
            $this->n((float) ($color[2] ?? 0)) . ' ' . $operator;
    }

    private function n(float $value): string
    {
        $formatted = number_format($value, 3, '.', '');

        return rtrim(rtrim($formatted, '0'), '.') ?: '0';
    }

    private function add(string $command): void
    {
        $this->pages[$this->pageNumber - 1][] = $command;
    }

    private function footerCommands(int $page, int $total): array
    {
        $label = 'Page ' . $page . ' of ' . $total;
        $x = self::PAGE_WIDTH - self::MARGIN - 110;
        $y = 22.0;

        return [
            'q ' . $this->rgb([0.82, 0.86, 0.90], 'RG') . ' 0.8 w ' .
            $this->n(self::MARGIN) . ' 34 m ' . $this->n(self::PAGE_WIDTH - self::MARGIN) . ' 34 l S Q',
            'BT /F1 8 Tf ' . $this->rgb([0.38, 0.43, 0.50], 'rg') . ' 1 0 0 1 ' .
            $this->n(self::MARGIN) . ' ' . $this->n($y) . ' Tm (Hair Salon Management System) Tj ET',
            'BT /F1 8 Tf ' . $this->rgb([0.38, 0.43, 0.50], 'rg') . ' 1 0 0 1 ' .
            $this->n($x) . ' ' . $this->n($y) . ' Tm (' . $this->escapeText($label) . ') Tj ET',
        ];
    }

    private function buildPdf(): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
        ];
        $kids = [];
        $nextObject = 5;
        $totalPages = count($this->pages);

        foreach ($this->pages as $index => $commands) {
            $content = implode("\n", array_merge($commands, $this->footerCommands($index + 1, $totalPages)));
            $contentObject = $nextObject++;
            $pageObject = $nextObject++;

            $objects[$contentObject] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' .
                $this->n(self::PAGE_WIDTH) . ' ' . $this->n(self::PAGE_HEIGHT) .
                '] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents ' .
                $contentObject . ' 0 R >>';
            $kids[] = $pageObject . ' 0 R';
        }

        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $totalPages . ' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0 => 0];

        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $body . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $maxObject = max(array_keys($objects));

        $pdf .= "xref\n0 " . ($maxObject + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $maxObject; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }

        $pdf .= "trailer\n<< /Size " . ($maxObject + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }
}
