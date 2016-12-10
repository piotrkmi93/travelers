(function(){
    'use strict';

    angular.module('SearchEngineModule')
        .factory('SearchEngineService', ['$http', 'SERVER', function($http, SERVER){
            return {
                search: function(user_id, phrase){
                    return $http.get(SERVER.url + 'search/' + user_id + '/' + phrase)
                        .then(function(response){
                            return response.data;
                        });
                }
            };
        }]);
})();