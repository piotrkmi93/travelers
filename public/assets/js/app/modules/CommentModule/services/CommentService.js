/**
 * Created by Piotr on 03.09.2016.
 */
(function(){
    'use strict';

    angular.module('CommentModule')
        .factory('CommentService', ['$http', 'SERVER', function($http, SERVER){
            return {
                addPostComment: function(user_id, post_id, text){
                    return $http.post(SERVER.url + 'add_comment', {
                        user_id: user_id,
                        post_id: post_id,
                        text: text,
                        type: 'post'
                    }).then(function(response){
                        return response.data;
                    });
                },

                deleteComment: function(comment_id){
                    return $http.post(SERVER.url + 'delete_comment', {
                        comment_id: comment_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getPostComments: function(user_id, post_id){
                    return $http.post(SERVER.url + 'get_post_comments', {
                        user_id: user_id,
                        post_id: post_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getUpdatedCommentStatistics: function(comment_id){
                    return $http.post(SERVER.url + 'get_updated_comment_statistics', {
                        comment_id: comment_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                addPlaceComment: function(user_id, place_id, text){
                    return $http.post(SERVER.url + 'add_comment', {
                        user_id: user_id,
                        place_id: place_id,
                        text: text,
                        type: 'place'
                    }).then(function(response){
                        return response.data;
                    });
                },

                getPlaceComments: function(user_id, place_id){
                    return $http.post(SERVER.url + 'get_place_comments', {
                        user_id: user_id,
                        place_id: place_id
                    }).then(function(response){
                        return response.data;
                    });
                },
            };
        }]);
})();