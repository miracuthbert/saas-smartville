<?php

namespace Smartville\Http\Issue\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueStoreRequest extends FormRequest
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
            'title' => 'required|max:100',
            'body' => 'required|max:5000',
            'property_id' => 'required|exists:properties,id',
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
            'body' => 'details',
            'property_id' => 'property',
        ];
    }


}
