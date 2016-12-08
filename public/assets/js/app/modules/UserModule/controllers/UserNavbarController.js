/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserNavbarController', ['$scope', '$rootScope', '$interval', 'UserService', 'NotificationService', 'SERVER', '$timeout', 'MessageService', '$sce', function ($scope, $rootScope, $interval, UserService, NotificationService, SERVER, $timeout, MessageService, $sce) {
            $scope.notificationsCount = 0;
            $scope.newMessagesCount = 0;
            $scope.notifications = [];
            $scope.newNotifications = false;
            var userId = 0;
            var lastNotificationId = 0;
            var lastMessageId = 0;
            var isLoadingNotifications = false;
            var isLoadingMessages = false;

            $scope.openNotificationsModal = function(){
                angular.element(document.querySelector('#notifications-modal')).modal().focus();
            };

            function getNotifications(){
                isLoadingNotifications = true;
                return NotificationService.getNotifications(userId, lastNotificationId)
                    .then(function(data){
                        angular.forEach(data.notifications, function(notification){
                            if (notification.type == 'post-comment' || notification.type == 'post-like'){
                                $rootScope.$emit('update-post', notification.post_id);
                            } else if (notification.type == 'comment-like') {
                                $rootScope.$emit('update-comment', notification.comment_id);
                            }
                        });

                        $scope.notifications = $scope.notifications.concat(data.notifications);
                        $scope.notificationsCount += data.notificationsCount;
                        lastNotificationId = data.lastId;
                        isLoadingNotifications = false;
                    });
            }

            function getMessages(){
                isLoadingMessages = true;
                return MessageService.getMessages(userId, lastMessageId)
                    .then(function(data){
                        // console.log(data);

                        angular.forEach(data.messages, function (message) {
                            $rootScope.$emit('unread-message', message);
                        });

                        $scope.newMessagesCount += data.messagesCount;
                        if(data.messageLastId) lastMessageId = data.messageLastId;
                        isLoadingMessages = false;
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
                lastNotificationId = 0;
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

            // notifications && messages interval <- 1 second
            $interval(function(){
                var oldNotificationsCount = $scope.notificationsCount;
                var oldMessagesCount = $scope.newMessagesCount;

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

                if(!isLoadingMessages) getMessages().then(function(){
                    if (oldMessagesCount < $scope.newMessagesCount){
                        // TODO: zmienić dźwięk nowej wiadomości
                        var notificationSound = new Audio(SERVER.url+'sounds/message.mp3');
                        notificationSound.play();
                    }
                });

            }, 1000);

            // user active interval <- 5 minutes
            function extendActiveTimestamp(){
                UserService.extendActiveTimestamp(userId);
            }
            $interval(extendActiveTimestamp, 300000);

            $scope.trustAsHtml = function(string) {
                return $sce.trustAsHtml(string);
            };
        }]);
})();