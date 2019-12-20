@extends('admin.layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/totem/css/app.css') }}">
@endsection

@section('container')
<main id="root">
    <div class="uk-container uk-section">
        <div class="uk-grid">
            @include('admin.partials.sidebar')
            <section class="uk-width-5-6@l">
                @include('admin.partials.alerts')
                @yield('main-panel-before')
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @yield('title')
                    </div>
                    <div class="uk-card-body">
                        @yield('main-panel-content')
                    </div>
                    <div class="uk-card-footer">
                        @yield('main-panel-footer')
                    </div>
                </div>
                @yield('main-panel-after')
                @yield('additional-panels')
                <div class="uk-margin-top">
                    @include('admin.partials.footer')
                </div>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('/vendor/totem/js/app.js') }}"></script>
@endpush
