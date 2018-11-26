@extends('tenant.layouts.default')

@section('title', page_title("Utility Invoice | Payment History"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.show', $utilityInvoice) }}">{{ $utilityInvoice->hash_id }}</a>
    </li>
    <li class='breadcrumb-item active'>Payments</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Invoice Payments</h4>

                    <aside>
                        <div class="btn-group" role="group">
                            @if(!$utilityInvoice->cleared_at)
                                <a href="{{ route('tenant.utilities.invoices.remind', $utilityInvoice) }}"
                                   class="btn btn-link btn-sm">
                                    Send Reminder
                                </a>
                                <a href="{{ route('tenant.utilities.invoices.clear', $utilityInvoice) }}"
                                   class="btn btn-link btn-sm">
                                    Issue Receipt
                                </a>
                            @endif

                            <a href="{{ route('account.invoices.utilities.preview', $utilityInvoice) }}"
                               class="btn btn-link btn-sm" target="_blank">
                                Preview
                            </a>
                            <a href="{{ route('account.invoices.utilities.pdf.download', $utilityInvoice) }}"
                               class="btn btn-outline-primary btn-sm">
                                Download
                            </a>
                        </div>
                    </aside>
                </div>
            </div>
            <hr>

            <!-- Invoice Summary -->
            <div class="media">
                <div class="media-body">
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Invoice #</span>
                        <strong>{{ $utilityInvoice->hash_id }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Utility</span>
                        <strong>{{ $utilityInvoice->utility->name }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>For</span>
                        <strong>{{ $utilityInvoice->formattedInvoiceMonth }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Property</span>
                        <strong>{{ $utilityInvoice->property->name }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Tenant</span>
                        <strong>{{ $utilityInvoice->user->name }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Due</span>
                        <strong>{{ $utilityInvoice->formattedDueAt }}</strong>
                    </article>
                    <article class="d-flex justify-content-between align-content-center">
                        <span>Cleared On</span>
                        <strong>{{ $utilityInvoice->formattedClearedAt }}</strong>
                    </article>
                </div>
            </div>
            <hr>

            @if($payments->count())
                <div class="table-responsive-sm">
                    <table class="table table-hover table-borderless">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Paid at</th>
                            <th scope="col">Processed by</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                <td>{{ $payment->formattedAmount }}</td>
                                <td>{{ $payment->formattedPaidAt }}</td>
                                <td>{{ $payment->admin->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- View -->
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#paymentModal"
                                                data-title="{{ $payment->hash_id }}"
                                                data-amount="{{ $payment->formattedAmount }}"
                                                data-admin="{{ $payment->admin->name }}"
                                                data-description="{{ $payment->description }}">
                                            View
                                        </button>

                                        <!-- Download -->
                                        <a href="{{ route('account.payments.utilities.pdf.download', $payment) }}"
                                           class="btn btn-outline-primary btn-sm" role="button">Download</a>

                                        <!-- Cancle -->
                                        <a class="btn btn-danger btn-sm" role="button"
                                           href="{{ route('tenant.utilities.invoices.payments.destroy', [$utilityInvoice, $payment]) }}"
                                           data-toggle="modal" data-target="#confirmModal"
                                           data-title="Revoke Payment Confirmation"
                                           data-message="Do you want to cancel payment #: <strong>{{ $payment->hash_id }}</strong>?"
                                           data-warning="Payment will removed from history and invoice will be updated to match the changes."
                                           data-type="danger"
                                           data-action="cancel-payment-{{ $payment->id }}">
                                            Cancel
                                        </a>
                                    </div>

                                    {{-- Cancel Payment Form --}}
                                    <form action="{{ route('tenant.utilities.invoices.payments.destroy', [$utilityInvoice, $payment]) }}"
                                          method="POST" id="cancel-payment-{{ $payment->id }}" style="display: none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Amount Charged -->
                        <tr>
                            <th scope="row">Amount Charged</th>
                            <th scope="row" colspan="4">{{ $utilityInvoice->formattedAmount }}</th>
                        </tr>
                        <!-- Amount Paid -->
                        <tr>
                            <th scope="row">Amount Paid</th>
                            <th scope="row" colspan="4">{{ $utilityInvoice->formattedPaymentTotal }}</th>
                        </tr>
                        <!-- Overdue Bal. -->
                        <tr>
                            <th scope="row">Overdue Bal.</th>
                            <th scope="row" colspan="4">{{ $utilityInvoice->formattedOutstandingBalance }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
                     aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Amount</h5>
                                <p id="amount"></p>

                                <h5>Description</h5>
                                <div id="description" class="mb-3"></div>

                                <h5>Processed by</h5>
                                <p id="admin"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Modal -->
                @includeIf('layouts.partials.modals._js_confirm_modal')

            @else
                <p>No payments found.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')

    <script>
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var title = button.data('title') // Extract info from data-* attributes
            var description = button.data('description') // Extract info from data-* attributes
            var amount = button.data('amount') // Extract info from data-* attributes
            var admin = button.data('admin') // Extract info from data-* attributes
            var modal = $(this)
            modal.find('.modal-title').text('Payment # ' + title)
            modal.find('.modal-body #description').html(description)
            modal.find('.modal-body p#amount').html(amount)
            modal.find('.modal-body p#admin').html(admin)
        })
        $('#paymentModal').modal('handleUpdate')
    </script>
@endpush