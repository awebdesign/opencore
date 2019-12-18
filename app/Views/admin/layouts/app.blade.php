@hasSection('header')
    @yield('header')
@else
    {!! $header !!}
@endif
@yield('styles')
@hasSection('column_left')
    @yield('column_left')
@else
    {!! $column_left !!}
@endif

<!-- Bootstrap Boilerplate... -->
<div id="content">
    <div class="container-fluid">
        <div class="page-header">
            <!-- Display Validation Errors -->
            @include('admin.common.errors')

            @yield('content')
        </div>
    </div>
</div>

@yield('modals')
@yield('scripts')

@hasSection('footer')
    @yield('footer')
@else
    {!! $footer !!}
@endif
