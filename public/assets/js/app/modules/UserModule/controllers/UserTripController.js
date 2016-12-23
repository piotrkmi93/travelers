(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserTripController', ['$scope', 'TripService', function($scope, TripService){

            function getTrips(){
                TripService.getUserTrips($scope.user_id).then(function(data){
                    if(data.trips){
                        $scope.trips = data.trips;
                    }
                });
            }

            getTrips();

            $scope.date = function(date){
                return new Date(date);
            };

            $scope.delete = function(id){
                TripService.delete(id).then(function(data){
                    if(data.success){
                        getTrips();
                    }
                });
            };

        }]);
})();