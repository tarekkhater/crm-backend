<template>
<div>
    <footer class="foot px-2 py-1 hidden-sm">
        <div
            class="d-flex align-items-center justify-content-start"
        >
            <div>
                <p class="acct-type">
                    <i class="fa fa-gem"></i>&nbsp;
                    {{  plan  }}
                </p>
            </div>
            <div class="px-4 py-1">
                <p>
                    <!-- <span>BALANCE: <strong>{{  formatPrice(bal) }}</strong></span>
                    <span>BONUS : <strong>{{  formatPrice(bonus) }}</strong></span> -->
                    <span>EQUITY: <strong>{{ formatPrice(equity) }}</strong></span>
                    <span>= &emsp;P/L: <strong :class="pnl < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(pnl) }}</strong></span>
                    <span>+ &emsp;MARGIN: <strong>{{ formatPrice(margin) }}</strong></span>
                    <span>+ &emsp;FREE MARGIN: <strong>{{ formatPrice(free_margin) }}</strong></span>
                </p>
            </div>
            <div></div>
        </div>
    </footer>
    <footer class="foot d-sm-none d-md-none d-lg-none">
        <div
            class="d-flex align-items-center justify-content-center"
            style="padding-left: 1rem; padding-right: 1rem;"
        >
            <div>
                <p class="d-flex justify-content-between">
                    <span :class="pnl < 1 ? 'text-danger' : 'text-success'">PROFIT/LOSS:<br/>{{ formatPrice(pnl) }} |</span>&nbsp;
                    <span>EQUITY:<br/> {{ formatPrice(equity) }} |</span>&nbsp;
                    <span>MARGIN:<br/> {{ formatPrice(margin) }} |</span>&nbsp;
                    <span>FREE MARGIN:<br/> {{ formatPrice(free_margin) }}</span>
                </p>
            </div>
        </div>
    </footer>
</div>
</template>

<script>
export default {
    props : ['bal','pnl','equity','margin','free_margin','plan','bonus'],
name: "TradeFooter",
    mounted() {
        console.log('balance'+this.bal)
    },
    data(){
        return {
            balPercent : null
        }
    },
    methods : {
        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

        closeAllTrades(){
            // axios.post('/dashboard/close/all/trades').then((res) =>{
            //
            // })
        }
    },
    watch : {
        free_margin(){
                if(this.free_margin > 0 && this.free_margin < 2){
                    toastr.success("closing all trades .........")
                    // this.closeAllTrades();
            }
        },
        margin(){
            if(this.balPercent){
                console.log('watch bal'+this.balPercent)
            }
            console.log(this.margin)

            if(this.margin < 1){
                console.log(this.margin)
            }
        },
        bal() {
            this.balPercent = (this.bal * 10) / 100;
        }
    },
    computed : {

    }
}
</script>

<style scoped>

</style>
