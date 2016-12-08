(function(){
    'use strict';

    angular.module('BugReportModule')
        .factory('BugReportService', ['$http', 'SERVER', function($http, SERVER){
            return {
                add: function(user_id, description){
                    return $http.post(SERVER.url + 'bug_reports/add', {
                        user_id: user_id,
                        description: description
                    })
                        .then(function(response){
                            return response.data;
                        });
                }
            }
        }]);
})();