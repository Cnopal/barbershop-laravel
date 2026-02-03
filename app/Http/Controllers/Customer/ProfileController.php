<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    // Show the customer profile
    public function show()
    {
        $user = Auth::user();
        return view('customer.profile.show', compact('user'));
    }

    // Show the edit form
    public function edit()
    {
        $user = Auth::user();
        return view('customer.profile.edit', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
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

        // Update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('customer.profile.show')->with('success', 'Profile updated successfully!');
    }
}
