<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserService
{
    public function getAllUsers($filters)
    {
        $query = User::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['phone'])) {
            $query->where('phone', 'LIKE', '%' . $filters['phone'] . '%');
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(30);
    }

    public function createUser($data)
    {
        DB::transaction(function () use ($data) {
            $user_data = $this->prepareUserData($data);
            $user = User::create($user_data);
            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            }
        });

        return $user ?? null;
    }

    public function updateUser($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $user = User::findOrFail($id);
            $user_data = $this->prepareUserData($data, 'update');
            $user->update($user_data);

            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            }
        });

        return $user ?? null;
    }

    public function deleteUser($id)
    {
        DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $user->delete();
        });
    }

    public function getUserById($id)
    {
        return User::with('roles:id,name')->findOrFail($id);
    }

    protected function prepareUserData($request, $from = 'create'): array
    {
        $primary_data = [
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'active' => $from === 'update' ? $request['active'] : 1,
            'email_verified_at' => now(),
        ];

        if ($from === 'create') {
            $primary_data['password'] = Hash::make($request['password']);
        }

        return $primary_data;
    }
}
