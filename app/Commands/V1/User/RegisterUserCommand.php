<?php

namespace App\Commands\V1\User;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterUserCommand extends Command {
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): Result
    {
        $request = $this->request;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        $result = new Result();
        $result->token = $token;
        $result->user = $user;

        return $result;
    }
}