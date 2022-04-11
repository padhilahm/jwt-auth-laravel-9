<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    // create token
    public function createNewToken($token)
    {
        // 3600 s *60
        return [
            'code' => 200,
            'status' => true,
            'message' => 'Token created',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 720,
            'data' => auth()->user()
        ];
    }

    // login user
    public function loginUser($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ];
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return [
                'code' => 401,
                'status' => false,
                'message' => 'Unauthorized',
                'errors' => [
                    'message' => 'Invalid credentials'
                ]
            ];
        }

        return $this->createNewToken($token);
    }
}
