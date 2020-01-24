@extends('admin.layouts.app')

@section('meta.title')
    {{ trans('modules.title') }}
@endsection

@section('inline.styles')
    <style>
        .jsUpdateModule {
            transition: all .5s ease-in-out;
        }
    </style>
@endsection

@section('container')
    <h1>{{ trans('modules.title') }}</h1>
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
                            <th>{{ trans('modules.name') }}</th>
                            <th width="15%">{{ trans('modules.version') }}</th>
                            <th width="15%">{{ trans('modules.enabled') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($modules)): ?>
                        <?php foreach ($modules as $module): ?>
                        <tr>
                            <td>
                                <a href="{{ route('admin::core.modules.show', [$module->getLowerName()]) }}">
                                    {{ $module->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin::core.modules.show', [$module->getLowerName()]) }}">
                                    {{ module_version($module) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin::core.modules.show', [$module->getLowerName()]) }}">
                                    <span class="label label-{{$module->enabled() ? 'success' : 'danger'}}">
                                        {{ $module->enabled() ? trans('general.status.enabled') : trans('general.status.disabled') }}
                                    </span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{ trans('modules.name') }}</th>
                            <th>{{ trans('modules.version') }}</th>
                            <th>{{ trans('modules.enabled') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('inline.scripts')
    <?php $locale = app()->getLocale(); ?>
    <script>
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "asc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                },
                "columns": [
                    null,
                    null,
                    null,
                ]
            });
        });
    </script>
<script>
$( document ).ready(function() {
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
    $('.jsUpdateModule').on('click', function(e) {
        $(this).data('loading-text', '<i class="fa fa-spinner fa-spin"></i> Loading ...');
        var $btn = $(this).button('loading');
        var token = '<?= csrf_token() ?>';
        $.ajax({
            type: 'POST',
            url: '<?= route('admin::core.modules.update') ?>',
            data: {module: $btn.data('module'), _token: token},
            success: function(data) {
                console.log(data);
                if (data.updated) {
                    $btn.button('reset');
                    $btn.removeClass('btn-primary');
                    $btn.addClass('btn-success');
                    $btn.html('<i class="fa fa-check"></i> Module updated!')
                    setTimeout(function() {
                        $btn.removeClass('btn-success');
                        $btn.addClass('btn-primary');
                        $btn.html('Update')
                    }, 2000);
                }
            }
        });
    });
});
</script>
@endsection
