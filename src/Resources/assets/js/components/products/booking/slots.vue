<template>
    <div class="book-slots">
        <label>{{ title ? title : $t('booking.book_an_appointment') }} :</label>

        <div class="control-group-container">
            <div class="control-group date" :class="[errors.has('booking[date]') ? 'has-error' : '']">
                <input type="date" name="booking[date]" class="control" v-model="formData.booking.date" :placeholder="$t('Date Of Birth')" v-on:input="dateSelected()"/>
                <span class="control-error" v-if="errors.has('booking[date]')">{{ errors.first('booking[date]') }}</span>
            </div>

            <div class="control-group slots" :class="[errors.has('booking[slot]') ? 'has-error' : '']">
                <select v-validate="'required'" name="booking[slot]" class="control" :data-vv-as="$t('booking.slot')" v-model="formData.booking.slot">
                    <option v-for="slot in slots" :value="slot.timestamp">{{ slot.from + ' - ' + slot.to }}</option>
                </select>

                <span class="control-error" v-if="errors.has('booking[slot]')">{{ errors.first('booking[slot]') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import date from './date';

    export default {
        name: 'slots',

        inject: ['$validator'],

        components: {
            date
        },

        props: ['title', 'bookingProduct', 'formData'],

        data: function() {
            return {
                slots: []
            }
        },

        methods: {
            dateSelected: function() {
                this.$http.get(this.bookingProduct.slot_index_route, {params: {date: this.formData.booking.date}})
                    .then (response => {
                        this.slots = response.data.data;

                        this.errors.clear();
                    })
                    .catch (function (error) {})
            }
        }
    }
</script>