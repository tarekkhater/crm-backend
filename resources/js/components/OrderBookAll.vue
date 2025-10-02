<template>
    <div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div style="margin-top: 10px" class="card">

        <div class="card-body">
            <ul class="nav nav-tabs colo_black">
                <li class="active">
                    <a style="margin-right: 20px" href="#" @click="setTrade(0)" class="btn btn-success tap0" data-toggle="tab">
                        Open Orders ({{ p_trades.length }})
                    </a>
                </li>
                <li>
                    <a href="#" @click="setTrade(1)" class="btn btn-outline-secondary tap1" data-toggle="tab">
                        Closed Trades({{ closed_trades.length }})
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="open">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <div class="table0">
                            <div class="d-sm-none d-md-block hide-sm_mol">
                                <table  class="table     table-striped datatable10" id="">
                                    <thead>
                                    <tr>
                                        <th>Opened at</th>
                                        <th>Currency / Asset</th>
                                        <th>Amount</th>
                                        <th>Qty</th>
                                        <th>Leverage</th>
                                        <th>Current Price</th>
                                        <th>Opening Price</th>
                                        <th>PnL</th>
                                        <th>Direction</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="p_trades.length < 1">
                                    <tr>
                                        <td class="text-center" colspan="100%">No open trades</td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr v-for="(i, index) in p_trades">
                                        <td>{{ i.open_at }}</td>
                                        <td>{{ i.currency ? i.currency.name : '' }}</td>
                                        <td>${{ formatPrice(i.traded_amount) }} </td>
                                        <td>{{ i.qty }} {{ i.currency ? i.currency.sym : '' }}</td>
                                        <td>{{ i.leverage }}X</td>
                                        <td>${{ formatPrice(i.closing_price) }}</td>
                                        <td>${{ formatPrice(i.opening_price) }}</td>
                                        <td :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(i.profit) }}</td>

                                        <td>
                                            <span v-if="i.trade_type === 'buy'" class="text-success p-2">Buy</span>
                                            <span v-else class="text-danger p-2">Sell</span>
                                        </td>
                                        <td>
                                            <span v-if="i.status === 0" class="text-warning p-2">Running</span>
                                        </td>
                                        <td>
                                            <button v-if="i.by_admin"  disabled class="btn btn-danger">Close</button>
                                            <button v-else  @click="closeOrder(i, index)" class="btn btn-danger">Close</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-md-none d-block">



                                    <div id="accordion" style="font-size: 13px" >

                                        <div class="card listp_trades " v-for="(i, index) in p_trades">
                                            <div style="width: 100%" class="card-header d-flex justify-content-between" :id="'heading'+index">
                                                <div>
                                                    <img :src="i.currency?i.currency.image:''" style="margin-right: 25px">
                                                    <!--                                            <img style="max-height: 20px; max-width: 20px" :src="item.image" alt="account balance" />-->
                                                    {{ i.currency ? i.currency.name : '' }}
                                                </div>

                                                <div>
                                                    <span class="text-white">


                                                    ${{ formatPrice(i.traded_amount) }}
                                                    <i data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index" class="fa fa-angle-up"></i>
                                                         </span>
<br>
                                                    <span
                                                        :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(i.profit) }}
                                                    </span>

                                                </div>

                                            </div>

                                            <div :id="'collapse'+index" class="collapse " :aria-labelledby="'heading'+index"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div style="margin-bottom: 5px" class="d-flex justify-content-between">
                                                        <span > Amount</span>
                                                        <span class="text-white">
${{ formatPrice(i.traded_amount) }}
                                                        </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            qty
                                                        </span>
                                                        <span class="text-white">
                                                            {{ i.qty }}
                                                        </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            leverage
                                                        </span>
                                                        <span class="text-white">
                                                           {{ i.leverage }}X
                                                        </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Closing Price
                                                        </span>
                                                        <span class="text-white">
                                                         ${{ formatPrice(i.closing_price) }}
                                                        </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Opening Price
                                                        </span>
                                                        <span class="text-white">
                                                       ${{ formatPrice(i.opening_price) }}
                                                        </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Pnl
                                                        </span>
                                                        <span
                                                            :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(i.profit) }}
                                                    </span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Direction
                                                        </span>
                                                        <span v-if="i.trade_type === 'buy'" class="text-success p-2">Buy</span>
                                                        <span v-else class="text-danger p-2">Sell</span>

                                                    </div>

                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            STatus
                                                        </span>
                                                        <span v-if="i.status === 0" class="text-warning p-2">Running</span>

                                                    </div>
                                                    <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>

                                                        </span>
                                                        <button v-if="i.by_admin"  disabled class="btn btn-danger">Close</button>
                                                        <button v-else  @click="closeOrder(i, index)" class="btn btn-danger">Close</button>


                                                    </div>



                                                </div>
                                            </div>
                                        </div>



                                    </div>






                            </div>

                        </div>
                        <div class="table1 d-none" >
                            <div class="d-sm-none d-md-block hide-sm_mol">
                            <table id="datatable_new10" class="table   table-striped datatable_new10">
                                <thead>
                                <tr>
                                    <th>Opened at </th>
                                    <th>Closed at </th>
                                    <th>Currency / Asset</th>
                                    <th>Amount</th>
                                    <th>Qty</th>
                                    <th>PnL</th>
                                    <th>Opening Price</th>
                                    <th >Closing Price</th>
                                    <th>Direction</th>

                                    <th >Result</th>

                                </tr>
                                </thead>
                                <tbody v-if="closed_trades < 1">
                                <tr>
                                    <td class="text-center" colspan="100%">No closed trades</td>
                                </tr>
                                </tbody>
                                <tbody v-else>
                                <tr v-for="i in closed_trades">
                                    <td>{{ i.open_at }}</td>
                                    <td> {{ i.close_at }}</td>
                                    <td>{{ i.currency ? i.currency.name : '' }}</td>
                                    <td>{{ i.traded_amount }} USD</td>
                                    <td>{{ i.qty }} {{ i.currency ? i.currency.sym : '' }}</td>
                                    <td :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ i.profit }}</td>
                                    <td>${{ formatPrice(i.opening_price) }}</td>
                                    <td>${{ formatPrice(i.closing_price) }}</td>
                                    <td>
                                        <span v-if="i.trade_type === 'buy'" class="text-success p-2">Buy</span>
                                        <span v-else class="text-warning p-2">Sell</span>
                                    </td>
                                    <td v-if="i.status === 0">
                                        <span v-if="i.status === 0" class="text-warning p-2">Running</span>
                                    </td>
                                    <td v-if="i.status === 1">
                                        <span v-if="i.result === 2" class="text-danger p-2">loss</span>
                                        <span v-if="i.result === 1" class="badge text-success p-2">Won</span>
                                        <span v-if="i.result === 3" class="badge text-warning p-2">Draw</span>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            </div>


                            <div class="d-md-none d-block">



                                <div id="accordion2" style="font-size: 13px" >

                                    <div class="card listp_trades " v-for="(i, index) in closed_trades">
                                        <div style="width: 100%" class="card-header d-flex justify-content-between" :id="'heading'+index">
                                            <div>
                                                <img :src="i.currency?i.currency.image:''" style="margin-right: 25px">
                                                <!--                                            <img style="max-height: 20px; max-width: 20px" :src="item.image" alt="account balance" />-->
                                                {{ i.currency ? i.currency.name : '' }}
                                            </div>

                                            <div>
                                                    <span class="text-white">


                                                    ${{ formatPrice(i.traded_amount) }}
                                                    <i data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index" class="fa fa-angle-up"></i>
                                                         </span>
                                                <br>
                                                <span
                                                    :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(i.profit) }}
                                                    </span>

                                            </div>

                                        </div>

                                        <div :id="'collapse'+index" class="collapse " :aria-labelledby="'heading'+index"
                                             data-parent="#accordion2">
                                            <div class="card-body">
                                                <div style="margin-bottom: 5px" class="d-flex justify-content-between">
                                                    <span > Open At</span>
                                                    <span class="text-white">
{{ i.open_at }}
                                                        </span>

                                                </div>
                                                <div style="margin-bottom: 5px" class="d-flex justify-content-between">
                                                    <span > close At</span>
                                                    <span class="text-white">
{{ i.close_at }}
                                                        </span>

                                                </div>
                                                <div style="margin-bottom: 5px" class="d-flex justify-content-between">
                                                    <span > Amount</span>
                                                    <span class="text-white">
${{ formatPrice(i.traded_amount) }}
                                                        </span>

                                                </div>
                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            qty
                                                        </span>
                                                    <span class="text-white">
                                                           {{ i.qty }} {{ i.currency ? i.currency.sym : '' }}
                                                        </span>

                                                </div>

<!--                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">-->
<!--                                                        <span>-->
<!--                                                            leverage-->
<!--                                                        </span>-->
<!--                                                    <span class="text-white">-->
<!--                                                           {{ i.leverage }}X-->
<!--                                                        </span>-->

<!--                                                </div>-->
                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Closing Price
                                                        </span>
                                                    <span class="text-white">
                                                         ${{ formatPrice(i.closing_price) }}
                                                        </span>

                                                </div>
                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Opening Price
                                                        </span>
                                                    <span class="text-white">
                                                       ${{ formatPrice(i.opening_price) }}
                                                        </span>

                                                </div>
                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Pnl
                                                        </span>
                                                    <span
                                                        :class="i.profit < 1 ? 'text-danger' : 'text-success'">{{ formatPrice(i.profit) }}
                                                    </span>

                                                </div>
                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Direction
                                                        </span>
                                                    <span v-if="i.trade_type === 'buy'" class="text-success p-2">Buy</span>
                                                    <span v-else class="text-danger p-2">Sell</span>

                                                </div>

                                                <div style="margin-bottom: 5px"  class="d-flex justify-content-between">
                                                        <span>
                                                            Result
                                                        </span>
                                                    <span v-if="i.result === 2" class="text-danger p-2">loss</span>
                                                    <span v-if="i.result === 1" class="badge text-success p-2">Won</span>
                                                    <span v-if="i.result === 3" class="badge text-warning p-2">Draw</span>

                                                </div>



                                            </div>
                                        </div>
                                    </div>



                                </div>






                            </div>
                        </div>


                    </div>

                </div>

            </div>
            <div class="clock w-100"></div>
        </div>

    </div>
        </div>
    </div>
        <trade_footer :plan="plan" :bonus="bonus" :bal="bal" :margin="margin" :free_margin="freeMargin" :equity="equity" :pnl="pnl"></trade_footer>
    </div>
</template>

<script>

export default {
name: "OrderBookAll",
    props : ['open_trades','interv','trades','balance','trade_url','all_trade_url','close_trade_url','plan','bonus'],
    data(){
    return {
        p_trades : [],
        play:true,
        bal : 0,
        is_type:0,
        all_trades:[],
    }
    },
    mounted() {
    this.p_trades = this.open_trades;

    this.all_trades = this.trades;

    this.getBal();

        this.startUpdate();
    // this.getTrades();

    },
    methods : {
        setTrade(i){
            this.is_type = i;
             if(i==0){
                 $('.tap0').addClass("btn-success")
                 $('.tap0').removeClass('btn-outline-secondary')
                 $('.tap1').removeClass("btn-success")
                 $('.tap1').addClass('btn-outline-secondary')


             }
             else{
                 $('.tap1').addClass("btn-success")
                 $('.tap1').removeClass('btn-outline-secondary')
                 $('.tap0').removeClass("btn-success")
                 $('.tap0').addClass('btn-outline-secondary')




             }
            this.show_table()

        },
        removeTrade(item, index){
            if(this.p_trades[index] === item) {
                this.p_trades.splice(index, 1)
                toastr.success('Trade successfully closed');
            } else {
                let found = this.items.indexOf(item)
                this.p_trades.splice(found, 1)
                toastr.success('Trade successfully closed');

            }
        },
        closeOrder(item, index){
            let id = item.id
            if(this.play){
                this.play = false
                toastr.success('Processing your trade .... hold on');
                axios.post(this.close_trade_url, { id : id}).then((res) => {
                    this.removeTrade(item, index)
                    this.play = true;
                    this.getAllTrades();
                    this.updateProfits();

                    // swal("Success!", "Order closed successfully", "success", 3000);
                })
            }else{
                toastr.error('Slow down a little, still processing some request');
            }
        },
        show_table(){
          if(this.is_type==0){
              $('.table1').addClass("d-none")
              $('.table0').removeClass("d-none")
          }  else{
              $('.table0').addClass("d-none")
              $('.table1').removeClass("d-none")
          }
        },


        // checkTrade(){
        //     axios.get(this.check_trade_url).then((response) => {
        //         if(response.data.status > 0){
        //             toastr.success("trade successfully closed")
        //             this.getTrades();
        //         }
        //     })
        // },

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
            console.log('updating profit');
            axios.get('/dashboard/api/update/profit').then((res) => {
                this.p_trades = res.data;
            })
            // console.log(this.updateTrades);
        },

        getAllTrades(){
                axios.get(this.all_trade_url).then((res) => {
                    this.all_trades = res.data;
                })
        },

        startUpdate(){
            console.log('updating at....' + this.interval)
            this.updateProfits();
            this.timer = setInterval(() => {
                this.updateProfits();
            }, this.interval)
        }
    },

    watch: {
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

        closed_trades(){
        return this.all_trades.filter(item => {
            return item.status === 1
        });
    },
        // pnl(){
        //     // return  this.p_trades.reduce((trade, item) => parseInt(trade + item.profit), 0);
        //     return  this.p_trades.reduce((trade, item) => parseFloat(trade + item.profit), 0);
        // },

        pnl: function(){
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
            return parseInt(this.bal) +  (parseInt(this.pnl))
        },
        freeMargin(){
            return parseInt(this.bal)  - (parseFloat(this.margin)) + (parseInt(this.pnl))
        },
    }
}

</script>


<style scoped>

</style>
