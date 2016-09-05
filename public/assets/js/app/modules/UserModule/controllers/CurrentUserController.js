/**
 * Created by Piotr on 15.08.2016.
 */
(function () {
    'use strict';

    angular.module('UserModule')
        .controller('CurrentUserController', ['$scope', '$interval', function ($scope, $interval) {

            $scope.chooseAvatarClick = function () {
                var input = angular.element(document.querySelector('#avatar-input'));
                var submit = angular.element(document.querySelector('#avatar-submit'));
                input.click();
                var interval = $interval(function(){
                    if(input.context.value != ""){
                        submit.click();
                        $interval.cancel(interval);
                    }
                }, 1000);
            };

            $scope.chooseBackgroundClick = function () {
                var input = angular.element(document.querySelector('#background-input'));
                var submit = angular.element(document.querySelector('#background-submit'));
                input.click();
                var interval = $interval(function(){
                    if(input.context.value != ""){
                        submit.click();
                        $interval.cancel(interval);
                    }
                }, 1000);
            };

        }]);
})();