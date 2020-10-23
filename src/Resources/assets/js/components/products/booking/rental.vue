<template>
    <div class="book-slots">
        <h3>{{ $t('booking.rent_an_item') }}</h3>

        <div v-if="renting_type == 'daily_hourly'">
            
        </div>
        
        <div v-if="renting_type != 'daily' && sub_renting_type == 'hourly'">
            
            
        </div>

        <div v-else>
            
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
                    var this_this = this;

                    this.$http.get(this.bookingProduct.slot_index_route, {params: {date: date}})
                        .then (function(response) {
                            this_this.selected_slot = '';
                            
                            this_this.slot_from = '';

                            this_this.slots = response.data.data;
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