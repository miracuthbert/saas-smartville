<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureStoreRequest extends FormRequest
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
            'name' => 'required|unique:features|max:100',
            'overview' => 'required|max:160',
            'description' => 'required',
            'order' => [
                'nullable',
                'required_with:node',
                Rule::in('before', 'after', 'child'),
            ],
            'node' => [
                'nullable',
                'required_with:order',
                Rule::exists('features', 'id')
            ],
            'body' => 'nullable',
            'usable' => 'required|boolean',
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
            'node' => 'page',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'node.exists' => 'The :attribute cannot be a descendant of itself.'
        ];
    }
}
