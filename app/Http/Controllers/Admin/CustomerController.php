<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->with(['customerAppointments' => function($query) {
                $query->with('service', 'barber')->orderBy('appointment_date', 'desc');
            }])
            ->paginate(10);
        
        // total customers
        $totalCustomers = User::where('role', 'customer')->count();
        
        // total appointments
        $totalAppointments = \App\Models\Appointment::whereHas('customer')->count();
        
        // average appointments per customer
        $avgAppointments = $totalCustomers > 0 ? round($totalAppointments / $totalCustomers, 2) : 0;
        
        return view('admin.customers.index', compact('customers', 'totalCustomers', 'totalAppointments', 'avgAppointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        // Get appointments with relationships
        $appointments = $customer->customerAppointments()
            ->with(['service', 'barber'])
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        // Calculate statistics
        $totalAppointments = $appointments->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $upcomingAppointments = $appointments->whereIn('status', ['pending_payment', 'confirmed'])->count();
        $totalSpent = $appointments->where('status', 'completed')->sum('price');

        return view('admin.customers.show', compact(
            'customer', 'appointments', 'totalAppointments', 'completedAppointments', 'upcomingAppointments', 'totalSpent'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $customer)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => $request->password ? Hash::make($request->password) : $customer->password,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    
    }
}
