<template>
    <div class="row">
        <template v-if="active === 'custom'">
            <div class="col-md-5 col-sm-12 text-center deposit-item-first">
                <div class="text-center mr-auto mb-3">
                    <img style="max-height: 350px; width: 100%" :src="custom.image" />
                </div>
                <div class="mb-3"></div>
            </div>
            <div class="col-md-7 col-sm-12">
                <div>
                    <!--                    <h6 class="mb-0 text-uppercase font-weight-bold">Enter Amount</h6>-->
                    <div v-html="custom.text"></div>
                    <div class="row mt-3 mb-3">
                        <div class="col-md-8 col-12 mt-2">
                            <a target="_blank" :href="custom.link" class="btn btn-primary"
                            >{{ custom.btn }} --></a
                            >
                        </div>
                    </div>
                </div>
                <div v-if="step === 2">
                    <span @click="pre()" class="text-danger">Previous Page</span>

                    <h6 class="mb-0 text-uppercase font-weight-bold">Confirm Transfer</h6>
                    <p>
                        Please confirm that you have transferred ${{ data.amount }} worth of
                        {{ wallet.symbol }} to the specified {{ wallet.name }} wallet
                        address, by uploading receipt
                    </p>
                    <p>Upload receipt</p>
                    <dropzone
                        @vdropzone-sending="sending"
                        @vdropzone-processing="processing"
                        @vdropzone-success="vsuccess"
                        ref="myVueDropzone"
                        id="dropzone"
                        :options="dropzoneOptions"
                    ></dropzone>

                    <div class="row mt-2">
                        <div class="col-8">
                            <button @click="submit()" class="btn btn-primary">
                                Submit Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else-if="active === 'card'">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-11">
                        <form role="form" onsubmit="return false;">
                            <p
                                class="text-center text-danger"
                                v-if="cardDepositError != null"
                            >
                                {{ cardDepositError }}
                            </p>

                            <div v-if="step==1" class="form-group">
                                <div class="row ">
                                    <div class="col-md-12  form-group">
                                        <label class="col-form-label">
                                            Number on card
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Number on card"
                                            v-on:keyup="formatCardNumber"
                                            maxlength="19"
                                            pattern="[0-9 ]{19}"
                                            v-model="data.card_number"
                                            required
                                            onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"
                                        />

                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="col-form-label">
                                            VALID THRU
                                        </label>
                                  <input type="text"
                                       class="bank-card__field"
                                       placeholder="MM/YY"
                                      v-on:keyup="formatExpiryDate"
                                       v-model="data.card_expiry_date"
                                      maxlength="5"
                                      pattern="(0[1-9]{1}|1[0-2]{1})[/](2[2-9]{1}|[3-9]{1}[0-9]{1})"
                                    required
                                     oninvalid="this.setCustomValidity('The month cannot be greater than 12')"
                                        oninput="this.setCustomValidity('')"
                                       style="text-align: center"
                                       onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"
                                            />

                                                                                                                        </div>
                                                                                                                        <div class="col-md-4 form-group">
                                                                                                                            <label class="col-form-label">
                                                                                                                                CVC
                                                                                                                            </label>
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                placeholder="CVC"
                                                                                                                                v-model="data.card_cvv"
                                                                                                                                maxlength="3"
                                                                                                                                pattern="[0-9]{3}"
                                                                                                                                required
                                                                                                                                style="text-align: center; margin-left: 10px"
                                                                                                                                onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"
                                                                                                                            />

                                                                                                                            <span
                                                                                                                                style="
                                                                                                                    font-size: 10px;
                                                                                                                    color: white;
                                                                                                                    text-align: left;
                                                                                                                  "
                                                                                                                            >The last three digits on the reverse</span
                                                                                                                            >

                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                            <label
                                                                                                                                class="col-form-label">
                                                                                                                              Holder of card
                                                                                                                            </label>
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                placeholder="Holder of card"
                                                                                                                                v-model="data.card_holder"
                                                                                                                                pattern="[A-Za-z, ]{2,}"
                                                                                                                                required
                                                                                                                                onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                                                                                                                            />
                                                                                                                        </div>

                                                                                                                    </div>
                                                                                                                    <div class="row ">
                                                                                                                        <div class="col-md-6 form-group">
                                                                                                                          <label class="col-form-label">Amount</label>

                                                                                                                                <input
                                                                                                                                    v-model="data.amount"

                                                                                                                                    type="number"
                                                                                                                                    class="form-control"
                                                                                                                                    required
                                                                                                                                    :min="card_payment_minimum"
                                                                                                                                    :max="card_payment_maximum"
                                                                                                                                    aria-label="Amount"
                                                                                                                                    placeholder="Amount"
                                                                                                                                />

                                                                                                                        </div>
                                                                                                                        <div class="col-md-6 form-group">
                                                                                                                            <label class="col-form-label">currency</label>
                                                                                                                            <select v-model="data.currency" class="form-control" style="height: auto !important;">
                                                                                                                                <option value="€">€ EUR</option>
                                                                                                                                <option value="£">£ GBP</option>
                                                                                                                                <option value="$">$ USD</option>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 px-0">
                                                                                                                            <button
                                                                                                                                @click="
                                                                                                              next();
                                                                                                              validateCardDepositStep2();
                                                                                                            "
                                                                                                                                class="btn btn-primary btn-lg"
                                                                                                                            >
                                                                                                                                Continue &#8594;
                                                                                                                            </button>
                                                                                                                        </div>
                                                                                                                        <!-- <div class="col-2" v-for="i in amounts" :key="i">
                                                                                                                                  <button
                                                                                                                                      type="button"
                                                                                                                                      class="btn btn-secondary btn-sm mb-1"
                                                                                                                                      @click="setAmount(i, active)"
                                                                                                                                  >
                                                                                                                                      $ {{ i }}
                                                                                                                                  </button>
                                                                                                                              </div> -->
                                </div>


                            </div>



<!--                            <div class="form-group" v-if="step === 1">-->
<!--                                <div class="demo">-->
<!--                                    <div class="payment-card">-->
<!--                                        <div class="bank-card">-->
<!--                                            <div class="bank-card__side bank-card__side_front">-->
<!--                                                <div class="bank-card__inner">-->
<!--                                                    <label-->
<!--                                                        class="bank-card__label bank-card__label_number"-->
<!--                                                    >-->
<!--                                                        <span class="bank-card__hint">Number on card</span>-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="bank-card__field"-->
<!--                                                            placeholder="Number on card"-->
<!--                                                            v-on:keyup="formatCardNumber"-->
<!--                                                            maxlength="19"-->
<!--                                                            pattern="[0-9 ]{19}"-->
<!--                                                            v-model="data.card_number"-->
<!--                                                            required-->
<!--                                                            onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"-->
<!--                                                        />-->
<!--                                                    </label>-->
<!--                                                </div>-->
<!--                                                <div-->
<!--                                                    class="bank-card__inner bank-card__footer"-->
<!--                                                    style="float: right"-->
<!--                                                >-->
<!--                                                    <label-->
<!--                                                        class="bank-card__label bank-card__month"-->
<!--                                                        style="float: right"-->
<!--                                                    >-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="bank-card__field"-->
<!--                                                            placeholder="MM/YY"-->
<!--                                                            v-on:keyup="formatExpiryDate"-->
<!--                                                            v-model="data.card_expiry_date"-->
<!--                                                            maxlength="5"-->
<!--                                                            pattern="(0[1-9]{1}|1[0-2]{1})[/](2[2-9]{1}|[3-9]{1}[0-9]{1})"-->
<!--                                                            required-->
<!--                                                            oninvalid="this.setCustomValidity('The month cannot be greater than 12')"-->
<!--                                                            oninput="this.setCustomValidity('')"-->
<!--                                                            style="text-align: center"-->
<!--                                                            onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"-->
<!--                                                        />-->
<!--                                                    </label>-->
<!--                                                    <label style="float: right">-->
<!--                            <span class="bank-card__caption"-->
<!--                            >VALID<br />THRU</span-->
<!--                            >-->
<!--                                                        &nbsp; &nbsp;-->
<!--                                                    </label>-->
<!--                                                </div>-->
<!--                                                <div class="bank-card__inner">-->
<!--                                                    <label-->
<!--                                                        class="bank-card__label bank-card__label_holder"-->
<!--                                                    >-->
<!--                                                        <span class="bank-card__hint">Holder of card</span>-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="bank-card__field"-->
<!--                                                            placeholder="Holder of card"-->
<!--                                                            v-model="data.card_holder"-->
<!--                                                            pattern="[A-Za-z, ]{2,}"-->
<!--                                                            required-->
<!--                                                            onkeydown="return /[a-zA-Z ]/i.test(event.key)"-->
<!--                                                        />-->
<!--                                                    </label>-->

<!--                                                    <label class="bank-card__label bank-card__cvc">-->
<!--                                                        <span class="bank-card__hint">CVC</span>-->

<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="bank-card__field"-->
<!--                                                            placeholder="CVC"-->
<!--                                                            v-model="data.card_cvv"-->
<!--                                                            maxlength="3"-->
<!--                                                            pattern="[0-9]{3}"-->
<!--                                                            required-->
<!--                                                            style="text-align: center; margin-left: 10px"-->
<!--                                                            onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"-->
<!--                                                        />-->
<!--                                                        <br />-->
<!--                                                        <span-->
<!--                                                            style="-->
<!--                                font-size: 10px;-->
<!--                                color: white;-->
<!--                                text-align: left;-->
<!--                              "-->
<!--                                                        >The last three digits on the reverse</span-->
<!--                                                        >-->
<!--                                                    </label>-->


<!--                                                    <div class="row mt-4">-->
<!--                                                        <div class="col-md-4">-->
<!--                                                            <div class="input-group mb-3">-->
<!--                                                                <div class="input-group-prepend">-->
<!--                          <span class="input-group-text">{{-->
<!--                                  data.currency-->
<!--                              }}</span>-->
<!--                                                                </div>-->
<!--                                                                <input-->
<!--                                                                    v-model="data.amount"-->
<!--                                                                    style="-->
<!--                            padding-left: 0px;-->
<!--                            border-left: 0px;-->
<!--                            margin-left: -20px;-->
<!--                          "-->
<!--                                                                    type="number"-->
<!--                                                                    class="form-control text-white"-->
<!--                                                                    required-->
<!--                                                                    :min="card_payment_minimum"-->
<!--                                                                    :max="card_payment_maximum"-->
<!--                                                                    aria-label="Amount"-->
<!--                                                                    placeholder="Amount"-->
<!--                                                                />-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <div class="col-md-4">-->
<!--                                                            <select v-model="data.currency" class="form-control">-->
<!--                                                                <option value="€">€ EUR</option>-->
<!--                                                                <option value="£">£ GBP</option>-->
<!--                                                                <option value="$">$ USD</option>-->
<!--                                                            </select>-->
<!--                                                        </div>-->
<!--                                                        <div class="col-md-4 px-0">-->
<!--                                                            <button-->
<!--                                                                @click="-->
<!--                          next();-->
<!--                          validateCardDepositStep2();-->
<!--                        "-->
<!--                                                                class="btn btn-primary btn-lg"-->
<!--                                                            >-->
<!--                                                                Continue &#8594;-->
<!--                                                            </button>-->
<!--                                                        </div>-->
<!--                                                        &lt;!&ndash; <div class="col-2" v-for="i in amounts" :key="i">-->
<!--                                                                  <button-->
<!--                                                                      type="button"-->
<!--                                                                      class="btn btn-secondary btn-sm mb-1"-->
<!--                                                                      @click="setAmount(i, active)"-->
<!--                                                                  >-->
<!--                                                                      $ {{ i }}-->
<!--                                                                  </button>-->
<!--                                                              </div> &ndash;&gt;-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--&lt;!&ndash;                                            <div class="bank-card__side bank-card__side_back">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="bank-card__inner">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <label class="bank-card__label bank-card__cvc">&ndash;&gt;-->
<!--&lt;!&ndash;                                                        <span class="bank-card__hint">CVC</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                        &ndash;&gt;-->
<!--&lt;!&ndash;                                                        <input&ndash;&gt;-->
<!--&lt;!&ndash;                                                            type="text"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            class="bank-card__field"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            placeholder="CVC"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            v-model="data.card_cvv"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            maxlength="3"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            pattern="[0-9]{3}"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            required&ndash;&gt;-->
<!--&lt;!&ndash;                                                            style="text-align: center; margin-left: 10px"&ndash;&gt;-->
<!--&lt;!&ndash;                                                            onkeydown="return (event.which >= 48 && event.which <= 57) || event.which == 8 || event.which == 46 || event.which == 9"&ndash;&gt;-->
<!--&lt;!&ndash;                                                        />&ndash;&gt;-->
<!--&lt;!&ndash;                                                        <br />&ndash;&gt;-->
<!--&lt;!&ndash;                                                        <span&ndash;&gt;-->
<!--&lt;!&ndash;                                                            style="&ndash;&gt;-->
<!--&lt;!&ndash;                                font-size: 10px;&ndash;&gt;-->
<!--&lt;!&ndash;                                color: white;&ndash;&gt;-->
<!--&lt;!&ndash;                                text-align: left;&ndash;&gt;-->
<!--&lt;!&ndash;                              "&ndash;&gt;-->
<!--&lt;!&ndash;                                                        >The last three digits on the reverse</span&ndash;&gt;-->
<!--&lt;!&ndash;                                                        >&ndash;&gt;-->
<!--&lt;!&ndash;                                                    </label>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--&lt;!&ndash;                                <div class="content-box mt-8" style="margin-top: 50px">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <div class="row mt-4">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="col-md-4">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="input-group mb-3">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="input-group-prepend">&ndash;&gt;-->
<!--&lt;!&ndash;                          <span class="input-group-text">{{&ndash;&gt;-->
<!--&lt;!&ndash;                            data.currency&ndash;&gt;-->
<!--&lt;!&ndash;                          }}</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <input&ndash;&gt;-->
<!--&lt;!&ndash;                                                    v-model="data.amount"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    style="&ndash;&gt;-->
<!--&lt;!&ndash;                            padding-left: 0px;&ndash;&gt;-->
<!--&lt;!&ndash;                            border-left: 0px;&ndash;&gt;-->
<!--&lt;!&ndash;                            margin-left: -20px;&ndash;&gt;-->
<!--&lt;!&ndash;                          "&ndash;&gt;-->
<!--&lt;!&ndash;                                                    type="number"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    class="form-control text-white"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    required&ndash;&gt;-->
<!--&lt;!&ndash;                                                    :min="card_payment_minimum"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    :max="card_payment_maximum"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    aria-label="Amount"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    placeholder="Amount"&ndash;&gt;-->
<!--&lt;!&ndash;                                                />&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="col-md-4">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <select v-model="data.currency" class="form-control">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <option value="€">€ EUR</option>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <option value="£">£ GBP</option>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <option value="$">$ USD</option>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </select>&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="col-md-4 px-0">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <button&ndash;&gt;-->
<!--&lt;!&ndash;                                                @click="&ndash;&gt;-->
<!--&lt;!&ndash;                          next();&ndash;&gt;-->
<!--&lt;!&ndash;                          validateCardDepositStep2();&ndash;&gt;-->
<!--&lt;!&ndash;                        "&ndash;&gt;-->
<!--&lt;!&ndash;                                                class="btn btn-primary btn-lg"&ndash;&gt;-->
<!--&lt;!&ndash;                                            >&ndash;&gt;-->
<!--&lt;!&ndash;                                                Continue &#8594;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </button>&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; <div class="col-2" v-for="i in amounts" :key="i">&ndash;&gt;-->
<!--&lt;!&ndash;                                                  <button&ndash;&gt;-->
<!--&lt;!&ndash;                                                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                                                      class="btn btn-secondary btn-sm mb-1"&ndash;&gt;-->
<!--&lt;!&ndash;                                                      @click="setAmount(i, active)"&ndash;&gt;-->
<!--&lt;!&ndash;                                                  >&ndash;&gt;-->
<!--&lt;!&ndash;                                                      $ {{ i }}&ndash;&gt;-->
<!--&lt;!&ndash;                                                  </button>&ndash;&gt;-->
<!--&lt;!&ndash;                                              </div> &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </div>&ndash;&gt;-->

<!--&lt;!&ndash;                                    <div class="row mt-4 justify-center text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div&ndash;&gt;-->
<!--&lt;!&ndash;                                            class="col-md-4 text-left d-flex align-items-center"&ndash;&gt;-->
<!--&lt;!&ndash;                                            style="line-height: 80%"&ndash;&gt;-->
<!--&lt;!&ndash;                                        >&ndash;&gt;-->
<!--&lt;!&ndash;                                            <img src="/images/icons/ssl.png" alt="ssl" /> &nbsp;&ndash;&gt;-->
<!--&lt;!&ndash;                                            &nbsp;<span&ndash;&gt;-->
<!--&lt;!&ndash;                                            style="&ndash;&gt;-->
<!--&lt;!&ndash;                          font-size: 12px;&ndash;&gt;-->
<!--&lt;!&ndash;                          color: lightgrey;&ndash;&gt;-->
<!--&lt;!&ndash;                          font-weight: bold;&ndash;&gt;-->
<!--&lt;!&ndash;                        "&ndash;&gt;-->
<!--&lt;!&ndash;                                        >SSL-certified 256-bit Security</span&ndash;&gt;-->
<!--&lt;!&ndash;                                        >&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="col-md-4 text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <img src="/images/icons/securecode.png" alt="ssl" />&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="col-md-4 text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <img src="/images/icons/visaverified.png" alt="ssl" />&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                    </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->

<!--                                &lt;!&ndash;                <p for="username" class="text-center">&ndash;&gt;-->
<!--                                &lt;!&ndash;                  Please specify the amount you would like to deposit into your&ndash;&gt;-->
<!--                                &lt;!&ndash;                  account using your credit card&ndash;&gt;-->
<!--                                &lt;!&ndash;                </p>&ndash;&gt;-->
<!--                            </div>-->

                            <div v-if="step === 2" class="bill-box">
                <span @click="pre()" style="cursor: pointer"
                >&#8592; previous</span
                >
                                <br />
                                <br />
                                <div class="form-group">
                                    <input
                                        type="text"
                                        v-model="data.billing_address"
                                        placeholder="Address"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        v-model="data.zip_code"
                                        placeholder="Zip Code"
                                        class="form-control"
                                    />
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            placeholder="State"
                                            v-model="data.state"
                                            class="form-control"
                                        />
                                        <input
                                            type="text"
                                            placeholder="Country"
                                            v-model="data.country"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        I confirm that all the filled details are correct
                                        <input type="checkbox" v-model="confirmedDetails" />
                                    </label>
                                </div>

                                <button
                                    :disabled="!confirmedDetails || cardDepositLoading"
                                    type="button"
                                    class="btn btn-success btn-block btn-lg shadow-sm"
                                    @click="
                    validateCardDepositStep3();
                    submitCardDeposit();
                  "
                                >
                                    Done
                                    <i
                                        class="fa fa-spinner fa-spin"
                                        v-if="cardDepositLoading"
                                    ></i>
                                </button>
                            </div>

                            <div v-if="step === 3">
                <span @click="pre()" style="cursor: pointer"
                >&#8592; previous</span
                >
                                <br />
                                <br />
                                <div class="text-center">
                                    <label>
                                        Please confirm that all the filled details are correct
                                        <input type="checkbox" v-model="confirmedDetails" />
                                    </label>
                                </div>
                                <button
                                    :disabled="!confirmedDetails || cardDepositLoading"
                                    type="button"
                                    class="btn btn-success btn-block shadow-sm"
                                    @click="submitCardDeposit()"
                                >
                                    Done
                                    <i
                                        class="fa fa-spinner fa-spin"
                                        v-if="cardDepositLoading"
                                    ></i>
                                </button>
                            </div>
                            <div v-if="cardDepositSuccessful">
                                <div>
                                    <div class="text-center">
                                        <img
                                            style="margin-top: 10px"
                                            height="100px"
                                            src="/img/completed.gif"
                                        />
                                    </div>
                                    <br />
                                    <p>
                                        The Account Department will process your payments and credit
                                        your account in a short while.
                                    </p>
                                    <p class="text-center">
                                        NB: When topping up your balance, please note: Per our fraud
                                        control guidelines, some transactions (especially those
                                        involving third-party payments) may require additional
                                        verification. In some cases, we'll need phone verification
                                        for the card holder.
                                    </p>
                                    <div class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-success shadow-sm"
                                            @click="closeModal()"
                                        >
                                            Exit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="col-md-12">
                <div class="text-center mr-auto mb-3">
                    <img
                        style="max-height: 100px; width: 150px;"
                        :src="wallet.barcode"
                    />
                </div>
                <label for="walletAddress">
          <span class="mt-2 text-center"
          >Make a deposit to this {{ wallet.name }} Wallet Address</span
          >
                </label>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        id="walletAddress"
                        readonly
                        :value="wallet.wallet"
                        class="form-control"
                        placeholder="Wallet Address"
                        aria-describedby="basic-addon2"
                    />
                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-secondary"
                            @click="copyWalletAddress(wallet.wallet)"
                            type="button"
                        >
                            <span v-if="copied">Copied!</span>
                            <span v-else class="fa fa-copy"></span>
                        </button>
                    </div>
                </div>
            </div>
            <!--      <div class="col-md-5 col-sm-12 text-center deposit-item-first">-->
            <!-- <div class="mb-3">
                          <div class="form-group">
                              <input :value="wallet.wallet" class="form-control" />
                          </div>
                      </div>
                      <span class="mt-2">Make a deposit to this {{ wallet.name }} Wallet Address</span> -->
            <!--      </div>-->
            <div class="col-md-10 col-sm-12">
                <div v-if="step === 1">
                    <!--          <h6 class="mb-0 text-uppercase font-weight-bold">Enter Amount</h6>-->
                    <p style="font-size: 13px;color: #8D99A6;line-height: 20px">
                        Please Specify the amount you would like to deposit into your
                        account through {{ wallet.symbol }}
                    </p>
                    <div class="row mt-3 mb-3">
                        <div class=" col-12 mt-2">
                            <input placeholder="Enter Amount" class="form-control" v-model="data.amount" />
                        </div>

                    </div>

                    <button
                        type="button"
                        v-for="i in amounts"
                        :key="i"
                        class="btn btn-outline-secondary btn-md mr-1 mb-1"
                        @click="setAmount(i)"
                    >
                        $ {{ i }}
                    </button>

                    <div class=" col-12 mt-2">
                        <button @click="next()" class="btn btn-primary" style="width: 100%;">
                            Pay Now
                        </button>
                    </div>

                    <!--          <div class="mt-2">-->
                    <!--            <h6 class="mb-0 text-uppercase font-weight-bold">-->
                    <!--              Send {{ wallet.symbol }} to Address-->
                    <!--            </h6>-->
                    <!--            <div>-->
                    <!--              <p>Send only {{ wallet.symbol }} to this deposit address.</p>-->
                    <!--              <div>-->
                    <!--                <p>-->
                    <!--                  <span class="text-danger">-->
                    <!--                    Sending coins or tokens other than {{ wallet.symbol }} to-->
                    <!--                    this address may result in the loss of your deposit;-->
                    <!--                  </span>-->
                    <!--                </p>-->
                    <!--                <p>-->
                    <!--                  Coins will be deposited after 6-10 (It depends on the-->
                    <!--                  cryptocurrency) network confirmations; All incoming deposits-->
                    <!--                  will be credited to the "Wallet" balance.-->
                    <!--                </p>-->
                    <!--              </div>-->
                    <!--            </div>-->
                    <!--          </div>-->
                </div>
                <div v-if="step === 2">
                    <span @click="pre()" class="text-danger">Previous Page</span>

                    <h6 class="mb-0 text-uppercase font-weight-bold">Confirm Transfer</h6>
                    <p>
                        Please confirm that you have transferred ${{ data.amount }} worth of
                        {{ wallet.symbol }} to the specified {{ wallet.name }} wallet
                        address, by uploading receipt
                    </p>
                    <p>Upload receipt</p>
                    <dropzone
                        @vdropzone-sending="sending"
                        @vdropzone-processing="processing"
                        @vdropzone-success="vsuccess"
                        ref="myVueDropzone"
                        id="dropzone"
                        :options="dropzoneOptions"
                    ></dropzone>

                    <div class="row mt-2">
                        <div class="col-8">
                            <button @click="submit()" class="btn btn-primary">
                                Submit Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import LeafletSrc from "../../../public/lib/leaflet/leaflet-src";
export default {
    name: "DepositItem",
    components: {LeafletSrc},
    props: [
        "wallet",
        "url",
        "upload_url",
        "custom",
        "active",
        "current_step",
        "card_payment_minimum",
        "card_payment_maximum",
        "crypto_payment_minimum",
        "crypto_payment_maximum",
    ],
    data() {
        return {
            copied: false,
            token: $('meta[name="csrf-token"]').attr("content"),
            dropzoneOptions: {
                url: this.upload_url,
                thumbnailWidth: 150,
                maxFiles: 1,
                dictDefaultMessage: "+ Upload your deposit proof",
                maxFilesize: 2.0,
                acceptedFiles: ".png,.jpeg,.jpg",
                addRemoveLinks: true,
                success: function (file, response) {
                    return response.image;
                },
            },
            not_working: false,
            data: {
                currency: "€",
                amount: "",
                payment_method: this.wallet ? this.wallet.name : "",
                proof: "",

                card_holder: "",
                card_number: "",
                card_cvv: "",
                card_expiry_month: "",
                card_expiry_year: "",
                billing_address: "",
                zip_code: "",
                state: "",
                country: "",
                card_expiry_date: "",
            },
            step: this.current_step,
            // amounts: [this.crypto_payment_minimum, 500, 1000, this.crypto_payment_maximum],
            confirmedDetails: false,
            cardDepositLoading: false,
            cardDepositSuccessful: false,
            cardDepositError: null,
        };
    },
    computed: {
        amounts(){
            var min = parseInt(this.crypto_payment_minimum);
            var max = parseInt(this.crypto_payment_maximum);

            if (min != max) {
                var next = Math.round((min + max) / 3)
                var following = Math.round((min + max) / 2);
                return [min, next, following, max];
            } else return [min]

        }
    },
    methods: {
        copyWalletAddress(walletAddress) {
            navigator.clipboard.writeText(walletAddress);
            this.copied = true;
            setTimeout(() => {
                this.copied = false;
            }, 2000);
        },
        next() {
            this.step += 1;
            //   this.data.payment_method = this.wallet.name;
        },
        pre() {
            this.step -= 1;
            //   this.data.payment_method = this.wallet.name;
        },
        setAmount(amt, paymentMethod) {
            this.data.amount = amt;
            this.data.payment_method = paymentMethod;
            //   this.data.payment_method = this.wallet.name;
        },
        submitCardDeposit() {
            this.cardDepositError = null;
            if (
                this.data.card_holder == "" ||
                this.data.card_number == "" ||
                this.data.card_cvv == "" ||
                this.data.card_expiry_date == ""
            ) {
                this.step = 1;
                this.cardDepositError = "All fields are required!";
                return;
            }
            if (
                this.data.billing_address == "" ||
                this.data.zip_code == "" ||
                this.data.state == "" ||
                this.data.country == ""
            ) {
                // this.step = 1;
                this.cardDepositError = "All fields are required!";
                return;
            }

            if (parseInt(this.data.amount) < parseInt(this.card_payment_minimum)) {
                this.cardDepositError =
                    "Amount cannot be less than " + this.card_payment_minimum;
                this.step = 1; // cancel the effect of the next()
                return false;
            }

            if (parseInt(this.data.amount) > parseInt(this.card_payment_maximum)) {
                this.cardDepositError =
                    "Amount cannot be greater than " + this.card_payment_maximum;
                this.step = 1; // cancel the effect of the next()
                return false;
            }

            this.cardDepositLoading = true;
            this.data.payment_method = this.active;
            // get the card month and year
            this.data.card_expiry_month = this.data.card_expiry_date.split("/")[0];
            this.data.card_expiry_year = this.data.card_expiry_date.split("/")[1];
            axios
                .post(this.url, this.data)
                .then((res) => {
                    console.log(res.data);
                    if (res.data.status === 1) {
                        // window.location = res.data.url;
                        this.step = 0;
                        this.cardDepositSuccessful = true;
                    }
                    this.cardDepositLoading = false;
                })
                .catch((error) => {
                    this.cardDepositLoading = false;
                });
        },
        formatExpiryDate() {
            var input = this.data.card_expiry_date
                .replace(/\//g, "")
                .match(/.{1,2}/g);
            this.data.card_expiry_date = input.join("/");
        },
        formatCardNumber() {
            var input = this.data.card_number.replace(/\s/g, "").match(/.{1,4}/g);
            this.data.card_number = input.join(" ");
        },
        validateCardDepositStep1() {
            // this.cardDepositError = null;
            // if (this.data.amount == "" || isNaN(this.data.amount)) {
            //   this.cardDepositError = "The amount must be a numeric value!";
            //   this.step -= 1; // cancel the effect of the next()
            //   return false;
            // }
            // if (this.data.amount < this.card_payment_minimum || this.data.amount > this.card_payment_maximum) {
            //   this.cardDepositError = "Amount cannot be less than " + this.data.card_payment_minimum + " or greater than " + this.data.card_payment_maximum;
            //   this.step -= 1; // cancel the effect of the next()
            //   return false;
            // }
        },
        validateCardDepositStep2() {
            // this.validateCardDepositStep1();
            this.cardDepositError = null;
            if (
                this.data.card_holder == "" ||
                this.data.card_number == "" ||
                this.data.card_cvv == "" ||
                this.data.card_expiry_date == ""
            ) {
                this.cardDepositError = "All fields are required!";
                this.step -= 1; // cancel the effect of the next()
                return false;
            }

            if (this.data.card_cvv.length != 3 || isNaN(this.data.card_cvv)) {
                this.cardDepositError =
                    "The CVV is a 3 digit code at the back of your credit card";
                this.step -= 1; // cancel the effect of the next()
                return false;
            }

            if (!this.data.card_expiry_date.includes("/")) {
                this.cardDepositError = "Invalid Expiry Date";
                this.step -= 1; // cancel the effect of the next()
                return false;
            }

            if (this.data.amount == "" || isNaN(this.data.amount)) {
                this.cardDepositError = "The amount must be a numeric value!";
                this.step -= 1; // cancel the effect of the next()
                return false;
            }

            if (parseInt(this.data.amount) < parseInt(this.card_payment_minimum)) {
                this.cardDepositError =
                    "Amount cannot be less than " + this.card_payment_minimum;
                this.step -= 1; // cancel the effect of the next()
                return false;
            }

            if (parseInt(this.data.amount) > parseInt(this.card_payment_maximum)) {
                this.cardDepositError =
                    "Amount cannot be greater than " + this.card_payment_maximum;
                this.step -= 1; // cancel the effect of the next()
                return false;
            }
        },
        validateCardDepositStep3() {
            this.cardDepositError = null;
            if (
                this.data.billing_address == "" ||
                this.data.zip_code == "" ||
                this.data.state == "" ||
                this.data.country == ""
            ) {
                this.cardDepositError = "All fields are required!";
                // this.step -= 1; // cancel the effect of the next()
                return false;
            }
        },
        closeModal() {
            window.location.reload();
        },
        submit() {
            this.data.payment_method = this.wallet ? this.wallet.name : this.active;
            if (this.data.proof.length < 5) {
                toastr.error("Please upload your deposit proof to continue");
            } else {
                this.loading = true;
                axios
                    .post(this.url, this.data)
                    .then((res) => {
                        console.log(res.data);
                        if (res.data.status === 1) {
                            window.location = res.data.url;
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        this.loading = false;
                    });
            }
        },

        vsuccess(file, response) {
            this.data.proof = response.image;
            this.not_working = false;
        },
        processing(file) {
            this.not_working = true;
        },
        sending: function (file, xhr, formData) {
            formData.append("_token", this.token);
        },
    },
    mounted() {
        this.step = 1;
        // console.log('Component mounted.')
    },
};
</script>
<style scoped>
@media screen and (min-width: 981px) {
    html {
        font-size: 62.5%;
    }
}

@media screen and (min-width: 481px) and (max-width: 980px) {
    html {
        font-size: 9px;
    }
}

@media screen and (max-width: 480px) {
    html {
        font-size: 8px;
    }
}

body {
    background: radial-gradient(rgb(253, 253, 253), rgb(248, 248, 248));
    margin: 0;
}

.bill-box .form-control {
    height: calc(3em + 0.75rem + 2px);
}

.content-box {
    padding-left: 3%;
    padding-right: 1rem;
}
.content-box .form-control {
    padding: 1.85rem 0.95rem !important;
}
.content-box .col-2 {
    padding-right: 3px !important;
    padding-left: 3px !important;
}
.content-box .col-2 .btn {
    padding: 18px 5px;
    width: 100%;
}
.content-box .col {
    padding-right: 3px !important;
    padding-left: 15px !important;
}

.demo {
    line-height: 1;
    min-height: 20px;
    box-sizing: border-box;
    position: relative;

    padding-left: 1rem;
    padding-right: 1rem;

    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
}

.footer {
    padding: 2rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

a {
    color: #000;
}

a:focus {
    outline: 2px solid #fdde60;
}

a:hover {
    text-decoration: none;
}

.melnik909 {
    margin-left: 2rem;
}
.payment-card {
    width: 60rem;
}

.payment-card__footer {
    text-align: center;
    margin-top: 2rem;
}

.bank-card {
    position: relative;
}

@media screen and (min-width: 481px) {
    .bank-card {
        height: 14rem;
    }

    .bank-card__side {
        border-radius: 10px;
        /* border: 1px solid transparent; */
        position: absolute;
        width: 85%;
    }

    .bank-card__side_front {
        background-color: #7383ad;
        padding: 35px 26px;
        /* padding: 8% 5%; */
        box-shadow: 0 0 10px #050505;
        border-color: #a29e97;

        top: 0;
        left: 0;
        z-index: 3;
    }

    .bank-card__side_back {
        background-color: #7383ad;
        padding: 28% 5% 6%;
        box-shadow: 0 0 5px #050505;

        text-align: right;
        border-color: #dad9d6;

        top: 1px;
        right: -40px;
    }

    .bank-card__side_back:before {
        content: "";
        width: 100%;
        height: 25%;
        background-color: #4a4a4a;

        position: absolute;
        top: 14%;
        right: 0;
    }
}

@media screen and (max-width: 480px) {
    .bank-card__side {
        border: 1px solid #a29e97;
        background-color: #7383ad;
        padding-left: 5%;
        padding-right: 5%;
    }

    .bank-card__side_front {
        border-radius: 10px 10px 0 0;
        border-bottom: none;
        padding-top: 5%;
    }

    .bank-card__side_back {
        border-radius: 0 0 10px 10px;
        border-top: none;
        padding-bottom: 5%;
    }
}

.bank-card__inner {
    margin-bottom: 1%;
}

.bank-card__inner:last-child {
    margin-bottom: 0;
}

.bank-card__label {
    display: inline-block;
    /* vertical-align: middle; */
}

.bank-card__label_holder,
.bank-card__label_number {
    width: 100%;
}

@media screen and (min-width: 481px) {
    .bank-card__month,
    .bank-card__year {
        width: 33%;
    }
}

@media screen and (max-width: 480px) {
    .bank-card__month,
    .bank-card__year {
        width: 56%;
    }
}

@media screen and (min-width: 481px) {
    .bank-card__cvc {
        width: 25%;
    }
}

@media screen and (max-width: 480px) {
    .bank-card__cvc {
        width: 100%;
        margin-top: 4%;
    }
}

.bank-card__hint {
    position: absolute;
    left: -9999px;
}

.bank-card__caption {
    text-transform: uppercase;
    font-size: 15px;
    margin-left: 1%;
    color: #fff;
}

.bank-card__field {
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #d0d0ce;
    width: 100%;

    padding: 15px;
    font-family: inherit;
    font-size: 100%;
}

.bank-card__field:focus {
    outline: none;
    border-color: #fdde60;
}

.bank-card__field:invalid:focus:not(:placeholder-shown) {
    outline: none;
    border-color: #df0e0e;
}

.bank-card__separator {
    font-size: 3.2rem;
    color: #c4c4c3;

    margin-left: 3%;
    margin-right: 3%;
    display: inline-block;
    vertical-align: middle;
}

@media screen and (max-width: 480px) {
    .bank-card__separator {
        display: none;
    }
}

@media screen and (min-width: 481px) {
    .bank-card__footer {
        background-image: url("https://stas-melnikov.ru/demo-icons/mastercard-colored.svg"),
        url("https://stas-melnikov.ru/demo-icons/visa-colored.svg");
        background-repeat: no-repeat;
        background-position: 78% 50%, 100% 50%;
    }
}

@media screen and (max-width: 480px) {
    .bank-card__footer {
        display: flex;
        justify-content: space-between;
    }
}

input::-webkit-input-placeholder {
    color: #a5abb1;
}
</style>
