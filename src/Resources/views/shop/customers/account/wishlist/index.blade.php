<x-shop-pwa::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop-pwa::breadcrumbs name="wishlist" />
    @endSection

    <!-- Wishlist Vue Component -->
    <v-wishlist-products>
        <!-- Wishlist Shimmer Effect -->
        <x-shop-pwa::shimmer.customers.account.wishlist :count="4" />
    </v-wishlist-products>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-wishlist-products-template"
        >
            <div>
                <!-- Wishlist Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop-pwa::shimmer.customers.account.wishlist :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

                <!-- Wishlist Information -->
                <template v-else>
                    <div class="flex justify-between items-center overflow-auto journal-scroll">
                        <h2 class="text-2xl font-medium">
                            @lang('shop::app.customers.account.wishlist.page-title')
                        </h2>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.before') !!}

                        <div
                            class="secondary-button flex gap-x-2.5 items-center py-3 px-5 border-[#E9E9E9]"
                            @click="removeAll"
                            v-if="wishlistItems.length"
                        >
                            <span class="icon-bin text-2xl"></span>
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.after') !!}
                    </div>

                    <div
                        v-if="wishlistItems.length"
                        v-for="(item, index) in wishlistItems"
                        class="flex gap-20 flex-wrap mt-8 max-1060:flex-col"
                    >
                        <div class="grid gap-8 flex-1">
                            <div class="grid gap-y-6">
                                <!-- Wishlist item -->
                                <div class="flex gap-x-2.5 justify-between pb-5 border-b border-[#E9E9E9]">
                                    <div class="flex gap-x-5">
                                        <div class="">
                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.image.before') !!}

                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product.url_key}`">
                                                <!-- Wishlist Item Image -->
                                                <img
                                                    class="max-w-[110px] max-h-[110px] min-w-[110px] w-[110px] h-[110px] rounded-xl"
                                                    :src="item.product.base_image.small_image_url"
                                                />
                                            </a>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.image.after') !!}
                                        </div>

                                        <div class="grid gap-y-2.5">
                                            <p
                                                class="text-base font-medium"
                                                v-text="item.product.name"
                                            >
                                            </p>

                                            <!--Wishlist Item attributes -->
                                            <div
                                                class="flex gap-x-2.5 gap-y-1.5 flex-wrap"
                                                v-if="item.options?.attributes"
                                            >
                                                <div class="grid gap-2">
                                                    <div class="">
                                                        <p
                                                            class="flex gap-x-4 text-base items-center cursor-pointer"
                                                            @click="item.option_show = ! item.option_show"
                                                        >
                                                            @lang('shop::app.customers.account.wishlist.see-details')

                                                            <span
                                                                class="text-2xl"
                                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="grid gap-2"
                                                        v-show="item.option_show"
                                                    >
                                                        <div v-for="option in item.options?.attributes">
                                                            <p class="text-sm font-medium">
                                                                @{{ option.attribute_name + ':' }}
                                                            </p>

                                                            <p class="text-sm">
                                                                @{{ option.option_label }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="sm:hidden">
                                                <p
                                                    class="text-lg font-semibold"
                                                    v-html="item.product.min_price"
                                                >
                                                </p>

                                                {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.before') !!}

                                                <!--Wishlist Item removed button-->
                                                <a
                                                    class="flex justify-end text-base text-[#0A49A7] cursor-pointer"
                                                    @click="remove(item.id)"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.remove')
                                                </a>

                                                {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.after') !!}
                                            </div>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.before') !!}

                                            <div class="flex gap-5 flex-wrap">
                                                <x-shop-pwa::quantity-changer
                                                    name="quantity"
                                                    ::value="item.options.quantity ?? 1"
                                                    class="flex gap-x-2.5 items-center max-h-10 py-1.5 px-3.5 border border-navyBlue  rounded-[54px]"
                                                    @change="setItemQuantity($event, item)"
                                                />

                                                <!--Wishlist Item Move-to-cart-->
                                                <x-shop-pwa::button
                                                    class="primary-button w-max max-h-10 py-1.5 px-6 rounded-2xl text-base text-center"
                                                    :title="trans('shop::app.customers.account.wishlist.move-to-cart')"
                                                    ::loading="isMovingToCart[item.id]"
                                                    ::disabled="isMovingToCart[item.id]"
                                                    @click="moveToCart(item.id,index)"
                                                />
                                            </div>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.after') !!}
                                        </div>
                                    </div>

                                    <div class="max-sm:hidden">
                                        <p
                                            class="text-lg font-semibold"
                                            v-html="item.product.min_price"
                                        >
                                        </p>

                                        <a
                                            class="flex justify-end text-base text-[#0A49A7] cursor-pointer"
                                            @click="remove(item.id)"
                                        >
                                            @lang('shop::app.customers.account.wishlist.remove')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Empty Wishlist-->
                    <div
                        class="grid items-center justify-items-center w-full m-auto h-[476px] place-content-center text-center"
                        v-else
                    >
                        <img
                            src="{{ bagisto_asset('images/wishlist.png') }}"
                            class=""
                            alt=""
                            title=""
                        >

                        <p class="text-xl">
                            @lang('shop::app.customers.account.wishlist.empty')
                        </p>
                    </div>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}

            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
                        isLoading: true,

                        isMovingToCart: {},

                        wishlistItems: [],
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.api.customers.account.wishlist.index') }}")
                            .then(response => {
                                this.isLoading = false;

                                this.wishlistItems = response.data.data;
                            })
                            .catch(error => {});
                    },

                    remove(id) {
                        this.$axios.delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${id}`)
                            .then(response => {
                                this.wishlistItems = this.wishlistItems.filter(item => item.id != id);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {

                            this.$axios.post("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                    '_method': 'DELETE',
                                })
                                .then(response => {
                                    this.wishlistItems = [];

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                            }
                        });
                    },

                    moveToCart(id, index) {
                        this.isMovingToCart[id] = true;

                        let url = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlist_id:') }}`.replace(':wishlist_id:', id);

                        let existingItem = this.wishlistItems.find(item => item.id == id);

                        if (! existingItem) {
                            return
                        }

                        this.$axios.post(url, {
                                quantity: existingItem.quantity ?? existingItem.options.quantity,
                                product_id: id
                            })
                            .then(response => {
                                if (response.data.redirect) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });

                                    window.location.href = response.data.data;
                                } else {
                                    this.wishlistItems = this.wishlistItems.filter(item => item.id != id);

                                    this.$emitter.emit('update-mini-cart', response.data.data.cart);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                }

                                this.isMovingToCart[id] = false;
                            })
                            .catch(error => {
                                this.isMovingToCart[id] = false;
                            });
                    },

                    setItemQuantity(quantity, requestedItem) {
                        let existingItem = this.wishlistItems.find((item) => item.id === requestedItem.id);

                        if (existingItem) {
                            existingItem.quantity = quantity;
                        }
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop-pwa::layouts.account>
