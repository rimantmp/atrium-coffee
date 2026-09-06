<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = env('DEMO_PASSWORD', 'password');
        foreach ([
            ['Admin Atrium', 'admin@atrium.test', 'ADMIN'],
            ['Kasir Atrium', 'kasir@atrium.test', 'CASHIER'],
            ['Dapur Atrium', 'dapur@atrium.test', 'KITCHEN'],
            ['Pemilik Atrium', 'pemilik@atrium.test', 'OWNER'],
        ] as [$name, $email, $role]) {
            User::updateOrCreate(['email' => $email], ['name' => $name, 'role' => $role, 'password' => $password, 'is_active' => true]);
        }

        $catalog = [
            'Kopi' => [['ESP-001', 'Espresso', 'Ekstraksi kopi pekat dengan aroma kuat.', 18000, 4], ['CAP-001', 'Cappuccino', 'Espresso, susu hangat, dan microfoam lembut.', 25000, 6], ['LAT-001', 'Cafe Latte', 'Kopi susu lembut untuk teman bersantai.', 26000, 6], ['AME-001', 'Americano', 'Espresso dan air panas dengan karakter bersih.', 20000, 4]],
            'Non Kopi' => [['CHO-001', 'Chocolate', 'Cokelat premium dengan susu hangat.', 24000, 5], ['MAT-001', 'Matcha Latte', 'Matcha creamy dengan rasa seimbang.', 27000, 6]],
            'Makanan' => [['CRO-001', 'Butter Croissant', 'Croissant renyah dengan aroma butter.', 18000, 5], ['SAN-001', 'Chicken Sandwich', 'Roti panggang dan isian ayam gurih.', 32000, 9]],
        ];

        foreach ($catalog as $categoryName => $items) {
            $category = Category::updateOrCreate(['name' => $categoryName], ['slug' => str($categoryName)->slug(), 'sort_order' => array_search($categoryName, array_keys($catalog)), 'is_active' => true]);
            foreach ($items as [$sku, $name, $description, $price, $minutes]) {
                Menu::updateOrCreate(['sku' => $sku], ['category_id' => $category->id, 'name' => $name, 'description' => $description, 'price' => $price, 'estimated_prep_minutes' => $minutes, 'is_available' => true, 'is_active' => true]);
            }
        }

        foreach (['default_priority' => 3, 'minimum_priority' => 1, 'aging_interval_minutes' => 10, 'overdue_minutes' => 30] as $key => $value) {
            \DB::table('system_settings')->updateOrInsert(['key' => $key], ['value' => json_encode($value), 'description' => 'Konfigurasi awal Atrium Coffee', 'created_at' => now(), 'updated_at' => now()]);
        }

        $this->call(DummyOrderSeeder::class);
    }
}
