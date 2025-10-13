<div class="row">
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Account Bal</p>
                <h4>$ {{ Auth()->user()->total() }} </h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Withdrawable</p>
                <h4>$ {{ Auth()->user()->withdrawable }} </h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Bonus Bal</p>
                <h4>$ {{ Auth()->user()->bonus }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Account Plan</p>
                <h4>{{ auth()->user()->plan }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Account Status</p>
                @if (auth()->user()->is_active)
                    <h4 class="badge badge-success">Verified</h4>
                @else
                    <h4 class="badge badge-danger">Unverified</h4>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center pt-2">
            <div class="card-body">
                <p class="mb-1">Connections</p>
                @if (auth()->user()->code)
                <h6 style="color: red" class="tx-danger">Account not connected</h6>
                @else
                    <h6 style="color: #0ec469" class="tx-success">Account connected</h6>
                @endif
            </div>
        </div>
    </div>
</div>
