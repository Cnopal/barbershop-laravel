<?php

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),

    // Default folders for different upload types
    'folders' => [
        'profile_images' => 'barberpro/profile_images',
        'gallery' => 'barberpro/gallery',
        'appointments' => 'barberpro/appointments',
        'temporary' => 'barberpro/temp',
    ],

    // Upload options
    'upload_options' => [
        'profile_image' => [
            'quality' => 'auto',
            'fetch_format' => 'auto',
            'width' => 400,
            'height' => 400,
            'crop' => 'fill',
            'gravity' => 'face',
        ],
        'gallery' => [
            'quality' => 'auto',
            'fetch_format' => 'auto',
        ],
    ],

    // Max file sizes (in bytes)
    'max_file_sizes' => [
        'profile_image' => 2048 * 1024, // 2MB
        'gallery' => 5120 * 1024, // 5MB
    ],
];
