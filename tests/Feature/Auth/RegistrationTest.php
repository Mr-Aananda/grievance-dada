<?php

use App\Models\Department;
use App\Models\Section;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $dept = Department::create(['name' => 'HR', 'code' => 'HR', 'status' => true]);
    $section = Section::create(['name' => 'Recruitment', 'code' => 'REC', 'department_id' => $dept->id, 'status' => true]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'emp_id' => 'EMP-TEST-99',
        'email' => 'test@example.com',
        'department_id' => $dept->id,
        'section_id' => $section->id,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'emp_id' => 'EMP-TEST-99',
        'email' => 'test@example.com',
    ]);
});
