@extends('admin.layouts.app')

@section('meta.title')
    {{ trans('installer_messages.welcome.next') }}
@endsection

@section('title')
    <i class="fa fa-key fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.permissions.title') }}
@endsection

@section('container')

<ul class="list-group">
@foreach($requirements['requirements'] as $type => $requirement)
    <li class="list-group-item {{ $phpSupportInfo['supported'] ? 'success' : 'list-group-item-danger' }}">
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
        <li class="list-group-item {{ $enabled ? 'success' : 'list-group-item-danger' }}">
            {{ $extention }}
            <i class="fa fa-fw fa-{{ $enabled ? 'check-circle-o' : 'exclamation-circle' }} row-icon" aria-hidden="true"></i>
        </li>
    @endforeach
    @endforeach
    @foreach($permissions['permissions'] as $permission)
    <li class="list-group-item {{ $permission['isSet'] ? 'success' : 'list-group-item-danger' }}">
        {{ $permission['folder'] }}
        <span>
            <i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle-o' : 'exclamation-circle' }}"></i>
            {{ $permission['permission'] }}
        </span>
    </li>
    @endforeach
</ul>
<p>
    {{ trans('general.cron.info') }}
</p>
<ul class="list-group">
    <li class="list-group-item list-group-item-info">* * * * * php {{ $cronpath }} schedule:run >> /dev/null 2>&1</li>
</ul>
@endsection
