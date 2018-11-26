<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyStoreRequest extends FormRequest
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
            'cc' => [
                'required',
                Rule::unique('currencies', 'cc')->ignore($this->currency->id)
            ],
            'symbol' => 'required',
            'name' => 'required|max:255',
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
            'cc' => 'currency code'
        ];
    }
}
