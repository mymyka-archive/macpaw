<?php

namespace App\Commands\V1\Collection;

use App\Commands\Command;
use Illuminate\Http\Request;
use App\Commands\Result;
use App\Filters\V1\Collection\SumLeftFilter;
use App\Filters\V1\Collection\ActiveCollectionFilter;
use App\Filters\FilterChainBuilder;
use Illuminate\Support\Facades\DB;

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
        
        $filterChainBuilder = new FilterChainBuilder();
        foreach ($request->filters as $filter) {
            $filterType = $this->getFilterByName($filter['name']);
            $filterChainBuilder->addFilter(new $filterType($filter));
        }
        $triger_filter = $filterChainBuilder->build()[0];
        $table = DB::table('collections_summary')
            ->select('id', 'title', 'description', 'target_amount', 'link', 'total', 'sum_left');
        $result->data = $triger_filter->filter($table)->get();
        return $result;
    }

    public function getFilterByName(string $name)
    {
        $filters = [
            'sumLeft' => SumLeftFilter::class,
            'activeCollection' => ActiveCollectionFilter::class,
        ];

        return $filters[$name];
    }
}