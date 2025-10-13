@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($currency->name) ? $currency->name : 'Currency' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('currencies.currency.destroy', $currency->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('currencies.currency.index') }}" class="btn btn-primary" title="Show All Currency">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('currencies.currency.create') }}" class="btn btn-success" title="Create New Currency">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('currencies.currency.edit', $currency->id ) }}" class="btn btn-primary" title="Edit Currency">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Currency" onclick="return confirm(&quot;Click Ok to delete Currency.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $currency->name }}</dd>
            <dt>Sign</dt>
            <dd>{{ $currency->sign }}</dd>
            <dt>Code</dt>
            <dd>{{ $currency->code }}</dd>

        </dl>

    </div>
</div>

@endsection