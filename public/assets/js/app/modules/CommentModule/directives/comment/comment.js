/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .directive('comment', ['CommentService', '$rootScope', 'SERVER', '$sce', function(CommentService, $rootScope, SERVER, $sce){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/CommentModule/directives/comment/comment.html',
                replace: true,
                scope: {
                    comment: '=',
                    userid: '=',
                    type: '@type'
                },
                link: function(scope){
                    scope.isowner = scope.userid == scope.comment.user.id;

                    scope.trustAsHtml = function(string) {
                        return $sce.trustAsHtml(string);
                    };

                    $rootScope.$on('update-comment', function(event, comment_id){
                        if(scope.comment.id == comment_id){
                            CommentService.getUpdatedCommentStatistics(comment_id).then(function(data){
                                scope.comment.likes_count = data.likes_count;
                            });
                        }
                    });
                }
            };
        }])
})();