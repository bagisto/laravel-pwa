<template>
    <div class="booking-product booking-information">

        <div class="booking-info-row" v-if="bookingProduct.location != ''">
            <span class="icon bp-location-icon"></span>
            <span class="title">{{ $t('booking.location') }}</span>
            <span class="value">{{ bookingProduct.location }}</span>
            <span>
                <a :href="`https://maps.google.com/maps?q=${bookingProduct.location}`" target="_blank">{{ $t('booking.view_on_map') }}</a>
            </span>
        </div>

        <default-view
            :form-data="formData"
            :booking-product="bookingProduct"
            v-if="bookingProduct.type == 'default'"
        ></default-view>

        <appointment
            :form-data="formData"
            :booking-product="bookingProduct"
            v-if="bookingProduct.type == 'appointment'"
        ></appointment>

        <event
            :form-data="formData"
            :booking-product="bookingProduct"
            v-if="bookingProduct.type == 'event'"
        ></event>

        <rental
            :form-data="formData"
            :booking-product="bookingProduct"
            v-if="bookingProduct.type == 'rental'"
        ></rental>

        <table-product
            :form-data="formData"
            :booking-product="bookingProduct"
            v-if="bookingProduct.type == 'table'"
        ></table-product>
    </div>
</template>

<script>
    require('flatpickr/dist/flatpickr.css');

    import Event        from './booking/event';
    import Rental       from './booking/rental';
    // import Reviews      from './booking/reviews';
    import Appointment  from './booking/appointment';
    import DefaultView  from './booking/default-view';
    import TableProduct from './booking/table-product';

    export default {
        name: 'booking-options',

        props: ['product', 'formData'],

        components: {
            Event,
            Rental,
            DefaultView,
            Appointment,
            TableProduct
        },

        inject: ['$validator'],

        data: function() {
            return {
                showDaysAvailability: false,
                bookingProduct: this.product.booking_product,
            }
        }
    }
</script>