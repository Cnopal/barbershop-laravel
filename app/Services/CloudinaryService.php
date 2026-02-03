<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Asset\Asset;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;

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
        // URL format: https://res.cloudinary.com/cloud_name/image/upload/v123456/folder/public_id.jpg
        preg_match('/\/([^\/]+)\/([^\/]+)$/', parse_url($url, PHP_URL_PATH), $matches);

        if (isset($matches[2])) {
            return $matches[1] . '/' . pathinfo($matches[2], PATHINFO_FILENAME);
        }

        return null;
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
