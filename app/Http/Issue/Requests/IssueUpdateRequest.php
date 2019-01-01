<?php

namespace Smartville\Http\Issue\Requests;

class IssueUpdateRequest extends IssueStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_only(parent::rules(), [
            'body'
        ]);
    }
}
