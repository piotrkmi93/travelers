(function(){
    'use strict';

    angular.module('TripModule')
        .controller('TripController', ['$scope', 'CommentService', function($scope, CommentService){

            $scope.users = [];
            $scope.places = [];
            $scope.trip_id = undefined;
            $scope.user_id = undefined;
            $scope.comments = [];
            $scope.map = {
                center: {
                    latitude: 52.069167,
                    longitude: 19.480556
                },
                zoom: 10,
                bounds: {}
            };
            $scope.polylines = [
                {
                    id: 1,
                    path: [],
                    stroke: {
                        color: '#6060FB',
                        weight: 3
                    },
                    editable: false,
                    draggable: false,
                    geodesic: true,
                    visible: true
                }
            ];

            $scope.init = function(users, places, trip_id, user_id){
                $scope.users = users;
                $scope.places = places;
                $scope.trip_id = trip_id;
                $scope.user_id = user_id;

                angular.forEach(places, function(place, index){
                    if(index==0){
                        $scope.map.center = {
                            latitude: place.latitude,
                            longitude: place.longitude
                        };
                    }
                    $scope.polylines[0].path.push(
                        {
                            latitude: place.latitude,
                            longitude: place.longitude
                        }
                    );
                });

                getTripComments();
            };

            $scope.date = function(date){
                return new Date(date);
            };

            $scope.$on('trip-comment-added', function(){
                getTripComments();
            });

            function getTripComments(){
                CommentService.getTripComments($scope.user_id, $scope.trip_id)
                    .then(function(comments){
                        console.log(comments);
                        $scope.comments = comments;
                    });
            }

        }]);
})();