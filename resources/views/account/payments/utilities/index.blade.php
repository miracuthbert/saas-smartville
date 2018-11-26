@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Utility Payments</h4>
        <p>List of utilities payment history.</p>
    </header>
    <hr>

    <section>
        @if($payments->count())
            <div class="table-responsive">
                <table class="table table-hover table-borderless">
                    <thead>
                    <tr>
                        <th scope="col">Utility</th>
                        <th scope="col">Property</th>
                        <th scope="col">For</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Paid at</th>
                        <th scope="col">Processed by</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->utility->name }}</td>
                            <td>{{ $payment->property->name }}</td>
                            <td>{{ $payment->invoice->formattedInvoiceMonth }}</td>
                            <td>{{ $payment->formattedAmount }}</td>
                            <td>{{ $payment->formattedPaidAt }}</td>
                            <td>{{ $payment->admin->name }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                            data-target="#paymentModal"
                                            data-title="{{ $payment->hash_id }}"
                                            data-company="{{ $payment->property->company->name }}"
                                            data-property="{{ $payment->property->name }}"
                                            data-utility="{{ $payment->utility->name }}"
                                            data-for-date="{{ $payment->invoice->formattedInvoiceMonth }}"
                                            data-amount="{{ $payment->formattedAmount }}"
                                            data-admin="{{ $payment->admin->name }}"
                                            data-description="{{ $payment->description }}">
                                        View
                                    </button>
                                    <a href="{{ route('account.payments.utilities.pdf.download', $payment) }}"
                                       class="btn btn-outline-primary btn-sm" role="button">Download</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{ $payments->links() }}
        @else
            <p>No utilities payment history found.</p>
        @endif
    </section>

    <!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
         aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl>
                        <dt>Company</dt>
                        <dd id="company"></dd>

                        <dt>Property</dt>
                        <dd id="property"></dd>

                        <dt>Utility</dt>
                        <dd id="utility"></dd>

                        <dt>For</dt>
                        <dd id="for-date"></dd>

                        <dt>Amount</dt>
                        <dd id="amount"></dd>

                        <dt>Description</dt>
                        <dd>
                            <pre id="description" class="mb-3"></pre>
                        </dd>

                        <dt>Processed by</dt>
                        <dd id="admin"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var title = button.data('title') // Extract info from data-* attributes
            var company = button.data('company') // Extract info from data-* attributes
            var property = button.data('property') // Extract info from data-* attributes
            var utility = button.data('utility') // Extract info from data-* attributes
            var invcdate = button.data('for-date') // Extract info from data-* attributes
            var description = button.data('description') // Extract info from data-* attributes
            var amount = button.data('amount') // Extract info from data-* attributes
            var admin = button.data('admin') // Extract info from data-* attributes
            var modal = $(this)
            modal.find('.modal-title').text('Receipt # ' + title)
            modal.find('.modal-body #company').html(company)
            modal.find('.modal-body #property').html(property)
            modal.find('.modal-body #utility').html(utility)
            modal.find('.modal-body #for-date').html(invcdate)
            modal.find('.modal-body #description').html(description)
            modal.find('.modal-body dd#amount').html(amount)
            modal.find('.modal-body dd#admin').html(admin)
        })
        $('#paymentModal').modal('handleUpdate')
    </script>
@endsection