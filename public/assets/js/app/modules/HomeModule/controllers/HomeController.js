(function(){
    'use strict';

    angular.module('HomeModule')
        .controller('HomeController', ['$scope', function($scope){

            $scope.minimized = false;

            $scope.minimize = function(){
                $scope.minimized = !$scope.minimized;
            };

        }]);
})();