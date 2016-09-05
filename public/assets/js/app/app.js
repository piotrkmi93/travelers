/**
 * Created by Piotr on 18.08.2016.
 */
(function(){
    angular.module('app', [
        'config',
        'ui.bootstrap',
        'infinite-scroll',
        // 'textAngular',
        'wysiwyg.module',

        'CityModule',
        'RegisterModule',
        'UserModule',
        'NotificationModule',
        'PostModule',
        'LikeModule',
        'CommentModule'
    ]);
})();