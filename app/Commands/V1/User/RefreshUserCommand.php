<?php

namespace App\Commands\V1\User;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use Illuminate\Support\Facades\Auth;

class RefreshUserCommand extends Command
{
    public function execute(): Result
    {
        $result = new Result();
        $result->user = Auth::user();
        $result->token = Auth::refresh();
        return $result;
    }
}