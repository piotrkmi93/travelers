@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="TripFormController" ng-init="init()" id="trip-form">
        <form>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 class="pull-left">
                                @if(isset($trip))
                                    Edycja wycieczki "{{ $trip -> name }}
                                @else
                                    Dodawanie wycieczki
                                @endif
                            </h4>

                            <div class="btn-group pull-right">
                                @if(isset($trip))
                                    <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Edytuj</button>
                                @else
                                    <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Dodaj</button>
                                @endif
                                <a href="{{ url('/') }}" class="btn btn-lg btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Anuluj</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Podstawowe informacje o wycieczce</h4>

                            @if(!isset($trip))
                                <div class="col-sm-12">
                                    <label for="name">Nazwa:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Wpisz nazwę wycieczki..." ng-model="trip.name" ng-blur="isSlugExists()" required>
                                    <p ng-show="slugExists == true" class="pull-right" style="color:red">Taka nazwa już istnieje, proszę wpisać inną</p>
                                    <p ng-show="slugExists == false" class="pull-right" style="color:green">Nazwa dostępna</p>
                                </div>

                                <div class="col-sm-12">
                                    <label for="slug">Przyjazny link:</label>
                                    <input type="text" name="slug" id="slug" ng-model="trip.slug" required style="display:none;">
                                    <input type="text" class="form-control" placeholder="Wpisz nazwę aby zobaczyć wygenerowany link..." ng-model="trip.slug" disabled>
                                </div>
                            @endif

                            <div class="col-sm-12">
                                <label for="description">Opis:</label>
                                <textarea name="description" id="description" class="form-control" required>@if(isset($trip)){{ $trip -> description }}@else Opisz wycieczkę...@endif</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Dodaj punkt wycieczki</h4>

                            <div class="col-sm-6">
                                <label>Miasto:</label>
                                <input class="form-control" type="text" maxlength="255" ng-model="phrases.city" autocomplete="off">
                                <div ng-if="!citySelected" id="city-select">
                                    <ul>
                                        <li ng-click="selectCity(city.name, city.id)" ng-repeat="city in cities" style="cursor:pointer"><% city.name %></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-6" ng-if="citySelected">
                                <label>Miejsce:</label>
                                <input class="form-control" type="text" maxlength="255" ng-model="phrases.place" autocomplete="off">
                                <div ng-if="!placeSelected" id="city-select">
                                    <ul>
                                        <li ng-click="selectPlace(place.name, place.id)" ng-repeat="place in places" style="cursor:pointer"><% place.name %></li>
                                    </ul>
                                </div>
                            </div>

                            <h4 ng-if="trip.places.length">Wybrane punkty wycieczki</h4>

                            <div class="panel panel-default" ng-repeat="place in trip.places">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <strong><% place.name %></strong>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Data odwiedzin</label>
                                        <input class="form-control" type="date" ng-model="place.date">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Godzina rozpoczęcia odwiedzin</label>
                                        <input class="form-control" type="time" ng-model="place.start">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Godzina zakończenia odwiedzin</label>
                                        <input class="form-control" type="time" ng-model="place.end">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Czas i miejsce rozpoczęcia oraz zakończenia wycieczki</h4>

                            <div class="col-sm-6">
                                <label for="start_date">Data rozpoczęcia wycieczki:</label>
                                <input id="start_date" class="form-control" type="date" name="start_date" value="<% trip.start_date %>">
                                <label for="start_time">Godzina:</label>
                                <input id="start_time" class="form-control" type="time" name="start_time" value="<% trip.start_time %>">
                            </div>

                            <div class="col-sm-6">
                                <label for="start_date">Data zakończenia wycieczki:</label>
                                <input id="start_date" class="form-control" type="date" name="start_date" value="<% trip.start_date %>">
                                <label for="start_time">Godzina:</label>
                                <input id="start_time" class="form-control" type="time" name="start_time" value="<% trip.start_time %>">
                            </div>


                            <div class="col-sm-12">
                                <label for="start_address">Miejsce rozpoczęcia wycieczki:</label>
                                <input id="start_address" name="start_address" type="text" maxlength="255" class="form-control" placeholder="Wpisz adres rozpoczęcia wycieczki...">
                            </div>
                            <div class="col-sm-6">
                                <label for="start_latitude">Szerokość geograficzna:</label>
                                <input class="form-control" type="number" ng-model="trip.start_marker.coords.latitude" disabled>
                                <input class="form-control" type="hidden" id="start_latitude" name="start_latitude" ng-model="trip.start_marker.coords.latitude">
                            </div>
                            <div class="col-sm-6">
                                <label for="start_longitude">Długość geograficzna:</label>
                                <input class="form-control" type="number" ng-model="trip.start_marker.coords.longitude" disabled>
                                <input class="form-control" type="hidden" id="start_longitude" name="start_longitude" ng-model="trip.start_marker.coords.longitude">
                            </div>
                            <div class="col-sm-12">
                                <ui-gmap-google-map center="trip.start_map.center" zoom="trip.start_map.zoom">
                                    <ui-gmap-marker coords="trip.start_marker.coords" options="trip.start_marker.options" events="trip.start_marker.events" idkey="trip.start_marker.id"></ui-gmap-marker>
                                </ui-gmap-google-map>
                            </div>


                            <div ng-if="!sameAddress">
                                <div class="col-sm-12">
                                    <label for="end_address">Miejsce zakończenia wycieczki:</label>
                                    <input id="end_address" name="end_address" type="text" maxlength="255" class="form-control" placeholder="Wpisz adres zakończenia zakończenia...">
                                </div>
                                <div class="col-sm-6">
                                    <label for="end_latitude">Szerokość geograficzna:</label>
                                    <input class="form-control" type="number" ng-model="trip.end_marker.coords.latitude" disabled>
                                    <input class="form-control" type="hidden" id="end_latitude" name="end_latitude" ng-model="trip.end_marker.coords.latitude">
                                </div>
                                <div class="col-sm-6">
                                    <label for="end_longitude">Długość geograficzna:</label>
                                    <input class="form-control" type="number" ng-model="trip.end_marker.coords.longitude" disabled>
                                    <input class="form-control" type="hidden" id="end_longitude" name="end_longitude" ng-model="trip.end_marker.coords.longitude">
                                </div>
                                <div class="col-sm-12">
                                    <ui-gmap-google-map center="trip.end_map.center" zoom="trip.end_map.zoom">
                                        <ui-gmap-marker coords="trip.end_marker.coords" options="trip.end_marker.options" events="trip.end_marker.events" idkey="trip.end_marker.id"></ui-gmap-marker>
                                    </ui-gmap-google-map>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" ng-model="sameAddress"> Takie samo miejsce zakończenia wycieczki</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
