<?php

namespace Smartville\Domain\Utilities\Rules;

use Illuminate\Contracts\Validation\Rule;
use Smartville\Domain\Utilities\Models\Utility;

class BillingDue implements Rule
{
    /**
     * Billing Interval.
     *
     * @var $billingInterval
     */
    private $billingInterval;

    /**
     * Billing Duration Cycle.
     *
     * @var
     */
    private $billingDuration;

    /**
     * Create a new rule instance.
     *
     * @param $billingInterval
     * @param $billingDuration
     */
    public function __construct($billingInterval, $billingDuration)
    {
        $this->billingInterval = array_get(Utility::$formattedBillingIntervals, $billingInterval);
        $this->billingDuration = $billingDuration;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $interval = $this->billingInterval;
        $duration  = $this->billingDuration;

        if ($interval == 'month' && $value > (30 * $duration)) {
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
        return 'The :attribute number of days cannot be more than the billing duration cycle number of days.';
    }
}
