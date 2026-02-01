<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CustomerBarberController extends Controller
{
    /**
     * Display a listing of all barbers.
     */
    public function index()
    {
        $barbers = User::where('role', 'staff')
            ->where('status', 'active')
            ->get();
        
        return view('customer.barbers.index', compact('barbers'));
    }

    /**
     * Display barber details.
     */
    public function show(string $id)
    {
        $barber = User::where('role', 'staff')
            ->where('id', $id)
            ->firstOrFail();
        
        // Get appointment count and ratings
        $appointmentCount = Appointment::where('barber_id', $id)->count();
        $completedAppointments = Appointment::where('barber_id', $id)
            ->where('status', 'completed')
            ->count();
        
        return view('customer.barbers.show', compact('barber', 'appointmentCount', 'completedAppointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
