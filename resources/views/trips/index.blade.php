@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="TripController" ng-init="init({{ json_encode($users) }}, {{ json_encode($places) }}, {{ $trip->id }}, {{ Auth::user()->id }})">
        <div class="row">
            <div class="col-md-6 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <h1><i class="fa fa-map"></i> {{ $trip -> name }}</h1>
                        <h4><a href="{{ $users[count($users)-1]['url'] }}"><i class="fa fa-user"></i> {{ $users[count($users)-1]['name'] }}</a></h4>
                        <hr>
                        <p>{{ $trip -> description }}</p>
                    </div>
                </div>

                <div class="panel panel-default">

                    <div id="map_canvas">
                        <ui-gmap-google-map center="map.center" zoom="map.zoom" draggable="true" options="options" bounds="map.bounds">
                            <ui-gmap-polyline ng-repeat="p in polylines" path="p.path" stroke="p.stroke" visible='p.visible'
                                              geodesic='p.geodesic' fit="false" editable="p.editable" draggable="p.draggable" icons='p.icons'></ui-gmap-polyline>
                        </ui-gmap-google-map>
                    </div>

                    <div class="panel-body">
                        <h4>Punkty Wycieczki</h4>

                        <div class="panel panel-default" ng-repeat="place in places | orderBy:'start'">
                            <div class="panel-body">
                                <h3 style="margin:0;">
                                    <i ng-class="{'fa-dot-circle-o':$middle, 'fa-flag-o':$first, 'fa-flag-checkered':$last}" class="fa"></i>
                                    <span ng-if="!place.url"><% place.name %></span>
                                    <a href="<% place.url %>" ng-if="place.url"><% place.name %></a>
                                </h3>
                                <h5 class="trip-place-info">
                                    <i class="fa fa-calendar"></i> <% date(place.start) | date:'fullDate' %>
                                    <i class="fa fa-clock-o"></i> <% date(place.start) | date:'shortTime' %>
                                    <span ng-if="place.end">
                                        <i class="fa fa-arrow-right"></i>
                                        <i class="fa fa-calendar"></i> <% date(place.end) | date:'fullDate' %>
                                        <i class="fa fa-clock-o"></i> <% date(place.end) | date:'shortTime' %>
                                    </span>
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body trip-users">
                        <h4>Członkowie wycieczki</h4>

                        <a href="<% user.url %>" ng-repeat="user in users | orderBy : '-status'" tooltips tooltip-side="bottom" tooltip-template="<% user.name %>">
                            <img src="<% user.avatar %>">
                            <i class="fa" ng-class="{'fa-question':!user.status, 'fa-check':user.status}"></i>
                        </a>


                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Napisz komentarz</h4>
                        <add-comment tripid="trip_id" userid="user_id" type="trip"></add-comment>
                        <hr>
                        <comment ng-repeat="comment in comments" comment="comment" userid="user_id"></comment>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
