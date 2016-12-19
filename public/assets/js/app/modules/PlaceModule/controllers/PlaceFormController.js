(function(){
    'use strict';

    angular.module('PlaceModule')
        .controller('PlaceFormController', ['$scope', '$interval', 'PlaceService', 'CitySearchService', '$timeout', function($scope, $interval, PlaceService, CitySearchService, $timeout){

            $scope.phrase = '';
            $scope.cities = [];
            $scope.citySelected = false;
            $scope.cityIdSelected = undefined;
            $scope.show = false;

            $scope.$watch(function(){
                return $scope.phrase;
            }, function (n,o) {
                if ($scope.phrase != '' && $scope.phrase != undefined){
                    // $scope.citySelected = false;
                    CitySearchService.getCities($scope.phrase, $scope.map.center.latitude, $scope.map.center.longitude)
                        .then(function (cities) {
                            $scope.cities = cities;
                        });
                }
            }, true);

            $scope.focus = function(){
                if($scope.show){
                    $timeout(function(){
                        $scope.show = false;
                    }, 200);
                } else {
                    $scope.show = true;
                }
            };

            $scope.selectCity = function(cityName, cityId){
                $scope.phrase = cityName;
                $scope.citySelected = true;
                $scope.cityIdSelected = cityId;
                console.log(cityId);
            };

            // variables

            var input = angular.element(document.querySelector('#place-images'));

            // $scope.place_images;

            $scope.images = [];

            $scope.imagesUploading = false;

            $scope.place = {
                name: '',
                slug: ''
            };

            $scope.map = {
                center: {
                    latitude: 53.5775,
                    longitude: 23.106111
                },
                zoom: 10
            };

            $scope.marker = {
                id: 0,
                coords: {
                    latitude: 53.5775,
                    longitude: 23.106111
                },
                options: { draggable: true },
                events: {
                    dragend: function (marker, eventName, args) {
                        console.log('marker dragend');
                        var lat = marker.getPosition().lat();
                        var lon = marker.getPosition().lng();
                        console.log(lat);
                        console.log(lon);

                        $scope.marker.options = {
                            draggable: true,
                            labelAnchor: "100 0",
                            labelClass: "marker-labels"
                        };
                    }
                }
            };

            $scope.slugExists = undefined;

            // public functions

            $scope.isSlugExists = function(){
                PlaceService.isSlugExists($scope.place.slug).then(function(exists){
                    $scope.slugExists = exists;
                });
            };

            $scope.placeFormInit = function(images, latitude, longitude, city_name, city_id){

                console.log(JSON.parse(images));

                $scope.selectCity(city_name, city_id);

                if(images){
                    console.log(images);
                    $scope.images = JSON.parse(images);
                }

                if(latitude && longitude){
                    $scope.map.center = {
                        latitude: latitude,
                        longitude: longitude
                    };

                    $scope.marker.coords = {
                        latitude: latitude,
                        longitude: longitude
                    };
                } else {
                    if(navigator.geolocation){
                        navigator.geolocation.getCurrentPosition(function(position){

                            $scope.$apply(function(){
                                $scope.map.center = {
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                };

                                $scope.marker.coords = {
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                };

                                $scope.map.zoom = 10;
                            });

                        });
                    }
                }
            };

            $scope.choosePlaceImages = function(){
                input.click();
            };

            input.bind('change', function(){
                if (input.context.files.length) {

                    $scope.imagesUploading = true;

                    angular.forEach(input.context.files, function(file){

                        var reader = new FileReader();

                        reader.onload = function(e){

                            var duplicate = false;
                            angular.forEach($scope.images, function(image){
                                if (e.target.result == image)
                                    duplicate = true;
                            });

                            if (!duplicate)
                                $scope.images.push(e.target.result);
                        };

                        reader.readAsDataURL(file);

                    });

                    $scope.imagesUploading = false;
                }

            });

            // watchers

            $scope.$watch(function(){
                return $scope.place.name;
            }, function(n,o){
                if (n) {
                    $scope.place.slug = n.replace(/ć/g, 'c').replace(/Ć/g, 'C')
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
                } else $scope.place.slug = '';

            });

        }]);
})();