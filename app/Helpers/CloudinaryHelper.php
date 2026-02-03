<?php

use App\Services\CloudinaryService;

/**
 * Get Cloudinary service instance
 *
 * @return CloudinaryService
 */
function cloudinary(): CloudinaryService
{
    return app(CloudinaryService::class);
}

/**
 * Upload profile image to Cloudinary
 *
 * @param \Illuminate\Http\UploadedFile $file
 * @param int $userId
 * @return string|null
 */
function uploadProfileImageToCloudinary($file, int $userId): ?string
{
    return cloudinary()->uploadProfileImage($file, $userId);
}

/**
 * Upload gallery image to Cloudinary
 *
 * @param \Illuminate\Http\UploadedFile $file
 * @param string $category
 * @return string|null
 */
function uploadGalleryImageToCloudinary($file, string $category = 'general'): ?string
{
    return cloudinary()->uploadGalleryImage($file, $category);
}

/**
 * Delete image from Cloudinary
 *
 * @param string $publicId
 * @return bool
 */
function deleteImageFromCloudinary(string $publicId): bool
{
    return cloudinary()->deleteImage($publicId);
}

/**
 * Get public_id from Cloudinary URL
 *
 * @param string $url
 * @return string|null
 */
function getCloudinaryPublicId(string $url): ?string
{
    return cloudinary()->getPublicIdFromUrl($url);
}
