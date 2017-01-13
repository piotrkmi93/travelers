/**
 * Created by Piotr on 27.08.2016.
 */
(function(){
    'use strict';

    angular.module('PostModule')
        .factory('PostService', ['$http', 'SERVER', function($http, SERVER){
			
			var busy = false;
			
            return {
                addPost: function(user_id, form){

                    var fd = new FormData();
                    fd.append('user_id', user_id);
                    fd.append('text', form.text);
                    if(form.photo) fd.append('photo', form.photo);

                    return $http.post(SERVER.url + 'add_post', fd, {
                            transformRequest: angular.identity,
                            headers: {
                                'Content-Type': undefined
                            }
                        })
                        .then(function(response){
                            return response.data;
                        });
                },

                deletePost: function(post_id){
                    return $http.post(SERVER.url + 'delete_post', {
                        post_id: post_id
                    }).then(function(response){
                        return response.data;
                    });
                },

                getUserPosts: function(user_id, offset, your_user_id){
					if(!busy){
						busy = true;
						return $http.post(SERVER.url + 'get_user_posts', {
							user_id: user_id,
							offset: offset,
							your_user_id: your_user_id
						}).then(function(response){
							busy = false;
							return response.data.posts;
						});
					}
                   
                },

                getUsersPosts: function(user_id, offset){
                    return $http.post(SERVER.url + 'get_users_posts', {
                        user_id: user_id,
                        offset: offset
                    }).then(function(response){
                        // console.log(response);
                        return response.data.posts;
                    });
                },

                getUpdatedPostStatistics: function(post_id){
                    return $http.post(SERVER.url + 'get_updated_post_statistics', {
                        post_id: post_id
                    }).then(function(response){
                        return response.data;
                    });
                }
            };
        }]);
})();