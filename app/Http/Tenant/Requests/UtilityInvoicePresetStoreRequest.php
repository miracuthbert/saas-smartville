<?php

namespace Smartville\Http\Tenant\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Smartville\Domain\Utilities\Rules\UtilityProperties;

class UtilityInvoicePresetStoreRequest extends FormRequest
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
        $now = Carbon::now();

        return [
            'utility_id' => [
                'required',
                Rule::exists('utilities', 'id')->where('usable', true),
                new UtilityProperties()
            ],
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'sent_at' => [
                'required',
                'date',
                "after:{$now->subDay()}"
            ],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        //replace sent_at attribute with parsed carbon datetime
        if ($sentAt = $this->request->get('sent_at')) {
            $this->request->set('sent_at', Carbon::parse($sentAt)->toDateTimeString());
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
            'utility_id' => 'utility',
            'start_at' => 'start',
            'end_at' => 'to',
            'sent_at' => 'send at',
        ];
    }
}
