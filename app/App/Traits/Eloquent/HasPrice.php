<?php

namespace Smartville\App\Traits\Eloquent;

use Smartville\App\Money\Money;

trait HasPrice
{
    /**
     * Get and return price as instance of Money.
     *
     * @param $value
     * @return Money
     */
    public function getPriceAttribute($value)
    {
        return new Money($value);
    }

    /**
     * Get amount from price.
     *
     * @return string
     */
    public function getPriceAmountAttribute()
    {
        return $this->price->amount();
    }

    /**
     * Get formatted price.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return $this->price->formatted();
    }
}