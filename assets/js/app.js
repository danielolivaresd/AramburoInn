//Min dates for room finding
Date.prototype.toDateInputValue = (function() {
	var local = new Date();
	local.setHours(0,0,0,0);
	return local.toJSON().slice(0,10);
});
Date.prototype.tomorrow = (function(){
	var local = new Date();
	local.setHours(0,0,0,0);
	local.setDate(local.getDate()+1);
	return local.toJSON().slice(0,10);
});
Date.prototype.tomorrowDate = (function(){
	var local = new Date();
	local.setHours(0,0,0,0);
	local.setDate(local.getDate()+1);
	return local;
});
$(document).ready(function(){
	$("input[name=arrivalDate]").attr("min", new Date().toDateInputValue()).val(new Date().toDateInputValue());
	$("input[name=leaveDate]").attr("min", new Date().tomorrow()).val(new Date().tomorrow());

	if($("input[name=people]").val()==0){
		$("input[name=people]").val(1);
	}

	$(document).on("click", "li.pure-menu-item", function(){
		//Remove pure-menu-selected
		$("ul.pure-menu-list li").removeClass("pure-menu-selected");
		$(this).addClass("pure-menu-selected");
	});
});

//Angular

var app = angular.module("hotel", []);
app.controller("FindRoomController", ["$scope","$filter", function($scope,$filter){
	var today = new Date();
	today.setHours(0,0,0,0);
	$scope.arrivalDate=today;
	$scope.leaveDate=new Date(today.tomorrowDate());
	$scope.people=0;
	$scope.availableRooms=[];
	$scope.rooms=[
	{
		id:1,
		name:"101a",
		type:"Individual",
		max_people:2,
		reservations: [1]
	},
	{
		id:2,
		name:"201",
		type:"Doble",
		max_people:4,
		reservations: [2]
	},
	{
		id:3,
		name:"305",
		type:"Familiar",
		max_people:6,
		reservations: []
	}
	]
	$scope.reservations=[
	{
		id:1,
		maker: "Rodrigo López",
		people: ["Rodrigo López", "Javier Martinez"],
		from: new Date(2015,04,04), //4 de Mayo de 2015
		to: new Date(2015,04,06),
		room_id: 1
	},
	{
		id:2,
		maker: "Fernanda Rosales",
		people: ["Fernanda Rosales", "Javier Gómez", "Lola Gómez", "Junior Gómez"],
		from: new Date(2015,05,04), //4 de Junio de 2015
		to: new Date(2015,05,14),
		room_id: 2
	}
	]
	$scope.validateLeaveDate=function(){
		if($scope.leaveDate < $scope.arrivalDate){
			console.log("Leave date cannot be less than arrival date.");
			$scope.leaveDate = $scope.arrivalDate;
		}
	}
	$scope.findRoomForPeople=function(){

	}
	$scope.availableOn=function(arrival,leave){
		var from = $scope.arrivalDate;
		var to = $scope.leaveDate;
		var rooms = $scope.rooms;
		var result = rooms;
		var reservations = $scope.reservations;
		//Get all rooms that don't belong to a reservation during the time [from..to]
		var filtered_reservations = $filter("betweenDates")(reservations, from, to); //All the reservations in the timeframe.
		//console.log(filtered_reservations);

		//Remove the rooms from the filtered reservations
		angular.forEach(rooms, function(obj){
			angular.forEach(filtered_reservations, function(r){
				if(obj.reservations.indexOf(r.room_id)>=0){ //Contains a reservation
					console.log("Room "+obj.id+" contains a reservation "+r.id);
					result.splice(result.indexOf(obj),1);
				}
			});
		});

		console.log(result);

/*		var availableRooms = $filter("notInFilteredReservations")(rooms, filtered_reservations);
		console.log(availableRooms);
*/
	}
}]);
app.filter('betweenDates', function(){

	return function(input, arrivalDate, leaveDate) {
		var filtered = [];
		angular.forEach(input, function(obj){
			if(obj.from >= arrivalDate && obj.to <= leaveDate)   {
				filtered.push(obj);
			}
		});
		return filtered;
	};
});
/*app.filter("notInFilteredReservations", function(){
	return function(input, filtered_reservations){
		var filtered=[];
		angular.forEach(input, function(obj){
			angular.forEach(filtered_reservations, function(fr){
				if(obj!=fr.room_id && $.inArray(obj, filtered) == -1){
					filtered.push(obj);
				}
			});
		});
		return filtered;
	}
});*/