/**
 * Created by Piotr Kmiecik on 12.09.2016.
 */
(function(){
    'use strict';

    angular.module('MessageModule')
        .controller('ConversationController', ['$scope', '$rootScope', '$routeParams', 'MessageService', 'UserService', function($scope, $rootScope, $routeParams, MessageService, UserService){
            $scope.interlocutor = null;
            $scope.user = null;
            $scope.messages = [];
            $scope.messageText = '';
            $scope.isLoading = true;
            $scope.isMoreMessagesToLoad = true;
            var offset = 0;

            $scope.initConversation = function(){

                // init interlocutor
                MessageService.getUserByUsername($routeParams.username).then(function(data){
                    $scope.interlocutor = data.interlocutor;

                    UserService.getUserBasicsById($scope.user_id).then(function(user){
                        $scope.user = user;
                    });

                }).finally(function(){
                    // get conversation
                    getConversation().finally(function(){
                        var ids = [];
                        angular.forEach($scope.messages, function(message){
                            if (message.is_read == 0) {
                                ids.push(message.id);
                                $scope.$emit('message-read', message.id); // hope to not crash
                            }
                        });

                        MessageService.readMessages(ids);
                        $scope.isLoading = false;
                    });
                });
            };

            $scope.loadOlderMessages = function() {
                getConversation().finally(function(){
                    $scope.isLoading = false;
                });
            };

            function getConversation(){
                $scope.isLoading = true;
                return MessageService.getConversation($scope.user_id, $scope.interlocutor.id, offset).then(function(data){
                    console.log(data.messages);
                    $scope.messages = data.messages.concat($scope.messages);
                    offset += data.messages.length; // 20 basically
                    if(data.messages.length < 20) $scope.isMoreMessagesToLoad = false;
                });
            }

            $scope.sendMessage = function(){
                console.log('click');
                if($scope.messageText != ''){
                    MessageService.sendMessage($scope.user_id, $scope.interlocutor.id, $scope.messageText)
                        .then(function(data){
                            if (data.message) {
                                $scope.messages.push(data.message);
                                $scope.messageText = '';
                            }
                        });
                }
            };

            $rootScope.$on('unread-message', function(event, message){
                if(message.from_user_id == $scope.interlocutor.id &&
                   message.to_user_id == $scope.user_id){
                    $scope.messages.push(message);
                    MessageService.readMessages([message.id]);
                    $scope.$emit('message-read', message.id);
                }
            });
        }]);
})();