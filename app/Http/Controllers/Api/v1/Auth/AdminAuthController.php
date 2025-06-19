<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
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

    // Register Admin
    public function register(StoreUserRequest $request){
        try {
            $params = [];
            $params['email'] = $request->email;
            $params['password'] = $request->password;
            $params['first_name'] = $request->first_name;
            $params['last_name'] = $request->last_name;
            $params['role'] = 'admin';
            $admin = $this->AuthSV->register($params);
            return $this->successResponse($admin, "Admin Register Successfully.");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Login Admin
    public function login(AdminLoginRequest $request){
        try{
            $identifier = $request->email ?? $request->phone_number ?? $request->username;
            $password = $request->password;
            $admin = $this->AuthSV->loginAdmin($identifier, $password);
            return $admin;
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
