/**
 * Created by Piotr Kmiecik on 06.10.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserGalleryController', ['$scope', 'UserService', '$document', function($scope, UserService, $document){

            $scope.userGalleryInit = function(){

                console.log('init');

                UserService.getUserGallery($scope.user_id)
                    .then(function(photos){
                        console.log(photos);
                        $scope.photos = photos;
                    });

            };

        }]);
})();