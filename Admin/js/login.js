$(document).ready(function() {
	$("#userData").tabs();
	$("#connect").button();
	$("#submit").button();
	$('#genderReg').buttonset();
	$("#country").selectmenu();
	$( "#addDeg" ).button({
	icon: "ui-icon ui-icon-plusthick",
		showLabel: true
	});
});
function removeField(number) {
        document.getElementById("member_" + number).remove();
}
function addField(){
	// Number of inputs to create
	var number = document.querySelectorAll(".educationMember").length;
	// Container <div> where dynamic content will be placed
	var container = document.getElementById("education");
	// Append a line break
	if (number != 0) {
		container.appendChild(document.createElement("br"));
	}
	var parent = document.createElement("div");
	parent.className = "educationMember";
	parent.id = "member_" + number;
	var select = document.createElement("select");
	select.innerHTML = '\n<option value = "HS">High School</option>\n<option value = "Cert">Certification</option>\n<option value = "BS">Bachelors Degree</option>\n<option value = "MS">Masters Degree</option>\n<option value = "PhD">PhD</option>\n';
	parent.appendChild(select);
	var schoolNameInput = document.createElement("input");
	schoolNameInput.type = "text";
	schoolNameInput.maxlength = 50;
	schoolNameInput.value = "";
	schoolNameInput.placeholder = " Enter School Name";
	schoolNameInput.classList.add("tbx");
	schoolNameInput.name = "schoolName_" + number;
	schoolNameInput.id = "schoolName_" + number;
	parent.appendChild(schoolNameInput);
	var majorInput = document.createElement("input");
	majorInput.type = "text";
	majorInput.maxlength = 50;
	majorInput.value = "";
	majorInput.placeholder = " Enter Major";
	majorInput.classList.add("tbx");
	majorInput.name = "major_" + number;
	majorInput.id = "major_" + number;
	parent.appendChild(majorInput);
	parent.appendChild(document.createTextNode(" Year Graduated:"));
	var graduationYearInput = document.createElement("input");
	graduationYearInput.type = "number";
	graduationYearInput.maxlength = 4;
	graduationYearInput.value = 2022;
	graduationYearInput.classList.add("spinner");
	graduationYearInput.name = "gradYear_" + number;
	graduationYearInput.id = "gradYear_" + number;
	parent.appendChild(graduationYearInput);
	//<input name = "addEntry" class = "btn"  id = "addDeg" type = "button" value = "Add degree" onclick = "addField()" />
	var deleteInputFieldButton = document.createElement("button");
	deleteInputFieldButton.className = "ui-button ui-corner-all ui-widget";
	deleteInputFieldButton.type = "button";
	deleteInputFieldButton.value = "Remove Degree";
	deleteInputFieldButton.id = "Div_" + number;
	deleteInputFieldButton.innerHTML='<span class="ui-icon ui-icon-trash"></span>';
	deleteInputFieldButton.onclick = function(){removeField(number);};
	parent.appendChild(deleteInputFieldButton);
	container.appendChild(parent);
}