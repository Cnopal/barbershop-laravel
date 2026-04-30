<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\CloudinaryService;

class ServiceController extends Controller
{
    public function __construct(private CloudinaryService $cloudinaryService)
    {
    }

    public function index()
    {
        $services = Service::paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedService($request);

        if ($request->hasFile('image')) {
            $imageUrl = $this->cloudinaryService->uploadServiceImage($request->file('image'), $validated['name']);

            if (!$imageUrl) {
                return back()
                    ->withErrors(['image' => 'Failed to upload service image to Cloudinary.'])
                    ->withInput();
            }

            $validated['image_url'] = $imageUrl;
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        // // Load appointments count if needed
        // $service->loadCount('appointments');

        // // Get recent appointments (optional)
        // $recentAppointments = $service->appointments()
        //     ->with('user')
        //     ->latest()
        //     ->take(5)
        //     ->get();
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $this->validatedService($request);

        if ($request->hasFile('image')) {
            $imageUrl = $this->cloudinaryService->uploadServiceImage($request->file('image'), $validated['name']);

            if (!$imageUrl) {
                return back()
                    ->withErrors(['image' => 'Failed to upload service image to Cloudinary.'])
                    ->withInput();
            }

            $this->deleteCloudinaryImage($service->image_url);
            $validated['image_url'] = $imageUrl;
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->deleteCloudinaryImage($service->image_url);
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    private function validatedService(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        unset($validated['image']);

        return $validated;
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
