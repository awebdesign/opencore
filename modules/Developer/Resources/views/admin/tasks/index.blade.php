@extends('developer::admin.tasks._layout')
@section('meta.title')
    Tasks
@stop
@section('title')
    <div class="uk-flex uk-flex-between uk-flex-middle">
        <h4 class="uk-card-title uk-margin-remove">Tasks</h4>
        {!! Form::open([
            'id' => 'totem__search__form',
            'url' => $fullurl,
            'method' => 'GET',
            'class' => 'uk-display-inline uk-search uk-search-default'
        ]) !!}
        <span uk-search-icon></span>
        {!! Form::text('q', request('q'), ['class' => 'uk-search-input', 'placeholder' => 'Search...']) !!}
        {!! Form::close() !!}
    </div>
@stop
@section('main-panel-content')
    <table class="uk-table uk-table-responsive" cellpadding="0" cellspacing="0" class="mb1">
        <thead>
            <tr>
                <th>{!! Html::columnSort('Description', 'description') !!}</th>
                <th>{!! Html::columnSort('Average Runtime', 'average_runtime') !!}</th>
                <th>{!! Html::columnSort('Last Run', 'last_ran_at') !!}</th>
                <th>Next Run</th>
                <th class="uk-text-center">Execute</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr is="task-row"
                    :data-task="{{$task}}"
                    showHref="{{route('developer::admin.tasks.view', $task)}}"
                    executeHref="{{route('developer::admin.tasks.execute', $task)}}">
                </tr>
            @empty
                <tr>
                    <td class="uk-text-center" colspan="5">
                        <img class="uk-svg" width="50" height="50" src="{{asset('vendor/totem/img/funnel.svg')}}">
                        <p>No Tasks Found.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div align="center">Don't forget to run "php artisan cache:clear" after each new task!</div>
@stop
@section('main-panel-footer')
    <div class="uk-flex uk-flex-between">
        <span>
            <a class="uk-icon-button uk-button-primary uk-hidden@m" uk-icon="icon: plus" href="{{route('developer::admin.tasks.create')}}"></a>
            <a class="uk-button uk-button-primary uk-button-small uk-visible@m" href="{{route('developer::admin.tasks.create')}}">New Task</a>
        </span>

        <span>
            <import-button url="{{route('developer::admin.tasks.import')}}"></import-button>
            <a class="uk-icon-button uk-button-primary uk-hidden@m" uk-icon="icon: cloud-download"  href="{{route('developer::admin.tasks.export')}}"></a>
            <a class="uk-button uk-button-primary uk-button-small uk-visible@m" href="{{route('developer::admin.tasks.export')}}">Export</a>
        </span>
    </div>
    {{$tasks->links('totem::partials.pagination', ['params' => '&' . http_build_query(array_filter(request()->except('page')))])}}
@stop
