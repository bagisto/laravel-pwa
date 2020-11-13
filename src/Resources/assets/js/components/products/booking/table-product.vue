<template>
    <div>
        <div class="booking-info-row">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.slot_duration') }} :

                {{ $t('booking.slot_duration_in_minutes', {'minutes': bookingProduct.table_slot.duration}) }}
            </span>
        </div>

        <div class="booking-info-row">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.today_availability') }}
            </span>

            <span class="value" v-html="bookingProduct.today_slots_html"></span>

            <div class="toggle" @click="showDaysAvailability = ! showDaysAvailability">
                {{ $t('booking.slots_for_all_days') }}

                <i class="icon" :class="[! showDaysAvailability ? 'arrow-down-icon' : 'arrow-up-icon']"></i>
            </div>

            <div class="days-availability" v-show="showDaysAvailability">

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
            :title="$t('booking.book_a_table')"
        ></slots>

        <div class="form-group">
            <label>{{ $t('booking.special_notes') }}</label>
            <textarea name="booking[note]" class="form-style" style="width: 100%"/>
        </div>
    </div>
</template>

<script>
    import Slots from './slots';

    export default {
        name: 'table-product',

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