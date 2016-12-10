(function(){
    'use strict';

    angular.module('TripModule')
        .factory('TripService', ['$http', 'SERVER', function($http, SERVER){
            return {

                exists: function(slug){
                    return $http.post(SERVER.url + 'trips/exists', {slug: slug})
                        .then(function(response){
                            return response.data;
                        });
                }

            };
        }]);
})();