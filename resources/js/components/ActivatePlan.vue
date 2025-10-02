<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Invest in {{  active_plan.name }} plan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form @submit.prevent="submit">
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <h5 class="font-weight-light">Account Balance : {{ formatPrice(user.balance)}} USD </h5>
                                <p v-if="!balanceGt" class="text-danger">Insufficient balance, pls fund account</p>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <label>Choose plan</label>
                    <select class="form-control" v-model="active_plan">
                        <option disabled="">Change Plan ...</option>
                        <option v-for="item in plans" :value="item" >{{ item.name }} - {{ item.percent_profit }} % / {{ item.period }} {{ item.period_type }} | ${{ formatPrice(item.price * item.min_unit) }} to ${{ formatPrice(item.max_unit * item.price)}}</option>
                    </select>
                </div>
                <div class="col-12 mt-2" v-if="active_plan.description">
                    <section class="col-md-6 border border-light rounded">
                        <p class="card-text">{{ active_plan.description }}</p>
                    </section>
                </div>

                <div class="col-md-12">
                    <div class="card-body">
                        <section class="card-title">
                            <h6 class="card-subtitle mb-2 text-light">Choose Units</h6>
                            <div class="input-group mb-3">
                                <span class="input-group-text">{{ active_plan.min_unit }}</span>
                                <input v-model="unit"  type="range" class="form-range form-control" :min="active_plan.min_unit" :max="active_plan.max_unit" step="1" />
                                <span class="input-group-text">{{ active_plan.max_unit }}</span>
                            </div>
                        </section>
                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-4">Units: {{ unit }}</div>
                                <div class="col-md-4">Amount: ${{ active_plan.price * unit }}</div>
                                <div class="col-md-4 text-right">ROI: ${{ (((active_plan.price * unit) * active_plan.percent_profit) / 100)  }} ( {{active_plan.percent_profit }}% )</div>
                            </div>
                        </div>
                    </div>
                    <div class="col--12 mt-2">
                        <input disabled class="form-control" :value="active_plan.price * unit" />
                    </div>

                    <div class="col-12 mt-2">
                        <p class="">Amount to invested : ${{ formatPrice(active_plan.price * unit) }}</p>
                    </div>
                    <div class="col-12">
                    <p class="">ROI : ${{ formatPrice(((active_plan.price * unit) * active_plan.percent_profit) / 100)  }} ( {{active_plan.percent_profit }}% )</p>
                    </div>
                    <div class="col-12">
                    <p class="">Duration  :  {{ active_plan.period }} {{ active_plan.period_type }}</p>
                    </div>
                    <button :disabled="!canProceed" type="submit" class="btn btn-primary nav-pills mt-2" style="border-radius: 40px;">Activate Subscription</button>

                </div>

            </div>
            </form>

            <div class="row">


            </div>
        </div>
    </div>

</template>

<script>
export default {
name: "ActivatePlan",
    props:['plan','plans','user','url'],
    data() {
        return {
            active_plan: [],
            unit:1,
            loading:false
        }
    },
    mounted() {
    this.active_plan = this.plan;
    },
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace('.', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        submit(){
            this.loading = true;
            axios.post(this.url,{
                plan_id : this.active_plan.id,
                unit : this.unit,
                name : this.active_plan.name,
                amount : parseInt(this.active_plan.price) * parseInt(this.unit)
            }).
            then((res)=>{
                console.log(res.data);
                if(res.data.status === 1){
                    window.location = res.data.url
                }
                this.loading = false
            }).catch((error)=>{
                this.loading = false
            })
        }
    },
    computed: {
    canProceed(){
        return !this.loading && this.balanceGt;
    },
        balanceGt(){
        let amt = parseInt(this.active_plan.price) * parseInt(this.unit);
        return this.user.balance > amt;
        }
    }
}
</script>

<style scoped>

</style>
