<?php

namespace App\Commands\V1\User;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use Illuminate\Support\Facades\Auth;

class LogInUserCommand extends Command
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): Result
    {
        $request = $this->request;
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        $result = new Result();
        $result->token = $token;
        $result->user = $user;

        return $result;
    }
}