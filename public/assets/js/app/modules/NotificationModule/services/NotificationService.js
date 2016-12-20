/**
 * Created by Piotr on 18.08.2016.
 */
(function(){
    'use strict';

    angular.module('NotificationModule')
        .factory('NotificationService', ['$http', 'SERVER', function ($http, SERVER) {
            return {
                getNotifications: function(user_id, last_id){
                    return $http.post(SERVER.url + 'get_notifications', {
                        user_id: user_id,
                        last_id: last_id
                    }).then(function (response) {
                        console.log(response.data);
                        return response.data;
                    });
                },

                deleteNotification: function(notification_id, type){
                    return $http.post(SERVER.url+'delete_notification', {
                        notification_id: notification_id,
                        type: type
                    }).then(function(response){
                        return response.data;
                    });
                }
            };
        }]);
})();