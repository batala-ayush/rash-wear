<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CatalogSeeder::class);

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'role' => 'customer', 'status' => 'active', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'owner@rashwear.com.np'],
            ['name' => 'Rashwear Owner', 'role' => 'owner', 'status' => 'active', 'password' => Hash::make('rashwear-owner-2026')]
        );

        User::updateOrCreate(
            ['email' => 'manager@rashwear.com.np'],
            ['name' => 'Shop Manager', 'role' => 'shop_manager', 'status' => 'active', 'password' => Hash::make('rashwear-manager-2026')]
        );

        foreach ([
            'maintenance' => false,
            'disableCheckout' => false,
            'contactEmail' => 'hello@rashwear.com.np',
            'contactPhone' => '+977-98-0000-0000',
            'whatsappLink' => 'https://wa.me/9779800000000',
        ] as $key => $value) {
            DB::table('store_settings')->updateOrInsert(['key' => $key], ['value' => json_encode($value), 'updated_at' => now(), 'created_at' => now()]);
        }
    }
}
