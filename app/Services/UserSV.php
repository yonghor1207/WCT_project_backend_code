<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
class UserSV extends BaseService
{
    protected function getQuery()
    {
        return User::query();
    }

    public function getAllUsers($params)
    {
        $query = $this->getQuery();

        return $this->getAll($query, $params);
    }


    public function createUser($data){
        try {
            $query = $this->getQuery();
            $status = isset($data['status']) ? $data['status'] : 1;

            $user = $query->create([
                'first_name'     => $data['first_name'],
                'last_name'       => $data['last_name'],
                'email'          => $data['email'],
                'password'       => Hash::make($data['password']),
                'role'           => $data['role'],
                'gender'        => $data['gender'],
                'phone'     => $data['phone'],
                'dob' =>$data['dob'],
                'status'       => $status,
            ]);

            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error creating user: ' . $e->getMessage(), 500);
        }   
    }


 
    public function getUserById($id)
    {
        $query =  $this->getQuery();
        return $query->find($id);
    }

    public function updateUser($data, $id){
        try {
            // $query = $this->getQuery();
            $user = $this->update($data, $id);
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error updating user: ' . $e->getMessage(), 500);
        }
    }


    public function deactivateUser($id)
    {
        try {
            $user = $this->getQuery()->findOrFail($id);
            $newStatus = $user->status == 1 ? 0 : 1;

            $this->getQuery()->where('id', $id)->update(['status' => $newStatus]);
            $user->refresh();

            // return ['id' => $id, 'status' => $newStatus];
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error toggling user status: ' . $e->getMessage(), 500);
        }
    }

    public function approveUser($id)
    {
        try {
            $user = $this->getQuery()->findOrFail($id);
            
            // Set status to active (1)
            $this->getQuery()->where('id', $id)->update(['status' => 1]);
            $user->refresh();

            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error approving user: ' . $e->getMessage(), 500);
        }
    }
}
