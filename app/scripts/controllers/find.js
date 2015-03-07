'use strict';

/**
* @ngdoc function
* @name aramburoApp.controller:MainCtrl
* @description
* # MainCtrl
* Controller of the aramburoApp
*/
angular.module('aramburoApp')
.controller('FindRoomsCtrl', function ($scope) {

    var today = new Date();

    today.setHours(0,0,0,0);
    $scope.arrivalDate = today;
    $scope.leaveDate = new Date(today.tomorrowDate());
    $scope.people = 1;
    $scope.availableRooms = [];
    $scope.peopleRangeArray = [];

    $scope.peopleRange = function(){
        $scope.peopleRangeArray = [];
        for(var i = 0; i < $scope.people - 1; i++){
            $scope.peopleRangeArray.push(i);
        }
    };

    $scope.initRooms = function(){
        $scope.rooms=[{
                id:1,
                name:"101a",
                type:"Individual",
                max_people:2,
                reservations: [1],
                price: 300
            },
            {
                id:2,
                name:"201",
                type:"Doble",
                max_people:4,
                reservations: [2],
                price: 550
                },
            {
                id:3,
                name:"305",
                type:"Familiar",
                max_people:6,
                reservations: [],
                price: 900
            }
        ];};

        $scope.reservations = [
            {
                id:1,
                maker: "Rodrigo López",
                maker_age: 18,
                maker_email: "rodrigo@gmail.com",
                maker_phone: 12345,
                maker_credit_card: 123412341234123,
                maker_ccv: 159,
                people: ["Rodrigo López", "Javier Martinez"],
                from: new Date(2015,04,04),  //4 de Mayo de 2015
                to: new Date(2015,04,06),
                room_id: 1
            },
            {
                id:2,
                maker: "Fernanda Rosales",
                maker_age: 18,
                maker_email: "fernanda@gmail.com",
                maker_phone: 12345,
                maker_credit_card: 123412341234123,
                maker_ccv: 159,
                people: ["Fernanda Rosales", "Javier Gómez", "Lola Gómez", "Junior Gómez"],
                from: new Date(2015,05,04), //4 de Junio de 2015
                to: new Date(2015,05,14),
                room_id: 2
            }];

        $scope.validateLeaveDate=function(){
            if($scope.leaveDate < $scope.arrivalDate){
                $scope.leaveDate = $scope.arrivalDate;
                }
        };

        $scope.findRoomForPeople=function(){
            $scope.initRooms();
            $scope.peopleRange();
            $scope.availableRooms = $scope.rooms;
            $scope.validateLeaveDate();
            $scope.availableRooms = $scope.availableOn();
        };

        $scope.availableOn=function(){
            var from = $scope.arrivalDate;
            var to = $scope.leaveDate;
            var rooms = $scope.rooms;
            var result = rooms;
            var reservations = $scope.reservations;
            //Get all rooms that don't belong to a reservation during the time [from..to]
            var filtered_reservations = $filter("betweenDates")(reservations, from, to); //All the reservations in the timeframe.

            //Remove the rooms from the filtered reservations
            angular.forEach(rooms, function(obj){
                angular.forEach(filtered_reservations, function(r){
                    if(obj.reservations.indexOf(r.room_id) >= 0){ //Contains a reservation
                        result.splice(result.indexOf(obj), 1);
                    }
                });
            });

            return result;
        };
});
