<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;
class AuthSV
{
    public function getQuery()
    {
        return User::query();
    }


    public function loginAdmin($identifier, $password)
    {
        $user = User::query()
            // ->with('role')
            ->where(function ($query) use ($identifier) {
                $query->where('email',$identifier);
            })
            ->first();
        if (!$user) {
            throw new Exception('User not found');
        }

        // if ($user->active == 0) {
        //     throw new Exception('User is deactivated');
        // }

        if (!Hash::check($password, $user->password)) {
            throw new Exception('Email or Password is incorrect');
        }

        $token = Auth::guard('api')->login($user);
        
        if (!$token) {
            throw new Exception('Unauthorized');
        }
        return ['user' => $user, 'token' => $token];

    }

    /**
     * Register a User.
     */
    public function register($data)
    {
        $query = $this->getQuery();

        return $query->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function GetProfile($role)
    {
        try {
            $guard = $role === 'admin' ? 'api' : 'api-user';

            return response()->json(Auth::guard($guard)->user());
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        // $guard = $role === 'admin' ? 'api' : 'api-user';
        Auth::guard('api')->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if(!$token){
            throw new \Exception('Token not provided');
        }
        try{
            config(['jwt.ttl' => config('jwt.refresh_ttl')]);
            $newToken = JWTAuth::refresh($token);
        }catch(\Exception $e){
            throw new \Exception('The token is invalid');
        }
        return $this->respondWithRefreshToken($newToken);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token, $user = null, $role)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => [
                'user' => $user,
            ],
            'expires_in_second' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    protected function respondWithRefreshToken($token)
    {
        return response()->json([
            'refresh_token' => $token,
            'token_type' => 'bearer',
            'expires_in_second' => config('jwt.refresh_ttl') * 60,
        ]);
    }
}
