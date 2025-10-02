<template xmlns="http://www.w3.org/1999/html">
<section>
    <div class="row">
        <div class="col-12 mt-3">
            <div class="position-relative">
                <div class="background-image-maker py-5"></div>
                <div class="holder-image">
                    <img src="/dist/images/profile-cover.jpg" alt="" class="img-fluid d-none">
                </div>
                <div class="position-relative">
                    <div class="media d-md-flex d-block">
                        <a href="#"><img :src="user.avatar" width="250" alt="" class="img-fluid"></a>
                        <div class="media-body align-self-end pt-5 z-index-1">
                            <div class="pl-4">
                                <h4 class="display-4 text-capitalize text-white mb-0">{{  user.name }}</h4>

                            </div>
                            <div class="profile-menu mt-4 theme-background border border-left-0 border-right-0 z-index-1 p-2">
                                <div class="d-sm-flex">
                                    <div class="align-self-center">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2 mb4">

        <div class="col-md-3">
            <ul class="nav nav-pills  flex-sm-row my_tap_list" id="myTab" role="tablist">
                <li class="nav-item ml-2">
                    <button @click="setTab('profile')" :class="tab === 'profile' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4">

                        <img :src="path+'/Group4232.svg'">
                        Profile</button>
                </li>
<!--                <li class="nav-item ml-2">-->
<!--                    <button @click="setTab('Social Networks')" :class="tab === 'Social Networks' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4">-->

<!--                        <img :src="path+'/Group5604.svg'">-->
<!--                        Social Networks</button>-->
<!--                </li>-->
<!--                <li class="nav-item ml-2">-->
<!--                    <button @click="setTab('BuyOnline')" :class="tab === 'BuyOnline' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4">-->

<!--                        <img :src="path+'/BuyOnline.svg'">-->
<!--                        BuyOnline</button>-->
<!--                </li>-->
                <li class="nav-item ml-2">
                    <button @click="setTab('accounts')" :class="tab === 'accounts' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Accounts</button>
                </li>
                <li class="nav-item ml-2">
                    <button @click="setTab('transactions')" :class="tab === 'transactions' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Transaction</button>
                </li>
<!--                <li class="nav-item ml-2">-->
<!--                    <button @click="setTab('trades')" :class="tab === 'trades' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Trades</button>-->
<!--                </li>-->
                <li class="nav-item ml-2 mb-2 mb-sm-0">
                    <button @click="setTab('messages')" :class="tab === 'messages' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Messages</button>
                </li>
            </ul>


        </div>

        <div class="col-md-9">
            <div v-if="tab === 'profile'" class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-header" style="font-size: 16px;border-bottom: none">
                         <h5 class="card-title" > <img :src="path+'/Group4232.svg'"> My Profile</h5>
                    </div>
                    <div class="card-body" style="background: #262e41;border-radius: 10px">
                        <div class="col-12  profile">
                            <div class="d-flex justify-content-between" style="color: #fff">
                                <span>
                                    Profile Details
                                </span>
                                <a :href="edite_profile">Edit Profile</a>

                            </div>
                            <div class="d-flex">
                                <img :src="user.avatar"  style="width: 70px;height:70px;border-radius: 50%;margin-right: 15px;margin-top: 15px" alt="" class="img-fluid radio">
                                <div>
                                    <span class="color-white" style="margin-top: 15px;color: #fff;display: block">{{ user.name }}</span>
                                    {{ user.country }}
                                    <br>
                                    {{  user.email }}

                                </div>

                            </div>
<!--                            <div class="sub-header mt-3 py-3 px-3  d-sm-flex w-100 ">-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Full Name </span></div>-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">{{ user.name }}</span></div>-->
<!--                                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">-->
<!--                                    <li class="breadcrumb-item"> <i class="fa fa-edit"></i> </li>-->
<!--                                </ol>-->
<!--                            </div>-->
<!--                            <div class="sub-header py-3 px-3 d-sm-flex w-100 ">-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Username </span></div>-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6">{{ user.username }}</span></div>-->
<!--                                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">-->
<!--                                    <li class="breadcrumb-item"> <i class="fa fa-edit"></i> </li>-->
<!--                                </ol>-->
<!--                            </div>-->
<!--                            <div class="sub-header py-3 px-3 d-sm-flex w-100 ">-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Email Address</span></div>-->
<!--                                <div class="w-sm-100 mr-auto"><span class="h6">{{  user.email }}</span></div>-->
<!--                                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">-->
<!--                                    <li class="breadcrumb-item"> <i class="fa fa-edit"></i> </li>-->
<!--                                </ol>-->
<!--                            </div>-->
                        </div>
                    </div>

                    <div class="d-flex  justify-content-between" style="background: #262e41;border-radius: 5px;padding: 12px; margin-top: 20px">
                        <span>Change Password</span>

                        <a data-toggle="modal" data-target="#update_bassword_model"
                           style="color: #fff;cursor: pointer">
                            Change Password</a>

                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-sm-12" v-if="tab === 'accounts'">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Your accounts</h5>
                        <p>Below accounts that you’d like to withdraw funds.</p>

                    </div>
                    <div class="card-body">
                        <a href="#"  aria-expanded="false" data-toggle="modal" data-target=".bd-example-modal-lg">
                            <h5 class="ml-3 mb-0" style="color: #27B254; font-weight: bold;">Account balance : ${{ user.balance }}</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-sm-12" v-if="tab === 'transactions'">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Your transactions</h5>
                        <p>All your account transactions.</p>
                    </div>
                    <div class="card-body">
                        <div v-if="transactions.length < 1" class="text-center p-4">
                            <p class="h5">No transactions  yet</p>
                            <button data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary mt-4">Make a deposit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-sm-12" v-if="tab === 'trades'">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Your trades </h5>
                        <p>All your account trades.</p>
                    </div>
                    <div class="card-body">
                        <div v-if="trades.length < 1" class="text-center p-4">
                            <p class="h5">No trades  yet</p>
                            <!--                        <button data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary mt-4">Make a deposit</button>-->
                        </div>
                        <div v-else>
                            <order_book_all :interv="interv"  :plan="user.plan" :trades='trades' :open_trades='open_trades' :check_trade_url="check_trade_url" :close_trade_url="close_trade_url" :trade_url="trade_url" :all_trade_url="all_trade_url" :balance="user.balance"></order_book_all>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-sm-12" v-if="tab === 'messages'">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Messages</h5>
                        <p>Account messages.</p>
                    </div>
                    <div class="card-body">
                        <div v-if="messages.length < 1" class="text-center p-4">
                            <p class="h5">No messages  yet</p>
                            <!--                        <button data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary mt-4">Make a deposit</button>-->
                        </div>
                        <duv class="list-message card">
                            <div class="card-body">
                                <div v-for="message in messages" class="single-message">
                                    <h4>{{message.subject}}</h4>
                                    <b>{{message.message}}</b>
                                    <br>
<span>{{message.date}}</span>



                                </div>


                            </div>


                        </duv>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
</template>

<script>
export default {
name: "Profile",
    props : ["path",'user','transactions','accounts','trades','messages','interv','close_trade_url','open_trades','check_trade_url','trade_url',
        'all_trade_url',"edite_profile"],
    data(){
    return {
        tab : 'profile'
    }
    },
    methods: {
    setTab(tab){
        this.tab = tab;
    }
    }
}
</script>

<style scoped>

</style>
