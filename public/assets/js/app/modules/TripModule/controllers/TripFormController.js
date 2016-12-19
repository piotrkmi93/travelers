(function(){
    'use strict';

    angular.module('TripModule')
        .controller('TripFormController', ['$scope', 'TripService', '$interval', 'CitySearchService', 'PlaceService', '$timeout', 'UserFriendsService', function($scope, TripService, $interval, CitySearchService, PlaceService, $timeout, UserFriendsService){

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
            $scope.placesShow = false;
            $scope.citiesShow = false;
            $scope.usersShow = false;
            var user_id = undefined;

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

                places: [],
                users: []
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
                $scope.trip.places.push({
                    id: id,
                    name: name,
                    date: undefined,
                    start: undefined,
                    end: undefined
                });

                $scope.phrases.city = undefined;
                $scope.phrases.place = undefined;
                $scope.citySelected = undefined;
                $scope.placeSelected = undefined;
                $scope.cities = [];
                $scope.places = [];
            };

            $scope.selectUser = function(first_name, last_name, thumb_url, id){
                $scope.trip.users.push({
                    id: id,
                    first_name: first_name,
                    last_name: last_name,
                    thumb_url: thumb_url
                });

                $scope.phrases.user = undefined;
                $scope.users = [];
            };

            // watchers

            $scope.$watch(function(){
                return $scope.phrases.city;
            }, function(n,o){
                if($scope.phrases.city){
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
                    PlaceService.getByPhraseAndCityId($scope.phrases.place,  $scope.citySelected.id)
                        .then(function(data){
                            $scope.places = data.places;
                        }).finally(function(){
                            angular.forEach($scope.trip.places, function(tripPlace){
                                angular.forEach($scope.places, function(place){
                                    if(place.id == tripPlace.id){
                                        place.disabled = true;
                                    }
                                });
                            });
                    }).finally(function(){
                        console.log($scope.places);
                    });
                }
            });

            $scope.$watch(function(){
                return $scope.phrases.user;
            }, function(){
                if($scope.phrases.user && $scope.phrases.user != ''){
                    UserFriendsService.getFriendsByPhrase(user_id, $scope.phrases.user)
                        .then(function (friends) {
                            $scope.users = friends;
                        }).finally(function(){
                            angular.forEach($scope.trip.users, function(user){
                                angular.forEach($scope.users, function(friend){
                                    if(user.id == friend.id){
                                        friend.disabled = true;
                                    }
                                });
                            });
                            console.log($scope.users);
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

            $scope.init = function(uid){
                setUserGeolocation();
                user_id = uid;
            };

            $scope.cityFocus = function(){
                if($scope.citiesShow){
                    $timeout(function(){
                        $scope.citiesShow = !$scope.citiesShow;
                    }, 200);
                } else {
                    $scope.citiesShow = !$scope.citiesShow;
                }
            };

            $scope.placeFocus = function(){
                if($scope.placesShow){
                    $timeout(function(){
                        $scope.placesShow = !$scope.placesShow;
                    }, 200);
                } else {
                    $scope.placesShow = !$scope.placesShow;
                }
            };

            $scope.userFocus = function(){
                if($scope.usersShow){
                    $timeout(function(){
                        $scope.usersShow = !$scope.usersShow;
                    }, 200);
                } else {
                    $scope.usersShow = !$scope.usersShow;
                }
            };
        }]);
})();