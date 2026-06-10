<?php

namespace Database\Seeders;

use App\Models\ComplainType;
use Illuminate\Database\Seeder;

class ComplainTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complainTypes = [
            // Complain Types
            [
                'name' => 'Buyer Complain',
                'code' => 'BUYER',
                'type' => 'complain',
                'note' => 'Complains related to buyers, orders, or client issues',
                'status' => true,
            ],
            [
                'name' => 'Sewing Complain',
                'code' => 'SEWING',
                'type' => 'complain',
                'note' => 'Complains related to sewing department operations',
                'status' => true,
            ],
            [
                'name' => 'Quality Complain',
                'code' => 'QUALITY',
                'type' => 'complain',
                'note' => 'Complains related to product quality and inspection',
                'status' => true,
            ],
            [
                'name' => 'Any Other Complain',
                'code' => 'OTHER',
                'type' => 'complain',
                'note' => 'Miscellaneous complains not covered by other types',
                'status' => true,
            ],

            // Manual Types
            [
                'name' => 'Sewing Manual',
                'code' => 'SEW-MAN',
                'type' => 'manual',
                'note' => 'Sewing department manuals and guidelines',
                'status' => true,
            ],
            [
                'name' => 'Quality Manual',
                'code' => 'QA-MAN',
                'type' => 'manual',
                'note' => 'Quality assurance manuals and procedures',
                'status' => true,
            ],
            [
                'name' => 'SOP Manual',
                'code' => 'SOP-MAN',
                'type' => 'manual',
                'note' => 'Standard Operating Procedures manuals',
                'status' => true,
            ],
            [
                'name' => 'Technical Manual',
                'code' => 'TECH-MAN',
                'type' => 'manual',
                'note' => 'Technical specifications and guidelines',
                'status' => true,
            ],
            [
                'name' => 'Any Other Manual',
                'code' => 'OTH-MAN',
                'type' => 'manual',
                'note' => 'Other miscellaneous manuals and documents',
                'status' => true,
            ],
        ];

        foreach ($complainTypes as $type) {
            ComplainType::create($type);
        }

        $this->command->info('✅ Complain types seeded successfully!');
    }
}
