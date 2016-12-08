@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="PostController" ng-init="postsInit({{Auth::user()->id}})">
        <div class="row">
            <div class="col-md-2 board-sidebar">
                @include('chunks.board_sidebar')
            </div>

            <div class="col-md-6 col-md-offset-4">

                <add-post user-id="{{Auth::user()->id}}"></add-post>

                <div infinite-scroll="loadMorePosts()">
                    <post userid="{{Auth::user()->id}}" post="post" isowner="{{Auth::user()->id}} == post.author_user_id" ng-repeat="post in posts"></post>
                </div>

                <div ng-if="loadingMorePosts" style="text-align: center">
                    <h1 class="animated infinite pulse"><i class="fa fa-download" aria-hidden="true"></i> Pobieranie postów...</h1>
                </div>

                <div style="text-align: center;"><hr><p>Posty się skończyły :(</p></div>
            </div>
        </div>
    </div>
@endsection
