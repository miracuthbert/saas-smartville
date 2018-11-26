<?php

namespace Smartville\Domain\Utilities\Rules;

use Illuminate\Contracts\Validation\Rule;
use Smartville\Domain\Properties\Models\Property;

class UtilityProperties implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (Property::whereHas('utilities', function ($query) use ($value) {
                return $query->where('utility_id', $value);
            })->has('currentLease')->count()) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot generate invoices for a :attribute that does not have active subscribers.';
    }
}
