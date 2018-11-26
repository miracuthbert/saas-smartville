<?php

namespace Smartville\Domain\Utilities\Rules;

use Illuminate\Contracts\Validation\Rule;
use Smartville\Domain\Utilities\Models\Utility;

class BillingDuration implements Rule
{
    /**
     * Billing interval.
     *
     * @var $billingInterval
     */
    private $billingInterval;

    /**
     * Bill interval.
     *
     * @var $billInterval
     */
    private $billInterval;

    /**
     * Formatted billing interval.
     *
     * @var $formattedBillingInterval
     */
    private $formattedBillingInterval;

    /**
     * Maximum billing cycle.
     *
     * @var
     */
    private $maxBillingCycle;

    /**
     * Create a new rule instance.
     *
     * @param $billingInterval
     */
    public function __construct($billingInterval)
    {
        $this->billingInterval = $billingInterval;
        $this->billInterval = array_get(Utility::$billingIntervals, $billingInterval);
        $this->formattedBillingInterval = array_get(Utility::$formattedBillingIntervals, $billingInterval);
        $this->maxBillingCycle = array_get(Utility::$allowedBillingCycles, $billingInterval);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value > $this->maxBillingCycle) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $interval = str_plural($this->formattedBillingInterval, $this->maxBillingCycle);

        return "The :attribute cycle for {$this->billInterval} bills cannot be more than {$this->maxBillingCycle} {$interval}.";
    }
}
