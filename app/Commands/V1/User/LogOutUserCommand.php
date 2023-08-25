<?php

namespace App\Commands\V1\User;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use Illuminate\Support\Facades\Auth;

class LogOutUserCommand extends Command
{
    public function execute(): Result
    {
        Auth::logout();
        return new Result();
    }
}