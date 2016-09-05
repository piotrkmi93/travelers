/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserFriendsController', ['$scope', 'UserFriendsService', 'UserService', '$interval', function ($scope, UserFriendsService, UserService, $interval) {

            var allFriends = [];
            $scope.friends = [];
            $scope.friendsCount = 0;
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

            function getFriends(){
                UserFriendsService.getFriends($scope.user_id)
                    .then(function(friends){
                        allFriends = friends;
                        $scope.friends = allFriends;
                        $scope.friendsCount = allFriends.length;
                    });
            }

            $interval(function(){
                var ids = [];
                angular.forEach(allFriends, function(friend){
                    ids.push(friend.id);
                });
                UserService.areUsersActive(ids).then(function(users){
                    angular.forEach(users, function(user){
                        angular.forEach(allFriends, function(friend){
                            if(user.id == friend.id) friend.is_active = user.is_active;
                        });

                        angular.forEach($scope.friends, function(friend){
                            if(user.id == friend.id) friend.is_active = user.is_active;
                        });
                    });
                })
            }, 10000);

            $scope.userFriendsControllerInit = function(user_id){
                if (user_id != undefined) $scope.user_id = user_id;
                getFriends();
            };
        }]);
})();