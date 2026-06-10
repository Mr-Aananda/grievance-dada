<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::where('status', true)->get();
        $sections = Section::where('status', true)->get();
        return view('auth.register', compact('departments', 'sections'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate request with proper error messages
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'emp_id' => ['required', 'string', 'max:50', 'unique:' . User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'department_id' => ['required', 'exists:departments,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot exceed 255 characters',
            'emp_id.required' => 'Employee ID is required',
            'emp_id.max' => 'Employee ID cannot exceed 50 characters',
            'emp_id.unique' => 'This Employee ID is already registered',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'email.max' => 'Email cannot exceed 255 characters',
            'department_id.required' => 'Please select a department',
            'department_id.exists' => 'Selected department does not exist',
            'section_id.required' => 'Please select a section',
            'section_id.exists' => 'Selected section does not exist',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'emp_id' => $validated['emp_id'],
                'email' => $validated['email'] ?? null,
                'department_id' => $validated['department_id'],
                'section_id' => $validated['section_id'],
                'password' => Hash::make($validated['password']),
                'status' => true,
            ]);

            // Assign "Employee" role to new user
            $employeeRole = Role::where('name', 'Office Staff')->first();
            if ($employeeRole) {
                $user->assignRole($employeeRole);
            }

            // Fire registered event
            event(new Registered($user));

            // Redirect to login page with success message
            return redirect()->back()
                ->with('success', 'Registration successful! Please login with your credentials.');

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Redirect back with error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Get sections by department
     */
    public function getSections($departmentId)
    {
        try {
            $sections = Section::where('department_id', $departmentId)
                ->where('status', true)
                ->orderBy('name')
                ->select('id', 'name')
                ->get();

            return response()->json($sections);
        } catch (\Exception $e) {
            \Log::error('Error loading sections: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
