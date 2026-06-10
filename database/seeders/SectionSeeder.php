<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        foreach ($departments as $department) {
            $deptName = $department->name;
            $sections = [];

            // For Sale department only - with numbers
            if ($deptName === 'Sale') {
                $sections = [
                    'Sale 1',
                    'Sale 2',
                    'Sale 3',
                    'Sale 4',
                    'Sale 5'
                ];
            }
            // For all other departments - just the department name
            else {
                // Just one section with department name
                $sections = [$deptName];
            }

            // Create sections for this department
            $counter = 1;
            foreach ($sections as $sectionName) {
                Section::create([
                    'name' => $sectionName,
                    'code' => $department->code . '-' . str_pad($counter, 2, '0', STR_PAD_LEFT),
                    'department_id' => $department->id,
                    'status' => true,
                    'note' => "{$deptName} Department",
                ]);
                $counter++;
            }
        }
    }
}
