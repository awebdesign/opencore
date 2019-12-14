{!! $header !!}
{!! $column_left !!}
<!-- Bootstrap Boilerplate... -->
<div id="content">
    <div class="container-fluid">
        <!-- Display Validation Errors -->
        @include('admin.common.errors')

        @yield('content')
    </div>
</div>
{!! $footer !!}
