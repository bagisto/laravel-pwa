<template>
    <div>
        <div class="booking-info-row" v-if="bookingProduct.default_slot.duration">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.slot_duration') }} :

                {{ $t('booking.slot_duration_in_minutes', {'minutes': bookingProduct.default_slot.duration}) }}
            </span>
        </div>
        
        <slots
            :form-data="formData"
            :booking-product="bookingProduct"
        ></slots>
    </div>
</template>

<script>
    import Slots from './slots';

    export default {
        name: 'default-view',

        props: ['bookingProduct', 'formData'],

        components: {
            Slots
        },

        inject: ['$validator'],

        data: function () {
            return {}
        }
    }
</script>