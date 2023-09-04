<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) return;

        $properties = [
            ['name' => 'Embakasi Tower', 'location' => 'Pipeline',
                'houses' => [
                    ['name' => 'B1', 'deposit' => 80000, 'rent' => 40000, 'goodwill' => 20000],
                    ['name' => 'B2', 'deposit' => 70000, 'rent' => 35000, 'goodwill' => 15000],
                ]
            ],
            ['name' => 'Montana Mall2', 'location' => 'Nairobi CBD',
                'houses' => [
                    ['name' => 'G1', 'deposit' => 30000, 'rent' => 12000, 'goodwill' => 10000],
                    ['name' => 'G2', 'deposit' => 40000, 'rent' => 20000, 'goodwill' => 15000],
                ]
            ],
        ];

        collect($properties)->each(function ($property) {
            $savedProperty = Property::create(['name' => $property['name'], 'location' => $property['location']]);
            $savedProperty->houses()->create(...$property['houses']);
        });

    }
}
