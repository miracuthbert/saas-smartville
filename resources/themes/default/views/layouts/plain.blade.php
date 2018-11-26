<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('layouts.partials._head')
    </head>
    <body>
        <div id="app">

            @include('layouts.partials._navigation')

            <main>
                @yield('content')
            </main>

            @include('layouts.partials._footer')
        </div>

        @include('layouts.partials._scripts')
    </body>
</html>
