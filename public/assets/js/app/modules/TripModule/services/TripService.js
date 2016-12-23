(function(){
    'use strict';

    angular.module('TripModule')
        .factory('TripService', ['$http', 'SERVER', function($http, SERVER){
            return {

                create: function(data){
                    return $http.post(SERVER.url + 'trips/create', data)
                        .then(function(response){
                            return response.data;
                        });
                },

                update: function(data){
                    return $http.post(SERVER.url + 'trips/update', data)
                        .then(function(response){
                            return response.data;
                        });
                },

                delete: function(id){
                    return $http.post(SERVER.url + 'trips/delete', {id:id})
                        .then(function(response){
                            return response.data;
                        });
                },

                exists: function(slug){
                    return $http.post(SERVER.url + 'trips/exists', {slug: slug})
                        .then(function(response){
                            return response.data;
                        });
                },

                accept: function(trip_user_id){
                    return $http.post(SERVER.url + 'trips/accept', {trip_user_id:trip_user_id})
                        .then(function(response){
                            return response.data;
                        });
                },

                decline: function(trip_user_id){
                    return $http.post(SERVER.url + 'trips/decline', {trip_user_id:trip_user_id})
                        .then(function(response){
                            return response.data;
                        });
                },

                getUserTrips: function(user_id){
                    return $http.post(SERVER.url + 'trips/get_user_trips', {user_id:user_id})
                        .then(function(response){
                            return response.data;
                        });
                }

            };
        }]);
})();