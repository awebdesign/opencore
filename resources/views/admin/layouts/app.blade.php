@hasSection('opencart_header')
    @yield('opencart_header')
@else
    {!! $opencart_header !!}
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

@hasSection('opencart_column_left')
    @yield('opencart_column_left')
@else
    {!! $opencart_column_left !!}
@endif

<!-- Bootstrap Boilerplate... -->
<div id="content">
    <div class="container-fluid">
        <div class="page-header">
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
                        <a href="{{ route('admin::core.home') }}" class="navbar-brand">
                            <i class="fa fa-cube"></i> Core Home
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="nav navbar-nav">
                            <li class="{{ Route::is('admin::core.logs.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.logs.dashboard') }}">
                                    <i class="fa fa-dashboard"></i> Logs Dashboard
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.logs.list') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.logs.list') }}">
                                    <i class="fa fa-archive"></i> Logs
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.requirements') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.requirements') }}">
                                    <i class="fa fa-life-ring"></i> System Requirements
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.tasks.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('admin::core.tasks.dashboard') }}">
                                        <i class="fa fa-clock-o"></i> Cron Jobs
                                    </a>
                                </li>
                            <li class="{{ Route::is('admin::example') ? 'active' : '' }}">
                                <a href="{{ route('admin::example') }}">
                                    <i class="fa fa-folder-open-o fw"></i> Example
                                </a>
                            </li>
                            <li class="{{ Route::is('admin::core.clear-cache') ? 'active' : '' }}">
                                <a href="{{ route('admin::core.clear-cache') }}">
                                    <i class="fa fa-eraser"></i> Clear Cache
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Display Validation Errors -->
            @include('admin.common.alerts')

            {{-- Main container --}}
            @yield('container')
        </div>
    </div>
</div>

@yield('modals')
@stack('scripts')

@hasSection('opencart_footer')
    @yield('opencart_footer')
@else
    {!! $opencart_footer !!}
@endif
