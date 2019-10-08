<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Filters;


use App\Category;
use App\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AbstractFilters
{

    protected $request;

    protected $query;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function apply(Builder $builder)
    {
        $this->query = $builder;

        foreach ($this->filters() as $key => $filter) {
            $method = Str::camel($key);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$filter]);
            }
        }

        return $this->query;
    }


    private function filters()
    {
        return array_filter($this->request->all());
    }



}