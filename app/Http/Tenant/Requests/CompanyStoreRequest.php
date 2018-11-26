<?php

namespace Smartville\Http\Tenant\Requests;

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
            'email' => [
                'required',
                'email',
                Rule::unique('companies', 'email')->ignore(request()->tenant()->id)
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
