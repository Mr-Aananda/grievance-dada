<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\Section;
use App\Models\User;
use App\Services\PermissionService\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $user;
    private $data;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_query = User::query();

        // Exclude users with the "Administrator" role
        $user_query->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Administrator');
        });

        // Search filters
        if (request()->name) {
            $user_query->where('name', 'LIKE', '%' . request()->name . '%');
        }
        if (request()->phone) {
            $user_query->where('phone', 'LIKE', '%' . request()->phone . '%');
        }
        if (request()->status === '1' || request()->status === '0') {
            $user_query->where('status', request()->status);
        }

        // Get users with pagination
        $users = $user_query->latest()->with('roles', 'department', 'section')->paginate(25)->withQueryString();

        return view('user.index', compact('users'))->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'Administrator')->get();
        $departments = Department::select('id', 'name')->get();
        $sections = Section::select('id', 'name')->get();
        return view('user.create', compact('roles', 'departments', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        DB::transaction(function () use ($request) {
            $user_data = $this->getUserData($request);
            $user = User::create($user_data);

            // Multiple roles assign for create
            if ($request->has('role') && !empty($request->role)) {
                $user->assignRole($request->role);
            }

            $this->user = $user;
        });

        if ($this->user) {
            return redirect()->back()->withSuccess('User created successfully');
        }

        return redirect()->back()->withError('Failed to create user');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, PermissionService $permissionService)
    {
        $data = [];
        $data['user'] = User::query()
            ->with(['roles:id,name', 'department', 'section'])
            ->findOrFail($id);
        $data['assigned_permission_area_groups'] = $permissionService->availablePermissionAreaGroupsByUser($data['user']);
        $data['assigned_partial_permission_groups'] = $permissionService->availablePartialPermissionGroupsByUser($data['user']);
        return view('user.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('roles')->findOrFail($id); // Load roles with user
        $roles = Role::where('name', '!=', 'Administrator')->get();
        $departments = Department::select('id', 'name')->get();
        $sections = Section::where('department_id', $user->department_id)->get();

        return view('user.edit', compact('user', 'roles', 'departments', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $request->validated();

        DB::transaction(function () use ($request, $id) {
            $user = User::findOrFail($id);
            $user_data = $this->getUserData($request, 'update');

            // Update user data
            $user->update($user_data);

            // Handle role updates - FIXED: Use syncRoles instead of assignRole
            if ($request->has('role')) {
                // If roles are provided, sync them (this will remove old roles and add new ones)
                if (!empty($request->role)) {
                    $user->syncRoles($request->role);
                } else {
                    // If empty array, remove all roles
                    $user->syncRoles([]);
                }
            }
            // If role field is not present in request, don't change roles

            $this->user = $user;
        });

        if ($this->user) {
            return redirect()->route('user.index')->withSuccess('User updated successfully');
        }

        return redirect()->back()->withError('Failed to update user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $user = User::find($id);
            if ($user) {
                // Remove roles before deleting
                $user->syncRoles([]);
                $user->delete();
            }
        });
        return redirect()->back()->withSuccess('User deleted Successfully.');
    }

    /**
     * Get member data
     */
    public function getUserData($request, $from = 'create'): array
    {
        $primary_data = [
            'name' => $request->name,
            'emp_id' => $request->emp_id,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'department_id' => $request->department_id,
            'section_id' => $request->section_id,
            'email' => $request->email,
            'status' => true,
            'email_verified_at' => now(),
        ];

        if ($from === 'update') {
            // For update, only set password if provided
            if ($request->filled('password')) {
                $primary_data['password'] = Hash::make($request->password);
            }
            $primary_data['status'] = $request->status;
            return $primary_data;
        } else {
            // For create, always set password
            $create_data = [
                'password' => Hash::make($request->password),
            ];
            return array_merge($primary_data, $create_data);
        }
    }

    /**
     * Get sections by department (for AJAX)
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
            return response()->json(['error' => 'Failed to load sections'], 500);
        }
    }
}
