/**
 * Created by Piotr on 28.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .directive('postLike', ['LikeService', 'SERVER', function(LikeService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/PostModule/directives/post-like/post-like.html',
                scope: {
                    liked: '=',
                    userid: '=',
                    postid: '=',
                    likes: '='
                },
                link: function(scope){
                    scope.likeThis = function(){
                        LikeService.postLikeToggle(scope.userid, scope.postid, scope.liked).then(function(success){
                            if (success) {
                                scope.liked = !scope.liked;
                                if(scope.liked) scope.likes++;
                                else scope.likes--;
                            }
                            else console.log(success);
                        })
                    };

                }
            };
        }]);
})();