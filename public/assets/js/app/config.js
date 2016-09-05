(function () {
    'use strict';

    angular.module('config', [])
        .constant('SERVER', {
            url: 'http://localhost:8000/'
        })
        .config(function($interpolateProvider){
            $interpolateProvider.startSymbol('<%').endSymbol('%>');
        });
})();
