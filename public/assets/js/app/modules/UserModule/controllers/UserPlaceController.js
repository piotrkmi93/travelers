/**
 * Created by Piotr Kmiecik on 08.10.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserPlaceController', ['$scope', 'PlaceService', function($scope, PlaceService){

            $scope.places = [];

            PlaceService.getUserPlaces($scope.user_id).then(function(data){
                if (data.hasOwnProperty('places')){
                    console.log(data.places);
                    $scope.places = data.places;
                }
            });

        }]);
})();