
@extends('admin.layouts.app')

@section('container')
    <!-- Create Example Form... -->
    @include('example::admin.add')

    @include('example::admin.list')
@endsection
