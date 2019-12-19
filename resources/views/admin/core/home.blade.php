
@extends('admin.layouts.app')

@section('container')
    <!-- Create Task Form... -->
    @include('admin.core.add_task')

    @include('admin.core.tasks_list')
@endsection
