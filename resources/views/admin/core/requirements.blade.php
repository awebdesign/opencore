@extends('admin.layouts.app')

@section('meta_title')
    {{ trans('installer_messages.permissions.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-key fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.permissions.title') }}
@endsection

@section('container')

<ul class="list">
@foreach($requirements['requirements'] as $type => $requirement)
    <li class="list__item list__title {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">
        <strong>{{ ucfirst($type) }}</strong>
        @if($type == 'php')
            <strong>
                <small>
                    (version {{ $phpSupportInfo['minimum'] }} required)
                </small>
            </strong>
            <span class="float-right">
                <strong>
                    {{ $phpSupportInfo['current'] }}
                </strong>
                <i class="fa fa-fw fa-{{ $phpSupportInfo['supported'] ? 'check-circle-o' : 'exclamation-circle' }} row-icon" aria-hidden="true"></i>
            </span>
        @endif
    </li>
    @foreach($requirements['requirements'][$type] as $extention => $enabled)
        <li class="list__item {{ $enabled ? 'success' : 'error' }}">
            {{ $extention }}
            <i class="fa fa-fw fa-{{ $enabled ? 'check-circle-o' : 'exclamation-circle' }} row-icon" aria-hidden="true"></i>
        </li>
    @endforeach
    @endforeach
    @foreach($permissions['permissions'] as $permission)
    <li class="list__item list__item--permissions {{ $permission['isSet'] ? 'success' : 'error' }}">
        {{ $permission['folder'] }}
        <span>
            <i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle-o' : 'exclamation-circle' }}"></i>
            {{ $permission['permission'] }}
        </span>
    </li>
    @endforeach
    <li>Cronjob path: * * * * * php {{ $cronpath }} schedule:run >> /dev/null 2>&1</li>
</ul>
@endsection
