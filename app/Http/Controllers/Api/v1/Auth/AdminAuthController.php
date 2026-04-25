<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthSV;
use App\Http\Controllers\Api\v1\BaseAPI;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\AdminLoginRequest;
class AdminAuthController extends BaseAPI
{
    protected $AuthSV;
    public function __construct ()
    {
        $this->AuthSV = new AuthSV();
    }

    // Register User (Admin, Teacher, Student)
    public function register(StoreUserRequest $request){
        try {
            $params = [];
            $params['email'] = $request->email;
            $params['password'] = $request->password;
            $params['first_name'] = $request->first_name;
            $params['last_name'] = $request->last_name;
            $params['role'] = $request->role ?? 'student'; // Accept role from request
            
            // Set status based on role
            // Teachers need admin approval (0 = pending), others are active (1) immediately
            $params['status'] = ($request->role === 'teacher') ? 0 : 1;
            
            $user = $this->AuthSV->register($params);
            
            // Generate token for students (they can login immediately)
            // Teachers don't get token until approved
            if ($request->role === 'student') {
                $token = Auth::guard('api')->login($user);
                return $this->successResponse([
                    'user' => $user,
                    'token' => $token
                ], "Registration Successfully.");
            }
            
            // For teachers, just return user without token
            return $this->successResponse($user, "Registration Successfully. Your account is pending admin approval.");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Login User (Admin, Teacher, Student)
    public function login(AdminLoginRequest $request){
        try{
            $identifier = $request->email ?? $request->phone_number ?? $request->username;
            $password = $request->password;
            $role = $request->role ?? null; // Accept role from request
            
            $user = $this->AuthSV->loginAdmin($identifier, $password, $role);
            return $user;
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Refresh Token

    public function refreshToken()
    {
        try {
            $token = $this->AuthSV->refreshToken();
            return $token;
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            $this->AuthSV->logout();   // Pass the role, not the user object
            return $this->successResponse(null, 'Admin logged out successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
