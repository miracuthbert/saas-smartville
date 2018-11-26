@if($heading)
    <h6 class="ml-2">{{ $heading }}</h6>
@endif
<ul class="nav flex-column nav-pills">
    @foreach($map as $value => $name)
        @if($map instanceof \Illuminate\Support\Collection && $map->count() > 1 || is_array($map) && count($map) > 1)
            <li class="nav-item">
                <a class="nav-link{{ request($key) === $value ? ' active' : '' }}"
                   href="{{ route('tenant.utilities.invoices.index', array_merge(request()->query(), [$key => $value, 'page' => 1])) }}">
                    {{ $name }}
                </a>
            </li>
        @else
            <li class="nav-item pt-0">
                <a class="nav-link pt-0{{ request($key) === $value ? ' active' : '' }}"
                   href="{{ route('tenant.utilities.invoices.index', array_merge(request()->query(), [$key => $value, 'page' => 1])) }}">
                    {{ $name }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
@if(request($key))
    <div>
        <a class="btn btn-primary"
           href="{{ route('tenant.utilities.invoices.index', array_except(request()->query(), [$key, 'page'])) }}">
            <i class="fa fa-times-circle"></i> Clear
        </a>
    </div>
@endif
