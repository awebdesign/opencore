@extends('admin.layouts.app')

@section('meta.title')
    {{ trans('system.routes.title') }}
@endsection

@section('container')
    <h1>{{ trans('system.routes.title') }}</h1>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('admin::core.system.routes.register') }}">{{ trans('system.routes.register') }}</a>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="data-table table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="15%">{{ trans('system.routes.method') }}</th>
                            <th>{{ trans('system.routes.uri') }}</th>
                            <th width="15%">{{ trans('system.routes.name') }}</th>
                            <th width="15%">{{ trans('system.routes.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($routes)): ?>
                        <?php foreach ($routes as $route): ?>
                        <tr>
                            <td>{{ $route->method }}</td>
                            <td>{{ $route->uri }}</td>
                            <td>{{ $route->name ?? '-' }}</td>
                            <td>
                                <?php $status = $route->status ? 'disable' : 'enable'; ?>
                                <?php $buttonClass = $route->status ? 'alert-danger' : 'alert-success' ?>
                                {!! Form::open(['route' => ["admin::core.system.routes.{$status}", $route->id], 'method' => 'post']) !!}
                                    <button class="btn btn-box-tool {{ $buttonClass }}" data-toggle="tooltip" type="submit"
                                            title="" data-original-title="{{ trans("general.status.{$status}") }}">
                                        <i class="fa fa-toggle-{{ $route->status ? 'on' : 'off' }}"></i>
                                        {{ trans("general.status.{$status}") }}
                                    </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="15%">{{ trans('system.routes.method') }}</th>
                                <th>{{ trans('system.routes.uri') }}</th>
                                <th width="15%">{{ trans('system.routes.name') }}</th>
                                <th width="15%">{{ trans('system.routes.action') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="pull-right">
                    {{ $routes->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('inline.scripts')
<script>
$( document ).ready(function() {

});
</script>
@endsection
