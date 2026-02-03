<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    public function show()
    {
        $user = auth()->user();

        return view('staff.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('staff.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        // Update profile image if uploaded
        if ($request->hasFile('profile_image')) {
            try {
                // Delete old image from Cloudinary if exists
                if ($user->profile_image) {
                    $publicId = $this->cloudinaryService->getPublicIdFromUrl($user->profile_image);
                    if ($publicId) {
                        $this->cloudinaryService->deleteImage($publicId);
                    }
                }

                // Upload new image to Cloudinary
                $file = $request->file('profile_image');
                $imageUrl = $this->cloudinaryService->uploadProfileImage($file, $user->id);

                if ($imageUrl) {
                    $validated['profile_image'] = $imageUrl;
                } else {
                    return back()->withErrors(['profile_image' => 'Failed to upload image to Cloudinary.']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['profile_image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

        // Hash password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('staff.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}