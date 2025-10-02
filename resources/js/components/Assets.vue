<template>
    <div class="modal-content">
        <div class="modal-header">
<h5>Market List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>


        <div class="modal-body">
           <div class="d-sm-none d-md-block hide-sm_mol">

               <div class="row">
                   <div class="col-3 list_mark_ri">

                       <ul class="list-unstyled">

                           <li @click="show_modeltype('showwatchlist')"
                               :class="i=='watchlist' ? 'active' : ''">
                               <h5 class="text-white">
                                   <img :src=" path +'/Path11669.svg'" style="margin-right: 25px">
                                   Favorite</h5>
                           </li>

                           <li @click="show_modeltype('showcrypto')"
                               :class="i=='crypto' ? 'active' : ''">
                               <img :src=" path +'/Group5771.svg'">
                               crypto
                           </li>
                           <li @click="show_modeltype('showstocks')"
                               :class="i=='stocks' ? 'active' : ''">
                               <img :src=" path +'/insert-money-euro.svg'">
                               stocks
                           </li>
                           <li @click="show_modeltype('showforex')"

                               :class="i=='forex' ? 'active' : ''">
                               <img :src=" path +'/Path11669.svg'">
                               forex
                           </li>
                           <li @click="show_modeltype('showindices')"
                               :class="i=='indices' ? 'active' : ''">
                               <img :src=" path +'/Group5774.svg'">
                               indices
                           </li>
                           <li @click="show_modeltype('showcommodities')"
                               :class="i=='commodities' ? 'active' : ''">
                               <img :src=" path +'/Group5775.svg'">
                               commodities
                           </li>

                       </ul>
                   </div>
                   <div class="col-9">
                       <!--                    <h5 v-if="assets.length > 0" class="modal-title" id="exampleModalLongTitle1">Select {{ i }} to trade</h5>-->
                       <input style="height: 56px !important;color: #fff !important;background: #232B3E;border-radius: 10px;margin-bottom: 20px" class="form-control search_model" v-model="text" :placeholder="'search '+i"/>



                       <table class="table table-striped">
                           <thead>
                           <tr>
                               <th class="text-left">Assest</th>
                               <th>Sell</th>
                               <th>Buy</th>
                               <th>
                                   Spread
                               </th>
                               <th>
                                   Action
                               </th>
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="item in assets">
                               <td class="text-left">
<!--                                   <img style="max-height: 20px; max-width: 20px" :src="item.image" alt="account balance" />-->
                                   <a :href="tradestation+'?cur='+item.id" class="d-flex float-left">
                                   <img style="width: 29px;height: 29px;border-radius: 50%;margin-right: 15px" :src="item.image">

                                 {{ item.name }}
                                   </a>
                               </td>
                               <td>
                                   {{parseFloat(item.rate-((item.sell_spread*item.rate)/100)).toFixed(2) }}</td>
                               <td>  {{parseFloat(item.rate+((item.buy_spread*item.rate)/100)).toFixed(2) }}</td>
                               <td> {{item.buy_spread - item.sell_spread}}</td>
                               <td>
                                   <i class="fa fa-star " @click="addtowatch(item.id)" :style=' my_asset.includes(item.id)?"color: #ff7700":""' ></i>

                               </td>

                           </tr>

                           </tbody>


                       </table>






                       <div class="text-center mx-auto" v-if="assets.length < 1">
                           <p class="text-center">No {{ i  }} available for trading</p>
                       </div>
                   </div>
               </div>

           </div>
            <div class="d-md-none d-block">
                <div class="row">
                    <div class="col-4 list_mark">

                        <ul class="list-unstyled">
                            <li @click="show_modeltype('showwatchlist')"
                                :class="i=='watchlist' ? 'active' : ''">


                                    Whislist
                            </li>
                            <li @click="show_modeltype('showcrypto')"
                                :class="i=='crypto' ? 'active' : ''">
                                crypto
                            </li>
                            <li @click="show_modeltype('showstocks')"
                                :class="i=='stocks' ? 'active' : ''">
                                stocks
                            </li>
                            <li @click="show_modeltype('showforex')"

                                :class="i=='forex' ? 'active' : ''">
                                forex
                            </li>
                            <li @click="show_modeltype('showindices')"
                                :class="i=='indices' ? 'active' : ''">
                                indices
                            </li>
                            <li @click="show_modeltype('showcommodities')"
                                :class="i=='commodities' ? 'active' : ''">
                                commodities
                            </li>

                        </ul>
                    </div>
                    <div class="  col-8">
                        <!--                    <h5 v-if="assets.length > 0" class="modal-title" id="exampleModalLongTitle1">Select {{ i }} to trade</h5>-->
                        <input style="margin-bottom: 15px;border-radius: 10px;height: 56px !important;color: #fff !important;background: #232B3E" class="form-control search_model" v-model="text" :placeholder="'search '+i"/>
                        <div class="row">


                            <div class=" col-md-6" v-for="item in assets">
                                <div class="card bg-accent-1 " style="border-bottom: 1px solid ;border-radius: 0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <a :href="tradestation+'?cur='+item.id" class="d-flex float-left">
                                                <img :src="item.image" style="margin-right: 25px">
                                                <!--                                            <img style="max-height: 20px; max-width: 20px" :src="item.image" alt="account balance" />-->
                                                {{ item.name }}
                                            </a>
                                            <i class="fa fa-star " @click="addtowatch(item.id)" :style=' my_asset.includes(item.id)?"color: #ff7700":""' ></i>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="text-center mx-auto" v-if="assets.length < 1">
                            <p class="text-center">No {{ i  }} available for trading</p>
                        </div>
                    </div>
                </div>
            </div>




            </div>

<!--        <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
<!--            </div>-->

    </div>
</template>

<script>
export default {
    props : ['asset','i','tradestation','path','url','my_asset'],
name: "Assets",
    data (){
        return {
            text : ''

        }
    },
    computed : {
        assets(){
            if(this.text.length < 2){
                return this.asset
            }else{
                return this.asset.filter(item => {
                    return item.name.toLowerCase().includes(this.text.toLowerCase())
                })
            }
        },


    },
    methods: {
        show_modeltype(modle){

            if($('#' + modle).hasClass("show")) {

            }
            else{

                $('#' + modle).modal('show')
                $('.modal:not(#'+modle+')').modal('hide')
            }

        },

        addtowatch(id){
            axios.post(this.url,{
                currency_pair_id : id,

            }).
            then((res)=>{
                console.log(res.data.data);

                   this.my_asset=res.data.data


            }).catch((error)=>{

            })
        }
    }


}
</script>

<style scoped>

</style>
