<?php

namespace Database\Seeders;

use App\Models\IncidentCategory;
use Illuminate\Database\Seeder;

class IncidentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Traffic',
                'description' => 'Traffic accidents, congestion, and road-related incidents',
            ],
            [
                'name' => 'Camera',
                'description' => 'Camera malfunction or monitoring issues',
            ],
            [
                'name' => 'Network',
                'description' => 'Internet, LAN, or system connectivity problems',
            ],
            [
                'name' => 'Equipment',
                'description' => 'Hardware and device-related incidents',
            ],
            [
                'name' => 'Security',
                'description' => 'Security and safety-related incidents',
            ],
            [
                'name' => 'Environment',
                'description' => 'Waste, flooding, pollution, and environmental issues',
            ],
            [
                'name' => 'Facility',
                'description' => 'Building, room, electricity, and infrastructure issues',
            ],
            [
                'name' => 'Software',
                'description' => 'Software bugs or system errors',
            ],
            [
                'name' => 'Other',
                'description' => 'Other types of incidents',
            ],
        ];

        foreach ($categories as $category) {
            IncidentCategory::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}