<?php

namespace Smartville\Http\Tenant\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaseInvoiceStoreRequest extends FormRequest
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
            'properties.*.id' => [
                'required',
                Rule::exists('properties', 'id')->whereNotNull('occupied_at'),
            ],
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'sent_at' => [
                'required',
                'date',
                'after:today'
            ],
            'due_at' => 'required|date|after:sent_at',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        //replace due_at attribute with parsed carbon datetime
        if ($dueAt = $this->request->get('due_at')) {
            $this->request->set('due_at', Carbon::parse($dueAt)->toDateTimeString());
        }

        //replace sent_at attribute with parsed carbon datetime
        if ($sentAt = $this->request->get('sent_at')) {
            $this->request->set('sent_at', Carbon::parse($sentAt)->toDateTimeString());
        }

        $this->request->set('today', now()->subDay());

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
            'property_id' => 'property',
            'start_at' => 'start',
            'end_at' => 'to',
            'sent_at' => 'send at',
            'due_at' => 'due at',
        ];
    }
}
