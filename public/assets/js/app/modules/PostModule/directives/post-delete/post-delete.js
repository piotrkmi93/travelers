/**
 * Created by Piotr on 30.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .directive('postDelete', ['PostService', 'SERVER', function(PostService, SERVER){
            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/PostModule/directives/post-delete/post-delete.html',
                scope: {
                    postid: '='
                },
                link: function(scope){


                    scope.deletePost = function(){
                        PostService.deletePost(scope.postid).then(function(success){
                            if(success){
                                scope.$emit('post-deleted');
                            }
                        });
                        angular.element(document.querySelector('#delete-post-'+scope.postid)).modal('hide');
                    };

                    scope.openDeletePostModal = function(){
                        angular.element(document.querySelector('#delete-post-'+scope.postid)).modal('show');
                    };
                }
            };
        }]);
})();