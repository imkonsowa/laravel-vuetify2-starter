<?php


namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method Builder|self applySearchMatch($search)
 * */
trait Searchable
{
    public function scopeApplySearchMatch(Builder $query, $search)
    {
        $searchable = implode(',', $this->searchable);

        $query->whereRaw("MATCH($searchable) AGAINST (? IN BOOLEAN MODE)", [
            "*$search*"
        ]);
    }
}
