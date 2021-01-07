<template>
    <div class="book-slots">
        <h3>{{ $t('booking.rent_an_item') }}</h3>

        <div v-if="renting_type == 'daily_hourly'">
            <div class="form-group">
                <label>{{ $t('booking.choose_rent_option') }}</label>

                <span class="radio">
                    <input type="radio" id="daily-renting-type" name="booking[renting_type]" value="daily" v-model="sub_renting_type">
                    <label class="radio-view" for="daily-renting-type"></label>
                    {{ $t('booking.daily_basis') }}
                </span>

                <span class="radio">
                    <input type="radio" id="hourly-renting-type" name="booking[renting_type]" value="hourly" v-model="sub_renting_type">
                    <label class="radio-view" for="hourly-renting-type"></label>
                    {{ $t('booking.hourly_basis') }}
                </span>
                
            </div>
        </div>
        
        <div v-if="renting_type != 'daily' && sub_renting_type == 'hourly'">
            <div>
                <label>{{ $t('booking.select_slot') }}</label>

                <div class="control-group control-group-container">
                    <div class="form-group date" :class="[errors.has('booking[date]') ? 'has-error' : '']">
                        <date @onChange="dateSelected($event)">
                            <input
                                type="text"
                                name="booking[date]"
                                data-min-date="today"
                                v-validate="'required'"
                                class="form-style control"
                                :data-vv-as="$t('booking.date')"
                                v-model="formData.booking.date"
                                :placeholder="$t('booking.select_date')"
                            />
                        </date>

                        <span class="control-error" v-if="errors.has('booking[date]')">{{ errors.first('booking[date]') }}</span>
                    </div>

                    <div class="form-group slots" :class="[errors.has('booking[slot]') ? 'has-error' : '']">
                        <select v-validate="'required'" name="booking[slot]" v-model="selected_slot" class="form-style control" :data-vv-as="$t('booking.slot')">
                            <option value="">{{ $t('booking.select_time_slot') }}</option>
                            <option v-for="(slot, index) in slots" :value="index" :key="index">{{ slot.time }}</option>
                        </select>

                        <span class="control-error" v-if="errors.has('booking[slot]')">{{ errors.first('booking[slot]') }}</span>
                    </div>
                </div>
            </div>

            <div v-if="slots[selected_slot] && slots[selected_slot]['slots'].length">
                <label>{{ $t('booking.select_rent_time') }}</label>

                <div class="control-group control-group-container">
                    <div class="form-group slots" :class="[errors.has('booking[slot][from]') ? 'has-error' : '']">
                        <select v-validate="'required'" name="booking[slot][from]" v-model="formData.booking.slot.from" class="form-style control" :data-vv-as="$t('booking.slot')">
                            <option value="">{{ $t('booking.select_time_slot') }}</option>

                            <option v-for="(slot, index) in slots[selected_slot]['slots']" :value="slot.from_timestamp" :key="index">
                                {{ slot.from }}
                            </option>
                        </select>

                        <span class="control-error" v-if="errors.has('booking[slot][from]')">{{ errors.first('booking[slot][from]') }}</span>
                    </div>
                    
                    <div class="form-group slots" :class="[errors.has('booking[slot][to]') ? 'has-error' : '']">
                        <select v-validate="'required'" name="booking[slot][to]" class="form-style control" :data-vv-as="$t('booking.slot')" v-model="formData.booking.slot.to">
                            <option value="">{{ $t('booking.select_time_slot') }}</option>

                            <option v-for="(slot, index) in slots[selected_slot]['slots']" :value="slot.to_timestamp" v-if="slot_from < slot.to_timestamp" :key="index">
                                {{ slot.to }}
                            </option>
                        </select>

                        <span class="control-error" v-if="errors.has('booking[slot][to]')">{{ errors.first('booking[slot][to]') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <label>{{ $t('booking.select_date') }}</label>

            <div class="control-group control-group-container">
                <div class="form-group date" :class="[errors.has('booking[date_from]') ? 'has-error' : '']">
                    <date @onChange="dateSelected($event)">
                        <input type="text" v-validate="'required|before_or_equal:date_to'" name="booking[date_from]" v-model="formData.booking.date_from" class="control" :data-vv-as="$t('booking.from')" ref="date_from" data-min-date="today"/>
                    </date>

                    <span class="control-error calendar-error" v-if="errors.has('booking[date_from]')">
                        {{ errors.first('booking[date_from]') }}
                    </span>
                </div>

                <div class="form-group date" :class="[errors.has('booking[date_to]') ? 'has-error' : '']">
                    <date @onChange="dateSelected($event)">
                        <input type="text" v-validate="'required|after_or_equal:date_from'" name="booking[date_to]" v-model="formData.booking.date_to" class="control" :data-vv-as="$t('booking.to')" ref="date_to" data-min-date="today"/>
                    </date>

                    <span class="control-error calendar-error" v-if="errors.has('booking[date_to]')">
                        {{ errors.first('booking[date_to]') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import date from './date';
    import Slots from './slots';

    export default {
        name: 'appointment',

        components: {
            date
        },

        props: ['bookingProduct', 'formData'],

        inject: ['$validator'],

            data: function() {
                return {
                    renting_type: this.bookingProduct.renting_type,

                    sub_renting_type: 'hourly',

                    slots: [],

                    selected_slot: '',

                    slot_from: '',

                    date_from: '',

                    date_to: ''
                }
            },

            watch: {
                sub_renting_type: function (newValue, oldValue) {
                    this.formData.booking.renting_type = newValue;
                }
            },

            created: function() {
                var self = this;

                this.$validator.extend('after_or_equal', {
                    getMessage(field, val) {
                        return 'The "To" must be equal or after "From"';
                    },

                    validate(value, field) {
                        if (! self.date_from) {
                            return true;
                        }

                        var from = new Date(self.date_from);
                        
                        var to = new Date(self.date_to);

                        return from <= to;
                    }
                });

                this.$validator.extend('before_or_equal', {
                    getMessage(field, val) {
                        return 'The "From must be equal or before "To"';
                    },

                    validate(value, field) {
                        if (! self.date_to) {
                            return true;
                        }

                        var from = new Date(self.date_from);
                        
                        var to = new Date(self.date_to);

                        return from <= to;
                    }
                });
            },

            methods: {
                dateSelected: function(date) {
                    this.$http.get(this.bookingProduct.slot_index_route, {params: {date: date}})
                        .then (response => {
                            this.selected_slot = '';
                            
                            this.slot_from = '';

                            this.slots = response.data.data;
                        })
                        .catch (function (error) {})
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