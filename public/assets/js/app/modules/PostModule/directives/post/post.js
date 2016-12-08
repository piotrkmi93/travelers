/**
 * Created by Piotr on 28.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .directive('post', ['PostService', 'CommentService', 'SERVER', '$sce', '$rootScope', function(PostService, CommentService, SERVER, $sce, $rootScope){
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

                    scope.trustAsHtml = function(string) {
                        return $sce.trustAsHtml(string);
                    };

                    scope.$on('post-comment-added', function(){
                        getPostComments();
                    });

                    scope.$on('comment-deleted', function(){
                        getPostComments();
                    });

                    $rootScope.$on('update-post', function(event, post_id){
                        if (scope.post.id == post_id){
                            PostService.getUpdatedPostStatistics(post_id).then(function(data){
                                scope.post.likes_count = data.likes_count;
                                scope.post.comments_count = data.comments_count;
                            });
                        }
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