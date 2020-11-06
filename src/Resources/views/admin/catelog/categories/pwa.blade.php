<div class="control-group boolean">
    <label for="{{ trans('pwa::app.admin.system.add_in_pwa') }}">
        {{ trans('pwa::app.admin.system.add_in_pwa') }}
    </label>

    <label class="switch">
        <input
            value="1"
            type="checkbox"
            class="control"
            @if (isset($category) && $category->category_product_in_pwa)
                checked="checked"
            @endif
            name="add_in_pwa"
            id="{{ trans('pwa::app.admin.system.add_in_pwa') }}"
            data-vv-as="&quot;{{ trans('pwa::app.admin.system.add_in_pwa') }}&quot;"
        />
        <span class="slider round"></span>
    </label>
</div>