@php
    $customer = $appointment->customer;
    $service = $appointment->service;
    $barber = $appointment->barber;
    $appointmentDate = $appointment->appointment_date?->format('l, d M Y');
    $startTime = $appointment->start_time ? date('h:i A', strtotime($appointment->start_time)) : '-';
    $endTime = $appointment->end_time ? date('h:i A', strtotime($appointment->end_time)) : '-';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
</head>
<body style="margin:0; padding:0; background:#f5f1e8; font-family:Arial, Helvetica, sans-serif; color:#1f1f1f;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f1e8; padding:32px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #eadfca;">
                    <tr>
                        <td style="padding:28px 28px 20px; background:#0f1115; color:#ffffff;">
                            <div style="font-size:13px; letter-spacing:1.8px; text-transform:uppercase; color:#d4af37; font-weight:700;">Men's Club</div>
                            <h1 style="margin:12px 0 0; font-size:28px; line-height:1.2;">Appointment confirmed</h1>
                            <p style="margin:10px 0 0; color:#d8d8d8; line-height:1.6;">Hi {{ $customer->name ?? 'there' }}, your payment was successful and your appointment is now confirmed.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:26px 28px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777; width:38%;">Booking for</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $appointment->recipient_display_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777;">Service</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $service->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777;">Barber</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $barber->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777;">Date</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $appointmentDate }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777;">Time</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $startTime }} - {{ $endTime }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; color:#777;">Amount paid</td>
                                    <td style="padding:14px 0; border-bottom:1px solid #eee7da; font-weight:700;">RM {{ number_format((float) $appointment->price, 2) }}</td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; line-height:1.7; color:#555;">Please arrive a few minutes early. If you need to cancel or review your appointment, you can open your appointment page below.</p>

                            <div style="margin-top:24px;">
                                <a href="{{ route('customer.appointments.show', $appointment) }}" style="display:inline-block; padding:13px 18px; border-radius:8px; background:#d4af37; color:#111111; text-decoration:none; font-weight:700;">View appointment</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px; background:#fbf8ef; color:#777; font-size:13px; line-height:1.6;">
                            This email was sent automatically after a successful appointment payment.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>