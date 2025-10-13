@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => "Hello ". $data['user']->name,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <p> {{ $data['message'] }}</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Go to Dashboard',
        	'link' => route('backend.dashboard')
    ])

@stop
