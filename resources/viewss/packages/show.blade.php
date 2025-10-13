@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($package->name) ? $package->name : 'Package' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('packages.package.destroy', $package->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('packages.package.index') }}" class="btn btn-primary" title="Show All Package">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('packages.package.create') }}" class="btn btn-success" title="Create New Package">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('packages.package.edit', $package->id ) }}" class="btn btn-primary" title="Edit Package">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Package" onclick="return confirm(&quot;Click Ok to delete Package.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $package->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $package->description }}</dd>
            <dt>Percent Profit</dt>
            <dd>{{ $package->percent_profit }}</dd>
            <dt>Period</dt>
            <dd>{{ $package->period }}</dd>
            <dt>Minimum Purchase</dt>
            <dd>{{ $package->minimum_purchase }}</dd>
            <dt>Maximum Purchase</dt>
            <dd>{{ $package->maximum_purchase }}</dd>
            <dt>Status</dt>
            <dd>{{ $package->status }}</dd>

        </dl>

    </div>
</div>

@endsection