<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

trait FilterByPeriode
{
    public function applyPeriodeFilter(Request $request, Builder $query, ?string $tablePrefix = null): Builder
    {
        $column = $tablePrefix ? "{$tablePrefix}.periode_id" : 'periode_id';

        if ($request->filled('periode_id')) {
            $query->where($column, $request->periode_id);
        }

        return $query;
    }
}
