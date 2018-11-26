<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageStoreRequest extends FormRequest
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
            'title' => 'required|max:250',
            'uri' => 'required|unique:pages|max:250',
            'name' => 'nullable|unique:pages|max:250',
            'template' => 'nullable',
            'order' => [
                'nullable',
                'required_with:node',
                Rule::in('before', 'after', 'child'),
            ],
            'node' => [
                'nullable',
                'required_with:order',
                Rule::exists('pages', 'id')
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
            'node.exists' => 'the :attribute cannot be a descendant of itself.'
        ];
    }
}
