@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="TripFormController" ng-init="init({{ Auth::user()->id}}, {{ isset($tripJSON)?$tripJSON:'undefined' }})" id="trip-form">
        <form>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 class="pull-left">
                                @if(isset($trip))
                                    Edycja wycieczki "{{ $trip -> name }}"
                                @else
                                    Dodawanie wycieczki
                                @endif
                            </h4>

                            <div class="btn-group pull-right">
                                @if(isset($trip))
                                    <button ng-click="update()" type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Edytuj</button>
                                @else
                                    <button ng-click="submit()" type="submit" class="btn btn-lg btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Dodaj</button>
                                @endif
                                <a href="{{ url('/') }}" class="btn btn-lg btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Anuluj</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
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
                                <textarea name="description" id="description" class="form-control" ng-model="trip.description" required></textarea>
                                {{--@if(isset($trip)){{ $trip -> description }}@else Opisz wycieczkę...@endif--}}
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 ng-if="trip.places.length">Wybrane punkty wycieczki</h4>

                            <div class="places-selected" ng-style="{height: ((trip.places.length * 140) + 'px')}">
                                <div ng-style="{top: (($index * 140) + 'px')}" class="panel panel-default place-selected" ng-repeat="place in trip.places | orderBy:'start'">
                                    <div class="panel-body">
                                        <div class="col-sm-12">
                                            <h3 style="margin:0;"><i class="fa fa-map-marker"></i> <% place.name %><i class="fa fa-times pull-right" style="color: red; cursor:pointer;" ng-click="unselectPlace(place.id)"></i></h3>
                                        </div>

                                        <div class="col-sm-3">
                                            <label><i class="fa fa-calendar-o"></i> Od dnia:</label>
                                            <input class="form-control" type="date" ng-model="place.start">
                                        </div>
                                        <div class="col-sm-3">
                                            <label><i class="fa fa-clock-o"></i> Od godziny:</label>
                                            <input class="form-control" type="time" ng-model="place.start">
                                        </div>
                                        <div class="col-sm-3">
                                            <label><i class="fa fa-calendar-o"></i> Do dnia:</label>
                                            <input class="form-control" type="date" ng-model="place.end">
                                        </div>
                                        <div class="col-sm-3">
                                            <label><i class="fa fa-clock-o"></i> Do godziny:</label>
                                            <input class="form-control" type="time" ng-model="place.end">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>Dodaj punkt wycieczki</h4>

                            <div class="col-sm-6">
                                <label>Miasto:</label>

                                <input placeholder="Wyszukaj miasto..." class="form-control" type="text" maxlength="255" ng-model="phrases.city" autocomplete="off" ng-focus="cityFocus()" ng-blur="cityFocus()">

                                <div ng-if="citiesShow && cities.length" id="city-select">
                                    <ul>
                                        <li ng-click="selectCity(city.name, city.id)" ng-repeat="city in cities" style="cursor:pointer"><% city.name %></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-6" ng-if="citySelected">
                                <label>Miejsce:</label>

                                <input placeholder="Wyszukaj miejsce..." class="form-control" type="text" maxlength="255" ng-model="phrases.place" autocomplete="off" ng-focus="placeFocus()" ng-blur="placeFocus()">

                                <div ng-if="placesShow && places.length" id="place-select">
                                    <ul>
                                        <li ng-repeat="place in places">
                                            <strong ng-click="selectPlace(place.name, place.id)" style="cursor:pointer"><% place.name %></strong>
                                            <div ng-if="place.disabled" class="trip-place-search-disabled"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Zaproś uczestników wycieczki</h4>

                            <div class="col-sm-6">
                                <label>Użytkownik:</label>

                                <input placeholder="Wyszukaj użytkownika" class="form-control" type="text" maxlength="255" ng-model="phrases.user" autocomplete="off" ng-focus="userFocus()" ng-blur="userFocus()">

                                <div ng-if="usersShow && users.length" id="user-select">

                                    <div class="trip-user-search" ng-repeat="user in users">
                                        <img src="<% user.thumb_url %>" class="min-avatar">
                                        <strong ng-click="selectUser(user.first_name, user.last_name, user.thumb_url, user.id)"><% user.first_name %> <% user.last_name %> <small ng-if="user.disabled"><i class="fa fa-check" style="color: green;"></i></small></strong>
                                        <div ng-if="user.disabled" class="trip-user-search-disabled"></div>
                                    </div>

                                </div>
                            </div>

                            <div ng-if="trip.users.length" class="col-sm-6">
                                <h4>Zaproszeni użytkownicy</h4>

                                <div ng-repeat="user in trip.users">
                                    <img src="<% user.thumb_url %>" class="min-avatar">
                                    <strong><% user.first_name %> <% user.last_name %></strong>
                                    <i ng-if="user.deletable" class="fa fa-times pull-right" style="color: red; cursor:pointer;" ng-click="unselectUser(user.id)"></i>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4>Czas i miejsce rozpoczęcia oraz zakończenia wycieczki</h4>

                            <div class="col-sm-6">
                                <label for="start_date">Data rozpoczęcia wycieczki: <small ng-if="trip.errors.date" style="color:red;"><br>Data rozpoczęcia jest większa od daty zakończenia</small></label>
                                <input id="start_date" class="form-control" type="date" name="start_date" ng-model="trip.start_date">
                                <label for="start_time">Godzina:</label>
                                <input id="start_time" class="form-control" type="time" name="start_time" ng-model="trip.start_date">
                            </div>

                            <div class="col-sm-6">
                                <label for="end_date">Data zakończenia wycieczki: <small ng-if="trip.errors.date" style="color:red;"><br>Data zakończenia jest mniejsza od daty rozpoczęcia</small></label>
                                <input id="end_date" class="form-control" type="date" name="end_date" ng-model="trip.end_date">
                                <label for="end_time">Godzina:</label>
                                <input id="end_time" class="form-control" type="time" name="end_time" ng-model="trip.end_date">
                            </div>


                            <div class="col-sm-12">
                                <label for="start_address">Miejsce rozpoczęcia wycieczki:</label>
                                <input ng-model="trip.start_address" id="start_address" name="start_address" type="text" maxlength="255" class="form-control" placeholder="Wpisz adres rozpoczęcia wycieczki...">
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


                            <div ng-if="!trip.same_address">
                                <div class="col-sm-12">
                                    <label for="end_address">Miejsce zakończenia wycieczki:</label>
                                    <input ng-model="trip.end_address" id="end_address" name="end_address" type="text" maxlength="255" class="form-control" placeholder="Wpisz adres zakończenia wycieczki...">
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
                                        <label><input type="checkbox" ng-model="trip.same_address"> Takie samo miejsce zakończenia wycieczki</label>
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
