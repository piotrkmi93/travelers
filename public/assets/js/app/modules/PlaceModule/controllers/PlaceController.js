/**
 * Created by Piotr Kmiecik on 07.10.2016.
 */
(function(){
    'use strict';

    angular.module('PlaceModule')
        .controller('PlaceController', ['$scope', 'LikeService', 'CommentService', function($scope, LikeService, CommentService){

            $scope.placeInit = function(images, latitude, longitude, userId, placeId, liked, likes){

                $scope.user_id = userId;
                $scope.place_id = placeId;
                $scope.liked = liked == 1;
                $scope.likes = likes;

                $scope.images = images;

                $scope.map = {
                    center: {
                        latitude: latitude,
                        longitude: longitude
                    },
                    zoom: 15
                };

                $scope.marker = {
                    id: 0,
                    coords: {
                        latitude: latitude,
                        longitude: longitude
                    },
                    options: { draggable: false }
                };

                getPlaceComments();
            };

            $scope.likeThis = function(){
                LikeService.placeLikeToggle($scope.user_id, $scope.place_id, $scope.liked).then(function(success){
                    if(success){
                        $scope.liked = !$scope.liked;
                        if($scope.liked) $scope.likes++;
                        else $scope.likes--;
                    }
                });
            };

            $scope.$on('place-comment-added', function(){
                getPlaceComments();
            });

            function getPlaceComments(){
                CommentService.getPlaceComments($scope.user_id, $scope.place_id)
                    .then(function(comments){
                        console.log(comments);
                        $scope.comments = comments;
                    });
            }

        }]);
})();