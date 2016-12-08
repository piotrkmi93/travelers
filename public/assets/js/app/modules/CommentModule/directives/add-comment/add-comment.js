/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .directive('addComment', ['UserService', 'CommentService', 'SERVER', function(UserService, CommentService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/CommentModule/directives/add-comment/add-comment.html',
                scope: {
                    userid: '=',
                    postid: '=',
                    placeid: '=',
                    type: '@type'
                },
                link: function(scope){
                    UserService.getUserBasicsById(scope.userid).then(function(user){
                        scope.user = user;
                    });

                    scope.addComment = function(){
                        if (scope.type == 'post' && scope.text != ''){
                            CommentService.addPostComment(scope.user.id, scope.postid, scope.text).then(function(success){
                                if(success){
                                    scope.text = '';
                                    scope.$emit('post-comment-added');
                                }
                            });
                        }

                        if (scope.type == 'place' && scope.text != ''){
                            CommentService.addPlaceComment(scope.user.id, scope.placeid, scope.text).then(function(success){
                                if(success){
                                    scope.text = '';
                                    scope.$emit('place-comment-added');
                                }
                            });
                        }
                    };
                }
            }
        }]);
})();