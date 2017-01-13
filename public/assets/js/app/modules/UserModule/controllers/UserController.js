/**
 * Created by Piotr on 17.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserController', ['$scope', '$interval', 'UserService', 'NotificationService', '$rootScope', function($scope, $interval, UserService, NotificationService, $rootScope){

            $scope.yourId = null;
            $scope.userId = null;
            $scope.isUserYourFriend = undefined;
            $scope.isInvitationAccepted = undefined;

            function checkIsUserYourFriend(yourId, userId) {
                UserService.checkIsUserYourFriend(yourId, userId)
                    .then(function (data) {
                        $scope.isUserYourFriend = data.isUserYourFriend;
                        $scope.isInvitationAccepted = data.isInvitationAccepted;
                        if(data.hasOwnProperty('invitationFrom')) $scope.invitationFrom = data.invitationFrom;
                    });
            }

            $scope.sendInvitation = function(yourId, userId){
                $scope.isUserYourFriend = undefined;
                $scope.isInvitationAccepted = undefined;
                UserService.sendInvitation(yourId, userId)
                    .then(function(success){
                        if (success) {
                            checkIsUserYourFriend(yourId, userId);
                        }

                    });
            };

            $scope.acceptInvitation = function(yourId, userId){
                $scope.isUserYourFriend = undefined;
                $scope.isInvitationAccepted = undefined;
                UserService.acceptInvitation(yourId, userId)
                    .then(function(success){
                        if (success){
                            checkIsUserYourFriend(yourId, userId);
                        }
                    });
            };

            $scope.deleteFromFriends = function(yourId, userId){
                $scope.isUserYourFriend = undefined;
                $scope.isInvitationAccepted = undefined;
                UserService.deleteFromFriends(yourId, userId)
                    .then(function(success){
                        if (success){
                            checkIsUserYourFriend(yourId, userId);
                        }
                    });
            };

            $scope.otherUserInit = function(yourId, userId) {
				$rootScope.yourId = yourId;
                $scope.yourId = yourId;
                $scope.userId = userId;
                checkIsUserYourFriend(yourId, userId);
            };

        }]);
})();
