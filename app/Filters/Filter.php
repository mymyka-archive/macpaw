<?php

namespace App\Filters;

use Illuminate\Database\Query\Builder;

abstract class Filter
{
    protected ?Filter $nextFilter = null;

    public function then(Filter $filter): Filter
    {
        $this->nextFilter = $filter;
        return $filter;
    }

    public function nextStep(Builder $data): ?Builder
    {
        if ($this->nextFilter) {
            return $this->nextFilter->filter($data);
        }
        return $data;
    }

    public abstract function filter(Builder $data): Builder;
}