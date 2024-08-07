<!-- default product listing -->
<x-shop-pwa::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index')"
    :navigation-link="route('shop.home.index')"
/>

<!-- category product listing -->
<x-shop-pwa::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['category_id' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- featured product listing -->
<x-shop-pwa::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['featured' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- new product listing -->
<x-shop-pwa::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['new' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- basic/traditional form  -->
<x-shop-pwa::form action="">

    <!-- Type E-mail -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label>
            Email
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="email"
            name="email"
            rules="required|email"
            value=""
            label="Email"
            placeholder="email@example.com"
        />

        <x-shop-pwa::form.control-group.error control-name="email" />
    </x-shop-pwa::form.control-group>

    <!-- Type Date -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label>
            Date of Birth
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="date"
            id="dob"
            name="date_of_birth"
            value=""
            label="Date of Birth"
            placeholder="Date of Birth"
        />

        <x-shop-pwa::form.control-group.error control-name="date_of_birth" />
    </x-shop-pwa::form.control-group>

    <!-- Type Date Time -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label>
            Start Timing
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="datetime"
            id="starts_from"
            name="starts_from"
            value=""
            label="Start Timing"
            placeholder="Start Timing"
        />

        <x-shop-pwa::form.control-group.error control-name="starts_from" />
    </x-shop-pwa::form.control-group>

    <!-- Type Text -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label class="required">
            @lang('name')
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="text"
            name="name"
            rules="required"
            :value=""
            label="name"
            placeholder="name"
        />

        <x-shop-pwa::form.control-group.error control-name="name" />
    </x-shop-pwa::form.control-group>

    <!-- Type Select -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label>
            @lang('shop::app.catalog.families.create.column')
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="select"
            name="column"
            rules="required"
            :label="trans('shop::app.catalog.families.create.column')"
        >
            <!-- Default Option -->
            <option value="">
                @lang('shop::app.catalog.families.create.select-group')
            </option>

            <option value="1">
                @lang('shop::app.catalog.families.create.main-column')
            </option>

            <option value="2">
                @lang('shop::app.catalog.families.create.right-column')
            </option>
        </x-shop-pwa::form.control-group.control>

        <x-shop-pwa::form.control-group.error control-name="column" />
    </x-shop-pwa::form.control-group>

    <!--Type Checkbox -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.control
            type="checkbox"
            id="is_unique"
            name="is_unique"
            value="1"
            for="is_unique"
        />

        <x-shop-pwa::form.control-group.label for="is_unique">
            @lang('shop::app.catalog.attributes.edit.is-unique')
        </x-shop-pwa::form.control-group.label>
    </x-shop-pwa::form.control-group>

    <!--Type Radio -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.control
            type="radio"
            id="is_unique"
            name="is_unique"
            value="1"
            for="is_unique"
        />

        <x-shop-pwa::form.control-group.label for="is_unique" />
            @lang('shop::app.catalog.attributes.edit.is-unique')
        </x-shop-pwa::form.control-group.label>
    </x-shop-pwa::form.control-group>

    <!-- Type Tinymce -->
    <x-shop-pwa::form.control-group>
        <x-shop-pwa::form.control-group.label>
            Description
        </x-shop-pwa::form.control-group.label>

        <x-shop-pwa::form.control-group.control
            type="textarea"
            class="description"
            name="description"
            rules="required"
            :value=""
            label="Description"
            :tinymce="true"
        />

        <x-shop-pwa::form.control-group.error control-name="description" />
    </x-shop-pwa::form.control-group>
</x-shop-pwa::form>

<!-- customized/ajax form -->
<x-shop-pwa::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, callMethodInComponent)">
        <x-shop-pwa::form.control-group>
            <x-shop-pwa::form.control-group.label>
                Email
            </x-shop-pwa::form.control-group.label>

            <x-shop-pwa::form.control-group.control
                type="email"
                name="email"
                rules="required"
                :value="old('email')"
                label="Email"
                placeholder="email@example.com"
            />

            <x-shop-pwa::form.control-group.error control-name="email" />
        </x-shop-pwa::form.control-group>

        <button>Submit</button>
    </form>
</x-shop-pwa::form>

<!-- Shimmer -->
<x-shop-pwa::shimmer.checkout.onepage.payment-method />

<!-- tabs -->
<x-shop-pwa::tabs>
    <x-shop-pwa::tabs.item
        title="Tab 1"
    >
        Tab 1 Content
    </x-shop-pwa::tabs.item>

    <x-shop-pwa::tabs.item
        title="Tab 2"
    >
        Tab 2 Content
    </x-shop-pwa::tabs.item>
</x-shop-pwa::tabs>

<!-- accordion -->
<x-shop-pwa::accordion>
    <x-slot:header>
        Accordion Header
    </x-slot>

    <x-slot:content>
        Accordion Content
    </x-slot>
</x-shop-pwa::accordion>

<!-- modal -->
<x-shop-pwa::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot>

    <x-slot:header>
        Modal Header
    </x-slot>

    <x-slot:content>
        Modal Content
    </x-slot>
</x-shop-pwa::modal>

<!-- drawer -->
<x-shop-pwa::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot>

    <x-slot:header>
        Drawer Header
    </x-slot>

    <x-slot:content>
        Drawer Content
    </x-slot>
</x-shop-pwa::drawer>

<!-- dropdown -->
<x-shop-pwa::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot>

    <x-slot:content>
        Content
    </x-slot>
</x-shop-pwa::dropdown>

<!--Range Slider -->
<x-shop-pwa::range-slider
    ::key="refreshKey"
    default-type="price"
    ::default-allowed-max-range="allowedMaxPrice"
    ::default-min-range="minRange"
    ::default-max-range="maxRange"
    @change-range="setPriceRange($event)"
/>

<!-- Image/Media -->
<x-shop-pwa::media.images.lazy
    class="min-w-[250px] relative after:content-[' '] after:block after:pb-[calc(100%+9px)] bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
    ::src="product.base_image.medium_image_url"
    ::key="product.id"
    ::index="product.id"
    width="291"
    height="300"
    ::alt="product.name"
/>

<!-- Page Title -->
<x-slot:title>
    @lang('Title')
</x-slot>

<!-- Page Layout -->
<x-shop-pwa::layouts>
   Page Content
</x-shop-pwa::layouts>

<!-- label class -->

<div class="label-canceled"></div>

<div class="label-info"></div>

<div class="label-completed"></div>

<div class="label-closed"></div>

<div class="label-processing"></div>

<div class="label-pending"></div>

<div class="label-canceled"></div>
