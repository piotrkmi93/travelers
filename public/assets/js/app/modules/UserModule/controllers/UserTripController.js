(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserTripController', ['$scope', 'TripService', function($scope, TripService){

            TripService.getUserTrips($scope.user_id).then(function(data){
                if(data.trips){
                    $scope.trips = data.trips;
                }
            });

            $scope.date = function(date){
                return new Date(date);
            }

        }]);
})();