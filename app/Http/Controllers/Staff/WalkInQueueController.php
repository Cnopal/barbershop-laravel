<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\WalkInQueue;
use App\Services\WalkInQueueService;
use Illuminate\Http\Request;

class WalkInQueueController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', today('Asia/Kuala_Lumpur')->toDateString());

        $queues = WalkInQueue::with(['customer', 'barber', 'service'])
            ->whereDate('queue_date', $date)
            ->orderBy('queue_number')
            ->get();

        return view('staff.walk_ins.index', [
            'queues' => $queues,
            'customers' => User::where('role', 'customer')->orderBy('name')->get(),
            'barbers' => User::where('role', 'staff')->where('status', 'active')->orderBy('name')->get(),
            'services' => Service::where('status', 'active')->orderBy('name')->get(),
            'date' => $date,
            'routePrefix' => 'staff',
        ]);
    }

    public function store(Request $request, WalkInQueueService $queueService)
    {
        $payload = $this->validatedPayload($request);
        $queueService->create($payload);

        return redirect()
            ->route('staff.walk-ins.index')
            ->with('success', 'Walk-in customer added to queue.');
    }

    public function updateStatus(Request $request, WalkInQueue $walkIn, WalkInQueueService $queueService)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting,serving,completed,skipped',
        ]);

        $queueService->updateStatus($walkIn, $validated['status']);

        return back()->with('success', 'Queue status updated.');
    }

    public function destroy(WalkInQueue $walkIn, WalkInQueueService $queueService)
    {
        $date = $walkIn->queue_date->toDateString();
        $walkIn->delete();
        $queueService->recalculateEstimates($date);

        return back()->with('success', 'Queue entry removed.');
    }

    private function validatedPayload(Request $request): array
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:registered,guest',
            'customer_id' => 'nullable|required_if:customer_type,registered|exists:users,id',
            'customer_name' => 'nullable|required_if:customer_type,guest|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'recipient_age' => 'nullable|integer|min:0|max:120',
            'barber_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['customer_type'] === 'registered') {
            $customer = User::where('role', 'customer')->findOrFail($validated['customer_id']);
            $validated['customer_name'] = $customer->name;
            $validated['customer_phone'] = $customer->phone;
        } else {
            $validated['customer_id'] = null;
        }

        if (empty($validated['barber_id'])) {
            $validated['barber_id'] = auth()->id();
        }

        return $validated;
    }
}
