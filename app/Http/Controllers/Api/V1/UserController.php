<?php

namespace App\Http\Controllers\Api\V1;

use App\Commands\V1\User\RegisterUserCommand;
use App\Commands\V1\User\RefreshUserCommand;
use App\Commands\V1\User\LogInUserCommand;
use App\Commands\V1\User\LogOutUserCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogInUserRequest;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(LogInUserRequest $request)
    {
        $result = LogInUserCommand::call($request);
        return response()->json([
                'status' => 'success',
                'user' => $result->user,
                'authorisation' => [
                    'token' => $result->token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(RegisterUserRequest $request){
        $result = RegisterUserCommand::call($request);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $result->user,
            'authorisation' => [
                'token' => $result->token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        $result = LogOutUserCommand::call();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        $result = RefreshUserCommand::call();
        return response()->json([
            'status' => 'success',
            'user' => $result->user,
            'authorisation' => [
                'token' => $result->token,
                'type' => 'bearer',
            ]
        ]);
    }
}
