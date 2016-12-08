(function(){
    'use strict';

    angular.module('BugReportModule')
        .controller('BugReportController', ['$scope', 'BugReportService', '$timeout', function($scope, BugReportService, $timeout){

            $scope.success = undefined;

            function resetBug(userId){
                $scope.bug = {
                    user_id: userId,
                    description: undefined
                }
            }

            $scope.send = function(){
                if($scope.bug.description){
                    BugReportService.add($scope.bug.user_id, $scope.bug.description)
                        .then(function(data){
                            $scope.success = data.success;
                            if(data.success) resetBug($scope.bug.user_id);
                            $timeout(function(){
                                $scope.success = undefined;
                            }, 5000);
                        });
                }
            };

            $scope.init = function(user_id){
                resetBug(user_id);
            };

        }]);
})();