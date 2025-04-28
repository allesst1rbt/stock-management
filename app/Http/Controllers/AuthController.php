<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct(private readonly UserService $UserService) {}

    public function register(StoreUserRequest $request)
    {
        $request->validated();
        $userDTO = new UserDTO(
            $request->input('name'),
            $request->input('email'),
            $request->input('roles'),
            $request->input('password')
        );
        try {
            $return = $this->UserService->register($userDTO);
        } catch (Exception $e) {
            return response()->json(['error' => 'Could not create User', 'message' => $e], 500);
        }

        return response()->json($return, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {


            $return = $this->UserService->login($credentials);

        } catch (Exception $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json($return);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser()
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }

    public function updateUser(UpdateUserRequest $request)
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $userDTO = new UserDTO(
                $request->input('name'),
                $request->input('email'),
                $request->input('roles'),
            );
            $this->UserService->updateUser($user->id, $userDTO);

            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }
}
