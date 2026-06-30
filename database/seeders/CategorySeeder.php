<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'name' => 'Harassment & Discrimination',
                'code' => 'HARASSMENT',
                'note' => 'Incidents regarding workplace harassment, bullying, or discrimination',
                'status' => true,
            ],
            [
                'name' => 'Salary, Wages & Overtime',
                'code' => 'WAGES_OVERTIME',
                'note' => 'Disputes regarding pay calculations, late salaries, or overtime details',
                'status' => true,
            ],
            [
                'name' => 'Workplace Safety & Environment',
                'code' => 'SAFETY_ENVIRONMENT',
                'note' => 'Unhygienic toilets, heat, poor drinking water, or unsafe machines',
                'status' => true,
            ],
            [
                'name' => 'Supervisor Behavior & Abuse',
                'code' => 'SUPERVISOR_BEHAVIOR',
                'note' => 'Abusive language, unfair treatment, or misbehavior from team leaders',
                'status' => true,
            ],
            [
                'name' => 'Leave & Holiday Entitlements',
                'code' => 'LEAVE_HOLIDAYS',
                'note' => 'Issues related to holiday payments, sick leaves, or maternity leaves',
                'status' => true,
            ],
            [
                'name' => 'Canteen & Canteen Food Quality',
                'code' => 'CANTEEN_QUALITY',
                'note' => 'Complaints about factory canteen food, hygiene, or prices',
                'status' => true,
            ],
            [
                'name' => 'Health & Medical Support',
                'code' => 'MEDICAL_SUPPORT',
                'note' => 'Lack of medicine, clinic support, or slow emergency response',
                'status' => true,
            ],
            [
                'name' => 'Factory Transport & Commute',
                'code' => 'FACTORY_TRANSPORT',
                'note' => 'Delayed factory buses, driver misbehavior, or route problems',
                'status' => true,
            ],
            [
                'name' => 'Suggestions & Improvements',
                'code' => 'SUGGESTIONS',
                'note' => 'Positive suggestions for factory operations and worker welfare',
                'status' => true,
            ],
            [
                'name' => 'Other General Complaints',
                'code' => 'OTHER_COMPLAINTS',
                'note' => 'Any other worker complains or miscellaneous issues',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ GMS Categories seeded successfully!');
    }
}
