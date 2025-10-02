@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => "Hello  Admin ",
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <p> {{ $msg }}</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Go Dashboard',
        	'link' => route('admin.dashboard')
    ])

@stop
