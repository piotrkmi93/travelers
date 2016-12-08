/**
 * Created by Piotr on 18.08.2016.
 */
(function(){
    angular.module('app', [
        'config',
        'ui.bootstrap',
        'infinite-scroll',
        'ngSanitize',
        'wysiwyg.module',
        'luegg.directives',
        'uiGmapgoogle-maps',
        'jkuri.gallery',

        'CityModule',
        'RegisterModule',
        'UserModule',
        'NotificationModule',
        'PostModule',
        'LikeModule',
        'CommentModule',
        'MessageModule',
        'PlaceModule'
    ]);
})();