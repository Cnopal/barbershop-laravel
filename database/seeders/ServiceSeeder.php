<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Haircut',
                'description' => 'Professional haircut tailored to your style.',
                'price' => 25.00,
                'duration' => 45, // in minutes
            ],
            [
                'name' => 'Beard Trim',
                'description' => 'Neat beard trim and shaping.',
                'price' => 15.00,
                'duration' => 30,
            ],
            [
                'name' => 'Shave',
                'description' => 'Smooth and clean shave experience.',
                'price' => 20.00,
                'duration' => 30,
            ],
            [
                'name' => 'Hair Color',
                'description' => 'Change or refresh your hair color.',
                'price' => 50.00,
                'duration' => 90,
            ],
            [
                'name' => 'Hair Wash & Style',
                'description' => 'Wash and style your hair professionally.',
                'price' => 20.00,
                'duration' => 30,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
