<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Smartville\Domain\Utilities\Models\Utility;
use Smartville\Domain\Utilities\Rules\BillingDue;
use Smartville\Domain\Utilities\Rules\BillingDuration;

class UtilityStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'currency' => [
                'required',
                Rule::exists('currencies', 'cc')->where('usable', true)
            ],
            'price' => 'required|numeric',
            'details' => 'required|max:1500',
            'usable' => 'required|boolean',
            'billing_interval' => [
                'required',
                Rule::in(array_keys(Utility::$billingIntervals))
            ],
            'billing_duration' => [
                'required',
                'integer',
                'min:1',
                new BillingDuration($this->get('billing_interval'))
            ],
            'billing_day' => 'required|integer|max:31',
            'billing_due' => [
                'required',
                'integer',
                new BillingDue($this->get('billing_interval'), $this->get('billing_duration')),
            ],
            'billing_type' => [
                'required',
                Rule::in(array_keys(Utility::$billingTypes))
            ],
            'billing_unit' => 'required_if:billing_type,varied',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'billing_due' => 'bill due'
        ];
    }


}
