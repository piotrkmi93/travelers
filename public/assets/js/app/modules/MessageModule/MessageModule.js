/**
 * Created by Piotr Kmiecik on 12.09.2016.
 */
(function(){
    'use strict';

    angular.module('MessageModule', [])

        .config(['$routeProvider', function($routeProvider){
            $routeProvider

                .when('/', {
                    template: '<h3>Proszę wybrać rozmówcę</h3>'
                })

                .when('/:username', {
                    templateUrl: 'assets/js/app/modules/MessageModule/views/conversation.html',
                    controller: 'ConversationController'
                })

                .otherwise({
                    redirectTo: '/'
                });
        }]);
})();