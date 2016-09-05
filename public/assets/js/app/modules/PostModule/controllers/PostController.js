/**
 * Created by Piotr on 27.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .controller('PostController', ['$scope', 'PostService', function($scope, PostService){

            $scope.posts = [];
            $scope.loadingMorePosts = false;
            var user_id;
            var offset = 0;
            var isMorePostsToLoad = true;

            function getUsersPosts(){
                $scope.loadingMorePosts = true;
                PostService.getUsersPosts(user_id, offset).then(function(posts){
                    if (posts.length < 10) isMorePostsToLoad = false;
                    $scope.posts = $scope.posts.concat(posts);
                    offset += 10;
                }).finally(function(){
                    $scope.loadingMorePosts = false;
                });
            }

            $scope.loadMorePosts = function(){
                if (!$scope.loadingMorePosts && isMorePostsToLoad){
                    getUsersPosts();
                }
            };

            $scope.$on('post-added', function(){
                $scope.postsInit(user_id);
            });

            $scope.$on('post-deleted', function(){
                $scope.postsInit(user_id);
            });

            $scope.postsInit = function(userId){
                $scope.posts = [];
                user_id = userId;
                offset = 0;
                getUsersPosts();
            };
        }]);
})();