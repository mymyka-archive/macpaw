<?php

namespace App\Filters;

class FilterChainBuilder
{
    private array $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    public function addFilter(Filter $filter): FilterChainBuilder
    {
        $target_filter = end($this->filters);
        if ($target_filter) {
            $target_filter->then($filter);
        }
        array_push($this->filters, $filter);
        return $this;
    }

    public function build(): array
    {
        return $this->filters;
    }
}