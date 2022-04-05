<template>
<div class="container">
        <input type="text" 
            id="searchorder"
            v-model="searchString"
            v-on:keyup="searchData"
            placeholder="Search Order Id, Status, Order Date, or Grand Total." 
            class="control">   
</div>
</template>

<script>
    export default {
        name: 'order-filter',
        data () {
			return {
                searchString:null
            }
        },
        methods: {
        searchData () {
            if(this.searchString.length < 1)
            {
               $('.order-card').show() 
            }
            else{
                $('.order-card').hide()
                $("div[class*="+this.searchString+"]").show();
            }
        } 
        },

    }
</script>
<style>
.container{
    padding:15px 15px 15px 15px;
}
.control{
    height:50px;
    width:100%;
}
</style>