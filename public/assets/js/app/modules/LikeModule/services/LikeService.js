/**
 * Created by Piotr on 31.08.2016.
 */
(function(){
    'use strict';

    angular.module('LikeModule')
        .factory('LikeService', ['$http', 'SERVER', function($http, SERVER){
            return {
                postLikeToggle: function(user_id, post_id, liked){
                    var url = liked ? 'unlike_post' : 'like_post';
                    return $http.post(SERVER.url + url, {
                        user_id: user_id,
                        post_id: post_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                commentLikeToggle: function(user_id, comment_id, liked){
                    var url = liked ? 'unlike_comment' : 'like_comment';
                    return $http.post(SERVER.url + url, {
                        user_id: user_id,
                        comment_id: comment_id
                    }).then(function(response){
                        return response.data;
                    });
                }
            };
        }]);
})();