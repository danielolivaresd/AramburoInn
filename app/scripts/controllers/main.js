'use strict';

/**
 * @ngdoc function
 * @name aramburoApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the aramburoApp
 */
angular.module('aramburoApp')
  .controller('MainCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
