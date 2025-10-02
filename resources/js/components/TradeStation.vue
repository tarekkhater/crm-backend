<script>
export default {
name: "TradeStation",
    props : ['trades','cur_currency','interv','updateTrades','u_can_trade','currencies','balance','trade_url','all_trade_url','close_trade_url','check_trade_url','cannot_trade_msg'],
    data(){
    return {
        currency:this.cur_currency,
        type : 'buy',
        // amt : 1,
        autoclose:0,
        duration:10,
        p_trades : [],
        time : 10,
        play:true,
        bal : 0,
        coinPrice : 0,
        coinPriceFixed : 0,
        coinId : '',
        is_type:0,
        sell_price:0,
        buy_price:0,
        is_stop_loss:false,
        is_take_profit:false,
        stop_loss:0,
        take_profit:0,
        volume:1,
        rang_amount:0,
        all_trades:[],
        open_trades:[],
        closed_trades:[],
    }
    },
    mounted() {
    this.p_trades = this.trades;
    this.bal = this.balance;
    this.getBal();

        this.startUpdate();

        this.getCurPrice();
        this.getCurPriceFixed();


        // this.timer = setInterval(() => {
        //     this.updateProfits();
        // }, 10000)


        this.currency = this.cur_currency;
    },
    methods : {
        loadCur(item){
            this.currency = item;
        },
        decreaseVolume(){
            if(this.volume > 0.1){
                this.volume = this.volume - 0.1;
            }

        },
        decreaseVolumeupdate(){
            if(this.volume > 1){
                this.volume = this.volume - 1;
            }

        },
        increaseVolume(){
            this.volume = this.volume + 0.1;
        },
        increaseVolumeupdate(){
            this.volume = this.volume + 1;
        },
        updateRange(){
            var id = event.target.getAttribute('data-balance');
            this.volume=parseInt((parseInt(id)*this.rang_amount)/100)

        },

        playNow(type){
            this.type = type;
            if(this.play){
                this.playGame();
            }else{
                toastr.error('Opps!!! You are too fast, slow down a little');
            }
        },
        playGame(){
            this.play = false;
            let data = {
                amount: this.amt,
                coinId: this.currency.id,
                auto_close : this.autoclose,
                type: this.type,
                is_stop_loss: this.is_stop_loss,
                stop_loss: parseFloat(this.stop_loss),
                is_take_profit: this.is_take_profit,
                take_profit: parseFloat(this.take_profit),
                duration: this.duration,
            };
            if(this.is_stop_loss && this.stop_loss < 1){
                this.play = true;
                toastr.error('Pls set your stop loss ');
            }else if(this.is_take_profit && this.take_profit < 1){
                this.play = true;
                toastr.error('Pls set your take profit ');
            }else if (this.balance < this.amt) {
                this.play = true;
                toastr.error('Insufficient balance, pls fund your account to trade');
            } else if(!this.can_trade){
                this.play = true;
                toastr.error(this.cannot_trade_msg)
            } else if (isNaN(this.amt) || this.amt <= 0) {
                this.play = true;
                toastr.error('Enter amount to trade');
            } else if (this.auto_close > 0) {
                if(isNaN(this.duration) || this.duration <= 0){
                    this.play = true;
                    toastr.error('Please Select Duration');
                }else {
                    //send game
                    this.sendGame(data)
                }
            } else {
                this.sendGame(data)
            }
        },
        sendGame(data){
            this.play = false;
            let element = this.$refs.conf
            let sellEl = this.$refs.confsell
            $(element).modal('hide')
            $(sellEl).modal('hide')
            axios.post(this.trade_url, data).then((res) => {
                this.startUpdate();
                this.play = true;

                this.getBal();

                if (res.data.status === 1) {
                    // this.getGameLog();
                    swal("Success!", "Order placed successfully, pls dont close this window for short interval trades", "success", 3000);

                } else {
                    this.play = false;
                    toastr.error(res.data.msg);
                }
            })
        },

        closeOrder(id){
            if(this.play){
                this.play = false
                toastr.success('Processing your trade');
                axios.post(this.close_trade_url, { id : id}).then((res) => {
                    this.updateProfits();
                    this.play = true

                    swal("Success!", "Order closed successfully", "success", 3000);
                })
            }else{
                toastr.error('Slow down a little, still processing some request');
            }
        },

        canNotTrade(){
            toastr.error(this.cannot_trade_msg)
        },
        checkTrade(){
            axios.get(this.check_trade_url).then((response) => {
                if(response.data.status > 0){
                    toastr.success("trade successfully closed")
                    this.updateProfits();
                }
            })
        },
        getCoinPrice(){
            axios.post('/api/crypto/rate', {coinSymbol: this.cur_currency.sym}).then((res) =>{
                this.coinPrice = res.data;
                // if(this.open_trades.length > 0){
                //     this.checkTrade();
                // }
            })
        },



        getCurPrice(){
            axios.get('/api/cur/rate/'+this.cur_currency.sym+'/'+this.cur_currency.base+'/'+this.cur_currency.type).then((res) =>{
                this.coinPrice = res.data;
            })
        },

        getBuySellPrice(){
            // this.getCurPrice();
            let sell_spread = parseFloat((this.cur_currency.sell_spread * this.coinPrice) / 100);
            let buy_spread = parseFloat((this.cur_currency.buy_spread * this.coinPrice) / 100);
            const  s_price = parseFloat(this.coinPrice) - parseFloat(sell_spread);
            const b_price = parseFloat(this.coinPrice) + parseFloat(buy_spread);
            this.sell_price = s_price.toFixed(2)
            this.buy_price = b_price.toFixed(2)
        },

        getCurPriceFixed(){
            axios.get('/api/cur/rate/'+this.cur_currency.sym+'/'+this.cur_currency.base+'/'+this.cur_currency.type).then((res) =>{
                this.coinPriceFixed = res.data;
            })
        },


        turnTrader(){
            this.canNotTrade();
        },


        getBal(){
            axios.get('/api/user/balance').then((res) => {
                this.bal = res.data;

            })

        },

        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

        updateProfits(){
            axios.get('/dashboard/api/update/profit').then((res) => {
                this.p_trades = res.data;
            })
            // console.log(this.updateTrades);
        },

        startUpdate(){
            console.log('updating interval .... ='+this.interval)
            this.getBuySellPrice();
            this.updateProfits();
            this.timer = setInterval(() => {
                this.updateProfits();
                this.getCurPrice();
                this.getBuySellPrice();
            }, this.interval)
        },
    },
    watch: {
    amount(){
        this.getCurPrice();
    },
    open_trades(){
        // if(this.open_trades.length > 0){
        //     this.timer = setInterval(() => {
        //         this.checkTrade()
        //     }, 10000)
        // }

        // else {
        //     clearInterval(this.timer)
        // }
    },
    },

    computed : {
        interval(){
            if(this.interv){
                return parseInt(this.interv)
            }else {
                return 2000;
            }
        },
        // pnl(){
        //     return  this.p_trades.reduce((trade, item) => parseFloat(trade + item.profit), 0);
        // },
        amt(){
            return this.priceInUsd;
        },
        userValue(){
            return parseInt(this.bal / parseInt(this.priceInUsdFixed));
        },
        pnl(){
            let sum = 0;
            for(let i = 0; i < this.p_trades.length; i++){
                sum += (parseFloat(this.p_trades[i].profit));
            }
            return sum;
        },

        margin(){
            return  this.p_trades.reduce((trade, item) => parseInt(trade + item.traded_amount), 0);
        },

        equity(){
            return parseFloat(this.bal) + (parseFloat(this.pnl))
        },
        freeMargin(){

            return parseFloat(this.bal) - (parseFloat(this.margin)) + (parseFloat(this.pnl))
        },
    can_trade(){
        return this.u_can_trade > 0;
    },
        priceInCoin(){
            let newAmt = 0;
            let lev = 1;
            if(this.cur_currency.leverage > 0){
                lev = this.cur_currency.leverage;
            }
            if(this.amt > 0 && this.coinPrice > 0){
                newAmt = (this.amt * lev) / this.coinPrice;
            }

            return newAmt;

        },
        priceInUsd(){
            let newAmt = 0;
            let lev = 1;
            if(this.cur_currency.leverage > 0){
                lev = this.cur_currency.leverage;
            }
            if(this.volume > 0 && this.coinPrice > 0){
                newAmt = (this.volume * this.coinPrice) /  lev;
            }
            return newAmt.toFixed(2);
        },
        priceInUsdFixed(){
            let newAmt = 0;
            let lev = 1;
            if(this.cur_currency.leverage > 0){
                lev = this.cur_currency.leverage;
            }
            if(this.coinPriceFixed > 0){
                newAmt = (this.coinPriceFixed) /  lev;
            }
            return newAmt;
        }
    }
}
</script>

<style scoped>

</style>
