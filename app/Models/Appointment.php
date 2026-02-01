<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'barber_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'price',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'price' => 'decimal:2',
    ];

    /* =========================
        RELATIONSHIPS
    ========================== */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /* =========================
        QUERY SCOPES
    ========================== */

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())
                     ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('appointment_date', [$start, $end]);
    }



    
}
