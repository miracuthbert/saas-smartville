<?php

namespace Smartville\Http\Tenant\Requests;

class LeaseInvoiceUpdateRequest extends LeaseInvoiceStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_except(parent::rules(), 'property_id');
    }
}
