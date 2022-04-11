<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginate($perPage)
    {
        $users = $this->userRepository->getPaginate($perPage);
        if ($users) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Users found',
                'data' => $users
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Users not found'
            ];
        }
    }

    public function getById($id)
    {
        $user = $this->userRepository->getById($id);
        if ($user) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'User found',
                'data' => $user
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'User not found'
            ];
        }
    }

    public function saveUser($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 400,
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $user = $this->userRepository->save($request);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Success create user',
                'data' => $user
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to create user',
                'errors' => $e->getMessage()
            ];
        }
    }

    public function updateUser($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 400,
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ];
        }

        $user = $this->userRepository->getById($id);
        if (!$user) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'User not found',
                'errors' => [
                    'message' => 'User not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $user = $this->userRepository->update($id, $request);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Success update user',
                'data' => $user
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to update user',
                'errors' => $e->getMessage()
            ];
        }
    }

    public function deleteUser($id)
    {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'User not found',
                'errors' => [
                    'message' => 'User not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $this->userRepository->delete($id);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Success delete user',
                'data' => $user
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to delete user',
                'errors' => $e->getMessage()
            ];
        }
    }
}
