$(document).ready(function() {
	//admin commands
	$("#command").accordion();
	//select a mentor
	$("#mentor").selectmenu();
	//select a mentee
	$("#mentee").selectmenu();
	//sumbit a match manually
	$( "#match" ).button({
		icon: "ui-icon ui-icon-check",
		showLabel: true
	});	
	$( "#logout" ).button({
		icon: "ui-icon ui-icon-locked",
		showLabel: true
	});
	$( "#settings" ).button({
		icon: "ui-icon ui-icon-gear",
		showLabel: true
	});
	$("#type").selectmenu();
	$( "#editSearch" ).button({
		icon: "ui-icon ui-icon-search",
		showLabel: true
	});
	$("#upgrade").button();
	$( "#searchDB" ).button({
		icon: "ui-icon ui-icon-search",
		showLabel: true
	});
});