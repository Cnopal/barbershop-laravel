<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Asset\Asset;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);
    }

    /**
     * Upload profile image to Cloudinary
     *
     * @param UploadedFile $file
     * @param int $userId
     * @return string|null - Returns the secure URL of the uploaded image
     */
    public function uploadProfileImage(UploadedFile $file, int $userId): ?string
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'barberpro/profile_images',
                'public_id' => 'profile_' . $userId . '_' . time(),
                'resource_type' => 'auto',
                'quality' => 'auto',
                'fetch_format' => 'auto',
                'width' => 400,
                'height' => 400,
                'crop' => 'fill',
                'gravity' => 'face',
            ]);

            return $response['secure_url'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Cloudinary Profile Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload gallery image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $galleryName
     * @return string|null - Returns the secure URL of the uploaded image
     */
    public function uploadGalleryImage(UploadedFile $file, string $galleryName = 'general'): ?string
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'barberpro/gallery/' . $galleryName,
                'public_id' => 'gallery_' . time() . '_' . uniqid(),
                'resource_type' => 'auto',
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]);

            return $response['secure_url'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Cloudinary Gallery Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    public function uploadProductImage(UploadedFile $file, string $productName): ?string
    {
        return $this->uploadCatalogImage(
            $file,
            'barberpro/products',
            'product_' . $this->safePublicId($productName),
            [
                'width' => 900,
                'height' => 900,
                'crop' => 'fill',
                'gravity' => 'auto',
            ],
            'Product'
        );
    }

    public function uploadServiceImage(UploadedFile $file, string $serviceName): ?string
    {
        return $this->uploadCatalogImage(
            $file,
            'barberpro/services',
            'service_' . $this->safePublicId($serviceName),
            [
                'width' => 1000,
                'height' => 700,
                'crop' => 'fill',
                'gravity' => 'auto',
            ],
            'Service'
        );
    }

    private function uploadCatalogImage(UploadedFile $file, string $folder, string $publicId, array $options, string $label): ?string
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload($file->getRealPath(), array_merge([
                'folder' => $folder,
                'public_id' => $publicId . '_' . time(),
                'resource_type' => 'auto',
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ], $options));

            return $response['secure_url'] ?? null;
        } catch (\Exception $e) {
            \Log::error("Cloudinary {$label} Upload Error: " . $e->getMessage());
            return null;
        }
    }

    private function safePublicId(string $name): string
    {
        return Str::slug($name) ?: 'image';
    }

    /**
     * Delete image from Cloudinary by public_id
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Cloudinary Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract public_id from Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    public function getPublicIdFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (!$path || !str_contains($path, '/image/upload/')) {
            return null;
        }

        $publicPath = substr($path, strpos($path, '/image/upload/') + strlen('/image/upload/'));
        $publicPath = preg_replace('#^v\d+/#', '', $publicPath);
        $publicPath = preg_replace('/\.[^.\/]+$/', '', $publicPath);

        return $publicPath ? ltrim($publicPath, '/') : null;
    }

    /**
     * Get Cloudinary instance
     *
     * @return Cloudinary
     */
    public function getCloudinary(): Cloudinary
    {
        return $this->cloudinary;
    }
}
