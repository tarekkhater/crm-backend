@extends('admin.layouts.admin-app')

@section('style')
@include('admin.partials.dt-css')
    <style>
        .dataTables_paginate {
            display: none;
        }
        .dropdown-menu {
            min-width : 100%
        }
        .dataTables_info {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="br-mainpanel">

        <div class="br-pagebody">
            @if (check_permission('import-leads'))

            <div class="collapse mb-2" id="importLead" >
                <div class="br-section-wrapper">
                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Import Lead</h6>
                    <p class="text-danger">Only .csv files are accepted, remember to user the defined format below</p>
                    <p>CSV heading : [email,phone,first_name,last_name,source,country,address]</p>

                    <form method="POST" action="{{ route('admin.import.leads') }}"  class="restaurant-info-form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="mb-1">
                            <label class="form-label" for="name">Select a csv file</label>
                            <input type="file" name="import_file" class="form-control" id="name" placeholder="CSV" required>
                            {!! $errors->first('import_file', '<p class="help-block">:message</p>') !!}
                        </div>
                        <p>Import csv of leads</p>
                        <div class="d-grid mb-1">
                            <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Import </button>
                            <a href="{{ asset('csv/lead_import.csv') }}" class="btn btn-warning waves-effect waves-float waves-light">Download Template </a>
                        </div>
                    </form>

                </div>
            </div>
@endif
            @include('notification')

            <div id="content">
            <div class="br-section-wrapper mt-1">
                    @if (check_permission('update-leads'))
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">
{{--                    Lead Listing--}}
                    <button class="btn btn-success m-1" data-toggle="modal" data-target="#bulkAction"> Bulk Action </button>
                    @if (check_permission('assign-manager-leads'))
                    <div class="dropdown d-inline">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Assign Manager <i class="fa fa-spinner fa-spin" v-if="changingManager"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            @forelse ($managers as $manager)
                                <button class="dropdown-item" type="button" v-on:click="setManagerAs({{ $manager->id }})">{{ $manager->name }}</button>
                            @empty
                                <button class="dropdown-item" type="button">No Manager Avaialable </button>
                            @endforelse
                        </div>
                    </div>
                    @endif
                    @if (check_permission('create-leads'))
                        <button class="btn btn-danger m-1" data-toggle="modal" data-target="#add_lead" style="float: right">New Lead</button>
                    @endif
                    @if (check_permission('import-leads'))
                    <button class="btn btn-warning m-1" data-toggle="collapse" data-target="#importLead" aria-expanded="false" aria-controls="importLead" style="float: right"> Import Lead</button>
                   @endif
@if(check_permission('create-status-leads'))
                    <button class="btn btn-success m-1" data-toggle="modal" data-target="#createStatus" aria-expanded="false" aria-controls="createStatus" style="float: right"> Create Status</button>
                  @endif
                   @if(check_permission('create-source-leads'))
                    <button class="btn btn-info m-1" data-toggle="modal" data-target="#createSource" aria-expanded="false" aria-controls="createSource" style="float: right"> Create Source</button>
                    @endif
                </h6>
                @endif

            <div class="table-wrapper">
                <form action="{{ route('admin.users.leads') }}" method="get">
                    <div class="row mb-4">
                      <div class="col-2">
                        <select class="form-control" name="source">
                            <option value="" class="text-muted">Filter Source</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}" @if (Request::get('source') == $source->id ) selected @endif>{{ $source->name }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-2">
                        <select class="form-control" name="status">
                            <option value="" class="text-muted">Filter Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" @if (Request::get('status') == $status->id ) selected @endif>{{ $status->name }}</option>
                            @endforeach
                        </select>
                      </div>

                        @if (check_permission('assign-manager-leads'))
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
                          <a href="{{ route('admin.users.leads') }}" class="btn btn-danger"><em class="fa fa-times text-white"></em></a>
                      </div>
                    </div>
                  </form>

                <form action="" method="post">
                <input type="hidden" name="type" required>
                @csrf
                    <div class="table-responsive">
                <table class="table table-bordered table_dt  responsive ">
                <thead>
                    <tr>
{{--                        @if (auth()->user()->hasRole(['superadmin','admin']))--}}
                        <th class="wd-2p">
                            <input type="checkbox" v-model="selectAllUsers"/>
                        </th>
{{--                        @endif--}}
                    <th>ID</th>
                        <th class="w-25">Name</th>
                    <th  class="w-25">Email</th>
                        <th class="w-25">Source </th>
                    <th width="30%" class="wd-15p-force">Status</th>
                        <th class="w-25">Phone</th>


                            <th class="w-25">Manager</th>



                    {{-- @if (auth()->user()->hasRole('superadmin,'admin'))
                            <th class="w-25">Actions</th>
                        @endif --}}
                        <th class="w-25">Date Added</th>
                    </tr>

                </thead>
                <tbody>

                    @php
                        $count = 1;
                    @endphp
                    @forelse ($users as $user)
                        <tr v-cloak>
                            <td width="2">
                                <input type="checkbox" value="{{ $user->id }}" v-model="selected_users"/>
                            </td>
                            <td>{{ $user->id }}</td>
                            <td><a class="text-capitalize" href="#">{{ $user->name }}</a> <br />
                            </td>
{{--                            <td><img src="{{ $user->avatar }}" height="50px" width="50px"></td>--}}
                            <td  width="5px"><span style="font-size: 1.1em"> {{ $user->email }}</span> <br />
                                <a href="#" data-toggle="modal" data-target="#notes" v-on:click="createNote({{ $user }})">Notes (@{{ noteCount({!! $user->id !!}) }})</a>
                            </td>


                            <td>

                                {{--                                @if($user->sourcerecord) {{ $user->sourcerecord->name }} @else NA @endif--}}


                                @if (check_permission('create-source-leads'))
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle full-width" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                            <span ref="changingSource{{ $user->id }}"></span> @{{showSource({!! $user->id !!})}}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @foreach ($sources as $source)
                                            <li class="dropdown-item" v-on:click="updateSource({{ $user->id }}, {{ $source->id }})" style="cursor: pointer;">{{ $source->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                @else
                                    @{{showSource({!! $user->id !!})}}
                                @endif

                            </td>

                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle full-width" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                        <span ref="changingStatus{{ $user->id }}"></span> @{{showStatus({!! $user->id !!})}}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        @foreach ($statuses as $status)
                                       {{-- <a class="dropdown-item" href="{{ route('admin.set.status', array_merge([$status->id, $user->id], request()->query())) }}">{{ $status->name }}</a> --}}
                                       <li class="dropdown-item" v-on:click="updateStatus({{ $user->id }}, {{ $status->id }})" style="cursor: pointer;">{{ $status->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                            <td>
                                <a  href="tel:{{ $user->phone }}"> @{{showPhone({!! $user->id !!})}}
                                    @if (check_permission('update-leads'))
                                        <a href="#" data-toggle="modal" data-target="#changePhone" v-on:click="setPhoneUpdateData({{ $user }})"><i class="fa fa-edit"></i> </a>
                                    @endif
                                </a>

                                <br/>
                                @if ($user->country)
                                    {{ $user->country }} <em><sub>{{ $user->phone_code }}</sub></em>
                                @endif
                            </td>

                            @if (check_permission('assign-manager-leads'))
                                <td class="text-capitalize">@if($user->manager) {{ $user->manager->name }} @else NA @endif</td>
                            @endif



                                @if (check_permission('users-delete'))
                                <td>
                                    <button data-toggle="modal" data-target="#convert{{ $user->id }}" type="button" class="btn btn-danger" >Delete</button>
                                </td>
                            @endif
                            <td>
                                {{ $user->created_at  }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <i>No Record Found!</i>
                            </td>
                        </tr>
                    @endforelse



                </tbody>
                </table>
                    </div>

                    <div class="d-felx justify-content-center">

                        {{ $users->links() }}

                    </div>
            </form>
            </div><!-- table-wrapper -->



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
                            @if (check_permission('users-update'))
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
                            @endif
                                    {{-- <div class="row">
                                        <label class="col-sm-12 form-control-label">Email: <span class="tx-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input  type="email" placeholder="Email" class="form-control" v-model="data.email" required autocomplete="email">
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="status">Change Status</label>
                                                <select class="form-control" id="status" v-model="bulkAction.status">
                                                    <option value="">Select Status</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if (check_permission('create-source-leads'))
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="source">Change Source</label>
                                                    <select class="form-control" id="source" v-model="bulkAction.source">
                                                        <option value="">Select Source</option>
                                                        @foreach ($sources as $source)
                                                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="form-group form-check">
                                        <label class="form-check-label">
                                          <input class="form-check-input" type="checkbox" value="customer" v-model="bulkAction.convertTo"> Convert To Customer
                                        </label>
                                      </div>
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

                    @foreach ($users as $item)

                        <div class="modal fade" id="changePhone{{$item->id}}" tabindex="0" aria-labelledby="changePhone{{$item->id}}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationLabel text-capitalise">Update {{ $item->name }} Phone</h5>
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
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mg-t-20">
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

                    @endforeach
                        @if (check_permission('create-leads'))
                    <div class="modal fade" id="add_lead" tabindex="0" aria-labelledby="AddLead" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationLabel">New Lead</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="#" @submit.prevent="submitForm">
                                    <div class="modal-body">
                                        <div class="row">
                                                <div v-if="errors" class="col-12">
                                                    <ul class="alert alert-danger">
                                                        <li class="alert alert-danger" v-for="(value, key, index) in validationErrors">@{{ value }}</li>
                                                    </ul>
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Email: <span class="tx-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <input  type="email" placeholder="Email" class="form-control" v-model="data.email" required autocomplete="email">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">First Name: <span class="tx-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <input  v-model="data.first_name" type="text" placeholder="First Name" class="form-control" name="first_name" required autocomplete="first_name">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Last Name: </label>
                                                    <div class="col-sm-12">
                                                        <input  v-model="data.last_name" type="text" placeholder="Last Name" class="form-control"  autocomplete="last_name">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">User Name: </label>
                                                    <div class="col-sm-12">
                                                        <input  v-model="data.username" type="text" placeholder="User Name" class="form-control" required  autocomplete="username">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Source :</label>

                                                    <div class="col-sm-12">

                                                        <select required class="form-control" id="source" v-model="data.source">
                                                            <option value="">Select Source</option>
                                                            @foreach ($sources as $source)
                                                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Status :</label>

                                                    <div class="col-sm-12">

                                                        <select required class="form-control" id="status" v-model="data.status">
                                                            <option value="">Select Status</option>
                                                            @foreach ($statuses as $status)
                                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-6 mg-t-20">
                                                <div class="row">
                                                <label class="col-sm-12 form-control-label">Country:  <span class="tx-danger">*</span></label>
                                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">
                                                    <select  v-model="data.country" @change="endpoint('{{ route('get-country-info','') }}',this.value)" name="country" class="form-control @error('country') is-invalid @enderror" autocomplete="email" required autofocus>
                                                        <option value="" >Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->nicename ?? '' }}">{{ $country->nicename ?? ''}}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('country')
                                                        <span class="invalid-feedback" role="alert">
                                                            <small><strong>{{ $message }}</strong></small>
                                                        </span>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>

                                            <div class="col-6 mg-t-20">
                                            <div class="row">
                                                <label class="col-sm-12 form-control-label">Mobile Number:  <span class="tx-danger">*</span></label>
                                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">

                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend" style="backgroud-color:#e9ecef;">

                                                                <img id="flag-icon" src="{{ asset('flags/ddd.png') }}" width="32" height="22" style="margin-top:7px;margin-right:4px; backgroud-color:#e9ecef;"/>

                                                            </div>
                                                            <input v-model="data.phone_code" name="phone_code" id="phone_code"  style="width:80px;"  required />

                                                            <div class="input-group-append">
                                                                <input id="phone" class="form-control" v-model="data.phone" type="text" placeholder="Phone number" class=" @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus style="width:100%; height:40px;">
                                                            </div>
                                                        </div>


                                                        @error('phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <small><strong>{{ $message }}</strong></small>
                                                        </span>
                                                        @enderror

                                                </div>
                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" :disabled="isLoading" class="btn btn-sm btn-danger" id="submitBtn"  >Save Lead</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
@endif
                        @if(check_permission('create-status-leads'))
                    <div class="modal fade" id="createStatus" tabindex="0" aria-labelledby="CreateStatus" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationLabel">New Status</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="#" @submit.prevent="createStatus">
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
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Name: <span class="tx-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <input  type="text" placeholder="Status Name" class="form-control" v-model="status.name" required autocomplete="name">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" :disabled="isLoading" class="btn btn-sm btn-danger" id="submitBtn">Create Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                        @endif
                        @if(check_permission('create-source-leads'))

                    <div class="modal fade" id="createSource" tabindex="0" aria-labelledby="CreateSource" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationLabel">New Source</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="#" @submit.prevent="createSource">
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
                                                <div class="row">
                                                    <label class="col-sm-12 form-control-label">Name: <span class="tx-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <input  type="text" placeholder="Source Name" class="form-control" v-model="source.name" required autocomplete="name">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" :disabled="isLoading" class="btn btn-sm btn-danger" id="submitBtn">Create Source</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
@endif
                    <div class="modal fade" id="notes" tabindex="0" aria-labelledby="Notes" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationLabel">Notes - @{{newNote.user}}</h5>
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
</div>
@endsection

@section('js')

    @include('admin.partials.dt-js')


            <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>




            <script>

                let bulkActionUrl = "{{ route('admin.users.bulkaction') }}";

                let saveUserUrl = "{{ route('admin.lead.store') }}";
                let createStatusUrl = "{{ route('admin.lead.storeStatus') }}";
                let changeManagerUrl = "{{ route('admin.lead.updateManager') }}";

                let createSourceUrl = "{{ route('admin.lead.storeSource') }}";

                let addNoteUrl = "{{ route('admin.lead.addNote') }}";
                let updatePhoneUrl = "{{ route('admin.lead.updatePhone') }}";

                document.addEventListener('DOMContentLoaded', function () {
                    new Vue({
                        el: '#content',
                        data() {
                            return {
                                bulkAction: {
                                    delete: false,
                                    status: '',
                                    source: '',
                                    convertTo: null,
                                    admin_password: ''
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
                                    status : '',
                                    phone_code : '',
                                    last_name : '',
                                    email : '',
                                    username : '',
                                    manager_id : '',
                                    country : ''
                                },
                                //
                                selected_users: [],
                                // change to customer
                                changingToCustomer: false,
                                // status
                                status: {
                                    name: '',
                                },
                                // source
                                source: {
                                    name: '',
                                },
                                changeManagerData: {
                                    manager_id: '',
                                },
                                changingManager: false,
                                // notes
                                newNote: {
                                    user: '',
                                    user_id: '',
                                    content: '',
                                    contacted_at: null,
                                    contacted: true
                                },
                                addingNote: false,
                                userNotes: [],
                                leads: {!! json_encode($users) !!},
                                // phone update
                                updatePhoneData: {
                                    phone: '',
                                },
                                phoneUpdateSuccess: false,
                            }
                        },
                        mounted() {
                            // this.getAll Trades();
                        },
                        methods: {
                             endpoint(url){
                                 let th = this;
                        axios.get(url+'/'+this.data.country, {
                            c_name : this.data.country
                        })
                            .then(function (response) {
                                console.log(response.data.phonecode)
                                th.data.phone_code = "+"+response.data.phonecode;
                                // document.getElementById('phone_code').innerHTML = "+"+response.data.phonecode;
                                document.getElementById('flag-icon').src =  response.data.iso;

                                // console.log(response.data.iso)

                            }).catch(function (error) {
                            toastr.error('An error occuied, Please try again', 'Failed',{
                                "positionClass" : "toast-top-right"
                            })
                        });
                    },
                    updateStatus(userId, statusId) {
                        this.$refs[`changingStatus${userId}`].innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

                        axios.post(bulkActionUrl, {selected_users: [userId], status: statusId, direct: 'yes'}).then((res)=>{
                            this.leads.data.find((u) => u.id == userId).statusrecord = res.data[0].statusrecord;

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

                        axios.post(bulkActionUrl, {selected_users: [userId], source: sourceId, direct: 'yes'}).then((res)=>{
                            this.leads.data.find((u) => u.id == userId).sourcerecord = res.data[0].sourcerecord;

                            this.$refs[`changingSource${userId}`].innerHTML = '';
                            toastr.success('Source Updated!', 'Success', {
                                "positionClass" : "toast-top-right"
                            })
                        }).catch((error)=>{
                            this.$refs[`changingSource${userId}`].innerHTML = '';
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
                    submitBulkAction() {
                                if (this.selected_users.length < 1) { alert('No User is selected'); return; }
                                if(!confirm('Are you sure you want to apply the selected changes?')) return ;

                                if (this.bulkAction.convertTo) this.bulkAction.convertTo = 'user'
                                this.bulkAction.selected_users = this.selected_users

                                this.isLoading = true;
                                this.error = null;
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
                                this.errors = null;
                                this.phoneUpdateSuccess = false;
                                axios.post(updatePhoneUrl, this.updatePhoneData).then((res)=>{
                                    this.isLoading = false;
                                    this.leads.data.find((u) => u.id == res.data.id).phone = res.data.phone;
                                    this.phoneUpdateSuccess = true;
                                    // window.location.reload();
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
                            noteCount(userId) {
                                return this.leads.data.find((user) => user.id === userId).notes.length;
                            },
                            showPhone(userId) {
                                return this.leads.data.find((user) => user.id === userId).phone;
                            },
                            showStatus(userId) {
                                var statusrecord = this.leads.data.find((user) => user.id == userId).statusrecord;
                                return statusrecord != null ? statusrecord.name : 'NA';
                            },
                            showSource(userId) {
                                var sourcerecord = this.leads.data.find((user) => user.id == userId).sourcerecord;
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
                                this.userNotes = this.leads.data.find((u) => u.id == user.id).notes; // copy a reference of this users note
                            },
                            addNote() {
                                if (!this.newNote.contacted) {
                                    this.newNote.contacted_at = null
                                }

                                this.addingNote = true;
                                axios.post(addNoteUrl, this.newNote).then((res) => {
                                    this.addingNote = false;
                                    console.log(res.data);
                                    this.leads.data.find((u) => u.id == res.data.user_id).notes.unshift(res.data); // add to notes to increase count
                                }).catch((error) =>{
                                    this.addingNote = false;
                                    if (error.response.status === 422) {
                                        this.errors = error.response.data.errors;
                                    } else if (error.response.status === 500){
                                        alert(error.response.data.message);
                                    }
                                })
                            },
                            createStatus(){
                                this.isLoading = true;
                                this.errors = null;
                                // this.reg_success = false;
                                axios.post(createStatusUrl, this.status).then((res)=>{
                                    this.isLoading = false;
                                    // this.reg_success = true;
                                    window.location.reload();
                                }).catch((error)=>{
                                    this.isLoading = false
                                    if (error.response.status === 422){
                                        this.errors = error.response.data.errors;
                                    }
                                })
                            },
                            setManagerAs (id) {
                                if (!confirm('Are you sure you want to perform this actions')) return;
                                if (this.selected_users.length < 1) { alert('No User is selected'); return; }

                                this.changeManagerData.manager_id = id;
                                this.changeManagerData.selected_users = this.selected_users // attach the selected users
                                this.changingManager = true;
                                axios.post(changeManagerUrl, this.changeManagerData).then((res) => {
                                    this.changingManager = false;
                                    window.location.reload();
                                }).catch((error) => {
                                    this.changingManager = false;
                                    if (error.response.status === 422){
                                        this.errors = error.response.data.errors;
                                        // alert(error.response.data.errors[0]);
                                    } else if (error.response.status === 500){
                                        alert(error.response.data.message);
                                    }
                                })
                            },
                            createSource(){
                                this.isLoading = true;
                                this.errors = null;
                                // this.reg_success = false;
                                axios.post(createSourceUrl, this.source).then((res)=>{
                                    this.isLoading = false;
                                    // this.reg_success = true;
                                    window.location.reload();
                                }).catch((error)=>{
                                    this.isLoading = false
                                    if (error.response.status === 422){
                                        this.errors = error.response.data.errors;
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
                                    this.leads.data.forEach((user) => {
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
