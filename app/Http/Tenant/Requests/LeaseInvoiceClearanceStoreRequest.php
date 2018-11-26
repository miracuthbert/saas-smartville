<?php

namespace Smartville\Http\Tenant\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class LeaseInvoiceClearanceStoreRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'paid_at' => 'required|date',
            'description' => 'nullable|max:300',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        //replace paid_at attribute with parsed carbon datetime
        if ($paidAt = $this->request->get('paid_at')) {
            $this->request->set('paid_at', Carbon::parse($paidAt)->toDateTimeString());
        }

        return parent::validationData();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'paid_at' => 'paid at',
            'cleared_at' => 'cleared at',
        ];
    }
}
