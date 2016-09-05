/**
 * Created by Piotr on 27.08.2016.
 */
(function(){
    angular.module('PostModule')
        .directive('addPost', ['UserService', 'PostService', '$interval', 'SERVER', function(UserService, PostService, $interval, SERVER){

            return {
                restrict: 'E',
                templateUrl: SERVER.url + 'assets/js/app/modules/PostModule/directives/add-post/add-post.html',
                scope: {
                    user_id: '@userId'
                },
                link: function(scope){

                    var newPostImageInput = angular.element(document.querySelector('#new-post-image-input'));
                    var newPostImage = angular.element(document.querySelector('img#new-post-image'));

                    scope.form = {
                        text: undefined,
                        photo: undefined
                    };

                    scope.newPostSending = false;

                    UserService.getUserBasicsById(scope.user_id)
                        .then(function(user){
                            scope.user = user;
                        });

                    scope.choosePostImageClick = function(){
                        newPostImageInput.click();
                        var interval = $interval(function(){
                            if (newPostImageInput.context.files.length) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    newPostImage.attr('src', e.target.result);
                                };
                                reader.readAsDataURL(newPostImageInput.context.files[0]);
                                scope.form.photo = newPostImageInput.context.files[0];
                                $interval.cancel(interval);
                            }
                        }, 100);
                    };

                    scope.addPost = function(){
                        if (scope.form && scope.form.text) {
                            scope.newPostSending = true;
                            PostService.addPost(scope.user_id, scope.form).then(function(data){
                                if (data.post_id) {
                                    scope.newPostSending = false;
                                    scope.form = {
                                        text: undefined,
                                        photo: undefined
                                    };
                                    newPostImage.removeAttr('src');
                                    newPostImageInput.val('');
                                    scope.$emit('post-added');
                                }
                            });
                        }
                    };
                }
            };
        }]);
})();