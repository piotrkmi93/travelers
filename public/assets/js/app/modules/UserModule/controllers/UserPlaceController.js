/**
 * Created by Piotr Kmiecik on 08.10.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserPlaceController', ['$scope', 'PlaceService', function($scope, PlaceService){

            $scope.places = [];

            getPlaces();

            function getPlaces(){
                PlaceService.getUserPlaces($scope.user_id).then(function(data){
                    if (data.hasOwnProperty('places')){
                        $scope.places = data.places;
                    }
                });
            }

            $scope.delete = function(place_id){
                PlaceService.delete(place_id)
                    .then(function(data){
                        if(data.success){
                            getPlaces();
                        }
                    });
            };

        }]);
})();