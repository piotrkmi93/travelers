/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .directive('comment', ['SERVER', function(SERVER){
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
                }
            };
        }])
})();