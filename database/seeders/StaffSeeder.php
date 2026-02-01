<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Ahmad Kamarul',
            'email' => 'kamrul@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0126523141',
            'address' => 'Jalan Bukit Bintang, Muar, Jasin',
            'position' => 'Senior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Ahmad Syafiq',
            'email' => 'syafiq@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0126523141',
            'address' => 'Jalan Bukit Baldu, Muar, Jasin',
            'position' => 'Senior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Ahmad Syafiq Zufail',
            'email' => 'syafiqZufail@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0126523141',
            'address' => 'Jalan Bukit Baldu, Muar, Jasin',
            'position' => 'Senior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Muhammad Kamarul Ain',
            'email' => 'Ain@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0126523141',
            'address' => 'Jalan Bukit Sempit, Muar, Jasin',
            'position' => 'Senior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Syafiq Kyle',
            'email' => 'Kyle@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0146523141',
            'address' => 'Jalan Bukit Luasnya, Muar, Jasin',
            'position' => 'Junior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Kamal Adli',
            'email' => 'adli@gmail.com',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'phone' => '0126523141',
            'address' => 'Jalan Bukit Baldu, Muar, Jasin',
            'position' => 'Junior Barber',
            'profile_image' => 'nullable',
            'status' => 'active',
        ]);
    }
}
