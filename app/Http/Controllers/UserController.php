<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('admin:api');
    }

    public function index()
    {
        $users = $this->userService->getPaginate(10);
        $response['code'] = $users['code'];
        $response['message'] = $users['message'];

        if ($users['status']) {
            $response['data'] = $users['data'];
        }
        return response()->json($response, $users['code']);
    }

    public function store(Request $request)
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

    public function show($id)
    {
        $user = $this->userService->getById($id);
        $response['code'] = $user['code'];
        $response['message'] = $user['message'];

        if ($user['status']) {
            $response['data'] = $user['data'];
        }
        return response()->json($response, $user['code']);
    }

    public function update($id, Request $request)
    {
        $user = $this->userService->updateUser($id, $request);
        $response['code'] = $user['code'];
        $response['message'] = $user['message'];

        if ($user['status']) {
            $response['data'] = $user['data'];
        } else {
            $response['errors'] = $user['errors'];
        }

        return response()->json($response, $user['code']);
    }

    public function destroy($id)
    {
        $user = $this->userService->deleteUser($id);
        $response['code'] = $user['code'];
        $response['message'] = $user['message'];

        if ($user['status']) {
            $response['data'] = $user['data'];
        } else {
            $response['errors'] = $user['errors'];
        }

        return response()->json($response, $user['code']);
    }
}
