/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserViewController', ['$scope', function ($scope) {
            $scope.viewInit = function(user_id){
                $scope.user_id = user_id;
            }
        }]);
})();