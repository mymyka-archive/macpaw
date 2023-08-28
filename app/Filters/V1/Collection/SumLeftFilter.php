<?php

namespace App\Filters\V1\Collection;

use App\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class SumLeftFilter extends Filter 
{    
    protected string $sortField = 'sum_left';
    protected string $sortOrder = 'asc';

    public function __construct(array $params = [])
    {
        $this->sortField = $params['sortField'] ?? $this->sortField;
        $this->sortOrder = $params['sortOrder'] ?? $this->sortOrder;
    }

    public function filter(Builder $data): Builder
    {
        $next = $this->nextStep($data);
        return $next->orderBy($this->sortField, $this->sortOrder);
    }
}