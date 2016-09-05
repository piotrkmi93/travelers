/**
 * Created by Piotr on 08.08.2016.
 */
(function () {
    'use strict';

    angular.module('CityModule')
        .factory('CitySearchService', ['$http', 'SERVER', function($http, SERVER){
            return {
                getCities: function (phrase, latitude, longitude) {
                    return $http.post(SERVER.url + 'get_cities', {
                        phrase: phrase,
                        latitude: latitude,
                        longitude: longitude
                    })
                        .then(function (response) {
                            return response.data.cities;
                        });
                }
            };
        }]);
})();