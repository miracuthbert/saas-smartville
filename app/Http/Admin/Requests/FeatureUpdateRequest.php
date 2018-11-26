<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class FeatureUpdateRequest extends FeatureStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            array_except(parent::rules(), array('name', 'node')),
            [
                'name' => [
                    'required',
                    'max:250',
                    Rule::unique('features')->ignore($this->feature->id),
                ],
                'node' => [
                    'nullable',
                    'required_with:order',
                    Rule::exists('features', 'id')->whereNot('id', array($this->feature->id))
                ],
            ]
        );
    }
}
