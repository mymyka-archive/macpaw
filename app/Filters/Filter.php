<?php

namespace App\Filters;

use Illuminate\Database\Query\Builder;

/**
 * Base class for all filters
 * Implements the Chain Of Responsobility Pattern
 */
abstract class Filter
{
    protected ?Filter $nextFilter = null;

    /**
     * Called to select nex filter in the chain
     */
    public function then(Filter $filter): Filter
    {
        $this->nextFilter = $filter;
        return $filter;
    }

    /**
     * Called to call next filter in the chain
     */
    public function nextStep(Builder $data): ?Builder
    {
        if ($this->nextFilter) {
            return $this->nextFilter->filter($data);
        }
        return $data;
    }

    /**
     * Define in subclasses
     * and put the logic of a filter
     */
    public abstract function filter(Builder $data): Builder;
}