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
        '720kb.tooltips',

        'HomeModule',
        'CityModule',
        'RegisterModule',
        'UserModule',
        'NotificationModule',
        'PostModule',
        'LikeModule',
        'CommentModule',
        'MessageModule',
        'PlaceModule',
        'BugReportModule',
        'SearchEngineModule',
        'TripModule'
    ]);
})();