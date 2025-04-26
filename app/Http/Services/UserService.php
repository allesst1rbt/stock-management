<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class UserService
{
    /**
     * Get all users with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllUsers(int $perPage = 10): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return UserDTO|null
     */
    public function getUserById(int $id): ?UserDTO
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        return UserDTO::fromArray($user->toArray());
    }

    /**
     * Create new user
     *
     * @param UserDTO $userDTO
     * @return UserDTO
     * @throws Exception
     */
    public function createUser(UserDTO $userDTO): UserDTO
    {
        DB::beginTransaction();
        try {
            $userData = $userDTO->toArray();
            $userData['password'] = Hash::make($userData['password']);

            $user = User::create($userData);
            
            DB::commit();
            
            return UserDTO::fromArray($user->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update user
     *
     * @param int $id
     * @param UserDTO $userDTO
     * @return UserDTO
     * @throws Exception
     */
    public function updateUser(int $id, UserDTO $userDTO): UserDTO
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $userData = $userDTO->toArray();

            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            } else {
                unset($userData['password']);
            }

            $user->update($userData);
            
            DB::commit();
            
            return UserDTO::fromArray($user->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteUser(int $id): bool
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $result = $user->delete();
            
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Change user password
     *
     * @param int $userId
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     * @throws Exception
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = User::findOrFail($userId);

        if (!Hash::check($currentPassword, $user->password)) {
            throw new Exception('Current password is incorrect');
        }

        return $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}
