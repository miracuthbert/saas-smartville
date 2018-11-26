<?php

namespace Smartville\Http\Tenant\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Smartville\Domain\Utilities\Rules\UtilityProperties;

class UtilityInvoiceStoreRequest extends FormRequest
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
//            'due_at' => 'required|date|after:sent_at',
            'properties.*.id' => [
                'required',
                Rule::exists('properties', 'id')->whereNotNull('occupied_at'),
            ],
            'properties.*.previous' => [
                'bail',
                $this->request->get('billing_type') == 'fixed' ? 'nullable' : 'required_with:properties.*.id',
                'numeric',
            ],
            'properties.*.current' => [
                'bail',
                $this->request->get('billing_type') == 'fixed' ? 'nullable' : 'required_with:properties.*.previous',
                'numeric',
                'gte:properties.*.previous',
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
        // replace sent_at attribute with parsed carbon datetime
        if ($sentAt = $this->request->get('sent_at')) {
            $this->request->set('sent_at', Carbon::parse($sentAt)->toDateTimeString());
        }

        // filter out unselected properties
        $filteredProperties = collect($this->request->get('properties'))->filter(function ($property) {
            return isset($property['id']);
        });

        // replace properties attribute with filtered properties
        $this->request->set(
            'properties',
            $filteredProperties->isNotEmpty() ? $filteredProperties->toArray() : $this->request->get('properties')
        );

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
            'due_at' => 'due at',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'properties.*.id.required' => 'At least one property is required.',
            'properties.*.previous.required_with' => 'The previous field is required when property is present.',
            'properties.*.previous.numeric' => 'The previous field must be a number.',
            'properties.*.current.required_with' => 'The current field is required when property is present.',
            'properties.*.current.numeric' => 'The current field must be a number.',
        ];
    }
}
