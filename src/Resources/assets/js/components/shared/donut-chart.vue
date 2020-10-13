<template>
    <svg class="pie">
        <circle
            v-for="item in dataObjects"
            v-bind:style="{strokeDasharray: `${item.relativeSize} ${circleLength}`, strokeDashoffset: item.offset}"
            r="43%"
            cx="50%"
            cy="50%"
            />

        <foreignObject x="34" y="55" width="110" height="50">
            <slot></slot>
        </foreignObject>
    </svg>
</template>

<script>
    export default {
        name: 'donut-chart',

        props: ['data'],

        data () {
            return {
                circleLength: 455.9451599121094,

                hasMounted: true
            }
        },

        methods: {
            shuffle (data) {
                let dataCopy = data.slice();
                let temp;
                let index;
                let randomIndex;

                for (index = 0; index < dataCopy.length; index++) {
                    randomIndex = Math.floor(Math.random() * index);
                    temp = dataCopy[index];
                    dataCopy[index] = dataCopy[randomIndex];
                    dataCopy[randomIndex] = temp;
                }

                this.data = dataCopy;
            }
        },

        computed: {
            dataTotal () {
                return this.data.reduce((previous, current) => previous + current);
            },

            dataObjects () {
                let startingPoint = 0;

                return this.data.map(item => {
                    let relativeSize = ((item / this.dataTotal) * this.circleLength);
                    let dataObject =  { relativeSize: this.hasMounted ? relativeSize: 0 , offset: -startingPoint };
                    startingPoint += relativeSize;
                    
                    return dataObject;
                })
            }
        }
    }
</script>

<style lang="scss">
    svg {
        width: 162px;
    }
    
    .pie circle {
        fill: none;
        stroke-width: 12;
        transition: stroke-dasharray 0.3s ease-in-out,stroke-dashoffset 0.3s ease-in-out;
    }

    $colors: #E51A1A, #FF4848, #FFA100, #FFCC00, #6BC700;

    @for $i from 1 through length($colors) {
        .pie circle:nth-child(#{$i}) {
            stroke: nth($colors, $i);
        }
    }
</style>