<template>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div style="margin-top: 10px" class="card">

        <div class="card-body">

            <div class="">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table class="table table-small-font no-mb table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Opened at</th>
                                <th>Currency / Asset</th>
                                <th>Amount</th>
                                <th>Qty</th>
                                <th>PnL</th>
                                <th>Opening Price</th>
                                <th>Direction</th>
                                <th>Result</th>
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
                                <td>{{ i.currency.name }}</td>
                                <td>{{ i.traded_amount }} USD</td>
                                <td>{{ i.qty }} {{ i.currency.sym }}</td>
                                <td>{{ i.profit }}</td>
                                <td>${{ formatPrice(i.opening_price) }}</td>
                                <td>
                                    <span v-if="i.trade_type === 'buy'" class="badge badge-success p-2">Buy</span>
                                    <span v-else class="badge badge-warning p-2">Sell</span>
                                </td>
                                <td v-if="i.status === 1">
                                    <span v-if="i.result === 2" class="badge badge-danger p-2">loss</span>
                                    <span v-if="i.result === 1" class="badge badge-success p-2">Won</span>
                                    <span v-if="i.result === 3" class="badge badge-warning p-2">Draw</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
            <div class="clock w-100"></div>
        </div>

    </div>
        </div>
    </div>
</template>

<script>

export default {
name: "TradeHistory",
    props : ['closed_trades'],
    data(){
    return {
        is_type:1,
    }
    },
    mounted() {

    },
    methods : {

        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

    },
}
</script>

<style scoped>

</style>
