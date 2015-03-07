$(document).ready(function(){
	//Min dates for room finding
	Date.prototype.toDateInputValue = (function() {
		var local = new Date(this);
		local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
		return local.toJSON().slice(0,10);
	});
	Date.prototype.tomorrow = (function(){
		var local = new Date(this);
		local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
		local.setDate(local.getDate()+1);
		return local.toJSON().slice(0,10);
	});
	$("input[name=arrivalDate]").attr("min", new Date().toDateInputValue()).val(new Date().toDateInputValue());
	$("input[name=leaveDate]").attr("min", new Date().tomorrow()).val(new Date().tomorrow());

	$(document).on("click", "li.pure-menu-item", function(){
		//Remove pure-menu-selected
		$("ul.pure-menu-list li").removeClass("pure-menu-selected");
		$(this).addClass("pure-menu-selected");
	});
});

//Angular

var app = angular.module("hotel", []);
app.controller("FindRoomController", ["$scope","$filter", function($scope,$filter){
	$scope.arrivalDate="";
	$scope.leaveDate="";
	$scope.validateLeaveDate=function(){
		if($scope.leaveDate < $scope.arrivalDate){
			console.log("Leave date cannot be less tan arrival date.");
			$scope.leaveDate = $scope.arrivalDate;
		}
	}
}]);
