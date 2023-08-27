<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    protected ?Filter $nextFilter = null;

    public function then(Filter $filter): Filter
    {
        $this->nextFilter = $filter;
        return $filter;
    }

    public function nextStep(Request $request): ?array
    {
        if ($this->nextFilter) {
            return $this->nextFilter->filter($request);
        }
        return null;
    }

    public abstract function filter(Request $request): array;

    public abstract function getFromDatabase(Request $request): array;
}