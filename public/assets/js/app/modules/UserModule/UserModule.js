(function () {
    'use strict';

    angular.module('UserModule', ['ngRoute'])

        .config(['$routeProvider', 'SERVER', function($routeProvider, SERVER){
            $routeProvider

                .when('/', {
                    redirectTo: '/board'
                })

                .when('/board', {
                    templateUrl: SERVER.url + 'assets/js/app/modules/UserModule/views/board.html',
                    controller: 'UserBoardController'
                })

                .when('/information', {
                    templateUrl: SERVER.url + 'assets/js/app/modules/UserModule/views/information.html',
                    controller: 'UserInformationController'
                })

                .when('/gallery', {
                    templateUrl: SERVER.url + 'assets/js/app/modules/UserModule/views/gallery.html',
                    controller: 'UserGalleryController'
                })

                .when('/friends', {
                    templateUrl: SERVER.url + 'assets/js/app/modules/UserModule/views/friends.html',
                    controller: 'UserFriendsController'
                })

                .when('/places', {
                    templateUrl: SERVER.url + 'assets/js/app/modules/UserModule/views/places.html',
                    controller: 'UserPlaceController'
                })

                .otherwise({redirectTo: '/board'});
        }]);
})();