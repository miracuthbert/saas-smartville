@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="mb-3">
        <issues endpoint="{{ route('account.issues.index') }}" :show-heading="true">
            <!-- New Property Issue -->
            <div class="mb-3">
                <new-property-issue endpoint="{{ route('account.issues.index') }}"
                                    :is-expanded="false"
                                    :autofocus="true"/>
            </div>
        </issues>
    </div>
@endsection
