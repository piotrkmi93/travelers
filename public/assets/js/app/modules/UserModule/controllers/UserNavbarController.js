/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserNavbarController', ['$scope', '$interval', 'UserService', 'NotificationService', 'SERVER', '$timeout', function ($scope, $interval, UserService, NotificationService, SERVER, $timeout) {
            $scope.notificationsCount = 0;
            $scope.newMessagesCount = 0;
            $scope.notifications = [];
            $scope.newNotifications = false;
            var userId = 0;
            var lastId = 0;
            var isLoadingNotifications = false;

            $scope.openNotificationsModal = function(){
                angular.element(document.querySelector('#notifications-modal')).modal().focus();
            };

            function getNotifications(){
                isLoadingNotifications = true;
                return NotificationService.getNotifications(userId, lastId)
                    .then(function(data){
                        //console.log(data.notifications);
                        $scope.notifications = $scope.notifications.concat(data.notifications);
                        $scope.notificationsCount += data.notificationsCount;
                        lastId = data.lastId;
                        isLoadingNotifications = false;
                    });
            }

            $scope.sendInvitation = function(yourId, userId){
                UserService.sendInvitation(yourId, userId)
                    .then(function(success) {
                        if (success) {
                            refreshNotifications();
                        }
                    });
            };

            $scope.acceptInvitation = function(yourId, userId){
                UserService.acceptInvitation(yourId, userId)
                    .then(function(success) {
                        if (success) {
                            refreshNotifications();
                        }
                    });
            };

            function refreshNotifications(){
                $scope.notificationsCount = 0;
                $scope.newMessagesCount = 0;
                $scope.notifications = [];
                lastId = 0;
            }

            $scope.navbarInit = function(user_id){
                userId = user_id;
                extendActiveTimestamp();
            };

            $scope.deleteNotification = function(notification_id, type){
                NotificationService.deleteNotification(notification_id, type).then(function(success){
                    if (success == true){
                        refreshNotifications();
                    }
                })
            };

            // notifications interval <- 1 second
            $interval(function(){
                var oldNotificationsCount = $scope.notificationsCount;
                if (!isLoadingNotifications) getNotifications().then(function(){
                    if (oldNotificationsCount < $scope.notificationsCount){
                        var notificationSound = new Audio(SERVER.url+'sounds/notification.mp3');
                        notificationSound.play();

                        $timeout(function(){
                            $scope.newNotifications = true;
                            $timeout(function(){
                                $scope.newNotifications = false;
                            }, 1000);
                        }, 500);
                    }
                });
            }, 1000);

            // user active interval <- 5 minutes
            function extendActiveTimestamp(){
                UserService.extendActiveTimestamp(userId).then(function(success){
                    if (success == true){
                        console.log('Przedłużono aktywność o 5 minut');
                    }
                });
            }
            $interval(extendActiveTimestamp, 300000);
        }]);
})();