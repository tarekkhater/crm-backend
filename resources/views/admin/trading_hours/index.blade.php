@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
    <style>

    </style>
@endsection

@section('content')
    @include('sweetalert::alert')
    <div class="br-mainpanel">


        <div class="br-pagebody" id="vue-content">

            <div class="br-section-wrapper">

                @include('notification')




                <div class="">

                    <div class="table-wrapper table-responsive">

                        <table class="table tble-border table-striped" id="datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>ex_sym</th>
                                    <th>sym</th>
                                    <th>base</th>
                                    <th>type</th>
                                    <th>open_at</th>
                                    <th>close_at</th>
                                    <th>days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currency_pairs as $key => $currency_pair)
                                    @php
                                        $openArray = json_decode($currency_pair->open_at, true);

                                        $daysArray = json_decode($currency_pair->days, true);

                                    @endphp

                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $currency_pair->name }}</td>
                                        <td>{{ $currency_pair->ex_sym }}</td>
                                        <td>{{ $currency_pair->sym }}</td>
                                        <td>{{ $currency_pair->base }}</td>
                                        <td>{{ $currency_pair->type }}</td>
                                        <td>
                                            @if (isset($openArray))
                                                @foreach ($openArray as $array)
                                                    @php
                                                        $parts = explode('-', $array);
                                                        $start = $parts[0];


                                                    @endphp

                                                    <span class="badge badge-success">
                                                        {{$start}}

                                                    </span>
                                                @endforeach
                                               @else
                                               <span class="badge badge-success">
                                              {{$currency_pair->open_at}}
                                               </span>

                                            @endif

                                        </td>
                                        <td>
                                            @if (isset($openArray))
                                                @foreach ($openArray as $array)
                                                    @php
                                                        $parts = explode('-', $array);
                                                       // $start = $parts[0];

                                                        $end = $parts[1];


                                                    @endphp
                                                    <span class="badge badge-success">
                                                        {{$end}}
                                                    </span>
                                                @endforeach
                                               @else
                                               <span class="badge badge-success">
                                              {{$currency_pair->close_at}}
                                               </span>

                                            @endif

                                        </td>
                                        <td>
                                            @foreach ($daysArray as $d)

                                                <span class="badge badge-info">
                                                    {{$d}}
                                                     </span>
                                            @endforeach

                                        </td>
                                        <td>



@if(check_permission('edit-trading-hours'))
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('admin.trading_hours.edit', $currency_pair->id) }}">Edit</a>


@endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- table-wrapper -->
            </div>
        </div>
    @endsection

    @section('js')
        @include('admin.partials.dt-js')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Vue({
                    el: '#vue-content',
                    data() {
                        return {
                            fetchingAccessToken: false,
                            generatedAccessToken: '',
                            changingToCustomer: false,
                            changingManager: false,
                            copied: false, // if accessToken has been copied
                            bulkAction: {
                                delete: false,
                                convertTo: null,
                            },
                            selectAllUsers: false,
                            asset: '',
                            isLoading: false,
                            errors: null,
                            error: null,
                            form: {
                                password: '',
                                confirm: '',
                                user_id: '',
                            },
                            data: {
                                first_name: '',
                                source: '',
                                last_name: '',
                                email: '',
                                username: '',
                                manager_id: '',
                                country: ''
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
                            changeManagerData: {
                                manager_id: '',
                            },
                            addingNote: false,
                            userNotes: [],
                            customers: {!! json_encode($currency_pairs) !!},

                            // phone update
                            updatePhoneData: {
                                phone: '',
                            },
                            phoneUpdateSuccess: false
                        }
                    },
                    mounted() {
                        // this.getAllTrades();
                        $.fn.dataTable.moment('DD.MM.YYYY');
                        $('#datatable').DataTable({

                            iDisplayLength: 10,

                            language: {
                                searchPlaceholder: 'Search...',
                                sSearch: '',
                                lengthMenu: '_MENU_ items/page',
                            },
                            columnDefs: [{
                                "orderSequence": ["desc", "asc"],
                                type: 'numeric-comma',
                                targets: 0
                            }],

                        });
                    },
                    methods: {
                        setManagerAs(id) {
                            if (!confirm('Are you sure you want to perform this actions')) return;
                            if (this.selected_users.length < 1) {
                                alert('No User is selected');
                                return;
                            }

                            this.changeManagerData.manager_id = id;
                            this.changeManagerData.selected_users = this
                                .selected_users // attach the selected users
                            this.changingManager = true;
                            axios.post(changeManagerUrl, this.changeManagerData).then((res) => {
                                this.changingManager = false;
                                window.location.reload();
                            }).catch((error) => {
                                this.changingManager = false;
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                    // alert(error.response.data.errors[0]);
                                } else if (error.response.status === 500) {
                                    alert(error.response.data.message);
                                }
                            })
                        },
                        setUsersAsFree() {
                            if (!confirm('Are you sure you want to perform this actions')) return;
                            if (this.selected_users.length < 1) {
                                alert('No User is selected');
                                return;
                            }
                            this.changeManagerData.selected_users = this
                                .selected_users // attach the selected users
                            this.changingManager = true;
                            axios.post(setUsersAsFreeUrl, this.changeManagerData).then((res) => {
                                this.changingManager = false;
                                window.location.reload();
                            }).catch((error) => {
                                this.changingManager = false;
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                    // alert(error.response.data.errors[0]);
                                } else if (error.response.status === 500) {
                                    alert(error.response.data.message);
                                }
                            })
                        },
                        setUsersAsArcheive() {
                            if (!confirm('Are you sure you want to perform this actions')) return;
                            if (this.selected_users.length < 1) {
                                alert('No User is selected');
                                return;
                            }
                            this.changeManagerData.selected_users = this
                                .selected_users // attach the selected users
                            this.changingManager = true;
                            axios.post(setUsersAsArcheiveUrl, this.changeManagerData).then((res) => {
                                this.changingManager = false;
                                window.location.reload();
                            }).catch((error) => {
                                this.changingManager = false;
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                    // alert(error.response.data.errors[0]);
                                } else if (error.response.status === 500) {
                                    alert(error.response.data.message);
                                }
                            })
                        },
                        generateAccessToken(user_id) {
                            this.fetchingAccessToken = true;
                            this.error = null;
                            axios.post(generateAccessTokenUrl, {
                                user_id
                            }).then((res) => {
                                this.fetchingAccessToken = false
                                // show modal with
                                this.generatedAccessToken = res.data

                            }).catch((error) => {
                                this.fetchingAccessToken = false
                                if (errors.response) {
                                    if (error.response.status === 422) {
                                        this.errors = error.response.data.errors;
                                    } else if (error.response.status === 500) {
                                        alert(error.response.data.message);
                                    } else {
                                        alert(errror.response.statusText);
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
                            if (this.selected_users.length < 1) {
                                alert('No User is selected');
                                return;
                            }
                            if (!confirm('Are you sure you want to convert selected users to customer?'))
                                return;

                            if (this.bulkAction.convertTo) this.bulkAction.convertTo = 'lead'
                            this.bulkAction.selected_users = this.selected_users

                            this.isLoading = true;
                            this.error = null;
                            axios.post(bulkActionUrl, this.bulkAction).then((res) => {
                                this.isLoading = false;
                                this.reg_success = true;
                                window.location.reload();
                            }).catch((error) => {
                                this.isLoading = false
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500) {
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
                            axios.post(updatePhoneUrl, this.updatePhoneData).then((res) => {
                                this.isLoading = false;
                                this.customers.data.find((u) => u.id == res.data.id).phone = res.data
                                    .phone;
                                this.phoneUpdateSuccess = true;
                            }).catch((error) => {
                                this.isLoading = false;
                                this.phoneUpdateSuccess = false;
                                if (error.response) {
                                    if (error.response.status === 422) {
                                        this.errors = error.response.data.errors;
                                    } else if (error.response.status === 500) {
                                        alert(error.response.data.message);
                                    }
                                }
                            })
                        },

                        deleteCustomers() {
                            if (this.selected_users.length < 1) {
                                alert('No User is selected');
                                return;
                            }
                            if (!confirm(
                                    'Are you sure you want to delete this customer?\nThis action CANNOT be reversed!'
                                )) return;

                            console.log('fffff');
                            this.deletingCustomers = true;
                            this.error = null;
                            axios.post(deleteCustomersUrl, {
                                selected_users: this.selected_users
                            }).then((res) => {
                                this.deletingCustomers = false;
                                window.location.reload();
                            }).catch((error) => {
                                this.deletingCustomers = false
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500) {
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
                        createNote(user) {
                            this.newNote = {
                                user: user.name,
                                user_id: user.id,
                                content: '',
                                contacted_at: null,
                                contacted: true
                            }
                            this.userNotes = this.customers.data.find((u) => u.id == user.id)
                                .notes; // copy a reference of this users note
                        },
                        addNote() {
                            if (!this.newNote.contacted) {
                                this.newNote.contacted_at = null
                            }

                            this.addingNote = true;
                            axios.post(addNoteUrl, this.newNote).then((res) => {
                                this.addingNote = false;
                                console.log(res.data);
                                this.customers.data.find((u) => u.id == res.data.user_id).notes.unshift(
                                    res.data); // add to notes to increase count
                            }).catch((error) => {
                                this.addingNote = false;
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                } else if (error.response.status === 500) {
                                    alert(error.response.data.message);
                                }
                            })
                        },
                        submitForm() {
                            this.isLoading = true;
                            this.errors = null;
                            this.reg_success = false;
                            axios.post(saveUserUrl, this.data).then((res) => {
                                this.isLoading = false;
                                this.reg_success = true;
                                window.location.reload();
                            }).catch((error) => {
                                this.isLoading = false
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors;
                                }
                            })
                        },
                    },

                    computed: {
                        validationErrors() {
                            let errors = Object.values(this.errors);
                            errors = errors.flat();
                            return errors;
                        },

                    },

                    watch: {
                        selectAllUsers: function(newValue, oldValue) {
                            if (newValue > oldValue) {
                                this.selected_users = []
                                this.customers.data.forEach((user) => {
                                    this.selected_users.push(user.id);
                                });

                            } else {
                                this.selected_users = []
                            }
                        },

                    }
                })
            })
        </script>
    @endsection
