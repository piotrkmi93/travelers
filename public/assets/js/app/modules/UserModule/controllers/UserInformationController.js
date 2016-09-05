/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserInformationController', ['$scope', 'UserService', function($scope, UserService) {
            UserService.getUserById($scope.user_id)
                .then(function(user){
                    console.log(user);
                    $scope.user = user;
                });
        }]);
})();