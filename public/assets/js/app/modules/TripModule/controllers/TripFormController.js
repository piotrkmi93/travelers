(function(){
    'use strict';

    angular.module('TripModule')
        .controller('TripFormController', ['$scope', 'TripService', '$interval', 'CitySearchService', 'PlaceService', function($scope, TripService, $interval, CitySearchService, PlaceService){

            // variables
            $scope.phrases = {
                city: undefined,
                place: undefined,
                user: undefined
            };

            $scope.citySelected = undefined;
            $scope.placeSelected = undefined;
            $scope.places = [];
            $scope.users = [];
            $scope.cities = [];

            $scope.trip = {
                name: undefined,
                slug: undefined,

                start_time: undefined,
                start_date: undefined,
                end_time: undefined,
                end_date: undefined,

                start_marker: {
                    id: 0,
                    coords: {
                        latitude: 53.5775,
                        longitude: 23.106111
                    },
                    options: { draggable: true },
                    events: {
                        dragend: function (marker, eventName, args) {
                            var lat = marker.getPosition().lat();
                            var lon = marker.getPosition().lng();

                            $scope.trip.start_marker.options = {
                                draggable: true,
                                labelAnchor: "100 0",
                                labelClass: "marker-labels"
                            };
                        }
                    }
                },
                end_marker: undefined,
                start_map: {
                    center: {
                        latitude: 53.5775,
                        longitude: 23.106111
                    },
                    zoom: 10
                },
                end_map: undefined,

                places: []
            };

            $scope.slugExists = undefined;
            $scope.sameAddress = true;

            // functions

            function setUserGeolocation(){
                var interval = $interval(function () {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position){
                            $interval.cancel(interval);
                            $scope.$apply(function(){
                                $scope.trip.start_marker.coords.latitude = position.coords.latitude;
                                $scope.trip.start_marker.coords.longitude = position.coords.longitude;
                                $scope.trip.start_map.center.latitude = position.coords.latitude;
                                $scope.trip.start_map.center.longitude = position.coords.longitude;
                            });
                        });
                    } else {
                        $interval.cancel(interval);
                        console.log('Twoja przeglądarka nie obsługuje funckji udostepniania geolokalizacji');
                    }
                }, 100);
            }

            $scope.isSlugExists = function(){
                TripService.exists($scope.trip.slug)
                    .then(function(data){
                        $scope.slugExists = data.exists;
                    });
            };

            $scope.selectCity = function(name, id){
                $scope.phrases.city = name;
                $scope.citySelected = {
                    id: id,
                    name: name
                }
            };

            $scope.selectPlace = function(name, id){
                $scope.phrases.place = name;
                $scope.placeSelected = true;
                $scope.trip.places.push({
                    id: id,
                    name: name,
                    position: $scope.trip.places.length,
                    date: undefined,
                    start: undefined,
                    end: undefined
                });
            };

            // watchers

            $scope.$watch(function(){
                return $scope.phrases.city;
            }, function(n,o){
                if($scope.phrases.city){
                    $scope.citySelected = false;
                    CitySearchService.getCities($scope.phrases.city, $scope.trip.start_marker.coords.latitude, $scope.trip.start_marker.coords.longitude)
                        .then(function(cities){
                            $scope.cities = cities;
                        });
                }
            });

            $scope.$watch(function(){
                return $scope.phrases.place;
            },function(n,o){
                if($scope.phrases.place && $scope.citySelected){
                    $scope.placeSelected = false;
                    PlaceService.getByPhraseAndCityId($scope.phrases.place,  $scope.citySelected.id)
                        .then(function(data){
                            $scope.places = data.places;
                        });
                }
            });

            $scope.$watch(function(){
                return $scope.trip.name;
            }, function(n,o){
                if (n) {
                    $scope.trip.slug = n.replace(/ć/g, 'c').replace(/Ć/g, 'C')
                        .replace(/ę/g, 'e').replace(/Ę/g, 'E')
                        .replace(/ł/g, 'l').replace(/Ł/g, 'L')
                        .replace(/ń/g, 'n').replace(/Ń/g, 'N')
                        .replace(/ó/g, 'o').replace(/Ó/g, 'O')
                        .replace(/ś/g, 's').replace(/Ś/g, 'S')
                        .replace(/ż/g, 'z').replace(/Ż/g, 'Z')
                        .replace(/ź/g, 'z').replace(/Ź/g, 'Z')
                        .replace(/[^a-zA-Z0-9| ]/g, '')
                        .replace(/ {1,}/g, '-')
                        .toLowerCase();
                } else $scope.trip.slug = '';
            });

            $scope.$watch(function(){
                return $scope.sameAddress;
            }, function(n,o){
                if(n){

                    $scope.trip.end_marker = undefined;
                    $scope.trip.end_map = undefined;

                } else {
                    $scope.trip.end_marker = {
                        id: 0,
                        coords: {
                            latitude: $scope.trip.start_marker.coords.latitude,
                            longitude: $scope.trip.start_marker.coords.longitude
                        },
                        options: { draggable: true },
                        events: {
                            dragend: function (marker, eventName, args) {
                                var lat = marker.getPosition().lat();
                                var lon = marker.getPosition().lng();

                                $scope.trip.end_marker.options = {
                                    draggable: true,
                                    labelAnchor: "100 0",
                                    labelClass: "marker-labels"
                                };
                            }
                        }
                    };

                    $scope.trip.end_map = {
                        center: {
                            latitude: $scope.trip.start_map.center.latitude,
                            longitude: $scope.trip.start_map.center.longitude
                        },
                        zoom: 10
                    };
                }
            });

            // init

            $scope.init = function(){
                setUserGeolocation();
            }
        }]);
})();