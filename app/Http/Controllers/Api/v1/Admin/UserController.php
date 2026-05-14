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

    public function approveUser($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->userService->approveUser($id);
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            DB::commit();
            return $this->successResponse($user, 'User approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Get the authenticated user
            $authUser = auth()->user();
            
            // Prevent deleting yourself
            if ($authUser && $authUser->id == $id) {
                return $this->errorResponse('You cannot delete your own account', 403);
            }
            
            $user = $this->userService->deleteUser($id);
            
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            
            DB::commit();
            return $this->successResponse(null, 'User deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:paid_1_semester,paid_2_semester,pending,not_yet'
            ]);

            DB::beginTransaction();
            $user = $this->userService->updatePaymentStatus($id, $request->payment_status);
            
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            
            DB::commit();
            return $this->successResponse($user, 'Payment status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
