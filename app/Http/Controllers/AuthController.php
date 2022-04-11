<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authService;
    protected $userService;
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $user = $this->authService->loginUser($request);
        $response['code'] = $user['code'];
        $response['message'] = $user['message'];
        if ($user['status']) {
            $response['access_token'] = $user['access_token'];
            $response['token_type'] = $user['token_type'];
            $response['expires_in'] = $user['expires_in'];
            $response['data'] = $user['data'];
        } else {
            $response['errors'] = $user['errors'];
        }

        return response()->json($response, $response['code']);
    }

    public function register(Request $request)
    {
        $user = $this->userService->saveUser($request);
        $response['code'] = $user['code'];
        $response['message'] = $user['message'];

        if ($user['status']) {
            $response['data'] = $user['data'];
        } else {
            $response['errors'] = $user['errors'];
        }
        return response()->json($response, $user['code']);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(
            [
                'message' => 'User successfully signed out'
            ],
            200
        );
    }

    public function refresh()
    {
        return response()->json(
            $this->authService->createNewToken(auth()->refresh()),
            200
        );
    }

    public function userProfile()
    {
        return response()->json([
            'message' => 'User successfully fetched',
            'data' => auth()->user()
        ], 200);
    }
}
