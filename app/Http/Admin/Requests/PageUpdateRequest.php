<?php

namespace Smartville\Http\Admin\Requests;

use Illuminate\Validation\Rule;

class PageUpdateRequest extends PageStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            array_except(parent::rules(), array('uri', 'name', 'node')),
            [
                'uri' => [
                    'required',
                    'max:250',
                    Rule::unique('pages')->ignore($this->page->id),
                ],
                'name' => [
                    'nullable',
                    'max:250',
                    Rule::unique('pages')->ignore($this->page->id),
                ],
                'node' => [
                    'nullable',
                    'required_with:order',
                    Rule::exists('pages', 'id')->whereNot('id', array($this->page->id))
                ],
            ]
        );
    }
}
