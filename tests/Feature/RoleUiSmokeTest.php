<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleUiSmokeTest extends TestCase
{
    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->admin = $this->makeUser('admin', 'ui-admin@example.test');
        $this->staff = $this->makeUser('staff', 'ui-staff@example.test');
        $this->customer = $this->makeUser('customer', 'ui-customer@example.test');

        Service::create([
            'name' => 'UI Smoke Haircut',
            'description' => 'Temporary service for UI smoke testing.',
            'price' => 25,
            'duration' => 30,
            'status' => 'active',
        ]);

        $this->product = Product::create([
            'name' => 'UI Smoke Pomade',
            'description' => 'Temporary product for UI smoke testing.',
            'category' => 'Styling',
            'price' => 35,
            'stock' => 10,
            'status' => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        DB::rollBack();

        parent::tearDown();
    }

    public function test_admin_ui_pages_render(): void
    {
        foreach ([
            '/admin/dashboard',
            '/admin/staffs',
            '/admin/customers',
            '/admin/appointments',
            '/admin/services',
            '/admin/products',
            '/admin/products/create',
            '/admin/products/' . $this->product->id,
            '/admin/products/' . $this->product->id . '/edit',
            '/admin/product-orders',
            '/admin/pos',
            '/admin/pos/orders',
        ] as $uri) {
            $this->actingAs($this->admin)->get($uri)->assertOk();
        }
    }

    public function test_staff_ui_pages_render(): void
    {
        foreach ([
            '/staff/dashboard',
            '/staff/appointments',
            '/staff/schedule',
            '/staff/services',
            '/staff/feedbacks',
            '/staff/products',
            '/staff/products/' . $this->product->id,
            '/staff/product-orders',
            '/staff/pos',
            '/staff/pos/orders',
        ] as $uri) {
            $this->actingAs($this->staff)->get($uri)->assertOk();
        }
    }

    public function test_customer_ui_pages_render(): void
    {
        foreach ([
            '/customer/dashboard',
            '/customer/appointments',
            '/customer/services',
            '/customer/barbers',
            '/customer/ai-hair',
            '/customer/products',
            '/customer/products/' . $this->product->id,
            '/customer/product-orders',
            '/customer/profile',
        ] as $uri) {
            $this->actingAs($this->customer)->get($uri)->assertOk();
        }
    }

    private function makeUser(string $role, string $email): User
    {
        return User::create([
            'name' => 'UI Smoke ' . ucfirst($role),
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
            'phone' => '0100000000',
            'address' => 'Smoke Test',
            'position' => $role === 'staff' ? 'Barber' : null,
            'status' => 'active',
        ]);
    }
}
