<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Validation\Rule;

class RoleUpdateRequest extends RoleStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge((array_except(parent::rules(), 'name')), [
            'name' => [
                'required',
                'max:50',
                Rule::unique('company_roles', 'name')->ignoreModel($this->companyRole)
                ->where('company_id', $this->companyRole->company_id)
            ],
            'usable' => 'required|boolean',
        ]);
    }
}
