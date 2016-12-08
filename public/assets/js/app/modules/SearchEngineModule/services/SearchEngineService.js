(function(){
    'use strict';

    angular.module('SearchEngineModule')
        .factory('SearchEngineService', ['$http', 'SERVER', function($http, SERVER){
            return {
                search: function(phrase){
                    return $http.get(SERVER.url + 'search/' + phrase)
                        .then(function(response){
                            return response.data;
                        });
                }
            };
        }]);
})();