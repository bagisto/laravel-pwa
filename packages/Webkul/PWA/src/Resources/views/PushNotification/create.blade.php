@extends('admin::layouts.content')

@section('page_title')
    index
@stop

@section('content')
<form action="{{route('pwa.pushnotification.store')}}" method="POST" enctype="multipart/form-data" @submit.prevent="onSubmit">
    <div class="content" style="height: 100%;">
            <div class="page-header">
                <div class="page-title">
                        <h1>{{ __('pwa::app.admin.system.notification') }}</h1>
                </div>

                <div class="page-action">
                    <button class="btn btn-lg btn-primary">
                        {{ __('pwa::app.admin.system.save') }}
                    </button>
                </div>
            </div>

        <div class="page-content">

            <!-- Push notification form for admin-->
                @csrf()

                <accordian :title="'{{ __('pwa::app.admin.system.createpushnotification') }}'" :active="true">
                    <div slot="body">

                        {{-- {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.controls.before') !!} --}}

                        <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                            <label for="name" class="required">Title</label>
                            <input type="text" v-validate="'required'" class="control" id="title" name="title" />
                            <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="name" class="required">Description</label>
                            <input type="text" v-validate="'required'" class="control" id="description" name="description" />
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('targeturl') ? 'has-error' : '']">
                            <label for="name" class="required">Target URL</label>
                            <input type="url" placeholder="http://example.com" v-validate="'required'" class="control" id="tergeturl" name="targeturl" />
                            <span class="control-error" v-if="errors.has('targeturl')">@{{ errors.first('targeturl') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('icon') ? 'has-error' : '']">
                            <label for="name" class="required">Icon</label>
                            <input type="file" accept="image/*" style="padding-top: 5px;" v-validate="'required'" class="control" id="icon" name="icon" />
                            <span class="control-error" v-if="errors.has('icon')">@{{ errors.first('icon') }}</span>
                        </div>
                    </div>
                </accordian>
        </div>
    </div>
</form>
@stop

