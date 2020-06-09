@extends('admin::layouts.content')

@section('page_title')
    {{ __('pwa::app.admin.push-notification.title-edit') }}
@stop

@section('content')
    <form action="{{ route('pwa.pushnotification.update', $pushnotification->id) }}" method="POST" @submit.prevent="onSubmit" enctype="multipart/form-data" >
        <div class="content" style="height: 100%">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('pwa::app.admin.push-notification.notification') }}</h1>
                </div>

                <div class="page-action">
                    <button class="btn btn-lg btn-primary">
                        {{ __('pwa::app.admin.push-notification.btn-save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <!-- Edit Push Notification form for Admin -->
                @csrf

                <accordian :title="'{{ __('pwa::app.admin.push-notification.edit-notification') }}'" :active="true">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                            <label for="title" class="required">{{ __('pwa::app.admin.push-notification.label-title') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="title" name="title" value="{{ $pushnotification->title }}"/>
                            <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description" class="required">{{ __('pwa::app.admin.push-notification.description') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="description" name="description" value="{{ $pushnotification->description }}" />
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('targeturl') ? 'has-error' : '']">
                            <label for="targeturl" class="required">{{ __('pwa::app.admin.push-notification.target-url') }}</label>
                            <input type="url" placeholder="http://example.com" v-validate="'required'" class="control" id="tergeturl" name="targeturl" value="{{ $pushnotification->targeturl }}"/>
                            <span class="control-error" v-if="errors.has('targeturl')">@{{ errors.first('targeturl') }}</span>
                        </div>

                        <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                            <label for="icon">{{ __('pwa::app.admin.push-notification.icon') }}</label>

                            <image-wrapper :button-label="'{{ __('pwa::app.admin.push-notification.add-image-btn-title') }}'" input-name="image" :multiple="false" :images='"{{ $pushnotification->imageurl_url }}"'></image-wrapper>

                            <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                @foreach ($errors->get('image.*') as $key => $message)
                                    @php echo str_replace($key, 'image', $message[0]); @endphp
                                @endforeach
                            </span>

                        </div>
                    </div>
                </accordian>
            </div>
        </div>
    </form>
@stop