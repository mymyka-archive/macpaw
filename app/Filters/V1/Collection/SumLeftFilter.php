<?php

namespace App\Filters\V1\Collection;

use App\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SumLeftFilter extends Filter 
{
    public function filter(Request $request): array
    {
        $data = $this->nextStep($request);
        if ($data == null) {
            return $this->getFromDatabase($request);
        }
        $data = collect($data);
        $result = $data->filter()->toArray();
        return $result;
    }

    public function getFromDatabase(Request $request): array
    {
        return DB::select('CALL contributions_by_collection()');
    }
}