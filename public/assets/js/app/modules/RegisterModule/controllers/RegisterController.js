/**
 * Created by Piotr on 08.08.2016.
 */
(function () {
    'use strict';

    angular.module('RegisterModule')
        .controller('RegisterController', ['$scope', '$interval', 'CitySearchService', '$timeout', function($scope, $interval, CitySearchService, $timeout){

            $scope.userGeolocation = undefined;

            function setUserGeolocation(){
                var interval = $interval(function () {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position){
                            $interval.cancel(interval);
                            $scope.$apply(function(){
                                $scope.userGeolocation = {
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                };

                            });
                        });
                    } else {
                        $interval.cancel(interval);
                        console.log('Twoja przeglądarka nie obsługuje funckji udostepniania geolokalizacji');
                    }
                }, 100);
            }


            $scope.phrase = '';
            $scope.cities = [];
            $scope.citySelected = false;
            $scope.cityIdSelected = undefined;
            $scope.show = false;

            $scope.$watch(function(){
                return $scope.phrase;
            }, function(n,o){
                if($scope.phrase && $scope.phrase != ''){
                    CitySearchService.getCities($scope.phrase, $scope.userGeolocation.latitude, $scope.userGeolocation.longitude)
                        .then(function(cities){
                            $scope.cities = cities;
                        });
                }
            });

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



            /***************************************************************/

            $scope.today = function() {
                $scope.dt = new Date();
            };
            $scope.today();

            $scope.clear = function() {
                $scope.dt = null;
            };

            $scope.options = {
                customClass: getDayClass,
                minDate: new Date(),
                showWeeks: false
            };

            $scope.toggleMin = function() {
                $scope.options.minDate = $scope.options.minDate ? null : new Date();
            };

            $scope.toggleMin();

            $scope.setDate = function(year, month, day) {
                $scope.dt = new Date(year, month, day);
            };

            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            var afterTomorrow = new Date(tomorrow);
            afterTomorrow.setDate(tomorrow.getDate() + 1);
            $scope.events = [
                {
                    date: tomorrow,
                    status: 'full'
                },
                {
                    date: afterTomorrow,
                    status: 'partially'
                }
            ];

            function getDayClass(data) {
                var date = data.date,
                    mode = data.mode;
                if (mode === 'day') {
                    var dayToCheck = new Date(date).setHours(0,0,0,0);

                    for (var i = 0; i < $scope.events.length; i++) {
                        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

                        if (dayToCheck === currentDay) {
                            return $scope.events[i].status;
                        }
                    }
                }

                return '';
            }


            $scope.init = function(){
                setUserGeolocation();
            };


            $scope.$watch(function(){return $scope.dt}, function(n, o){
                var year = n.getFullYear();
                var month = ((n.getMonth()+1)<10?'0':'')+(n.getMonth()+1);
                var day = ((n.getDate())<10?'0':'')+(n.getDate());
                $scope.birthday = year + '-' + month + '-' + day;
            }, true);
        }]);
})();