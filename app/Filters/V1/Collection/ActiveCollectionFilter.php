<?php

namespace App\Filters\V1\Collection;

use App\Filters\Filter;
use Illuminate\Support\Facades\DB;

class ActiveCollectionFilter extends Filter
{
    public function filter($request): array
    {
        $data = $this->nextStep($request);
        if ($data == null) {
            return $this->getFromDatabase($request);
        }
        $data = collect($data);
        $result = $data->filter(function($value) {
            return true;
        })->toArray();
        return $result;
    }

    public function getFromDatabase($request): array
    {
        return DB::select('CALL active_collections()');
    }
}