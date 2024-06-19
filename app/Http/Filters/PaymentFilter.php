<?php

namespace App\Http\Filters;

use App\Common\Filters\QueryFilter;

class PaymentFilter extends QueryFilter
{
    // TODO: Example
    public function status($value)
    {
        $this->builder->where('status', $value);
    }
}
