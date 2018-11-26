<?php

namespace Smartville\Domain\Utilities\Rules;

use Illuminate\Contracts\Validation\Rule;

class BillingDay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($billingInterval)
    {
        //
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
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
