<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Services\UserSV;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\DB;


class UserController extends BaseAPI
{
    //
    private $userService;
    public function __construct(UserSV $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('status')) {
            $filters['status'] = $request->query('status');
        }

        $params = [
            'filterBy' => $filters,
            'perPage' => $request->query('perPage',500), 
        ];

        $users = $this->userService->getAllUsers($params);
        return $this->successResponse($users, 'Users retrieved successfully');
    }


    public function show($id)
    {

        try {
            $user = $this->userService->getUserById($id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }
        // Check if the user is an admin
        return $this->successResponse($user, 'User retrieved successfully');
    }

    public function store(StoreUserRequest $request){
        try {
            $params = $request->validated();
            $params['status'] = $params['status'] ?? 1;

            DB::beginTransaction();
            $user = $this->userService->createUser($params);
            DB::commit();
            return $this->successResponse($user, 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        try {
            $params = $request->validated();
            $params['status'] = $params['status'] ?? 1;

            $updatedUser = $this->userService->updateUser($params, $id);
            return $this->successResponse($updatedUser, 'User updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function deactivateUser($id)
    {
        try {

            DB::beginTransaction();
            $user = $this->userService->deactivateUser($id);
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            DB::commit();
            return $this->successResponse($user, 'User deactivated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }



}
