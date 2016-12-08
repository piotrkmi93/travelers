@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="PlaceController" ng-init="placeInit({{ $images }}, {{ $place -> latitude }}, {{ $place -> longitude }}, {{ Auth::user()->id }}, {{ $place -> id }}, {{ $liked }}, {{ $likes }})">
        <div class="row">
            <div class="col-md-6 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>{{ $place -> name }} <small class="pull-right"><a ng-click="likeThis()" class="like" ng-class="{'like-liked': liked}"><i class="fa fa-heart"></i> Lubię to!</a> <small ng-if="likes">(<% likes %>)</small></small></h1>
                        <hr>
                        <p>{{ $place -> long_description }}</p>
                        @if($place -> phone && $place -> address && $place -> email) <hr> @endif
                        @if( $place -> phone ) <p><strong>Telefon: </strong>{{ $place -> phone }}</p>@endif
                        @if( $place -> address ) <p><strong>Adres: </strong>{{ $place -> address }}</p>@endif
                        @if( $place -> email ) <p><strong>E-mail: </strong>{{ $place -> email }}</p>@endif
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>Galeria</h3>
                        <hr>
                        <div class="content">
                            <ng-gallery images="images"></ng-gallery>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <ui-gmap-google-map center="map.center" zoom="map.zoom">
                        <ui-gmap-marker coords="marker.coords" options="marker.options" events="marker.events" idkey="marker.id"></ui-gmap-marker>
                    </ui-gmap-google-map>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Napisz komentarz</h4>
                        <add-comment placeid="place_id" userid="user_id" type="place"></add-comment>
                        <hr>
                        <comment ng-repeat="comment in comments" comment="comment" userid="user_id"></comment>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection