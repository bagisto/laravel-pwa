<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton">
                <i class="icon back-icon" @click="handleBack"></i>
            </div>

            <div slot="content">
                <h2>{{ stepLabels[step] }}</h2>

                <div class="stepper">
                    <div class="step" :class="'step-' + step">
                    </div>
                </div>
            </div>
        </custom-header>

        <div class="checkout-container" v-if="cart">
            <div class="address-section" v-show="step == 1">
                <form data-vv-scope="address-form">
                    <div class="panel">
                        <div class="panel-heading">{{ $t('Billing Address') }}</div>

                        <div v-if="! new_address['billing']">
                            <div class="panel-content">
                                <div class="address-list">
                                    <label class="address-item" v-for="addressTemp in addresses.billing" :for="'billing_address_' + addressTemp.id">
                                        <span class="radio" :class="'billing_address_' + addressTemp.id">
                                            <input type="radio" v-validate="'required'" :id="'billing_address_' + addressTemp.id" name="billing[address_id]" :value="addressTemp.id" v-model="address.billing.address_id" :data-vv-as="$t('Billing Address')">
                                            <label class="radio-view" :for="'billing_address_' + addressTemp.id"></label>
                                        </span>

                                        <div class="address_details">
                                            {{ addressTemp.address1.join(' ') }}</br>
                                            {{ addressTemp.city }}</br>
                                            {{ addressTemp.state }}</br>
                                            {{ addressTemp.country_name ? addressTemp.country_name : addressTemp.country  + ' ' + addressTemp.postcode }}</br>
                                            {{ addressTemp.phone }}
                                        </div>

                                        <!--<i class="icon sharp-arrow-right-icon"></i>-->
                                    </label>

                                    <input type="radio" v-validate="'required'" name="billing[address_id]" value="0" :data-vv-as="$t('Billing Address')" style="display: none">

                                    <div class="control-group" :class="[errors.has('address-form.billing[address_id]') ? 'has-error' : '']">
                                        <span class="control-error" v-if="errors.has('address-form.billing[address_id]')">
                                            {{ errors.first('address-form.billing[address_id]') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="address-actions">
                                <div class="new-address-link" @click="new_address['billing'] = true">
                                    <i class="icon sharp-plus-icon"></i>

                                    <span>{{ $t('New Address') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-container address" v-else>
                            <custom-header>
                                <div slot="back-botton">
                                    <i class="icon sharp-cross-icon" @click="new_address['billing'] = false"></i>
                                </div>

                                <div slot="content">
                                    <h2>{{ $t('Add Billing Address') }}</h2>
                                </div>
                            </custom-header>

                            <checkout-address :address="address.billing" type="billing"></checkout-address>

                            <div class="button-group">
                                <button type="button" class="btn btn-black btn-lg" @click="validateForm('billing')">{{ $t('Add') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">{{ $t('Shipping Address') }}</div>

                        <div style="padding: 16px">
                            <span class="checkbox" :class="'shipping_address_' + address.id">
                                <input type="checkbox" :id="'shipping_address_' + address.id" name="billing[use_for_shipping]" v-model="address.billing.use_for_shipping">
                                <label class="checkbox-view" :for="'shipping_address_' + address.id"></label>
                                {{ $t('Same as Billing Address') }}
                            </span>
                        </div>

                        <div v-if="! address.billing.use_for_shipping">
                            <div v-if="! new_address['shipping']">
                                <div class="panel-content">
                                    <div class="address-list">
                                        <label class="address-item" v-for="addressTemp in addresses.shipping" :for="'shipping_address_' + addressTemp.id">
                                            <span class="radio" :class="'shipping_address_' + addressTemp.id">
                                                <input type="radio" v-validate="'required'" :id="'shipping_address_' + addressTemp.id" name="shipping[address_id]" :value="addressTemp.id" v-model="address.shipping.address_id" :data-vv-as="$t('Shipping Address')">
                                                <label class="radio-view" :for="'shipping_address_' + addressTemp.id"></label>
                                            </span>

                                            <div class="address_details">
                                                {{ addressTemp.address1.join(' ') }}</br>
                                                {{ addressTemp.city }}</br>
                                                {{ addressTemp.state }}</br>
                                                {{ addressTemp.country_name + ' ' + addressTemp.postcode }}</br>
                                                {{ addressTemp.phone }}
                                            </div>

                                            <i class="icon sharp-arrow-right-icon"></i>
                                        </label>

                                        <input type="radio" v-validate="'required'" name="shipping[address_id]" value="0" :data-vv-as="$t('Shipping Address')" style="display: none">

                                        <div class="control-group" :class="[errors.has('address-form.shipping[address_id]') ? 'has-error' : '']">
                                            <span class="control-error" v-if="errors.has('address-form.shipping[address_id]')">
                                                {{ errors.first('address-form.shipping[address_id]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="address-actions">
                                    <div class="new-address-link" @click="new_address['shipping'] = true">
                                        <i class="icon sharp-plus-icon"></i>

                                        <span>{{ $t('New Address') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-container address" v-else>
                                <custom-header>
                                    <div slot="back-botton">
                                        <i class="icon sharp-cross-icon" @click="new_address['shipping'] = false"></i>
                                    </div>

                                    <div slot="content">
                                        <h2>{{ $t('Add Shipping Address') }}</h2>
                                    </div>
                                </custom-header>

                                <checkout-address :address="address.shipping" type="shipping"></checkout-address>

                                <div class="button-group">
                                    <button type="button" class="btn btn-black btn-lg" @click="validateForm('shipping')">{{ $t('Add') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="shipping-section" v-show="step == 2">
                <form data-vv-scope="shipping-form">
                    <div class="panel">
                        <div class="panel-heading">{{ $t('Shipping Methods') }}</div>

                        <div class="panel-content">
                            <div class="form-container shipping-methods" :class="[errors.has('shipping-form.shipping_method') ? 'has-error' : '']">

                                <div class="method" v-for="method in shippingRates">
                                    <h2>{{ method['carrier_title'] }}</h2>

                                    <label class="radio" v-for="rate in method['rates']" :for="rate.method">
                                        <input type="radio" v-validate="'required'" :id="rate.method" name="shipping_method" :value="rate.method" v-model="selected_shipping_method">
                                        <label class="radio-view" :for="rate.method"></label>

                                        {{ rate.method_title + ' - ' + rate.formated_price }}
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="payment-section" v-show="step == 3">
                <form data-vv-scope="payment-form">
                    <div class="panel">
                        <div class="panel-heading">{{ $t('Payment Methods') }}</div>

                        <div class="panel-content">
                            <div class="form-container payment-methods" :class="[errors.has('payment-form.payment_method') ? 'has-error' : '']">

                                <div class="method" v-for="payment in paymentMethods">
                                    <label class="radio">
                                        <input type="radio" v-validate="'required'" :id="payment.method" name="payment_method" :value="payment.method" v-model="selected_payment_method">
                                        <label class="radio-view" :for="payment.method"></label>

                                        {{ payment.method_title }}
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="review-section" v-show="step == 4">
                <div class="panel">
                    <div class="panel-heading">{{ $t('Billing Info') }}</div>

                    <div class="panel-content" style="padding: 16px">
                        <div class="billing-address" v-if="cart.billing_address">
                            <h2>{{ $t('Billing Address') }}</h2>
                            <div class="address-details">
                                {{ cart.billing_address.address1.join(' ') }}</br>
                                {{ cart.billing_address.city }}</br>
                                {{ cart.billing_address.state }}</br>
                                {{ cart.billing_address.country_name ? cart.billing_address.country_name : cart.billing_address.country  + ' ' + cart.billing_address.postcode }}</br>
                                {{ cart.billing_address.phone }}
                            </div>
                        </div>

                        <div class="payment-method" v-if="cart.payment">
                            <h2>{{ $t('Payment Method') }}</h2>
                            <div class="method-title">
                                {{ cart.payment.method_title }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">{{ $t('Shipping Info') }}</div>

                    <div class="panel-content" style="padding: 16px">
                        <div class="shipping-address" v-if="cart.shipping_address">
                            <h2>{{ $t('Shipping Address') }}</h2>
                            <div class="address-details">
                                {{ cart.shipping_address.address1.join(' ') }}</br>
                                {{ cart.shipping_address.city }}</br>
                                {{ cart.shipping_address.state }}</br>
                                {{ cart.shipping_address.country_name ? cart.shipping_address.country_name : cart.shipping_address.country  + ' ' + cart.shipping_address.postcode }}</br>
                                {{ cart.shipping_address.phone }}
                            </div>
                        </div>

                        <div class="shipping-method" v-if="cart.selected_shipping_rate">
                            <h2>{{ $t('Shipping Method') }}</h2>
                            <div class="method-title">
                                {{ cart.selected_shipping_rate.method_title + ' - ' + cart.selected_shipping_rate.formated_price }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">{{ cart.items.length }} {{ $t('Item(s)') }}</div>

                    <div class="panel-content" style="padding-left: 16px">
                        <div class="cart-item-list">
                            <div class="cart-item" v-for="cartItem in cart.items">
                                <div class="product-name">{{ cartItem.product.name }}</div>

                                <div class="cart-item-options">
                                    <div class="attributes" v-if="cartItem.additional.attributes">
                                        <div class="option" v-for="attribute in cartItem.additional.attributes">
                                            <label>{{ attribute.attribute_name }} : </label>
                                            <span>{{ attribute.option_label }}</span>
                                        </div>
                                    </div>

                                    <div class="cart-item-price">
                                        <label>{{ $t('Price :') }} </label>
                                        <span>{{ cartItem.formated_price }}</span>
                                    </div>

                                    <div class="cart-item-subtotal">
                                        <label>{{ $t('Subtotal :') }} </label>
                                        <span>{{ cartItem.formated_total }}</span>
                                    </div>
                                </div>
                            </diV>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-content" style="padding: 0px 16px 16px 16px;">
                        <table class="total-summary">
                            <tbody>
                                <tr>
                                    <td>{{ $t('Subtotal') }}</td>
                                    <td>{{ cart.formated_sub_total }}</td>
                                </tr>

                                <tr v-if="cart.selected_shipping_rate">
                                    <td>{{ $t('Shipping') }}</td>
                                    <td>{{ cart.selected_shipping_rate.formated_price }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $t('Tax') }}</td>
                                    <td>{{ cart.formated_tax_total }}</td>
                                </tr>

                                <tr class="last">
                                    <td>{{ $t('Order Total') }}</td>
                                    <td>{{ cart.formated_grand_total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="checkout-action">
                <span class="total-info">
                    <p>{{ $t('Amount to be paid') }}</p>
                    <h3>{{ cart.formated_grand_total }}</h3>
                </span>

                <button type="button" class="btn btn-black" v-if="step != 4" @click="validateForm()" :disabled="disable_button">{{ $t('Proceed') }}</button>

                <button type="button" class="btn btn-success" v-if="step == 4" @click="placeOrder()" :disabled="disable_button">{{ $t('Place Order') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader    from '../../layouts/custom-header';
    import CheckoutAddress from './address';

    export default {
        name: 'onepage',

        components: { CustomHeader, CheckoutAddress },

        data () {
            return {
                customer: null,

                addresses: {
                    billing: [],

                    shipping: []
                },

                cart: null,

                step: 1,

                stepLabels: {
                    1: this.$t('Address'),
                    2: this.$t('Shipping'),
                    3: this.$t('Payment'),
                    4: this.$t('Review'),
                },

                address: {
                    billing: {
                        address1: [''],

                        use_for_shipping: true,
                    },

                    shipping: {
                        address1: ['']
                    },
                },

                new_address: {
                    shipping: false,
                    billing: false,
                },

                shippingRates: [],

                selected_shipping_method: '',

                paymentMethods: [],

                selected_payment_method: '',

                disable_button: false,

                formScopes: {
                    1: 'address-form',
                    2: 'shipping-form',
                    3: 'payment-form'
                }
            }
        },

        mounted () {
            this.getAuthCustomer();

            this.getCart();
        },

        methods: {
            getAuthCustomer () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/customer/get')
                    .then(function(response) {
                        this_this.customer = response.data.data;

                        EventBus.$emit('hide-ajax-loader');

                        this_this.getCustomerAddresses(this_this.customer.id)
                    })
                    .catch(function (error) {});
            },

            getCustomerAddresses (customerId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/addresses', { params: { customer_id: customerId, pagination: 0 } })
                    .then(function(response) {
                        this_this.$set(this_this.addresses, 'billing', response.data.data.slice(0))

                        this_this.$set(this_this.addresses, 'shipping', response.data.data.slice(0))

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            getCart () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/checkout/cart')
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = response.data.data;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);

                        if (! this_this.cart)
                            this_this.$router.go(-2);
                    })
                    .catch(function (error) {});
            },

            validateForm: function (type = '') {
                this.$validator.validateAll(this.formScopes[this.step]).then((result) => {
                    if (result) {
                        if (this.formScopes[this.step] == 'address-form') {
                            if (type != '') {
                                this.addAddress(type);
                            } else {
                                this.saveAddress();
                            }
                        } else if (this.formScopes[this.step] == 'shipping-form') {
                            this.saveShipping();
                        } else if (this.formScopes[this.step] == 'payment-form') {
                            this.savePayment();
                        }
                    }
                });
            },

            addAddress (type) {
                var addressId = 'address_' + (this.addresses[type].length + 1);

                this.address[type]['id'] = addressId;

                this.addresses[type].push(this.address[type]);

                this.new_address[type] = false;

                this.$set(this.address, type, {address1: [''], use_for_shipping: this.address.billing.use_for_shipping})
            },

            saveAddress () {
                var this_this = this;

                if (! Number.isInteger(this.address.billing.address_id)) {
                    var newAddress = this.addresses.billing.filter(address => address.id == this.address.billing.address_id);

                    Object.assign(this.address.billing, newAddress[0]);
                }

                if (! Number.isInteger(this.address.shipping.address_id)) {
                    var newAddress = this.addresses.shipping.filter(address => address.id == this.address.shipping.address_id);

                    Object.assign(this.address.shipping, newAddress[0]);
                }

                this.disable_button = true;

                this.$http.post('/api/checkout/save-address', this.address)
                    .then(function(response) {
                        this_this.disable_button = false;

                        this_this.shippingRates = response.data.data.rates;

                        this_this.cart = response.data.data.cart;

                        this_this.step++;
                    })
                    .catch(function (error) {
                        this_this.disable_button = false;
                    })
            },

            saveShipping () {
                var this_this = this;

                this.disable_button = true;

                this.$http.post('/api/checkout/save-shipping', { 'shipping_method': this.selected_shipping_method })
                    .then(function(response) {
                        this_this.disable_button = false;

                        this_this.paymentMethods = response.data.data.methods;

                        this_this.cart = response.data.data.cart;

                        this_this.step++;
                    })
                    .catch(function (error) {
                        this_this.disable_button = false;
                    })
            },

            savePayment () {
                var this_this = this;

                this.disable_button = true;

                this.$http.post('/api/checkout/save-payment', { 'payment': { 'method': this.selected_payment_method } })
                    .then(function(response) {
                        this_this.disable_button = false;

                        this_this.cart = response.data.data.cart;

                        this_this.step++;
                    })
                    .catch(function (error) {
                        this_this.disable_button = false;
                    })
            },

            placeOrder () {
                var this_this = this;

                this.disable_button = true;

                this.$http.post('/api/checkout/save-order')
                    .then(function(response) {
                        if (response.data.success) {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this_this.$router.push({ path: '/checkout/success/' + response.data.order.id })

                                EventBus.$emit('checkout.cart.changed', null);
                            }
                        }
                    })
                    .catch(function (error) {
                        this_this.disable_button = true;
                    })
            },

            handleBack () {
                if (this.step == 1) {
                    if (window.history.length > 2) {
                        this.$router.back();
                    } else {
                        this.$router.push({name: 'home'})
                    }
                } else {
                    this.step--;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        width: 100%;

        .stepper {
            position: absolute;
            height: 4px;
            width: 100%;
            left: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.08);

            .step {
                background: #000000;
                position: absolute;
                height: 4px;
                width: 25%;

                &.step-2 {
                    width: 50%;
                }

                &.step-3 {
                    width: 75%;
                }

                &.step-4 {
                    width: 100%;
                }
            }
        }

        .checkout-container {
            margin-top: 80px;
            padding-bottom: 60px;

            .panel {
                display: inline-block;
                width: 100%;

                .panel-heading {
                    border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
                    color: rgba(0, 0, 0, 0.6);
                }

                .panel-content {
                    padding: 0;

                    .address-list {
                        .address-item {
                            position: relative;
                            background: #ffffff;
                            border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
                            padding: 16px;
                            font-size: 14px;
                            width: 100%;
                            display: inline-block;
                            cursor: pointer;

                            .radio {
                                position: absolute;
                                display: inline-block;
                                float: left;
                                top: 50%;
                                margin-top: -10px;
                            }

                            .address_details {
                                padding-left: 36px;
                            }

                            .sharp-arrow-right-icon {
                                position: absolute;
                                right: 16px;
                                top: 50%;
                                margin-top: -12px;
                                opacity: 0.16;
                            }
                        }

                        .control-group {
                            display: none;

                            &.has-error {
                                display: block;
                                border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                                margin-bottom: 0;
                                padding: 16px;
                            }
                        }
                    }

                    .shipping-methods, .payment-methods {
                        padding-left: 16px;
                        border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                        .method {
                            border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                            padding: 16px 0;

                            &:last-child {
                                border-bottom: 0;
                            }

                            h2 {
                                font-weight: 700;
                                font-size: 12px;
                                color: rgba(0, 0, 0, 0.6);
                                text-transform: uppercase;
                                margin-bottom: 15px;
                            }

                            .radio {
                                font-size: 14px;
                                color: rgba(0, 0, 0, 0.86);
                                margin-bottom: 8px;

                                &:last-child {
                                    margin-bottom: 0;
                                }
                            }
                        }
                    }
                }

                .form-container {
                    &.address {
                        padding: 16px;
                        position: absolute;
                        background: #fff;
                        z-index: 10;
                        top: 55px;
                        width: 100%;

                        .header {
                            z-index: 10;
                            background: #ffffff;
                            width: 100%;
                            top: 0;
                            left: 0;
                        }
                    }
                }

                .address-actions {
                    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                    font-weight: 600;
                    font-size: 14px;
                    background: #ffffff;
                    width: 100%;
                    float: left;
                    text-align: center;

                    .new-address-link {
                        padding: 16px;
                        cursor: pointer;
                        color: rgba(0, 0, 0, 0.87);

                        .icon {
                            margin-right: 8px;
                            opacity: 0.56;
                            vertical-align: middle;
                        }

                        span {
                            vertical-align: middle;
                        }
                    }
                }
            }

            .review-section {
                .panel {
                    .panel-content {
                        border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                        h2 {
                            font-weight: 700;
                            font-size: 12px;
                            color: rgba(0, 0, 0, 0.6);
                            text-transform: uppercase;
                            margin-bottom: 8px;
                        }

                        .billing-address {
                            margin-bottom: 16px;
                        }

                        .payment-method {
                            font-size: 14px;
                            color: rgba(0, 0, 0, 0.86);
                        }

                        .shipping-method {
                            font-size: 14px;
                            color: rgba(0, 0, 0, 0.86);
                        }

                        .address-details {
                            font-size: 14px;
                            color: rgba(0, 0, 0, 0.86);
                            margin-bottom: 16px;
                        }

                        .cart-item-list {
                            .cart-item {
                                border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
                                padding: 8px 0;

                                &:last-child {
                                    border-bottom: 0;
                                }

                                .product-image {
                                    margin-right: 16px;
                                    float: left;

                                    img {
                                        width: 96px;
                                        height: 96px;
                                    }
                                }


                                .product-name {
                                    font-size: 14px;
                                    margin-bottom: 8px;
                                    color: rgba(0, 0, 0, 0.87);
                                }

                                .cart-item-options {
                                    .attributes {
                                        display: inline-block;

                                        .option {
                                            display: inline-block;

                                            span {
                                                margin-right: 8px;
                                            }
                                        }
                                    }

                                    > div {
                                        margin-bottom: 8px;
                                        font-size: 14px;

                                        &:last-child {
                                            margin-bottom: 0;
                                        }

                                        label {
                                            color: rgba(0, 0, 0, 0.56);
                                        }

                                        span {
                                            color: #000000;
                                        }
                                    }
                                }
                            }
                        }

                        table.total-summary {
                            width: 100%;

                            tr {
                                td {
                                    padding: 8px 0;

                                    &:first-child {
                                        font-size: 14px;
                                        color: rgba(0, 0, 0, 0.56);
                                    }

                                    &:last-child {
                                        font-size: 14px;
                                        text-align: right;
                                    }
                                }

                                &.last {
                                    td {
                                        padding: 16px 0 0 0;
                                        border-top: 1px solid rgba(0, 0, 0, 0.12);

                                        &:first-child {
                                            font-size: 18px;
                                            color: rgba(0, 0, 0, 0.56);
                                        }

                                        &:last-child {
                                            font-size: 18px;
                                            font-weight: 600;
                                            color: rgba(0, 0, 0, 0.87);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            .checkout-action {
                position: fixed;
                bottom: 0;
                padding: 6px 8px;
                width: 100%;
                background: #ffffff;
                -webkit-box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);
                box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);

                .total-info {
                    float: left;

                    p {
                        font-weight: 600;
                        font-size: 12px;
                        color: rgba(0, 0, 0, 0.56);
                        margin-bottom: 2px;
                    }

                    h3 {
                        font-weight: 600;
                        font-size: 18px;
                        color: rgba(0, 0, 0, 0.87);
                    }
                }

                .btn {
                    float: right;
                }
            }
        }
    }
</style>