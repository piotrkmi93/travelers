/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .directive('commentDelete', ['CommentService', 'SERVER', function(CommentService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/CommentModule/directives/comment-delete/comment-delete.html',
                scope: {
                    comentid: '='
                },
                link: function(scope){
                    scope.deleteComment = function(){
                        CommentService.deleteComment(scope.commentid).then(function(success){
                            if (success) scope.$emit('comment-deleted');
                        });
                        angular.element(document.querySelector('#delete-comment-'+scope.commentid)).modal('hide');
                    };

                    scope.openDeleteCommentModal = function(){
                        angular.element(document.querySelector('#delete-comment-'+scope.commentid)).modal('show');
                    };
                }
            };
        }]);
})();