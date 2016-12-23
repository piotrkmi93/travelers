@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="PlaceFormController" @if(isset($place)) ng-init="placeFormInit({{"'".$images."'"}}, {{ $place->latitude }}, {{ $place->longitude }}, {{ "'".$city->name."'" }}, {{ $city->id }})" @else ng-init="placeFormInit(undefined,undefined,undefined, undefined, undefined)" @endif>
        <form method="POST" enctype="multipart/form-data" action="{{ url('places/save') }}">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 class="pull-left">
                                @if( isset($place) )
                                    Edycja miejsca "{{ $place->name }}"
                                @else
                                    Dodawanie miejsca
                                @endif
                            </h4>

                            <div class="btn-group pull-right">
                                @if( isset($place) )
                                    <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check" aria-hidden="true" ng-disabled="slugExists == true"></i> Edytuj</button>
                                @else
                                    <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-plus" aria-hidden="true" ng-disabled="slugExists == true"></i> Dodaj</button>
                                @endif
                                <a href="{{ url('/') }}" class="btn btn-lg btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Anuluj</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 class="pull-left">Podstawowe dane</h4>
                            <hr style="clear: both">

                            @if(!isset($place))
                            <div class="form-group">
                                <label for="name">Nazwa:</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Wpisz nazwę miejsca..." ng-model="place.name" ng-blur="isSlugExists()" required>
                                <p ng-show="slugExists == true" class="pull-right" style="color:red">Taka nazwa już istnieje, proszę wpisać inną</p>
                                <p ng-show="slugExists == false" class="pull-right" style="color:green">Nazwa dostępna</p>
                            </div>

                            <div class="form-group">
                                <label for="slug">Przyjazny link:</label>
                                <input type="text" name="slug" id="slug" ng-model="place.slug" required style="display:none;">
                                <input type="text" class="form-control" placeholder="Wpisz nazwę aby zobaczyć wygenerowany link..." ng-model="place.slug" disabled>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="city">Miasto:</label>
                                <input autocomplete="off" type="text" id="city" class="form-control" name="city" ng-model="phrase" ng-focus="focus()" ng-blur="focus()" required>

                                <div ng-if="show && cities.length" id="city-select">
                                    <ul>
                                        <li ng-click="selectCity(city.name, city.id)" ng-repeat="city in cities" style="cursor:pointer"><% city.name %> <small><% city.distance %>km stąd</small></li>
                                    </ul>
                                </div>

                                <input type="hidden" name="city_id" value="<% cityIdSelected %>">
                            </div>

                            <div class="form-group">
                                <label for="place_type">Typ miejsca:</label>
                                <select name="place_type" id="place_type" class="form-control" required>
                                    <option value="attraction" @if(isset($place)&&$place->place_type=='attraction') selected @endif>Atrakcja turystyczna</option>
                                    <option value="accommodation" @if(isset($place)&&$place->place_type=='accommodation') selected @endif>Nocleg</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="short_description">Krótki opis:</label>
                                <textarea name="short_description" id="short_description" class="form-control" required>@if(isset($place)){{ $place -> short_description }}@else Opis widoczny w wyszukiwarce...@endif</textarea>
                            </div>

                            <div class="form-group">
                                <label for="long_description">Pełny opis:</label>
                                <textarea name="long_description" id="long_description" class="form-control" required>@if(isset($place)){{ $place -> long_description }}@else Pełny opis...@endif</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h4 class="pull-left">Dane kontaktowe <small>(opcjonalne)</small></h4>
                            <hr style="clear: both">

                            <div class="form-group">
                                <label for="phone">Numer telefonu:</label>
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Wpisz numer telefonu..." @if(isset($place)&&$place->phone) {{ $place->phone }} @endif>
                            </div>

                            <div class="form-group">
                                <label for="address">Dokładny adres:</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Wpisz dokładny adres..." @if(isset($place)&&$place->address) {{ $place->address }} @endif>
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Wpisz e-mail..." autocomplete="off" @if(isset($place)&&$place->email) {{ $place->email }} @endif>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-body">

                                <h4 class="pull-left">Miejsce na mapie</h4>
                                <hr style="clear: both">

                                <div class="form-group">
                                    <label for="latitude">Szerokość geograficzna:</label>
                                    <input type="hidden" name="latitude" id="latitude" value="<% marker.coords.latitude %>">
                                    <input type="text" class="form-control" placeholder="<% marker.coords.latitude %>" required disabled>
                                </div>

                                <div class="form-group">
                                    <label for="longitude">Długość geograficzna:</label>
                                    <input type="hidden" name="longitude" id="longitude" value="<% marker.coords.longitude %>">
                                    <input type="text" class="form-control" placeholder="<% marker.coords.longitude %>" required disabled>
                                </div>

                                <ui-gmap-google-map center="map.center" zoom="map.zoom">

                                    <ui-gmap-marker coords="marker.coords" options="marker.options" events="marker.events" idkey="marker.id"></ui-gmap-marker>

                                </ui-gmap-google-map>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body place-images-panel">

                            <h4 class="pull-left">Zdjęcia</h4>
                            <button ng-click="choosePlaceImages()" type="button" class="btn btn-success pull-right"><i class="fa fa-camera" aria-hidden="true"></i> Wybierz zdjęcia</button>
                            <input type="file" accept="image/*" name="images[]" multiple id="place-images">
                            <hr style="clear: both">

                            <div ng-repeat="image in images" class="image-preview">
                                <img src="<% image %>">
                            </div>

                            <div ng-show="imagesUploading" class="images-loading">
                                <h3>Wgrywanie zdjęć...</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
