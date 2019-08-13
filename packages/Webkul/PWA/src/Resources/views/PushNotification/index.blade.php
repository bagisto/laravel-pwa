@extends('admin::layouts.content')

@section('page_title')
    View
@stop

@section('content')
    <div class="content" style="height: 100%;">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('pwa::app.admin.system.notification') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('pwa.pushnotification.create') }}" class="btn btn-lg btn-primary">
                    Create Push Notifications
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('pushnotification', 'Webkul\PWA\DataGrids\PushNotificationDataGrid')
            {!! $pushnotification->render() !!}
        </div>
    </div>
@stop