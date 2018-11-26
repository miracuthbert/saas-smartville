<?php

namespace Smartville\Http\Account\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
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
            'name' => 'required|max:160',
            'short_name' => 'required|alpha|max:7|unique:companies',
            'email' => [
                'required',
                'email',
                Rule::unique('companies', 'email')
            ],
            'country' => [
                'required',
                Rule::exists('countries', 'name')->where('usable', true)
            ],
            'currency' => [
                'required',
                Rule::exists('currencies', 'cc')->where('usable', true)
            ],
            'timezone' => [
                'required',
            ],
        ];
    }
}
