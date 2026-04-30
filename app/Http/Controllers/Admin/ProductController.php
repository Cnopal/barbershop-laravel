<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrderItem;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private CloudinaryService $cloudinaryService)
    {
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'low_stock' => Product::where('stock', '<=', 5)->count(),
            'inventory_value' => Product::all()->sum(fn ($product) => $product->price * $product->stock),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Product::whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedProduct($request);

        if ($request->hasFile('image')) {
            $imageUrl = $this->cloudinaryService->uploadProductImage($request->file('image'), $validated['name']);

            if (!$imageUrl) {
                return back()
                    ->withErrors(['image' => 'Failed to upload product image to Cloudinary.'])
                    ->withInput();
            }

            $validated['image_url'] = $imageUrl;
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->loadCount('orderItems');

        $recentItems = ProductOrderItem::with('order')
            ->where('product_id', $product->id)
            ->latest()
            ->take(8)
            ->get();

        return view('admin.products.show', compact('product', 'recentItems'));
    }

    public function edit(Product $product)
    {
        $categories = Product::whereNotNull('category')
            ->where('id', '!=', $product->id)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validatedProduct($request);

        if ($request->hasFile('image')) {
            $imageUrl = $this->cloudinaryService->uploadProductImage($request->file('image'), $validated['name']);

            if (!$imageUrl) {
                return back()
                    ->withErrors(['image' => 'Failed to upload product image to Cloudinary.'])
                    ->withInput();
            }

            $this->deleteCloudinaryImage($product->image_url);
            $validated['image_url'] = $imageUrl;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteCloudinaryImage($product->image_url);

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function validatedProduct(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'price' => 'required|numeric|min:0|max:999999',
            'stock' => 'required|integer|min:0|max:999999',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['category'] = $validated['category'] ? trim($validated['category']) : null;
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
