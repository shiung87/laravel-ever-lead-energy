<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\VehicleBrand;
use App\Models\VehicleCompatibility;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::pluck('id', 'slug');

        $catalog = [
            'Perodua' => [
                'country' => 'Malaysia',
                'models' => [
                    ['name' => 'Myvi', 'type' => 'Hatchback', 'from' => 2011, 'to' => 2026, 'compat' => [
                        ['slug' => 'everlead-ultracharge-ns40zl', 'from' => 2011, 'to' => 2017, 'engine' => '1.3L / 1.5L', 'fuel' => 'Petrol', 'rec' => true],
                        ['slug' => 'everlead-ecostart-m42r-efb', 'from' => 2018, 'to' => 2026, 'engine' => '1.3L / 1.5L Eco-Idle', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Ativa', 'type' => 'SUV', 'from' => 2021, 'to' => 2026, 'compat' => [
                        ['slug' => 'everlead-ecostart-m42r-efb', 'from' => 2021, 'to' => 2026, 'engine' => '1.0L Turbo', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Bezza', 'type' => 'Sedan', 'from' => 2016, 'to' => 2026, 'compat' => [
                        ['slug' => 'everlead-ultracharge-ns40zl', 'from' => 2016, 'to' => 2026, 'engine' => '1.0L / 1.3L', 'fuel' => 'Petrol', 'rec' => true],
                        ['slug' => 'everlead-ecostart-m42r-efb', 'from' => 2020, 'to' => 2026, 'engine' => '1.3L Eco-Idle', 'fuel' => 'Petrol', 'rec' => false],
                    ]],
                ],
            ],
            'Proton' => [
                'country' => 'Malaysia',
                'models' => [
                    ['name' => 'X50', 'type' => 'SUV', 'from' => 2020, 'to' => 2026, 'compat' => [
                        ['slug' => 'varta-blue-dynamic-din55', 'from' => 2020, 'to' => 2026, 'engine' => '1.5L Turbo', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'X70', 'type' => 'SUV', 'from' => 2018, 'to' => 2026, 'compat' => [
                        ['slug' => 'century-marathoner-max-din75l', 'from' => 2018, 'to' => 2026, 'engine' => '1.5L / 1.8L Turbo', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Saga', 'type' => 'Sedan', 'from' => 2016, 'to' => 2026, 'compat' => [
                        ['slug' => 'varta-blue-dynamic-din55', 'from' => 2019, 'to' => 2026, 'engine' => '1.3L VVT', 'fuel' => 'Petrol', 'rec' => true],
                        ['slug' => 'amaron-hi-life-pro-ns60l', 'from' => 2016, 'to' => 2018, 'engine' => '1.3L VVT', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                ],
            ],
            'Toyota' => [
                'country' => 'Japan',
                'models' => [
                    ['name' => 'Vios', 'type' => 'Sedan', 'from' => 2013, 'to' => 2026, 'compat' => [
                        ['slug' => 'amaron-hi-life-pro-ns60l', 'from' => 2013, 'to' => 2026, 'engine' => '1.5L Dual VVT-i', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Camry', 'type' => 'Sedan', 'from' => 2015, 'to' => 2026, 'compat' => [
                        ['slug' => 'bosch-megapower-55d23l', 'from' => 2015, 'to' => 2026, 'engine' => '2.0L / 2.5L', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Hilux', 'type' => 'Pickup', 'from' => 2016, 'to' => 2026, 'compat' => [
                        ['slug' => 'century-marathoner-max-din75l', 'from' => 2016, 'to' => 2026, 'engine' => '2.4L / 2.8L Turbo Diesel', 'fuel' => 'Diesel', 'rec' => true],
                    ]],
                ],
            ],
            'Honda' => [
                'country' => 'Japan',
                'models' => [
                    ['name' => 'City', 'type' => 'Sedan', 'from' => 2014, 'to' => 2026, 'compat' => [
                        ['slug' => 'everlead-ultracharge-ns40zl', 'from' => 2014, 'to' => 2026, 'engine' => '1.5L i-VTEC', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'Civic', 'type' => 'Sedan', 'from' => 2016, 'to' => 2026, 'compat' => [
                        ['slug' => 'amaron-hi-life-pro-ns60l', 'from' => 2016, 'to' => 2026, 'engine' => '1.5L VTEC Turbo / 1.8L', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                    ['name' => 'CR-V', 'type' => 'SUV', 'from' => 2017, 'to' => 2026, 'compat' => [
                        ['slug' => 'bosch-megapower-55d23l', 'from' => 2017, 'to' => 2026, 'engine' => '1.5L Turbo / 2.0L', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                ],
            ],
            'Mazda' => [
                'country' => 'Japan',
                'models' => [
                    ['name' => 'CX-5', 'type' => 'SUV', 'from' => 2015, 'to' => 2026, 'compat' => [
                        ['slug' => 'everlead-prodrive-q85-efb', 'from' => 2015, 'to' => 2026, 'engine' => '2.0L / 2.5L Skyactiv i-Stop', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                ],
            ],
            'BMW' => [
                'country' => 'Germany',
                'models' => [
                    ['name' => '3 Series (F30 / G20)', 'type' => 'Sedan', 'from' => 2014, 'to' => 2026, 'compat' => [
                        ['slug' => 'varta-silver-dynamic-agm-ln3', 'from' => 2014, 'to' => 2026, 'engine' => '2.0L TwinPower Turbo', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                ],
            ],
            'Mercedes-Benz' => [
                'country' => 'Germany',
                'models' => [
                    ['name' => 'C-Class (W205 / W206)', 'type' => 'Sedan', 'from' => 2015, 'to' => 2026, 'compat' => [
                        ['slug' => 'varta-silver-dynamic-agm-ln3', 'from' => 2015, 'to' => 2026, 'engine' => '1.5L / 2.0L Turbo', 'fuel' => 'Petrol', 'rec' => true],
                    ]],
                ],
            ],
        ];

        $sort = 1;
        foreach ($catalog as $brandName => $brandData) {
            $brand = VehicleBrand::updateOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName, 'country' => $brandData['country'], 'sort_order' => $sort++, 'is_active' => true]
            );

            foreach ($brandData['models'] as $m) {
                $model = VehicleModel::updateOrCreate(
                    ['vehicle_brand_id' => $brand->id, 'slug' => Str::slug($m['name'])],
                    [
                        'name' => $m['name'],
                        'vehicle_type' => $m['type'],
                        'year_from' => $m['from'],
                        'year_to' => $m['to'],
                        'is_active' => true,
                    ]
                );

                foreach ($m['compat'] as $c) {
                    if (! isset($products[$c['slug']])) {
                        continue;
                    }
                    VehicleCompatibility::updateOrCreate(
                        [
                            'vehicle_model_id' => $model->id,
                            'product_id' => $products[$c['slug']],
                            'engine_capacity' => $c['engine'],
                        ],
                        [
                            'year_from' => $c['from'],
                            'year_to' => $c['to'],
                            'fuel_type' => $c['fuel'],
                            'is_recommended' => $c['rec'],
                        ]
                    );
                }
            }
        }
    }
}
