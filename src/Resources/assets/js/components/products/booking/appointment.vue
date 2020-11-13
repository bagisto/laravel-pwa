
<template>
    <div>
        <div class="booking-info-row">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.slot_duration') }} :

                {{ $t('booking.slot_duration_in_minutes', {'minutes': bookingProduct.appointment_slot.duration}) }}
            </span>
        </div>

        <div class="booking-info-row">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.today_availability') }}
            </span>

            <span class="value left-shift" v-html="bookingProduct.today_slots_html">

            </span>

            <div class="toggle left-shift" @click="showDaysAvailability = ! showDaysAvailability">
                {{ $t('booking.slots_for_all_days') }}

                <i class="icon" :class="[! showDaysAvailability ? 'arrow-down-icon' : 'arrow-up-icon']"></i>
            </div>

            <div class="days-availability left-shift" v-show="showDaysAvailability">

                <table>
                    <tbody>
                        <tr v-for="day in bookingProduct.week_slot_durations">
                            <td>{{ day['name'] }}</td>

                            <td>
                                <template v-if="day['slots'] && day['slots'].length">
                                    <span v-for="slot in day['slots']">
                                        {{ slot['from'] + ' - ' + slot['to'] }}
                                    </span>

                                    <br/>
                                </template>

                                <template v-else>
                                    <span class="text-danger">{{ $t('booking.closed') }}</span>
                                </template>

                                    
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
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
        name: 'appointment',

        props: ['bookingProduct', 'formData'],

        components: {
            Slots
        },

        inject: ['$validator'],

        data: function () {
            return {
                showDaysAvailability: false
            }
        }
    }
</script>

<style>
    .title {
        top: -12px;
        position: relative;
    }

    .left-shift {
        left: 35px;
        display: block;
        position: relative;
    }

    .bp-slot-icon {
        width: 32px;
        height: 32px;
        background-image: url('/themes/default/assets/images/slot.svg');
    }

    .booking-info-row {
        padding-bottom: 20px;
    }

    .toggle {
        color: blue;
    }
</style>