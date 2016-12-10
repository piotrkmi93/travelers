/**
 * Created by Piotr Kmiecik on 06.10.2016.
 */
(function(){
    'use strict';

    angular.module('PlaceModule')
        .factory('PlaceService', ['$http', 'SERVER', function($http, SERVER){
            return {

                isSlugExists: function(slug){
                    return $http.post(SERVER.url + 'places/is_slug_exists', {
                        slug: slug
                    }).then(function(response){
                        return response.data;
                    });
                },

                getUserPlaces: function(user_id){
                    return $http.post(SERVER.url + 'places/get_user_places', {
                        user_id: user_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getByPhraseAndCityId: function(phrase, city_id){
                    return $http.get(SERVER.url + 'places/' + city_id + '/' + phrase)
                        .then(function(response){
                            return response.data;
                        })
                }

            };
        }]);
})();