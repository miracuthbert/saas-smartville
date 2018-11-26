@if(count(array_intersect(array_keys(request()->query()), array_keys($filters_mappings))))
    <section>
        <p>Results filtered by</p>

        <ul class="list-inline">
            @foreach(array_intersect(array_keys(request()->query()), array_keys($filters_mappings)) as $key)
                <li class="list-inline-item">
                    {{ array_get($filters_mappings, "{$key}.heading") }}&colon;
                    <span class="text-muted">
                        {{ array_get($filters_mappings, "{$key}.map." . request($key)) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </section>
@endif
