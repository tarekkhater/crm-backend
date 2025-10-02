<template>
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
                                        <ul class="nav nav-pills flex-column flex-sm-row" id="myTab" role="tablist">
                                            <li class="nav-item ml-2">
                                                <button @click="setTab('profile')" :class="tab === 'profile' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Profile</button>
                                            </li>

                                            <li class="nav-item ml-2">
                                                <button @click="setTab('trades')" :class="tab === 'trades' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Trades</button>
                                            </li>
                                            <li class="nav-item ml-2 mb-2 mb-sm-0">
                                                <button @click="setTab('messages')" :class="tab === 'messages' ? 'btn-primary' : 'btn-outline-info'" class="btn py-2 px-3 px-lg-4"> Posts</button>
                                            </li>
                                        </ul>
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
        <div v-if="tab === 'profile'" class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile details</h5>
                </div>
                <div class="card-body">
        <div class="col-6  profile">
            <div class="sub-header mt-3 py-3 px-3  d-sm-flex w-100 ">
                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Full Name </span></div>
                <div class="w-sm-100  text-capitalize"><span class="h6 my-auto">{{ user.name }}</span></div>
            </div>
            <div class="sub-header py-3 px-3 d-sm-flex w-100 ">
                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Username </span></div>
                <div class="w-sm-100 text-capitalize "><span class="h6">{{ user.username }}</span></div>
            </div>
            <div class="sub-header py-3 px-3 d-sm-flex w-100 ">
                <div class="w-sm-100 mr-auto"><span class="h6 my-auto">Email Address</span></div>
                <div class="w-sm-100"><span class="h6">XXXXXXXXXX</span></div>
            </div>
        </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-sm-12" v-if="tab === 'trades'">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-capitalize">{{ user.name }} trades </h5>
                    <p>All trades.</p>
                </div>
                <div class="card-body">
                    <div v-if="trades.length < 1" class="text-center p-4">
                        <p class="h5">No trades  yet</p>
<!--                        <button data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary mt-4">Make a deposit</button>-->
                    </div>
                    <div v-else>
                        <trade_history :closed_trades="trades"></trade_history>
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
                </div>
            </div>
        </div>
    </div>
</section>
</template>

<script>
export default {
name: "Profile",
    props : ['user','transactions','accounts','trades','messages'],
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
