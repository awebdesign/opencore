
@extends('admin.layouts.app')

{{--@section('meta.title')
TEST TITLE
@endsection
@section('styles')
TEST TITLE
@endsection
@section('scripts')
TEST TITLE
@endsection--}}
@section('container')
<div class="text-center">
    <p>{{ trans('general.home.title') }}</p>
    <p>{{ trans('general.home.description') }}</p>
    <p>
        <a class="btn btn-info" href="{{route('admin::core.system.requirements')}}">{{ trans('general.menu.requirements') }}</a>
    </p>
</div>
@endsection
