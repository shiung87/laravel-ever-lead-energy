<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CatalogSeeder::class,
            VehicleSeeder::class,
            MarketingSeeder::class,
            LeadsAndSettingsSeeder::class,
        ]);
    }
}
