<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class TutorialUpdateRequest extends TutorialStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'node' => [
                'nullable',
                'required_with:order',
                Rule::exists('tutorials', 'id')->whereNot('id', array($this->tutorial->id))
            ],
        ];
    }
}
