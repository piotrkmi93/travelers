(function(){
    'use strict';

    angular.module('SearchEngineModule')
        .controller('SearchEngineController', ['$scope', 'SearchEngineService', '$timeout', function($scope, SearchEngineService, $timeout){

            $scope.users = [];
            $scope.places = [];
            $scope.trips = [];
            $scope.phrase = undefined;
            $scope.show = false;
            var user_id = undefined;

            $scope.$watch(function(){
                return $scope.phrase;
            }, function(n,o){
                if($scope.phrase){
                    SearchEngineService.search(user_id, $scope.phrase).then(function(data){
                        $scope.users = data.users;
                        $scope.places = data.places;
                        $scope.trips = data.trips;
                    });
                }
            });

            $scope.init = function(id){
                user_id = id;
            };

            $scope.focus = function(){
                if($scope.show){
                    $timeout(function(){
                        $scope.show = !$scope.show;
                    }, 200);
                } else {
                    $scope.show = !$scope.show;
                }
            };

            $scope.date = function(date){
                return new Date(date);
            };

        }]);
})();