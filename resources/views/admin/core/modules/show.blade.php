@extends('admin.layouts.app')

@section('meta.title')
    {{ trans('modules.details') }}
@endsection

@section('inline.styles')
    <style>
        .module-type {
            text-align: center;
        }
        .module-type span {
            display: block;
        }
        .module-type i {
            font-size: 124px;
        }
        form {
            display: inline;
        }
    </style>
@endsection

@section('container')
    <h1>{{ trans('modules.details') }}</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool jsPublishAssets" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("modules.publish_assets") }}">
                            <i class="fa fa-cloud-upload"></i>
                            {{ trans("modules.publish_assets") }}
                        </button>
                        <button class="btn btn-box-tool jsUpdate" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("modules.update") }}">
                            <i class="fa fa-refresh"></i>
                            {{ trans("modules.update") }}
                        </button>
                        <?php $status = $module->enabled() ? 'disable' : 'enable'; ?>
                        <?php $buttonClass = $module->enabled() ? 'alert-danger' : 'alert-success' ?>
                        {!! Form::open(['route' => ["admin::core.modules.$status", $module->getName()], 'method' => 'post']) !!}
                            <button class="btn btn-box-tool {{ $buttonClass }}" data-toggle="tooltip" type="submit"
                                    title="" data-original-title="{{ trans("modules.{$status}") }}">
                                <i class="fa fa-toggle-{{ $module->enabled() ? 'on' : 'off' }}"></i>
                                {{ trans("modules.{$status}") }}
                            </button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 module-details">
                            <div class="module-type pull-left">
                                <i class="fa fa-cube"></i>
                            </div>
                            <h2>{{ ucfirst($module->getName()) }}</h2>
                            <p>{{ $module->getDescription() }}</p>
                            <span>{{ module_version($module) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($changelog) && count($changelog['versions'])): ?>
    <br/><br/><br/>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary jumbotron">
                <p>
                    <h3 class="box-title text-center">{{ trans('modules.changelog')}}</h3>
                </p>
                <div class="box-body">
                    @include('admin.core.modules.partials.changelog')
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
@stop

@section('inline.scripts')
    <script>
        $( document ).ready(function() {
            $('.jsPublishAssets').on('click',function (event) {
                event.preventDefault();
                var $self = $(this);
                $self.find('i').toggleClass('fa-cloud-upload fa-refresh fa-spin');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin::core.modules.publish', [$module->getName()]) }}',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function() {
                        $self.find('i').toggleClass('fa-cloud-upload fa-refresh fa-spin');
                    }
                });
            });

            $('.jsUpdate').on('click',function (event) {
                event.preventDefault();
                var $self = $(this);
                $self.find('i').toggleClass('fa-refresh fa-refresh fa-spin');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin::core.modules.update', [$module->getName()]) }}',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(response) {
                        $self.find('i').toggleClass('fa-refresh fa-refresh fa-spin');

                        alert(response.message);
                    }
                });
            });
        });
    </script>
@endsection
