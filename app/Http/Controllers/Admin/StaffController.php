<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct(private CloudinaryService $cloudinaryService)
    {
    }

    public function index()
    {
        $staffs = User::where('role', 'staff')->paginate(12);
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'status' => $request->status,
        ]);

        if ($request->hasFile('profile_image')) {
            $imageUrl = $this->cloudinaryService->uploadProfileImage($request->file('profile_image'), $staff->id);

            if (!$imageUrl) {
                $staff->delete();

                return back()
                    ->withErrors(['profile_image' => 'Failed to upload staff photo to Cloudinary.'])
                    ->withInput();
            }

            $staff->update(['profile_image' => $imageUrl]);
        }

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Staff successfully added');
    }


    public function show(User $staff)
    {
        // Get appointment statistics
        $totalAppointments = $staff->barberAppointments()->count();
        $completedAppointments = $staff->barberAppointments()->where('status', 'completed')->count();
        // $averageRating = $staff->barberAppointments()->whereNotNull('rating')->avg('rating') ?? 0;
        $appointmentRevenue = $staff->barberAppointments()->where('status', 'completed')->sum('price') ?? 0;
        $posRevenue = ProductOrder::paid()->where('staff_id', $staff->id)->sum('total') ?? 0;
        $totalRevenue = $appointmentRevenue + $posRevenue;

        // Add statistics to staff object
        $staff->total_appointments = $totalAppointments;
        $staff->completed_appointments = $completedAppointments;
        // $staff->average_rating = $averageRating;
        $staff->total_revenue = $totalRevenue;

        // Get recent appointments
        $recentAppointments = $staff->barberAppointments()
            ->with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.staffs.show', compact('staff', 'recentAppointments'));



        // In User.php model 
        // public function appointments()
        // {
        //     return $this->hasMany(Appointment::class, 'barber_id');
        // }

        // public function services()
        // {
        //     return $this->belongsToMany(Service::class, 'appointments', 'barber_id', 'service_id')
        //                 ->withPivot(['appointment_date', 'appointment_time', 'status', 'price', 'rating'])
        //                 ->withTimestamps();
        // }



        // return view('admin.staffs.show', compact('staff'));
    }


    public function edit(User $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    /**
     * Update the specified staff in storage.
     */
    public function update(Request $request, User $staff)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $staff->id,
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'position' => 'required|string',
        'role' => 'required|in:staff,senior,manager',
        'status' => 'required|in:active,inactive',
        'bio' => 'nullable|string',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'password' => 'nullable|min:8|confirmed',
        'custom_position' => 'nullable|string|max:255',
    ]);

    // Handle custom position
    if ($request->position === 'Other' && $request->filled('custom_position')) {
        $validated['position'] = $request->custom_position;
    }

    unset($validated['custom_position']);

    if ($request->input('remove_profile_image') === '1') {
        $this->deleteCloudinaryImage($staff->profile_image);
        $validated['profile_image'] = null;
    } elseif ($request->hasFile('profile_image')) {
        $imageUrl = $this->cloudinaryService->uploadProfileImage($request->file('profile_image'), $staff->id);

        if (!$imageUrl) {
            return back()
                ->withErrors(['profile_image' => 'Failed to upload staff photo to Cloudinary.'])
                ->withInput();
        }

        $this->deleteCloudinaryImage($staff->profile_image);
        $validated['profile_image'] = $imageUrl;
    } else {
        unset($validated['profile_image']);
    }

    // Update password only if provided
    if ($request->filled('password')) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $staff->update($validated);

    return redirect()->route('admin.staffs.show', $staff)
        ->with('success', 'Barber updated successfully!');
}

    /**
     * Remove the specified staff from storage.
     */
    public function destroy(User $staff)
    {
        $this->deleteCloudinaryImage($staff->profile_image);

        $staff->delete();

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Staff successfully deleted');
    }

    private function deleteCloudinaryImage(?string $imageUrl): void
    {
        if (!$imageUrl) {
            return;
        }

        $publicId = $this->cloudinaryService->getPublicIdFromUrl($imageUrl);

        if ($publicId) {
            $this->cloudinaryService->deleteImage($publicId);
        }
    }
}
