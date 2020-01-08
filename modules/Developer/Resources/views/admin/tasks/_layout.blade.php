@extends('admin.layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/totem/css/app.css') }}">
@endsection

@section('inline.styles')
<style>
html {
    background:unset;
}
*+ul {
    margin-top: unset;
}
p {
    margin: 0 0 8.5px;
}
</style>
@endsection

@section('container')
<main id="root">
    <div class="uk-container uk-section">
        <div class="uk-grid">
            @include('developer::admin.partials.sidebar')
            <section class="uk-width-5-6@l">
                @include('developer::admin.partials.alerts')
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
                    @include('developer::admin.partials.footer')
                </div>
            </section>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="{{ asset('/vendor/totem/js/app.js') }}"></script>
@endsection
