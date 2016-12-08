/**
 * Created by Piotr on 17.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .factory('UserService', ['$http', 'SERVER', function($http, SERVER){
            return {
                checkIsUserYourFriend: function(yourId, userId){
                    return $http.post(SERVER.url + 'check_is_user_your_friend', {
                        from_user_id: yourId,
                        to_user_id: userId
                    }).then(function (response) {
                        return response.data;
                    });
                },

                sendInvitation: function(yourId, userId){
                    return $http.post(SERVER.url + 'send_invitation', {
                        from_user_id: yourId,
                        to_user_id: userId
                    }).then(function (response) {
                        return response.data;
                    });
                },

                acceptInvitation: function(yourId, userId){
                    return $http.post(SERVER.url + 'accept_invitation', {
                        from_user_id: yourId,
                        to_user_id: userId
                    }).then(function (response) {
                        return response.data;
                    });
                },

                deleteFromFriends: function(yourId, userId) {
                    return $http.post(SERVER.url + 'delete_from_friends', {
                        from_user_id: yourId,
                        to_user_id: userId
                    }).then(function (response) {
                        return response.data;
                    });
                },

                getUserById: function(user_id){
                    return $http.post(SERVER.url + 'get_user_by_id', {user_id: user_id})
                        .then(function(response){
                            return response.data;
                        });
                },

                getUserBasicsById: function(user_id){
                    return $http.post(SERVER.url + 'get_user_basics_by_id', {user_id: user_id})
                        .then(function(response){
                            return response.data;
                        });
                },

                extendActiveTimestamp: function(user_id){
                    return $http.post(SERVER.url + 'extend_active_timestamp', {user_id: user_id})
                        .then(function(response){
                            return response.data;
                        });
                },

                isUserActive: function(user_id){
                    return $http.post(SERVER.url + 'is_user_active', {user_id: user_id})
                        .then(function(response){
                            //console.log(response.data);
                            return response.data;
                        });
                },

                areUsersActive: function(ids){
                    return $http.post(SERVER.url + 'are_users_active', {ids: ids})
                        .then(function(response){
                            //console.log(response.data);
                            return response.data;
                        });
                },

                getUserGallery: function(user_id){
                    return $http.post(SERVER.url + 'get_user_gallery', {user_id: user_id})
                        .then(function(response){
                            return response.data.photos;
                        });
                }
            }
        }]);
})();