@hasSection('header')
    @yield('header')
@else
    {!! $header !!}
@endif
<style>
.navbar-inverse {
    background-color: #1a237e;
    border-color: #1a237e;
}

.navbar-inverse .navbar-nav>.active>a,
.navbar-inverse .navbar-nav>.active>a:focus,
.navbar-inverse .navbar-nav>.active>a:hover {
    background-color: #3949ab;
}

.navbar-inverse .navbar-brand {
    color: #c5cae9;
}

.navbar-inverse .navbar-nav>li>a {
    color: #c5cae9;
}

.navbar-fixed-top {
    border: 0;
}
</style>
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

            {{-- Navbar --}}
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="{{ route('admin::core.logs.dashboard') }}" class="navbar-brand">
                            <i class="fa fa-cube"></i> Core
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="nav navbar-nav">
                            <li class="{{ Route::is('admin::core.logs.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.logs.dashboard') }}">
                                    <i class="fa fa-dashboard"></i> Dashboard
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.logs.list') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.logs.list') }}">
                                    <i class="fa fa-archive"></i> Logs
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.home') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.home') }}">
                                    <i class="fa fa-folder-open-o fw"></i> Task Test
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            {{-- Main container --}}

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
