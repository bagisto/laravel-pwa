<template>
    <div class="booking-product">
        <div class="booking-info-row">
            <span class="icon bp-slot-icon"></span>
            <span class="title">
                {{ $t('booking.event_on') }}
            </span>

            <span class="value left-shift" v-html="bookingProduct.event_date">
            </span>
        </div>

        <div class="book-slots">
            <label style="font-weight: 600">{{ $t('booking.book_your_ticket') }}</label>

            <div class="ticket-list">
                <div class="ticket-item" v-for="(ticket, index) in tickets">
                    <div class="ticket-info">
                        <div class="ticket-name">
                            {{ ticket.name }}
                        </div>

                        <div v-if="ticket.original_formated_price" class="ticket-price">
                            <span class="regular-price">{{ ticket.original_formated_price }}</span>
                            <span class="special-price">{{ ticket.formated_price_text }}</span>
                        </div>
                        <div v-else class="ticket-price">
                            {{ ticket.formated_price_text }}
                        </div>
                    </div>

                    <p>{{ ticket.description }}</p>

                    <div class="ticket-quantity qty">
                        <div class="quantity-container">
                            <label>{{ $t('Quantity') }}</label>

                            <div class="quantity">
                                <button type="button" class="btn btn-black decrease-qty"
                                    @click="changeQuantity('decrease', ticket.id)">
                                    <i class="icon minus-icon"></i>
                                </button>

                                <div class="quantity-label">
                                    <span :id="`qty-${ticket.id}`">{{ formData.booking.qty[ticket.id] }}</span>
                                    {{ $t('number Units', {number: null }) }}
                                </div>

                                <button type="button" class="btn btn-black increase-qty" @click="changeQuantity('increase', ticket.id)">
                                    <i class="icon plus-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

        data: function() {
            return {
                tickets: this.bookingProduct.tickets,
            }
        },

        methods: {
            changeQuantity: function (type, ticketId) {
                if (type == 'increase') {
                    this.$set(this.formData.booking.qty, ticketId, this.formData.booking.qty[ticketId] + 1);
                } else if (type == 'decrease') {
                    if (this.formData.booking.qty[ticketId] > 1) {
                        this.$set(this.formData.booking.qty, ticketId, this.formData.booking.qty[ticketId] - 1);
                    }
                }

                document.getElementById(`qty-${ticketId}`).innerHTML = this.formData.booking.qty[ticketId];
            },
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

    .ticket-price .regular-price{
        color: #a5a5a5;
        text-decoration: line-through;
        margin-right: 5px;
    }
    .ticket-price .special-price {
        color: #ff6472;
        font-size: larger;
    }
    .book-slots label {
        font-size: 14px;
    }

    .ticket-list {
        padding-top: 10px;
        line-height: 25px;
    }
</style>