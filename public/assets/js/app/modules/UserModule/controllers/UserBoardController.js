/**
 * Created by Piotr on 22.08.2016.
 */
(function(){
    'use strict';

    angular.module('UserModule')
        .controller('UserBoardController', ['$scope', 'PostService', '$interval', '$rootScope', function($scope, PostService, $interval, $rootScope){
            $scope.posts = [];
            $scope.loadingMorePosts = false;
            var offset = 0;
            var isMorePostsToLoad = true;

            function getUserPosts(){
                $scope.loadingMorePosts = true;
                PostService.getUserPosts($scope.user_id, offset, $rootScope.yourId).then(function(posts){
                    if (posts.length < 10) isMorePostsToLoad = false;
                    $scope.posts = $scope.posts.concat(posts);
                    offset += 10;
                }).finally(function(){
                    $scope.loadingMorePosts = false;
                });
            }

            $scope.loadMorePosts = function(){
                if (!$scope.loadingMorePosts && isMorePostsToLoad){
                    getUserPosts();
                }
            };

            $scope.$on('post-added', function(){
                $scope.posts = [];
                offset = 0;
                getUserPosts();
            });

            $scope.$on('post-deleted', function(){
                $scope.posts = [];
                offset = 0;
                getUserPosts();
            });

			$scope.userBoardInit = function(){
				var interval = $interval(function(){
				
					if($rootScope.yourId){
						$interval.cancel(interval);
					}
					
				},100);
				
				
			}
            
        }]);
})(); 