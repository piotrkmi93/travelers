@extends('layouts.app')

@section('content')

<div class="container" ng-controller="MessageController" ng-init="initMessages({{Auth::user()->id}})">
    <div class="row">
        <div class="col-xs-2 board-sidebar">
            <div class="row">
                <div class="col-md-12">
                    <input class="form-control" type="search" placeholder="Filtruj..." ng-model="search.phrase">
                </div>
            </div>

            <hr>

            <div class="row" ng-repeat="friend in friends | orderBy: 'is_active'">
                <div class="col-md-12">
                    <a href="#/<% friend.username %>">
                        <img src="<% friend.avatar_thumb %>" class="min-avatar pull-left" style="margin-right: 10px;">
                        <h4 class="pull-left"><% friend.name %></h4>
                        <small class="pull-right"><i class="fa fa-circle" ng-class="{'user-active': friend.is_active}" aria-hidden="true"></i></small>
                    </a>
                    <p class="last-message" ng-if="friend.last_message" ng-class="{'strong': !friend.last_message.is_read}"><% friend.last_message.text %></p>
                </div>
            </div>

        </div>

        <div class="col-xs-offset-4 col-xs-6">
            <ng-view></ng-view>
        </div>
    </div>
</div>



@endsection