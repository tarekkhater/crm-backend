@extends('admin.layouts.admin-app')

@section('style')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    @include('admin.partials.dt-css')
<style>
    .dataTables_paginate {
        display: none;
    }
    .dataTables_info {
        display: none;
    }
</style>
@endsection

@section('content')
    <div class="br-mainpanel">

        <div class="br-pagebody">
            <div class="br-section-wrapper">

            <a class="btn btn-primary m-1" href="{{ route('admin.users.ids') }}">KYC Verifications</a>
            <a class="btn btn-primary m-1" href="{{ route('admin.plans.index') }}">Plans</a>
            <a class="btn btn-dark m-1" href="{{ route('admin.deposits.index') }}">Deposits</a>
            <a class="btn btn-success m-1" href="{{ route('admin.withdrawals.index') }}">Withdrawal</a>
            <a class="btn btn-success m-1" href="{{ route('admin.transactions') }}">Transactions</a>
            </div>
        </div>
        <div class="br-pagebody" id="vue-content">

            <div class="br-section-wrapper">

                @include('notification')


                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">
                    @if(check_permission('update-customers'))
                    <button class="btn btn-success m-1" data-toggle="modal" data-target="#bulkAction"> Bulk Action </button>
                  @endif
                    {{-- <button class="btn btn-primary m-1" v-on:click="convertToLead">Revert to Lead <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="convertingToLead"></i></button>
                    <button class="btn btn-danger m-1" v-on:click="deleteCustomers"><em class="fa fa-trash"></em> Delete Customers <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="deletingCustomers"></i></button> --}}
@if(check_permission('verify-all-emails'))
                    <a href="{{ route('admin.verify.accounts') }}"><button class="btn btn-success m-1" style="float: right">Verify all emails</button></a>
                    @endif
                    @if(check_permission('create-admin'))
                    @if ($title == 'admin')
                        <a href="{{ route('admin.users.create') }}?r=admin"><button class="btn btn-danger m-1" style="float: right"> Add Admin</button></a>
                    @endif
                    @endif
@if(check_permission('create-manager'))
                    @if($title == 'manager')
                        <a href="{{ route('admin.users.create') }}?r=manager"><button class="btn btn-warning m-1" style="float: right"> Add Manager</button></a>
                    @endif
@endif
                    @if(check_permission('create-retention'))
                    @if($title == 'retention')
                        <a href="{{ route('admin.users.create') }}?r=retention"><button class="btn btn-warning m-1" style="float: right"> Add Retention</button></a>
                    @endif
@endif

                    @if (setting('autotrader'))
                        <a href="{{ route('admin.users.create') }}?r=autotrader"><button class="btn btn-primary m-1" style="float: right"> Add Autotrader</button></a>
                    @endif

                </h6>


            <div class="table-wrapper">
                <form action="{{ route('admin.users.index') }}" method="get">
                    <div class="row mb-4">
                      <div class="col-2"></div>
                        @if(is_active('admin.users.index'))
                      <div class="col-2">
                        <select class="form-control" name="manager">
                            <option value="" class="text-muted">Filter Manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" @if (Request::get('manager') == $manager->id ) selected @endif>{{ $manager->name }}</option>
                            @endforeach
                        </select>
                      </div>
                        @endif
                      <div class="col-4">
                        <input type="text" class="form-control" name="q" value="{{ Request::get('q') }}" placeholder="Search">
                      </div>
                      <div class="col-2">
                          <button class="btn btn-primary"><em class="fa fa-search"></em></button>
                          <a href="{{ route('admin.users.index') }}" class="btn btn-danger"><em class="fa fa-times text-white"></em></a>
                      </div>
                      <div class="col-2"></div>
                    </div>
                  </form>

                <table id="datatable" class="table table-bordered table-striped display responsive nowrap">
                <thead>
                    <tr>
                        @if (check_permission('update-'.$title))
                        <th class="wd-2p">
                            <input type="checkbox" v-model="selectAllUsers"/>
                        </th>
                        @endif
                        <th>ID</th>
                        <th class="w-25">Name</th>
{{--                    <th class="wd-5p">Profile Img</th>--}}
                    <th  class="w-35">Email</th>
                    <th class="w-25">Phone</th>
                    <th class="w-25">Fund Priviledge</th>
{{--                    <th class="wd-10p">Phone</th>--}}
                            @if (setting('invest'))
                                <th class="wd-10p">Invested</th>
                            @endif
                    <th class="w-50">Trades</th>
                    <th class="w-50">Finance</th>
                    <th class="w-50">Created at</th>

{{--                        <th class="wd-15p">Role</th>--}}

                    </tr>
                </thead>
                <tbody>

                    @php
                        $count = 1;
                    @endphp
                    @forelse ($users as $user)
                        <tr v-cloak>
                            @if (check_permission('update-'.$title))
                            <td width="2">
                                    <input type="checkbox" value="{{ $user->id }}" v-model="selected_users"/>
                                {{-- {{ $loop->index + 1 }} --}}
{{--                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="View User"><em class="fa fa-edit"></em></a>--}}
{{--                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="View User"><em class="fa fa-eye"></em></a>--}}

                            </td>
                            @endif
                            <td>{{ $user->id }}</td>
                            <td><a class="text-capitalize" href="{{ route('admin.users.show', $user->id) }}">{{ $user->name }}</a> <br />
                                @if ($user->email_verified_at)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-danger">Not Verified</span>
                                @endif
                                <br/>
                                @if ($user['last_seen'] >= \Carbon\Carbon::now('Europe/London')))
                                <span class="badge badge-success">Online</span>
                                @else
                                    <span class="badge badge-danger">Offline</span>
                                @endif
                            </td>
{{--                            <td><img src="{{ $user->avatar }}" height="50px" width="50px"></td>--}}
                            <td width="10"><span style="font-size: 1.1em"> {{ $user->email }}</span> <br />
                                <a href="#" data-toggle="modal" data-target="#notes" v-on:click="createNote({{ $user }})">Notes (@{{ noteCount({!! $user->id !!}) }})</a>
                                @if(check_permission('delete-'.$title))
                                <a href="{{ route('admin.user.toggle', $user->id) }}" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" data-toggle="tooltip" ><em class="fa {{ $user->is_active ? 'fa-times ' : ' fa-check' }} "></em></a>
                              @endif
                                @if(check_permission('update-'.$title))
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="View User"><em class="fa fa-edit"></em></a>
                               @endif
                                @if (check_permission('assign-user-to-manager'))
                                    <a href="{{ route('admin.assignUsers', $user->id) }}" class="btn btn-secondary"  data-placement="top" title="Assign Users"><em class="fa fa-plus"></em></a>
                                @endif
                                @if (check_permission('login-as-'.$title))
                                    @if($user->id!=1)
                                 <a href="{{ route('admin.user.gainaccess', $user->id) }}" onclick="alert('You are about to login as a Normal User that means you will be logged out as an admin on this browser')" target="_blank" class="btn btn-primary btn-small text-capitalize ">Login as {{ $user->first_name }}</a>
@endif
                                    @endif
                                    {{--
  <a href="#" data-toggle="modal" class="btn btn-success" data-target="#accessTokenModal" v-on:click="generateAccessToken({{ $user->id }})">Generate Access Token</a>--}}

{{--                               <button  class="btn btn-success btn-sm" id="gainaccessBtn">Gain Access</button>--}}
{{--                               <form action="{{ route('admin.user.gain.access') }}" id="gainaccess" method="POST">--}}
{{--                                   @csrf--}}
{{--                                   <input type="text" name="user_id" value="{{ $user->id }}" hidden>--}}
{{--                               </form>--}}
                            </td>
                                <td>
                                    <a  href="tel:{{ $user->phone }}">
                                        @{{showPhone({!! $user->id !!})}}
                                        <a href="#" data-toggle="modal" data-target="#changePhone" v-on:click="setPhoneUpdateData({{ $user }})"><i class="fa fa-edit"></i> </a>
                                    </a>
                                    <br/>
                                    @if ($user->country)
                                        {{ $user->country }} <em><sub>{{ $user->phone_code }}</sub></em>
                                    @endif
                                </td>
                                <td class="text-capitalize">
                                    <label class="switch">
                                        <input {{ $user->can_add_fund ? 'checked' : '' }} @change="updateFund({{$user->id}})" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @if (setting('invest'))
                            <td>{{ $user->invested() }}</td>
                                @endif

                                {{-- <td>
                                  Status : <br/>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle full-width" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                            <span ref="changingStatus{{ $user->id }}"></span> @{{showStatus({!! $user->id !!})}}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @foreach ($statuses as $status)
                                                <li class="dropdown-item" v-on:click="updateStatus({{ $user->id }}, {{ $status->id }})" style="cursor: pointer;">{{ $status->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div> <br/>
                                    Source : <br/>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle full-width" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                            <span ref="changingSource{{ $user->id }}"></span> @{{showSource({!! $user->id !!})}}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @foreach ($sources as $source)
                                            <li class="dropdown-item" v-on:click="updateSource({{ $user->id }}, {{ $source->id }})" style="cursor: pointer;">{{ $source->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td> --}}

                                <td width="40">
                                Open : <a href="{{ route('admin.trades.index') }}?user={{$user->id}}" >{{ \App\Models\Trade::whereUserId($user->id)->whereStatus(0)->count() }}</a>
                                <br/>
                                Closed : <a href="{{ route('admin.trades.index') }}?user={{$user->id}}" >{{ \App\Models\Trade::whereUserId($user->id)->whereStatus(1)->count() }}</a>
                            </td>
                            <td>
                                <span>Bal :  {{ amt($user->userInfo->balance) }}</span> <br/>
                                <span>Bonus :  {{ amt($user->userInfo->bonus) }}</span>

                            </td>
                            <td>{{ $user->created_at }}</td>

                        </tr>
                    @empty
                         <td colspan="9" class="text-center">
                            <i>No Record Found!</i>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
                </table>


                <div class="d-felx justify-content-center">

                    {{ $users->links() }}

                </div>
            </div><!-- table-wrapper -->
            {{-- <div>
                <form action="{{ route('checkPass', 'hello') }}" method="POST">
                    @csrf
                    <button type="submit">sub</button>
                </form>
            </div> --}}

            <div class="modal fade" id="bulkAction">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content container">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title">Bulk Action</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form  @submit.prevent="submitBulkAction">
                        <div class="modal-body">
                            <ul class="alert alert-danger" v-if="errors">
                                <li v-for="(value, key, index) in validationErrors">@{{ value }}</li>
                            </ul>
                            <div class="row">
                                <div class="form-group form-check col-12">
                                    <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" v-model="bulkAction.delete"> Mass Delete
                                    </label>
                                </div>
                            </div>

                            <div class="row" v-show="bulkAction.delete">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input id="password" class="form-control" type="password" placeholder="Enter Admin Password" v-model="bulkAction.admin_password" autofocus>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" v-model="bulkAction.convertTo"> Revert to lead
                                </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Change Password</label>
                                <input class="form-control mb-2" type="password" v-model="bulkAction.password" placeholder="Enter new password">

                                <input class="form-control" type="password" v-model="bulkAction.confirm_password" placeholder="Repeat password">
                            </div>

                                <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" :disabled="isLoading">Confirm <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="isLoading"></i></button>
                            </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="modal fade modal-xl" id="accessTokenModal" tabindex="-1" role="dialog" aria-labelledby="accessTokenModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Access Token</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    {{-- <div class="modal-body text-center">
                        <i class="fa fa-spinner fa-spin fa-3x" aria-hidden="true" v-if="fetchingAccessToken"></i></button>
                        <div v-else class="input-group mb-3">
                            <input type="text" readonly :value="generatedAccessToken" class="form-control" placeholder="Wallet Address" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" v-on:click="copyAccessToken(generatedAccessToken)" type="button">
                                    <span v-if="copied">Copied!</span>
                                    <span v-else class="fa fa-copy"></span>
                                </button>
                            </div>
                        </div>
                    </div> --}}
                  </div>
                </div>
              </div>

            <div class="modal fade" id="changePhone" tabindex="0" aria-labelledby="changePhone" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationLabel text-capitalise">Update @{{ updatePhoneData.name }} Phone</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" @submit.prevent="updatePhone">
                            <div class="modal-body">
                                <div class="row">
                                        <div v-if="errors" class="col-12">
                                            <ul class="alert alert-danger">
                                                <li class="alert alert-danger" v-for="(value, key, index) in validationErrors">@{{ value }}</li>
                                            </ul>
                                        </div>
                                        <div v-if="phoneUpdateSuccess" class=" col-12 alert alert-success">
                                            Phone Updated successfully!
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-sm-12 form-control-label">Phone Number: <span class="tx-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <input type="text" placeholder="Phone" class="form-control" v-model="updatePhoneData.phone" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Done</button>
                                <button type="submit" class="btn btn-sm btn-primary" id="submitBtn" :disabled="isLoading">Update <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="isLoading"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="confirmation" tabindex="0" aria-labelledby="confirmationLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="confirmationLabel">Authentication</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <form action="" onsubmit="return false" autocomplete="new-password">
                            <div class="form-group">
                                <label for="password" class="col-form-label">Please enter your password to confirm</label>
                                <input type="password" class="form-control" id="password" autocomplete="new-password">
                                <small id="passwordHelp" class="form-text  text-danger">There is no reversal to this! Are you sure you want to remove this user entirely from the system?</small>
                              </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-sm btn-danger" id="submitBtn"  >Delete</button>
                    </div>
                  </div>
                </div>
            </div>

            <div class="modal fade" id="notes" tabindex="0" aria-labelledby="Notes" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationLabel">Notes - @{{newNote.user}}<h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="#" @submit.prevent="addNote">
                            <div class="modal-body">
                                <div class="row">
                                    <div v-if="errors" class="col-12">
                                        <ul class="alert alert-danger">
                                            <li class="alert alert-danger" v-for="(value, key, index) in validationErrors">@{{ value }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mg-t-20">
                                        <label class="col-sm-12 form-control-label">Note:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" placeholder="Note" v-model="newNote.content" required autocomplete="note"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 mg-t-20" v-if="newNote.contacted">
                                        <label class="col-sm-12 form-control-label">Date Contacted:</label>
                                        <div class="col-sm-12">
                                            <input  type="datetime-local" placeholder="Status Name" class="form-control" v-model="newNote.contacted_at" required autocomplete="name">
                                        </div>
                                    </div>
                                    <div class="col-12 mg-t-20">
                                        <div class="col-sm-12">
                                            <label><input  type="radio" v-bind:value=true v-model="newNote.contacted"> I have contacted this user</label><br>
                                            <label><input  type="radio" v-bind:value=false v-model="newNote.contacted"> I have NOT contacted this user</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Done</button>
                                <button type="submit" :disabled="addingNote" class="btn btn-sm btn-success">Add Note <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="addingNote"></i> </button>
                            </div>
                            <div class="col-12 mg-t-20 px-4" v-for="(note, index) in userNotes" :key="index">
                                <b>@{{ note.writer_name ?? '' }}</b>
                                <p>@{{ note.content }}<br> <small v-if="note.contacted_at">Contacted @ @{{ note.contacted_at }}</small></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@section('js')


            <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


            @include('admin.partials.dt-js')

    <script>

        $(function(){ $('#toggle').bootstrapToggle() });


        let generateAccessTokenUrl = "{{ route('backend.user.token.generate') }}";
        let bulkActionUrl = "{{ route('admin.users.bulkaction') }}";
        let updateCanFundUrl = "{{ route('admin.users.can_fund') }}";

        let saveUserUrl = "{{ route('admin.lead.store') }}";
        // let convert = "{{ route('admin.users.lead.convert') }}";
        let deleteCustomersUrl = "{{ route('admin.user.deleteMultiple') }}";
        let addNoteUrl = "{{ route('admin.lead.addNote') }}";
        let updatePhoneUrl = "{{ route('admin.lead.updatePhone') }}";

        document.addEventListener('DOMContentLoaded', function () {
            new Vue({
                el: '#vue-content',
                data() {
                    return {
                        fetchingAccessToken: false,
                        generatedAccessToken: '',
                        copied: false, // if accessToken has been copied
                        bulkAction: {
                            delete: false,
                            convertTo: null,
                            password: '',
                            confirm_password: '',
                            admin_password: '',
                        },
                        selectAllUsers: false,
                        asset : '',
                        isLoading : false,
                        errors : null,
                        error : null,
                        form : {
                            password: '',
                            confirm: '',
                            user_id:'',
                        },
                        data : {
                            first_name : '',
                            source : '',
                            last_name : '',
                            email : '',
                            username : '',
                            manager_id : '',
                            country : ''
                        },
                        //
                        selected_users: [],
                        // note
                        newNote: {
                            user: '',
                            user_id: '',
                            content: '',
                            contacted_at: null,
                            contacted: true
                        },
                        addingNote: false,
                        userNotes: [],
                        customers: {!! json_encode($users) !!},

                        // phone update
                        updatePhoneData: {
                            phone: '',
                        },
                        phoneUpdateSuccess: false
                    }
                },
                mounted() {
                    // this.getAllTrades();
                    $('#datatable').DataTable({
                        iDisplayLength : 100,

                        language: {
                            searchPlaceholder: 'Search...',
                            sSearch: '',
                            lengthMenu: '_MENU_ items/page',
                        }
                    });
                },
                methods: {
                    updateStatus(userId, statusId, newStatus) {
                        this.$refs[`changingStatus${userId}`].innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

                        axios.post(bulkActionUrl, {selected_users: [userId], status: statusId, direct: 'yes'}).then((res)=>{
                            this.customers.data.find((u) => u.id == userId).statusrecord = res.data[0].statusrecord;

                            this.$refs[`changingStatus${userId}`].innerHTML = '';
                            toastr.success('Status Updated!', 'Success', {
                                "positionClass" : "toast-top-right"
                            })
                        }).catch((error)=>{
                            this.$refs[`changingStatus${userId}`].innerHTML = '';
                            if(error.response) {
                                if (error.response.status === 422){
                                    this.errors = error.response.data.errors;
                                    console.log(this.errors);
                                } else if (error.response.status === 500){
                                    toastr.error(error.response.data.message, 'Error',{
                                        "positionClass" : "toast-top-right"
                                    })
                                }
                            }
                        })
                    },
                    updateSource(userId, sourceId) {
                        this.$refs[`changingSource${userId}`].innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

                        axios.post(bulkActionUrl, {
                            selected_users: [userId],
                            source: sourceId,
                            direct: 'yes'
                        }).then((res) => {
                            this.customers.data.find((u) => u.id == userId).sourcerecord = res.data[0].sourcerecord;

                            this.$refs[`changingSource${userId}`].innerHTML = '';
                            toastr.success('Source Updated!', 'Success', {
                                "positionClass": "toast-top-right"
                            })
                        }).catch((error) => {
                            this.$refs[`changingSource${userId}`].innerHTML = '';
                            if (error.response) {
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                    console.log(this.errors);
                                } else if (error.response.status === 500) {
                                    toastr.error(error.response.data.message, 'Error', {
                                        "positionClass": "toast-top-right"
                                    })
                                }
                            }
                        })
                    },
                    updateFund(id){
                        axios.post(updateCanFundUrl, {id : id}).then((res)=>{
                        });
                    },
                    generateAccessToken(user_id) {
                        this.fetchingAccessToken = true;
                        this.error = null;
                        this.errors = null;
                        axios.post(generateAccessTokenUrl, {user_id}).then((res)=>{
                            this.fetchingAccessToken = false
                            // show modal with
                            this.generatedAccessToken = res.data

                        }).catch((error)=>{
                            this.fetchingAccessToken = false
                            if (error.response) {
                                if (error.response.status === 422){
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500){
                                    alert(error.response.data.message);
                                } else {
                                    alert(error.response.statusText);
                                }
                            }
                        })
                    },
                    // copyAccessToken(accessToken) {
                    //     navigator.clipboard.writeText(accessToken);
                    //     this.copied = true;
                    //     setTimeout(() => {
                    //         this.copied = false;
                    //     }, 2000);
                    // },
                    submitBulkAction() {
                        if (this.selected_users.length < 1) { alert('No User is selected'); return; }
                        if(!confirm('Are you sure you want to apply the selected changes?')) return ;

                        if (this.bulkAction.convertTo) this.bulkAction.convertTo = 'lead'
                        this.bulkAction.selected_users = this.selected_users

                        this.isLoading = true;
                        this.error = null;
                        this.errors = null;
                        this.errors = null;
                        axios.post(bulkActionUrl, this.bulkAction).then((res)=>{
                            this.isLoading = false;
                            this.reg_success = true;
                            window.location.reload();
                        }).catch((error)=>{
                            this.isLoading = false
                            if (error.response.status === 422){
                                this.errors = error.response.data.errors;
                            } else if (error.response.status === 500){
                                alert(error.response.data.message);
                        }
                        })
                    },
                    setPhoneUpdateData(user) {
                        this.updatePhoneData.name = user.name
                        this.updatePhoneData.phone = user.phone;
                        this.updatePhoneData.user_id = user.id;
                    },
                    updatePhone() {
                        this.isLoading = true;
                        this.error = null;
                        this.phoneUpdateSuccess = false;
                        axios.post(updatePhoneUrl, this.updatePhoneData).then((res)=>{
                            this.isLoading = false;
                            this.customers.data.find((u) => u.id == res.data.id).phone = res.data.phone;
                            this.phoneUpdateSuccess = true;
                        }).catch((error)=>{
                            this.isLoading = false;
                            this.phoneUpdateSuccess = false;
                            if (error.response) {
                                if (error.response.status === 422){
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500){
                                    alert(error.response.data.message);
                                }
                            }
                        })
                    },

                    deleteCustomers() {
                        if (this.selected_users.length < 1) { alert('No User is selected'); return; }
                        if(!confirm('Are you sure you want to delete this customer?\nThis action CANNOT be reversed!')) return ;

                            this.deletingCustomers = true;
                            this.error = null;
                            axios.post(deleteCustomersUrl, {selected_users: this.selected_users}).then((res)=>{
                                this.deletingCustomers = false;
                                window.location.reload();
                            }).catch((error)=>{
                                this.deletingCustomers = false
                                if (error.response.status === 422){
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500){
                                    alert(error.response.data.message);
                                }
                            })
                    },
                    noteCount(userId) {
                        return this.customers.data.find((user) => user.id == userId).notes.length;
                    },
                    showPhone(userId) {
                        return this.customers.data.find((user) => user.id == userId).phone;
                    },
                    showStatus(userId) {
                        var statusrecord = this.customers.data.find((user) => user.id == userId).statusrecord;
                        return statusrecord != null ? statusrecord.name : 'NA';
                    },
                    showSource(userId) {
                        var sourcerecord = this.customers.data.find((user) => user.id == userId).sourcerecord;
                        return sourcerecord != null ? sourcerecord.name : 'NA';
                    },
                    createNote(user) {
                        this.newNote = {
                            user: user.name,
                            user_id: user.id,
                            content: '',
                            contacted_at: null,
                            contacted: true
                        }
                        this.userNotes = this.customers.data.find((u) => u.id == user.id).notes; // copy a reference of this users note
                    },
                    addNote() {
                        if (!this.newNote.contacted) {
                            this.newNote.contacted_at = null
                        }

                        this.addingNote = true;
                        axios.post(addNoteUrl, this.newNote).then((res) => {
                            this.addingNote = false;
                            console.log(res.data);
                            this.customers.data.find((u) => u.id == res.data.user_id).notes.unshift(res.data); // add to notes to increase count
                        }).catch((error) =>{
                            this.addingNote = false;
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            } else if (error.response.status === 500){
                                alert(error.response.data.message);
                            }
                        })
                    },
                    submitForm(){
                        this.isLoading = true;
                        this.errors = null;
                        this.reg_success = false;
                        axios.post(saveUserUrl, this.data).then((res)=>{
                            this.isLoading = false;
                            this.reg_success = true;
                            window.location.reload();
                        }).catch((error)=>{
                            this.isLoading = false
                            if (error.response.status === 422){
                                this.errors = error.response.data.errors;
                            }
                        })
                    },
                },

                computed: {
                    validationErrors(){
                        let errors = Object.values(this.errors);
                        errors = errors.flat();
                        return errors;
                    },

                },

                watch: {
                    selectAllUsers: function (newValue, oldValue) {
                        if (newValue > oldValue) {
                            this.selected_users = []
                            this.customers.data.forEach((user) => {
                                this.selected_users.push(user.id);
                            });
                        } else {
                            this.selected_users = []
                        }
                    }
                }
            })
        })
    </script>
@endsection
