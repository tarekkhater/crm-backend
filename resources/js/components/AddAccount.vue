<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12  justify-content-center">
                    <div class=" text-center">
                        <h6>Add accounts for withdrawal</h6>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="m-4">
                            <div class="form-group col-md-12">
                                <label for="type">Account Type</label>
                                <select v-model="data.type" id="type" class="form-control">
                                    <option v-if="wire > 0" value="wire">WIRE TRANSFER</option>
                                    <option v-if="crypto > 0" value="crypto">CRYPTO WALLET</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12" v-if="data.type === 'crypto'">
                                <label for="wallet">Wallet Name</label>
                                <select v-model="data.wallet" id="wallet" class="form-control">
                                    <option value="bitcoin">BTC</option>
                                    <option value="usdt">USDT</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12" v-if="data.type === 'wire'">
                                <div v-if="wires.length > 0">
                                    <label for="wire">Select Wire Transfer Account </label>
                                    <select v-model="data.wire_id" id="wire" class="form-control">
                                        <option v-for="item in wires" :value="item.id">{{ item.account_name }} [{{ item.account_number}}]</option>
                                    </select>
                                </div>
                                <div v-else>
                                    <button type="button"  data-toggle="modal" data-target="#addWireAccount" class="btn btn-primary p-2 ">
                                        <span class="text-white font-weight-bold h6">+ Add Wire Account</span></button>
                                </div>

                            </div>
                            <div class="form-group col-md-12" v-if="data.type === 'crypto'">
                                <label for="address">Wallet Address *</label>
                                <input v-model="data.address" required id="address" type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-12" v-if="showButton">
                                <button :disabled="loading" type="submit" class="btn btn-primary">Add Account</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
name: "AddAccount",
    props : ['url','wires','wire','crypto'],
    data(){
    return {
        loading : false,
        data : {
            type : 'crypto',
            wallet : 'bitcoin',

            wire_id : null,
            address : '',
        }
    }
    },
    methods : {
        submit(){
            this.loading = true;
            axios.post(this.url,this.data).then((res)=>{
                console.log(res.data);
                if(res.data.status === 1){
                    window.location.reload()
                }
                this.loading = false
            }).catch((error)=>{
                this.loading = false
            })
        }
    },
    computed : {
        showButton(){
            if(this.data.type === 'wire' && !this.data.wire_id){
                return false
            }else {
                return true
            }
        }
    }
}
</script>

<style scoped>

</style>
