<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show admin profile
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update admin profile
     */
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
                // Create upload directory if it doesn't exist
                $uploadDir = public_path('uploads/profile_images');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Delete old profile image if exists
                if ($user->profile_image) {
                    // Try to delete from public folder
                    $oldPath = public_path($user->profile_image);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                
                // Store in public folder for universal hosting compatibility
                $file = $request->file('profile_image');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Move file to public folder
                if ($file->move($uploadDir, $filename)) {
                    $validated['profile_image'] = 'uploads/profile_images/' . $filename;
                } else {
                    return redirect()->back()->with('error', 'Failed to move uploaded file.');
                }
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
            }
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully!');
    }
}
