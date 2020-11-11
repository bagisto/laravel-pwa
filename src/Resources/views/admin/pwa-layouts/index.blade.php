@extends('admin::layouts.content')

@section('page_title')
    {{ __('pwa::app.admin.layouts.index') }}
@stop

@section('content')
    <div class="content">
        <form
            method="POST"
            @submit.prevent="onSubmit"
            enctype="multipart/form-data"
            >

            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('pwa::app.admin.layouts.index') }}</h1>
                </div>
    
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('pwa::app.admin.system.btn-save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <accordian :title="'{{ __('velocity::app.admin.meta-data.general') }}'" :active="true">
                    <div slot="body">
                        <div class="control-group">
                            <label style="width:100%;">
                                {{ __('velocity::app.admin.meta-data.home-page-content') }}
                            </label>

                            <textarea
                                class="control"
                                id="home_page_content"
                                name="home_page_content">{{ trim($layout->home_page_content ?? "") }}
                            </textarea>
                        </div>
                    </div>
                </accordian>
            </div>
        </form>
    </div>
@stop