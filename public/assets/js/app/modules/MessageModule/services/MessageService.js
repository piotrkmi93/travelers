/**
 * Created by Piotr Kmiecik on 12.09.2016.
 */
(function(){
    'use strict';

    angular.module('MessageModule')
        .factory('MessageService', ['$http', 'SERVER', function($http, SERVER){
            return {
                getFriends: function(user_id){
                    return $http.post(SERVER.url + 'get_messanger_friends', {
                        user_id: user_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getMessages: function(user_id, last_id){
                    return $http.post(SERVER.url + 'get_messages', {
                        user_id: user_id,
                        last_id: last_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getUserByUsername: function(username){
                    return $http.post(SERVER.url + 'get_user_by_username', {
                        username: username
                    }).then(function(response){
                        return response.data;
                    });
                },

                getConversation: function(user_one_id, user_two_id, offset){
                    return $http.post(SERVER.url + 'get_conversation', {
                        user_one_id: user_one_id,
                        user_two_id: user_two_id,
                        offset: offset
                    }).then(function(response){
                        return response.data;
                    });
                },

                sendMessage: function(from_user_id, to_user_id, text){
                    return $http.post(SERVER.url + 'send_message', {
                        from_user_id: from_user_id,
                        to_user_id: to_user_id,
                        text: text
                    }).then(function(response){
                        return response.data;
                    });
                },

                // to na samym końcu
                readMessages: function(ids){
                    return $http.post(SERVER.url + 'read_messages', {
                        ids: ids
                    }).then(function(response){
                        return response.data;
                    });
                }
            };
        }]);
})();