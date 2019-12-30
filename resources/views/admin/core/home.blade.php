
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
    <p>
        Welcome to OpenCore!
    </p>
    <p>
        Before you start using the system please make sure the system requirements are fulfill
    </p>
    <p>
        <a class="btn btn-info" href="{{route('admin::core.requirements')}}">System Requirements</a>
    </p>
</div>
@endsection
