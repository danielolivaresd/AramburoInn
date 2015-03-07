'use strict';

/**
 * @ngdoc overview
 * @name aramburoApp
 * @description
 * # aramburoApp
 *
 * Main module of the application.
 */
angular
  .module('aramburoApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      })
      .when('/find', {
        templateUrl: 'views/find.html',
        controller: 'FindRoomSCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
