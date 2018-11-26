<div class="mb-3">
    <form action="{{ route('tenant.rent.invoices.index', array_merge(request()->query(), ['page' => 1])) }}">
        @foreach(array_intersect(array_keys(request()->query()), array_keys($filters_mappings)) as $key)
            <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
        @endforeach

        <div class="form-group">
            <label for="tenant">Filter by tenant</label>
            <div class="input-group">
                <input type="text" name="tenant" class="form-control" id="tenant"
                       placeholder="tenant name..." value="{{ request('tenant') }}">

                <div class="input-group-append">
                    @if(request($key = 'tenant'))
                        <a class="btn btn-secondary"
                           href="{{ route('tenant.rent.invoices.index', array_except(request()->query(), [$key, 'page'])) }}">
                            <i class="fa fa-times-circle"></i> Clear
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div><!-- /.form-group -->
    </form>
</div>

<section class="collapse mb-3" id="filters">
    <p>Filter results by the options below</p>

    <!-- Lease Invoices Filters -->
    @foreach(collect($filters_mappings)->chunk(4) as $mappings)
        <div class="row">
            @foreach($mappings as $key => $filter)
                <div class="col-sm-3">
                    @include('tenant.rent.partials._filter', [
                        'map' => $filter['map'],
                        'key' => $key,
                        'heading' => isset($filter['heading']) ? $filter['heading'] : '',
                        'style' => isset($filter['style']) ? $filter['style'] : '',
                    ])
                </div>
            @endforeach
        </div>
        @if (!$loop->last)
            <hr>
        @endif
    @endforeach

    <hr>
    <div class="mb-3">
        @if(count(array_intersect(array_keys(request()->query()), array_keys($filters_mappings))))
            <a class=" btn btn-primary" href="{{ route('tenant.rent.invoices.index') }}">
                <i class="fa fa-times-circle"></i> Clear all filters
            </a>
        @endif
        <button type="button" data-target="#filters" data-toggle="collapse" aria-expanded="false"
                aria-controls="filters"
                class="btn btn-link">
            Hide Filters
        </button>
    </div>
</section>

@selectedfilters(['filters_mappings' => $filters_mappings])
@endselectedfilters
