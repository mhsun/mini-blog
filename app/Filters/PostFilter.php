<?php

namespace App\Filters;

use Illuminate\Http\Request;

class PostFilter
{
    /**
     * @var string
     */
    private $defaultSortKey = "desc";

    /**
     * @var int
     */
    private $defaultPageNo = 1;

    public function getSortByKey(Request $request): string
    {
        $filters = ["asc", "desc"];

        if (collect($filters)->contains($request->query('sort'))) {
            return $request->query('sort');
        }

        return $this->defaultSortKey;
    }

    public function getPageNumber(Request $request)
    {
        if ($request->has('page') && is_integer((int)$request->query('page'))) {
            return $request->query('page');
        }

        return $this->defaultPageNo;
    }
}
