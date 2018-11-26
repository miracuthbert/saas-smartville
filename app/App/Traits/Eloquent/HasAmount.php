<?php

namespace Smartville\App\Traits\Eloquent;

use Smartville\App\Money\Money;

trait HasAmount
{
    /**
     * Get and return amount as instance of Money.
     *
     * @param $value
     * @return Money
     */
    public function getAmountAttribute($value)
    {
        return new Money($value);
    }

    /**
     * Get actual amount from amount.
     *
     * @return string
     */
    public function getInitialAmountAttribute()
    {
        return $this->amount->amount();
    }

    /**
     * Get formatted price.
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return $this->amount->formatted();
    }
}