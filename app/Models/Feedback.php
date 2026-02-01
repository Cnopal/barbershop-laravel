<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks'; // ✅ IMPORTANT

    protected $fillable = [
        'barber_id',
        'customer_id',
        'rating',
        'comments',
    ];

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
