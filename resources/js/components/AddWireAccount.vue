<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12  justify-content-center">
                    <div class=" text-center">
                        <h6>Add New Wire Account for withdrawal</h6>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="m-4 row">

                            <div class="form-group col-md-6">
                                <label>Account Name *</label>
                                <input v-model="data.account_name" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Account Number *</label>
                                <input v-model="data.account_number" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Country  *</label>
                                <input v-model="data.bank_country" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Name  *</label>
                                <input v-model="data.bank_name" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Currency  *</label>
                                <input v-model="data.bank_currency" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Branch  *</label>
                                <input v-model="data.bank_branch" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Address  *</label>
                                <input v-model="data.bank_address" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sort Code  *</label>
                                <input v-model="data.sort_code" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Routine Number  *</label>
                                <input v-model="data.routine_number" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Swift Code  *</label>
                                <input v-model="data.swift_code" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Iban Number  *</label>
                                <input v-model="data.iban_number" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Account Label (optional)</label>
                                <input v-model="data.account_label" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-12">
                                <button :disabled="loading" type="submit" class="btn btn-primary">Add Wire Account</button>
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
    props : ['url'],
    data(){
    return {
        loading : false,
        data : {
            account_name : '',
            account_number : '',
            bank_country : '',
            bank_currency : '',
            bank_name : '',
            bank_branch : '',
            bank_address : '',
            sort_code : '',
            routine_number : '',
            bank_software : '',
            swift_code : '',
            iban_number : '',
            account_label : '',
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
    }
}
</script>

<style scoped>

</style>
