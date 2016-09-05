/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .factory('UserFriendsService', ['$http', 'SERVER', function ($http, SERVER) {
            return {
                getFriends: function (user_id) {
                    return $http.post(SERVER.url + 'get_user_friends', {user_id: user_id})
                        .then(function(response){
                            return response.data.friends;
                        });
                }
            };
        }]);
})();