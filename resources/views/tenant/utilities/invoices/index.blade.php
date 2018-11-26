@extends('tenant.layouts.default')

@section('title', page_title('Utilities Invoices'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item active'>Invoices</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Utilities Invoices</h4>

                    <aside>
                        <div class="btn-group" role="group">
                            <a href="{{ route('tenant.utilities.invoices.create') }}" class="btn btn-link">
                                Add Invoice
                            </a>
                            <!-- Filters link -->
                            <a href="#filters" data-toggle="collapse" aria-expanded="false" aria-controls="filters"
                               class="btn btn-link" id="toggle-filters">
                                Filters
                            </a>
                            <div class="btn-group">
                                <div class="btn-group dropdown" role="group">
                                    <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span data-toggle="tooltip" title="Download a quick report"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">Generate PDF report</h6>
                                        <a href="{{ route('tenant.utilities.invoices.reports.download', array_except(request()->query(), 'page')) }}"
                                           class="dropdown-item">
                                            From Current Results
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <a href="{{ route('tenant.utilities.invoices.reports.download') }}"
                                           class="dropdown-item">
                                            All
                                        </a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light" id="customReportsButton" data-toggle="modal"
                                        data-target="#reportsModal">
                                    <span data-toggle="tooltip" title="Customize report">
                                        <i class="fa fa-cog"></i> Reports
                                    </span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

            {{-- Filters --}}
            @include('tenant.utilities.invoices.partials._filters')
        </div>
    </div>

    @if($invoices->count())
        <div class="table-responsive-sm mb-3">
            <table class="table table-hover table-outline mb-0">
                <thead class="thead-light">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="selectAll">
                            <label class="custom-control-label" for="selectAll">&nbsp;</label>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Property</th>
                    <th>Utility</th>
                    <th>
                        <span data-toggle="tooltip" title="Date (or period) for invoice billing">
                            For <i class="icon-info"></i>
                        </span>
                    </th>
                    <th>
                        <span data-toggle="tooltip" title="Taken and calculated from the associated utility">
                            Amt. <i class="icon-info"></i>
                        </span>
                    </th>
                    {{-- todo: Create settings to hold company utilities invoice field preferences --}}
                    {{--<th>--}}
                    {{--<span data-toggle="tooltip" title="Date invoice was sent or is set to be sent">--}}
                    {{--Sent <i class="icon-info"></i>--}}
                    {{--</span>--}}
                    {{--</th>--}}
                    <th>
                        <span data-toggle="tooltip" title="Date payment due">
                            Due <i class="icon-info"></i>
                        </span>
                    </th>
                    {{--<th>--}}
                    {{--<span data-toggle="tooltip" title="Overdue balance">--}}
                    {{--Bal. <i class="icon-info"></i>--}}
                    {{--</span>--}}
                    {{--</th>--}}
                    <th>
                        <span data-toggle="tooltip" title="Date balance cleared">
                        Cleared <i class="icon-info"></i>
                        </span>
                    </th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $invoice)
                    <tr class="{{ invoiceContext($invoice) }}">
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="invoice{{ $invoice->id }}">
                                <label class="custom-control-label" for="invoice{{ $invoice->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $invoice->user->name }}</td>
                        <td>{{ $invoice->property->name }}</td>
                        <td>{{ $invoice->utility->name }}</td>
                        <td>{{ $invoice->formattedInvoiceMonth }}</td>
                        <td>{{ $invoice->formattedAmount }}</td>
                        {{-- todo: Create settings to hold company rent invoice field preferences --}}
                        {{--<td>{{ $invoice->formattedSentAt }}</td>--}}
                        <td>{{ $invoice->formattedDueAt }}</td>
                        {{--<td>{{ $invoice->formattedOutstandingBalance }}</td>--}}
                        <td>{{ $invoice->formattedClearedAt }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.utilities.invoices.edit', $invoice) }}">
                                    <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                </a>
                                <!-- Delete Button -->
                                <a class="nav-link text-danger" role="button"
                                   href="{{ route('tenant.utilities.invoices.destroy', $invoice) }}"
                                   data-toggle="modal" data-target="#confirmModal"
                                   data-title="Invoice Delete Confirmation"
                                   data-message="Do you want to delete invoice #: <strong>{{ $invoice->hash_id }}</strong>?"
                                   data-warning="Invoice will only be removed if not sent or no payments have been made to it."
                                   data-type="danger"
                                   data-action="delete-invoice-{{ $invoice->id }}">
                                    <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                </a>
                                <!-- Invoice options -->
                                <div class="btn-group dropleft" role="group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split" type="button"
                                            id="tenant-{{ $invoice->id }}-MenuButton" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" title="Invoice options">
                                        &nbsp;<i class="icon-options"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="tenant-{{ $invoice->id }}-MenuButton">
                                        <h6 class="dropdown-header">#{{ $invoice->hash_id }} | More options</h6>
                                        {{-- Due options --}}
                                        @if(!$invoice->cleared_at)
                                            <a class="dropdown-item"
                                               href="{{ route('tenant.utilities.invoices.clear', $invoice) }}">
                                                Issue Receipt
                                            </a>
                                            <a href="{{ route('tenant.utilities.invoices.remind', $invoice) }}"
                                               class="dropdown-item">
                                                Send Reminder
                                            </a>
                                        @else
                                            <a href="{{ route('tenant.utilities.invoices.payments.index', $invoice) }}"
                                               class="dropdown-item">
                                                Payment History
                                            </a>
                                        @endif
                                        <a href="{{ route('tenant.utilities.invoices.show', $invoice) }}"
                                           class="dropdown-item">
                                            View
                                        </a>
                                        <a href="{{ route('account.invoices.utilities.preview', $invoice) }}"
                                           class="dropdown-item" target="_blank">
                                            Preview Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.utilities.invoices.destroy', $invoice) }}" method="POST"
                                  id="delete-invoice-{{ $invoice->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($invoices->perPage() < $invoices->total())
            <div class="mb-3">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')

        <!-- Reports Modal -->
        <div class="modal fade" id="reportsModal" tabindex="-1" role="dialog" aria-labelledby="reportsModal"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Generate Utility Invoice Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('tenant.utilities.invoices.reports.download') }}" id="reportsForm">
                                <div class="form-group row">
                                    <label for="report_name" class="control-label col-md-4">Report name</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="report_name" class="form-control" id="report_name"
                                                   placeholder="name of the report" maxlength="100">

                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    Utilities Invoices Report
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('tenant.utilities.partials.form._utilities')

                                <div class="form-group row">
                                    <label class="control-label col-md-4">Status</label>
                                    <div class="col-md-6">
                                        <!-- Due -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="due" name="status"
                                                   class="custom-control-input report_status"
                                                   value="due" data-range="due">
                                            <label class="custom-control-label" for="due">
                                                Due
                                            </label>
                                        </div>
                                        <!-- Past Due -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="past_due" name="status"
                                                   class="custom-control-input report_status" value="past_due"
                                                   data-range="due">
                                            <label class="custom-control-label" for="past_due">
                                                Past Due
                                            </label>
                                        </div>
                                        <!-- Cleared -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="cleared" name="status"
                                                   class="custom-control-input report_status"
                                                   value="cleared" data-range="cleared">
                                            <label class="custom-control-label" for="cleared">
                                                Cleared
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-4">Duration</label>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="today" name="range"
                                                   class="custom-control-input range" value="today">
                                            <label class="custom-control-label" for="today">
                                                Today
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="week" name="range"
                                                   class="custom-control-input range" value="week">
                                            <label class="custom-control-label" for="week">
                                                This Week
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="month" name="range"
                                                   class="custom-control-input range" value="month">
                                            <label class="custom-control-label" for="month">
                                                This Month
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-4">Order by Amount</label>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="amt_max_min" name="order_amount"
                                                   class="custom-control-input" value="desc">
                                            <label class="custom-control-label" for="amt_max_min">
                                                Max - min
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="amt_min_max" name="order_amount"
                                                   class="custom-control-input" value="asc">
                                            <label class="custom-control-label" for="amt_min_max">
                                                Min - Max
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="report_type" class="control-label col-md-4">Type</label>
                                    <div class="col-md-6">
                                        <select name="report_type" class="custom-select" id="report_type">
                                            <option value="pdf" selected>PDF</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                                onclick="document.getElementById('reportsForm').submit()">
                            Generate
                        </button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @else
        <div class="card card-body">
            <div class="card-text">No invoices found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')

    <script>
        var button = $('#toggle-filters').first() // Button that triggered the collapse

        $('#filters').on('shown.bs.collapse', function (event) {
            button.html("Hide Filters")
        }).on('hide.bs.collapse', function (event) {
            button.html("Filters")
        })
    </script>

    <script>
        $('#reportsForm input.report_status').on('change', (event) => {
            var newName = $('input.report_status:checked').data('range')
            var input = $('input.range')
            input.removeAttr('name')
            input.attr('name', newName)

            event.preventDefault()
        })
    </script>

    {{--<script>--}}
    {{--introJs().setOptions({--}}
    {{--hints: [--}}
    {{--{ hint: 'Download a quick report', element: '#quickReportsButton' },--}}
    {{--{ hint: 'Click here to download a custom report', element: '#customReportsButton' }--}}
    {{--]--}}
    {{--})--}}
    {{--</script>--}}
@endpush
