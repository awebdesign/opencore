@hasSection('opencart_header')
    @yield('opencart_header')
@else
    {!! $opencart_header !!}
@endif
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
            <nav class="navbar navbar-color">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">{{ trans('general.menu.toggle') }}</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="{{ route('admin::core.home') }}" class="navbar-brand"><i class="fa fa-cube"></i> OpenCore</a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="nav navbar-nav">
                            <li>
                                <a class="dropdown-toggle" href="#" id="modulesDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-cubes"></i> {{ trans('general.menu.modules') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @foreach($modulesLinks as $module)
                                    <li class="dropdown-header"><i class="fa fa-folder-open-o"></i> {{ $module['name'] }}</li>
                                        @foreach($module['links'] as $route => $translation)
                                        <li class="{{ Route::is($route) ? 'active' : '' }}">
                                            <a href="{{ route($route) }}">
                                                @lang($translation)
                                            </a>
                                        </li>
                                        @endforeach
                                    @endforeach
                                    <li class="{{ Route::is('admin::core.modules.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin::core.modules.index') }}"><i class="fa fa-gears"></i> <strong>{{ trans('general.menu.modules_management') }}</strong></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-toggle" href="#" id="systemDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-gears"></i> {{ trans('general.menu.system') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="systemDropdown">
                                    <li class="{{ Route::is('admin::core.system.routes') }}">
                                        <a href="{{ route('admin::core.system.routes') }}">
                                            <i class="fa fa-registered"></i> {{ trans('general.menu.routes') }}
                                        </a>
                                    </li>
                                    <li class="{{ Route::is('admin::core.system.requirements') ? 'active' : '' }}">
                                        <a href="{{ route('admin::core.system.requirements') }}">
                                            <i class="fa fa-life-ring"></i> {{ trans('general.menu.requirements') }}
                                        </a>
                                    </li>
                                    <li class="{{ Route::is('admin::core.system.clear-cache') ? 'active' : '' }}">
                                        <a href="{{ route('admin::core.system.clear-cache') }}">
                                            <i class="fa fa-eraser"></i> {{ trans('general.menu.clear_cache') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Display Validation Errors -->
            @include('admin.common.alerts')

            {{-- Main container --}}
            @yield('container')

            {{-- Footer --}}
            <footer class="footer-container">
                <div class="container-fluid">
                    <p class="text-muted pull-left">
                        Laravel <span class="label label-info">{{ app()->version() }}</span>
                    </p>
                    <p class="text-muted pull-right">
                        {{ trans('general.powered_by') }} <a href="https://opencore.ro">OpenCore</a>
                    </p>
                </div>
            </footer>
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
