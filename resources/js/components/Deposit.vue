<template>
    <div class="car">
        <div class="card-body deposit" style="padding: 0px">
            <!-- <div class="row"> -->
            <div class="tab m-0">
                <button
                    v-if="card_payment"
                    @click="setCustom('card')"
                    class="tablinks"
                    :class="active === 'card' ? 'active' : ''"
                >
                    <div style="width: 15%; float: left">
                        <img style="margin-top: 10px" height="25px" src="/img/card.png" />
                    </div>
                    <div style="width: 80%; float: right">

                        Credit/Debit<br />
                        <span style="font-size: 0.8em">Instant</span>
                    </div>
                </button>
                <button
                    v-if="custom.link"
                    @click="setCustom('custom')"
                    class="tablinks"
                    :class="active === 'custom' ? 'active' : ''"
                >

                    <div style="width: 15%; float: left">
                        <img style="margin-top: 10px" height="25px" src="/img/card.png" />
                    </div>
                    <div style="width: 80%; float: right">

                        {{ custom.name }}
                    </div>


                </button>
                <button
                    v-for="item in wallets"
                    :key="item.id"
                    class="tablinks"
                    @click="setWallet(item)"
                    :class="active === item.name ? 'active' : ''"
                >
                    <div style="width: 15%; float: left">
                        <img style="margin-top: 10px" height="25px" :src="item.logo" />
                    </div>
                    <div style="width: 80%; float: right">
                        {{ item.name }} <br />
                        <span style="font-size: 0.8em">Instant</span>
                    </div>
                </button>
            </div>

            <div class="tabcontent">
                <DepositItem
                    :active="active"
                    :current_step="step"
                    :custom="custom"
                    :upload_url="upload_url"
                    :url="url"
                    :wallet="wallet"
                    :card_payment_minimum="card_payment_minimum"
                    :card_payment_maximum="card_payment_maximum"
                    :crypto_payment_minimum="crypto_payment_minimum"
                    :crypto_payment_maximum="crypto_payment_maximum"
                ></DepositItem>
            </div>

            <!-- </div> -->
            <!-- <div class="row">
              <div class="col-3" style="background-color: transparent">
                <div class="wizard-tab mb-4">
                  <ul
                    class="nav nav-tabs d-block d-sm-flex"
                    id="v-pills-tab"
                    aria-orientation="vertical"
                    style="border-bottom: 1px solid #000"
                  >
                    <li class="nav-item mr-auto mb-4" v-if="card_payment">
                      <button
                        @click="setCustom('card')"
                        class="nav-link btn btn-outline-light"
                        :class="active === 'card' ? 'active' : ''"
                      >
                        Card Payment
                      </button>
                    </li>
                    <li class="nav-item mr-auto mb-4">
                      <button
                        v-if="custom.link"
                        @click="setCustom('custom')"
                        class="nav-link btn btn-outline-light"
                        :class="active === 'custom' ? 'active' : ''"
                      >
                        {{ custom.name }}
                      </button>
                    </li>
                    <li
                      v-for="item in wallets"
                      class="nav-item mr-auto mb-4"
                      :key="item.id"
                    >
                      <button
                        @click="setWallet(item)"
                        class="nav-link btn btn-outline-light"
                        :class="active === item.name ? 'active' : ''"
                      >
                        {{ item.name }}
                      </button>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-9">
                <div class="tab-content">
                  <div class="tab-pane fade active show" id="tab1">
                    <DepositItem
                      :active="active"
                      :custom="custom"
                      :upload_url="upload_url"
                      :url="url"
                      :wallet="wallet"
                    ></DepositItem>
                  </div>
                </div>
              </div>
            </div> -->
        </div>
    </div>
</template>

<script>
import DepositItem from "./DepositItem";
export default {
    name: "Deposit",
    data() {
        return {
            step: 1,
            wallet: this.wallets[0],
            active: this.custom.link ? "custom" : this.wallets[0].name,
        };
    },
    props: [
        "wallets",
        "url",
        "upload_url",
        "custom",
        "card_payment",
        "card_payment_minimum",
        "card_payment_maximum",
        "crypto_payment",
        "crypto_payment_minimum",
        "crypto_payment_maximum",
    ],
    components: { DepositItem },
    mounted() {
        console.log("Component mounted.");
        console.log(this.wallets);
        console.log(this.card_payment);
    },
    methods: {
        setWallet(wallet) {
            this.wallet = wallet;
            this.active = wallet.name;
        },
        setCustom(item) {
            this.active = item;
        },
    },
};
</script>

<style scoped>
* {
    box-sizing: border-box;
}

.tab .tablinks {

}
.deposit .tab {

}
.active {
    border-color: transparent !important;
    background-color: #FF7700 !important;
    border-radius: 5px !important;

    color: white !important;

}

/* Style the tab */
.tab {
    float: left;
    /* border: 1px solid #ccc; */
    background-color: transparent;
    width: 30%;
    height: 300px;
}

.btn-outline-light {
    border-color: transparent;
}

/* Style the buttons inside the tab */
.tab button {
    display: block;
    background-color: inherit;
    color: lightgray;
    padding: 10px ;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    border-color: transparent !important;
    background-color: #ff7700 !important;

    color: white !important;
    border-radius: 5px;
}

/* Style the tab content */
.tabcontent {
    float: left;
    padding: 10px;
    /* border: 1px solid #ccc; */
    width: 69%;
    border-left: none;
    height: 100%;
}
</style>
