(function(){
    'use strict';

    angular.module('SearchEngineModule')
        .controller('SearchEngineController', ['$scope', 'SearchEngineService', function($scope, SearchEngineService){

            $scope.results = [];
            $scope.phrase = undefined;

            $scope.$watch(function(){
                return $scope.phrase;
            }, function(n,o){
                if($scope.phrase){
                    SearchEngineService.search($scope.phrase).then(function(data){

                    });
                }
            });

        }]);
})();