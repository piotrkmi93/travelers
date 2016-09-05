/**
 * Created by Piotr on 28.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .directive('post', ['CommentService', 'SERVER', function(CommentService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/PostModule/directives/post/post.html',
                scope: {
                    post: '=',
                    isowner: '=',
                    userid: '='
                },
                link: function(scope){
                    scope.post.comments = undefined;
                    scope.commentsShown = false;

                    scope.showComments = function(){
                        if(scope.commentsShown){
                            scope.commentsShown = false;
                            scope.comments = undefined;
                        } else {
                            scope.commentsShown = true;
                            getPostComments();
                        }

                    };

                    scope.$on('post-comment-added', function(){
                        getPostComments();
                    });

                    scope.$on('comment-deleted', function(){
                        getPostComments();
                    });

                    function getPostComments(){
                        CommentService.getPostComments(scope.userid, scope.post.id)
                            .then(function(comments){
                                console.log(comments);
                                scope.post.comments = comments;
                            });
                    }
                }
            }
        }])
})();