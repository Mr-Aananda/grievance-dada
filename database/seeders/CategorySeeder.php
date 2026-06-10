<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Buyer Complain Categories
            [
                'name' => 'Order Delay',
                'code' => 'ORDER_DELAY',
                'note' => 'Delay in order processing or delivery',
                'status' => true,
            ],
            [
                'name' => 'Wrong Quantity',
                'code' => 'WRONG_QTY',
                'note' => 'Incorrect quantity shipped or produced',
                'status' => true,
            ],
            [
                'name' => 'Communication Issue',
                'code' => 'COMM_ISSUE',
                'note' => 'Poor communication with buyer',
                'status' => true,
            ],
            [
                'name' => 'Payment Issue',
                'code' => 'PAYMENT',
                'note' => 'Payment related problems',
                'status' => true,
            ],

            // Sewing Complain Categories
            [
                'name' => 'Machine Breakdown',
                'code' => 'MACHINE_BREAK',
                'note' => 'Sewing machine malfunction or breakdown',
                'status' => true,
            ],
            [
                'name' => 'Thread Breakage',
                'code' => 'THREAD_BREAK',
                'note' => 'Frequent thread breakage issues',
                'status' => true,
            ],
            [
                'name' => 'Operator Issue',
                'code' => 'OPERATOR',
                'note' => 'Problems with sewing operators',
                'status' => true,
            ],
            [
                'name' => 'Production Delay',
                'code' => 'PROD_DELAY',
                'note' => 'Delay in sewing production line',
                'status' => true,
            ],
            [
                'name' => 'Stitching Defect',
                'code' => 'STITCH_DEFECT',
                'note' => 'Defects in stitching quality',
                'status' => true,
            ],

            // Quality Complain Categories
            [
                'name' => 'Fabric Defect',
                'code' => 'FABRIC_DEFECT',
                'note' => 'Defects in fabric quality',
                'status' => true,
            ],
            [
                'name' => 'Measurement Issue',
                'code' => 'MEASUREMENT',
                'note' => 'Incorrect measurements or sizing',
                'status' => true,
            ],
            [
                'name' => 'Color Issue',
                'code' => 'COLOR_ISSUE',
                'note' => 'Color mismatch or bleeding',
                'status' => true,
            ],
            [
                'name' => 'Finishing Defect',
                'code' => 'FINISHING',
                'note' => 'Problems in garment finishing',
                'status' => true,
            ],
            [
                'name' => 'Accessories Issue',
                'code' => 'ACCESSORIES',
                'note' => 'Defective or wrong accessories',
                'status' => true,
            ],

            // Other Complain Categories
            [
                'name' => 'Transportation',
                'code' => 'TRANSPORT',
                'note' => 'Transportation and logistics issues',
                'status' => true,
            ],
            [
                'name' => 'Raw Material',
                'code' => 'RAW_MATERIAL',
                'note' => 'Raw material supply problems',
                'status' => true,
            ],
            [
                'name' => 'Power Issue',
                'code' => 'POWER',
                'note' => 'Electricity or power supply problems',
                'status' => true,
            ],
            [
                'name' => 'Safety Issue',
                'code' => 'SAFETY',
                'note' => 'Workplace safety concerns',
                'status' => true,
            ],

            // New Categories
            [
                'name' => 'Repair',
                'code' => 'REPAIR',
                'note' => 'Repair and rework related issues',
                'status' => true,
            ],
            [
                'name' => 'Remake',
                'code' => 'REMAKE',
                'note' => 'Complete remake of products or parts',
                'status' => true,
            ],
            [
                'name' => 'Any Others',
                'code' => 'ANY_OTHERS',
                'note' => 'Any others miscellaneous issues not covered elsewhere',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}
