<?php

namespace Smartville\Http\Account\Controllers\Company;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Company\Models\Company;

class CompanyUserSetupController extends Controller
{
    /**
     * Complete user's tenant invitation setup.
     *
     * @param Request $request
     * @param Company $company
     * @param UserInvitation $invitation
     * @return \Illuminate\Http\Response
     */
    public function resumeUserSetup(Request $request, Company $company, UserInvitation $invitation)
    {
        $confirm = $request->user()->resumeCompanyInvitation($company->id, $invitation->id);

        if (!$confirm) {
            return back()->withError("Failed setting you up for {$company->name} dashboard access.")
                ->with('error_link', route('account.companies.setup.resume', [$company, $invitation]))
                ->with('alert_link_name', 'Try setting up again');
        }

        return redirect()->route('tenant.switch', $company)
            ->withSuccess("Your now have access company's dashboard.");
    }
}
