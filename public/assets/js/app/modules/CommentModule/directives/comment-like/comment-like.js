/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .directive('commentLike', ['LikeService', 'SERVER', function(LikeService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/CommentModule/directives/comment-like/comment-like.html',
                scope: {
                    liked: '=',
                    userid: '=',
                    commentid: '=',
                    likes: '='
                },
                link: function(scope){
                    scope.likeThis = function(){
                        LikeService.commentLikeToggle(scope.userid, scope.commentid, scope.liked).then(function(succes){
                            if (succes) {
                                scope.liked = !scope.liked;
                                if(scope.liked) scope.likes++;
                                else scope.likes--;
                            }
                        })
                    };
                }
            };
        }])
})();