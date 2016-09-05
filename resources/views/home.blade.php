@extends('layouts.app')

@section('content')



<div class="container" id="main-page-logo-box">
    <video playsinline autoplay loop id="bgvid">
        <source src="{{asset('video/background.mp4')}}" type="video/mp4">
    </video>
    <img src="{{asset('images/logo-main.png')}}">
</div>
@endsection
