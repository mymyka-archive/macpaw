<?php

namespace App\Commands\V1\Collection;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;

class FilterCollectionCommand extends Command
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): Result
    {
        $request = $this->request;

        $result = new Result();
        $result->data = "A";

        return $result;
    }
}