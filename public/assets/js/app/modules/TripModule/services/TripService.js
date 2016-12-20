(function(){
    'use strict';

    angular.module('TripModule')
        .factory('TripService', ['$http', 'SERVER', function($http, SERVER){
            return {

                create: function(data){
                    return $http.post(SERVER.url + 'trips/create', data)
                        .then(function(response){
                            return response.data;
                        })
                },

                exists: function(slug){
                    return $http.post(SERVER.url + 'trips/exists', {slug: slug})
                        .then(function(response){
                            return response.data;
                        });
                }

            };
        }]);
})();