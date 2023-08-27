<?php

namespace App\Filters\V1\Collection;

use App\Filters\Filter;
use Illuminate\Database\Query\Builder;

class ActiveCollectionFilter extends Filter
{
    public function filter(Builder $data): Builder
    {
        $next = $this->nextStep($data);
        return $next->where('sum_left', '>', '0');
    }
}