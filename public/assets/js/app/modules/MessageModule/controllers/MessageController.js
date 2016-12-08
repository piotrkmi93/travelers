/**
 * Created by Piotr Kmiecik on 12.09.2016.
 */
(function(){
    'use strict';

    angular.module('MessageModule')
        .controller('MessageController', ['$scope', 'MessageService', 'UserService', function($scope, MessageService, UserService){

            var allFriends = [];
            $scope.user_id = null;
            $scope.friends = [];
            $scope.search = {
                phrase: ''
            };

            $scope.$watch(function(){
                return $scope.search.phrase;
            }, function(){
                if($scope.search.phrase != '') {
                    $scope.friends = [];
                    angular.forEach(allFriends, function(friend){
                        if (friend.name.toLowerCase().indexOf($scope.search.phrase.toLowerCase()) >= 0){
                            $scope.friends.push(friend);
                        }
                    });
                } else {
                    $scope.friends = allFriends;
                }
            });

            $scope.initMessages = function(user_id){
                MessageService.getFriends(user_id).then(function(data){
                    console.log(data.friends);
                    $scope.user_id = user_id;
                    allFriends = $scope.friends = data.friends;
                })
            };

            $scope.$on('message-read', function(event, id){
                angular.forEach(allFriends, function(friend){
                    if (id == friend.id){
                        friend.last_message.is_read = true;
                    }
                });

                angular.forEach($scope.friends, function(friend){
                    if (id == friend.id){
                        friend.last_message.is_read = true;
                    }
                });
            });

        }]);
})();