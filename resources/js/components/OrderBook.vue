<template>
    <div id="order-table" class="table-responsive" style="background: #1d2435; height: 100%; overflow-y: auto;">
        <div class="d-sm-none d-md-block hide-sm_mol">

            <table class="table table-small-font no-mb table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Asset</th>
                    <th>Amount</th>
                    <th>Qty</th>
                    <th>Direction</th>
                    <th>Leverage</th>
                    <th>Current Price</th>
                    <th>Opening Price</th>
                    <th>PnL</th>
                    <th>Opened at</th>
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
                    <template v-if="index <= 2">
                        <td><img style="height : 25px" :src="i.currency.image"   /></td>
                        <td>{{ i.currency.name }}</td>
                        <td>${{ formatPrice(i.traded_amount) }} </td>
                        <td>{{ i.qty }} {{ i.currency.sym }}</td>
                        <td>
                            <span v-if="i.trade_type === 'buy'" class="badge badge-success p-2">Buy</span>
                            <span v-else class="badge badge-warning p-2">Sell</span>
                        </td>
                        <td>{{ i.leverage }}X</td>
                        <td>${{ formatPrice(i.closing_price) }}</td>
                        <td>${{ formatPrice(i.opening_price) }}</td>
                        <td>{{ formatPrice(i.profit) }}</td>



                        <td>{{ i.open_at }}</td>

                        <!-- <td>
                            <span v-if="i.status === 0" class="badge badge-warning p-2">Running</span>
                        </td> -->
                        <td>
                            <button v-if="i.by_admin" disabled class="btn btn-danger btn-sm">Close</button>
                            <button v-else @click="closeOrder(i, index)" class="btn btn-danger btn-sm">Close</button>
                        </td>
                    </template>

                <tr v-if="p_trades.length > 3">
                    <td class="text-center" colspan="100%"><a href="/dashboard/trades">View all open trades</a></td>
                </tr>


                </tbody>
            </table>
        </div>
        <div class="d-md-none d-block" id="accordi">
            <div v-if="p_trades.length < 1">
                <h3 class="text-center">No open trades</h3>
            </div>


            <div class="card" v-for="(i, index) in p_trades" v-if="index <= 2">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0 d-flex justify-content-between" style="background: #222D48;padding: 5px">
                        <label>
                            <img style="height : 25px" :src="i.currency.image"   />
                            {{i.currency.name}}
                            <br>

                        </label>
                        <button style="color: #94969B !important;text-decoration: none" class="btn btn-link"
                                data-toggle="collapse" :data-target="'#collapseOne'+index" aria-expanded="true" :aria-controls="'collapseOne'+index">
                            ${{ formatPrice(i.traded_amount) }}<br>
                            <span class="text-danger">{{ formatPrice(i.profit) }}</span><i class="fa fa-angle-down"></i>
                        </button>
                    </h5>
                </div>
                <div :id="'collapseOne'+index" class="collapse " aria-labelledby="headingOne" data-parent="#accordi">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <label>
                                Amount
                            </label>
                            <label class="color-white">
                                ${{ formatPrice(i.traded_amount) }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Qty
                            </label>
                            <label class="color-white">
                                {{ i.qty }} {{ i.currency.sym }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Direction
                            </label>
                            <label>
                                <span v-if="i.trade_type === 'buy'" class=" text-success p-2">Buy</span>
                                <span v-else class="text-warning p-2">Sell</span>

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Leverage
                            </label>
                            <label class="color-white">
                                ${{ formatPrice(i.closing_price) }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Current Price
                            </label>
                            <label class="color-white">
                                ${{ formatPrice(i.opening_price) }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Opening Price
                            </label>
                            <label class="color-white">
                                {{ formatPrice(i.profit) }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>
                                Opened at
                            </label>
                            <label class="color-white">
                                {{ i.open_at }}

                            </label>


                        </div>
                        <div class="d-flex justify-content-between">
                            <label>

                            </label>
                            <label>
                                <button v-if="i.by_admin" disabled class="btn btn-danger btn-sm">Close</button>
                                <button v-else @click="closeOrder(i, index)" class="btn btn-danger btn-sm">Close</button>


                            </label>


                        </div>












                    </div>
                </div>
            </div>


<div class="text-center" v-if="p_trades.length > 3">

      <a href="/dashboard/trades">View all open trades</a>
</div>

        </div>

    </div>
</template>

<script>

export default {
    name: "OrderBookAll",
    props : ['p_trades','close_trade_url'],
    data(){
        return {
            play:true,
        }
    },
    mounted() {
        // this.p_trades = this.open_trades;

    },
    methods : {
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
                    location.reload();
                    this.removeTrade(item, index)
                    this.play = true;

                    // swal("Success!", "Order closed successfully", "success", 3000);
                })
            }else{
                toastr.error('Slow down a little, still processing some request');
            }
        },
        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    },
}
</script>

<style scoped>

</style>
