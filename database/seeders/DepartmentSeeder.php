<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Admin', 'code' => 'ADMIN'],
            ['name' => 'Sale', 'code' => 'SALE'],
            ['name' => 'Planning', 'code' => 'PLN'],
            ['name' => 'Cutting', 'code' => 'CUT'],
            ['name' => 'Sewing', 'code' => 'SEW'],
            ['name' => 'Finishing', 'code' => 'FIN'],
            ['name' => 'Quality Control', 'code' => 'QC'],
            ['name' => 'Accounts & Finance', 'code' => 'ACC'],
            ['name' => 'HR & Compliance', 'code' => 'HR'],
            ['name' => 'IT Department', 'code' => 'IT'],
            ['name' => 'Store', 'code' => 'STR'],
            ['name' => 'Maintenance', 'code' => 'MNT'],
            ['name' => 'Production', 'code' => 'PRD'],
            ['name' => 'IE', 'code' => 'IE'],
            ['name' => 'Embroidery ', 'code' => 'EMBO'],
            ['name' => 'Korean Management', 'code' => 'KOR']
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department['name'],
                'code' => $department['code'],
                'note' => $department['name'] . ' Department',
                'status' => true,
            ]);
        }
    }
}
