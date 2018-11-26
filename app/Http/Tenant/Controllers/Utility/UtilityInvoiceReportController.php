<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use PDF;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class UtilityInvoiceReportController extends Controller
{
    /**
     * Handles report download.
     *
     * @param Request $request
     * @return mixed
     */
    public function download(Request $request)
    {
        $company = $request->tenant();

        $invoices = $company->utilityInvoices()
            ->filter($request)
            ->with('utility', 'property', 'user', 'payments.invoice')
            ->get();

        if ($reportType = $request->get('report_type', 'pdf')) {
            return $this->{$reportType}($request, $invoices);
        }

        return $this->pdf($request, $invoices);
    }

    /**
     * Generate's pdf report.
     *
     * @param Request $request
     * @param $invoices
     * @return mixed
     */
    public function pdf(Request $request, $invoices)
    {
        $company = $request->tenant();

        $date = now($company->timezone)->toDayDateTimeString();

        $title = title_case($request->get('report_name') . ' Utilities Invoices Report');

        $pdf = PDF::loadView('reports.utilities.pdf.default', compact('invoices', 'title', 'date'));

        return $pdf->setPaper('a4', 'landscape')->download($this->getFilename($request));
    }

    /**
     * Generate's the name of the report.
     *
     * @param Request $request
     * @return string
     */
    private function getFilename(Request $request)
    {
        $company = $request->tenant();

        $extType = $request->get('report_type', 'pdf');

        $prefix = $request->get('report_name', $company->name) . ' Utilities Invoices Report ';

        $name = str_slug($prefix . now()->timestamp);

        return "{$name}.{$extType}";
    }
}
