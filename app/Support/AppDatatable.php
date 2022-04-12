<?php


namespace App\Support;


use App\Contracts\SearchableContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AppDatatable
{
    private function build(Builder|SearchableContract|Relation $query): LengthAwarePaginator
    {
        if (Request::query('search')) {
            $query->applySearchMatch(Request::query('search'));
        }

        if (($filters = collect(request('filters')))->count()) {
            $filters->map(function ($filter) use (&$query) {
                $payload = explode('.', $filter['name']);

                if (count($payload) > 1) {
                    $query->whereHas($payload[0], function ($q) use ($filter, $payload) {
                        if (is_array($filter['value']) && count($filter['value'])) {
                            $q->whereIn($filter['name'], $filter['value']);
                        } else {
                            if ($filter['value']) {
                                $q->where($filter['name'], 'like', "%{$filter['value']}%");
                            }
                        }
                    });

                } else {
                    if (is_array($filter['value']) && count($filter['value'])) {
                        $query->whereIn($filter['name'], $filter['value']);
                    } else {
                        if ($filter['value']) {
                            $query->where($filter['name'], 'like', "%{$filter['value']}%");
                        }
                    }
                }
            });
        }

        Log::info($query->toSql());

        return $query->paginate(Request::input('per_page') ?? 20);
    }

    public static function of(Builder|SearchableContract|Relation $query): LengthAwarePaginator
    {
        return (new self())->build($query);
    }
}
