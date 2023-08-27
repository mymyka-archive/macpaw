<?php

namespace App\Commands\V1\Collection;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use App\Filters\V1\Collection\SumLeftFilter;
use App\Filters\V1\Collection\ActiveCollectionFilter;

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
        
        $sumLeftFilter = new SumLeftFilter();
        $activeCollectionFilter = new ActiveCollectionFilter();

        // $sumLeftFilter->then($activeCollectionFilter);
        $activeCollectionFilter->then($sumLeftFilter);

        $result->data = ($sumLeftFilter->filter($request));

        return $result;
    }
}