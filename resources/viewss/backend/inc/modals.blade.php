<div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="background-color: #1c2030;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Make Deposit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="">
                <deposit upload_url="{{ route('backend.post.image') }}" url="{{ route('backend.deposits.store') }}"
                    :custom='@json($custom_pay)' :wallets='@json($wallets)'
                    card_payment="{{ setting('card_payment') }}"
                    card_payment_minimum="{{ setting('card_payment_minimum') }}"
                    card_payment_maximum="{{ setting('card_payment_maximum') }}"

                    crypto_payment="{{ setting('crypto_payment') }}"
                    crypto_payment_minimum="{{ setting('crypto_payment_minimum') }}"
                    crypto_payment_maximum="{{ setting('crypto_payment_maximum') }}"
                >
                </deposit>

                {{-- <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical"> --}}
                {{-- <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a> --}}
                {{-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a> --}}
                {{-- <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a> --}}
                {{-- <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> --}}
                {{-- </div> --}}
                {{-- <div class="tab-content" id="v-pills-tabContent"> --}}
                {{-- <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, animi architecto consectetur cumque eveniet harum, nam porro quam recusandae rerum ut, voluptatem. Deleniti ipsam itaque nobis odit porro quam voluptatum.</div> --}}
                {{-- <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div> --}}
                {{-- <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div> --}}
                {{-- <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div> --}}
                {{-- </div> --}}


            </div>
        </div>
    </div>
</div>

<div class="modal fade connect" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="connect"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Connection request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="height: 300px" class="wave_b">
                    <div class="waves"></div>
                </div>

                <div class="text-center">
                    <h5 class="text-center">{{ Auth()->user()->msg }}</h5>
                    @if (auth()->user()->manager_id)
                        <a href="{{ route('backend.profile.view', auth()->user()->manager_id) }}"
                            class="btn btn-success text-white mb-2 mt-2">View profile</a>
                        <a href="{{ route('backend.account.connect', auth()->user()->manager_id) }}"
                            class="btn btn-primary text-white mb-2 mt-2">Accept Request</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="addAccountTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <add_account :crypto="{{ setting('crypto_withdraw', 0) }}" :wire="{{ setting('wire_withdraw', 0) }}"
                    :wires='@json(auth()->user()->wireAccounts()->get())' url="{{ route('backend.account.store') }}">
                </add_account>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addWireAccount" tabindex="-1" role="dialog" aria-labelledby="addWireAccountTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Wire Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <add_wire_account url="{{ route('backend.account.wire.store') }}"></add_wire_account>
            </div>
        </div>
    </div>
</div>
