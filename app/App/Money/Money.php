<?php

namespace Smartville\App\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;

class Money
{
    /**
     * Money Instance.
     *
     * @var BaseMoney
     */
    protected $money;

    /**
     * Money constructor.
     *
     * @param $value
     * @param null $currency
     */
    public function __construct($value, $currency = null)
    {
        $currency = ($currency !== null) ? $currency : config('settings.cashier.currency');

        $this->money = new BaseMoney($value, new Currency($currency));
    }

    /**
     * Return the objects amount.
     *
     * @return string
     */
    public function amount()
    {
        return $this->money->getAmount();
    }

    /**
     * Get formatted money.
     *
     * @return string
     */
    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en_US', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }
}