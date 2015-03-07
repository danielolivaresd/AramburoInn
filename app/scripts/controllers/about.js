'use strict';

/**
 * @ngdoc function
 * @name aramburoApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the aramburoApp
 */
angular.module('aramburoApp')
  .controller('AboutCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
