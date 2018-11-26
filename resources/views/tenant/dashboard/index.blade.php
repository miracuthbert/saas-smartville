@extends('tenant.layouts.default')

@section('title', page_title('Dashboard'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Dashboard</li>
@endsection

@section('tenant.content')
    <header class="mb-3">
        <div class="row">
            <div class="col-sm-12">
                <h4>{{ config('app.name') }} Dashboard</h4>
            </div>
        </div>
    </header>
    <section class="mb-3">
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h4 m-0">{{ $properties_occupancy_rate }}%</div>
                        <div>Current Month Occupancy</div>
                        <div class="progress progress-xs my-3">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $properties_occupancy_rate }}%"
                                 aria-valuenow="{{ $properties_occupied_count }}" aria-valuemin="0"
                                 aria-valuemax="{{ $property_count }}"></div>
                        </div>
                        <small class="text-muted">The percentage of properties occupied this month.</small>
                    </div>
                </div>
            </div>
            <!--/.col-->
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h4 m-0">{{ $rent_invoices_cleared_total }}</div>
                        <div>Monthly Rent Income</div>
                        <div class="progress progress-xs my-3">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: {{ $rent_invoices_clearance_rate }}%"
                                 aria-valuenow="{{ $rent_invoices_cleared_count }}" aria-valuemin="0"
                                 aria-valuemax="{{ $rent_invoices_count }}"></div>
                        </div>
                        <small class="text-muted">Rent payments due Vs. collected this month.</small>
                    </div>
                </div>
            </div><!--/.col-->
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h4 m-0">{{ $utility_invoices_cleared_total }}</div>
                        <div>Monthly Utility Income</div>
                        <div class="progress progress-xs my-3">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ $utility_invoices_clearance_rate }}%"
                                 aria-valuenow="{{ $utility_invoices_cleared_count }}" aria-valuemin="0"
                                 aria-valuemax="{{ $utility_invoices_count }}"></div>
                        </div>
                        <small class="text-muted">Utility payments due Vs. collected this month.</small>
                    </div>
                </div>
            </div><!--/.col-->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Current Month Activity</h6>
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="callout callout-info">
                                            <small class="text-muted">New Tenants</small>
                                            <br>
                                            <strong class="h4">{{ $new_tenants_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                    <div class="col-sm-6">
                                        <div class="callout callout-danger">
                                            <small class="text-muted">Vacated Tenants</small>
                                            <br>
                                            <strong class="h4">{{ $vacated_tenants_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                </div><!--/.row-->
                                <hr class="mt-0">
                                <h6>Tenants Activity This Week</h6>
                                <ul class="horizontal-bars">
                                    @foreach($current_week_tenants as $day => $value)
                                        <li>
                                            <div class="title">
                                                {{ $day }}
                                            </div>
                                            <div class="bars">
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                         style="width: {{ $value['new']['rate'] }}%"
                                                         aria-valuenow="{{ $value['new']['count'] }}" aria-valuemin="0"
                                                         aria-valuemax="{{ $current_week_new_tenants_count }}"></div>
                                                </div>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                         style="width: {{ $value['vacated']['rate'] }}%"
                                                         aria-valuenow="{{ $value['vacated']['count'] }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="{{ $current_week_vacated_tenants_count }}"></div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li class="legend">
                                        <span class="badge badge-pill badge-info"></span>
                                        <small>New tenants ({{ $current_week_new_tenants_count }})</small> &nbsp;
                                        <span class="badge badge-pill badge-danger"></span>
                                        <small>Vacated tenants ({{ $current_week_vacated_tenants_count }})</small>
                                    </li>
                                </ul>
                            </div><!--/.col-->
                            <div class="col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="callout callout-info">
                                            <small class="text-muted">Rent Invoices</small>
                                            <br>
                                            <strong class="h4">{{ $rent_invoices_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                    <div class="col-sm-6">
                                        <div class="callout callout-success">
                                            <small class="text-muted">Cleared</small>
                                            <br>
                                            <strong class="h4">{{ $rent_invoices_cleared_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-4" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                </div><!--/.row-->
                                <hr class="mt-0">
                                <ul class="list-unstyled">
                                    @if($rentInvoices->count())
                                        @foreach($rentInvoices as $invoice)
                                            <li class="media">
                                                <h3 class="h-100 mb-0 mr-2 mt-1 px-2 py-1 {{ invoiceContext($invoice, 'bg', 'bg-secondary') }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $invoice->status }}">
                                                    <div class="text-center">
                                                        <small><i class="icon-credit-card"></i></small>
                                                    </div>
                                                </h3>
                                                <div class="media-body border-bottom border-secondary">
                                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-content-center">
                                                        <div class="flex-fill">
                                                            <div class="title">{{ $invoice->user->name }}</div>
                                                            <small>{{ $invoice->property->name }}</small>
                                                        </div>
                                                        <div class="flex-fill text-sm-right">
                                                            <div class="small text-muted">
                                                                {{ $invoice->formattedInvoiceMonth }}
                                                            </div>
                                                            <strong>{{ $invoice->formattedAmount }}</strong>
                                                        </div>
                                                        <!-- Actions -->
                                                        <div class="text-sm-center">
                                                            <a href="{{ route('tenant.rent.invoices.show', $invoice) }}"
                                                               class="btn btn-link btn-sm" role="button">
                                                                <span class="d-sm-none">View</span>
                                                                <i class="icon-arrow-right small"></i>
                                                            </a>
                                                        </div>
                                                    </div><!-- /.d-flex -->
                                                </div><!-- /.media-body -->
                                            </li><!-- /.media -->
                                        @endforeach
                                        {{-- Show more link --}}
                                        <li class="divider text-center">
                                            <a href="{{ route('tenant.rent.invoices.index', ['due' => 'month']) }}"
                                               class="btn btn-sm btn-link text-muted" role="button"
                                               data-toggle="tooltip"
                                               data-placement="top" title="show more">
                                                <i class="icon-options"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="text-center">
                                            No rent invoices due this month.
                                        </li>
                                    @endif
                                </ul>
                            </div><!--/.col-->
                            <div class="col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="callout">
                                            <small class="text-muted">Utility Invoices</small>
                                            <br>
                                            <strong class="h4">{{ $utility_invoices_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-5" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                    <div class="col-sm-6">
                                        <div class="callout callout-primary">
                                            <small class="text-muted">Cleared</small>
                                            <br>
                                            <strong class="h4">{{ $utility_invoices_cleared_count }}</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-6" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/.col-->
                                </div><!--/.row-->
                                <hr class="mt-0">
                                <ul class="list-unstyled">
                                    @if($utilityInvoices->count())
                                        @foreach($utilityInvoices as $invoice)
                                            <li class="media">
                                                <h3 class="h-100 mb-0 mr-2 mt-1 px-2 py-1 {{ invoiceContext($invoice, 'bg', 'bg-secondary') }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $invoice->status }}">
                                                    <div class="text-center">
                                                        <small><i class="icon-credit-card"></i></small>
                                                    </div>
                                                </h3>
                                                <div class="media-body border-bottom border-secondary">
                                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-content-center">
                                                        <div class="flex-fill">
                                                            <div class="title">{{ $invoice->user->name }}</div>
                                                            <small>{{ $invoice->property->name }}</small>
                                                        </div>
                                                        <div class="flex-fill text-sm-right">
                                                            <div class="small text-muted">
                                                                {{ $invoice->utility->name }} &dash; {{ $invoice->formattedInvoiceMonth }}
                                                            </div>
                                                            <div><strong>{{ $invoice->formattedAmount }}</strong></div>
                                                        </div>
                                                        <!-- Actions -->
                                                        <div class="text-sm-center">
                                                            <a href="{{ route('tenant.utilities.invoices.show', $invoice) }}"
                                                               class="btn btn-link btn-sm" role="button">
                                                                <span class="d-sm-none">View</span>
                                                                <i class="icon-arrow-right small"></i>
                                                            </a>
                                                        </div>
                                                    </div><!-- /.d-flex -->
                                                </div><!-- /.media-body -->
                                            </li><!-- /.media -->
                                        @endforeach
                                        {{-- Show more link --}}
                                        <li class="divider text-center">
                                            <a href="{{ route('tenant.utilities.invoices.index', ['due' => 'month']) }}"
                                               class="btn btn-sm btn-link text-muted" role="button"
                                               data-toggle="tooltip"
                                               data-placement="top" title="show more">
                                                <i class="icon-options"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="text-center">
                                            No utility invoices due this month.
                                        </li>
                                    @endif
                                </ul>
                            </div><!--/.col-->
                        </div><!--/.row-->
                    </div>
                </div>
            </div><!--/.col-->
        </div><!--/.row-->
    </section>
@endsection