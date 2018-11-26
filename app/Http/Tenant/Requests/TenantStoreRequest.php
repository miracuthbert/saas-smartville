<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantStoreRequest extends FormRequest
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
            'name' => 'required|max:30',
            'email' => 'required|email',
            'property_id' => [
                'required',
                Rule::exists('properties', 'id')->whereNull('occupied_at')
            ],
            'moved_in_at' => 'required|date',
            'moved_out_at' => 'nullable|date|after:moved_in_at',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        //replace property_id attribute with passed (route) property id
        if ($this->property && $this->property->exists) {
            $this->merge(['property_id' => $this->property->id]);
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
            'property_id' => 'property',
            'moved_in_at' => 'move in',
            'moved_out_at' => 'move out',
        ];
    }
}
