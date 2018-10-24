function removeField(number) {
    console.log("Removing field " + number);

    document.getElementById("member_" + number).remove();
    document.getElementById("break_" + number).remove();

    var fieldCount = 0;
    var divs = document.querySelectorAll(".educationMember");
    [].forEach.call(divs, function(div) {
        var newNum = fieldCount.valueOf();
        var oldNumber = div.id.substring(7);
        div.id = "member_" + fieldCount;

        var brk = document.getElementById("break_" + oldNumber);
        brk.id = "break_" + newNum;
        var schoolName = document.getElementById("schoolName_" + oldNumber);
        schoolName.id = "schoolName_" + newNum;
        var majorName = document.getElementById("major_" + oldNumber);
        majorName.id = "major_" + newNum;
        var year = document.getElementById("gradYear_" + oldNumber);
        year.id = "gradYear_" + newNum;
        var button = document.getElementById("deleteButton_" + oldNumber);
        button.id = "deleteButton_" + newNum;
        button.onclick = function() {
            console.log("deleting new number: " + newNum);
            removeField(newNum);
        };
        fieldCount = fieldCount + 1;
    });

    document.getElementById("numDegs").value = document.querySelectorAll(".educationMember").length;
}

function addField() {
    // Number of inputs to create
    var number = document.querySelectorAll(".educationMember").length;
    // Container <div> where dynamic content will be placed
    var container = document.getElementById("education");
    // Append a line break
    if (number != 0) {
        var brk = document.createElement("br");
        brk.id = "break_" + number;
        container.appendChild(brk);
    }

    var parent = document.createElement("div");
    parent.className = "educationMember";
    parent.id = "member_" + number;

    var select = document.createElement("select");
    select.name = "degreeType_" + number;
    select.id = "degreeType_" + number;
    select.className = "w3-select w3-border";
    select.innerHTML = '<?php print listDegreeTypes(); ?>';

    parent.appendChild(select);

    var schoolNameInput = document.createElement("input");
    schoolNameInput.type = "text";
    schoolNameInput.maxlength = 50;
    schoolNameInput.value = "";
    schoolNameInput.placeholder = "School Name";
    schoolNameInput.name = "schoolName_" + number;
    schoolNameInput.id = "schoolName_" + number;
    schoolNameInput.className = "w3-input w3-border";
    parent.appendChild(schoolNameInput);

    var majorInput = document.createElement("input");
    majorInput.type = "text";
    majorInput.maxlength = 50;
    majorInput.value = "";
    majorInput.placeholder = "Major";
    majorInput.name = "major_" + number;
    majorInput.id = "major_" + number;
    majorInput.className = "w3-input w3-border";
    parent.appendChild(majorInput);

    parent.appendChild(document.createTextNode("Year Graduated:"));

    var graduationYearInput = document.createElement("input");
    graduationYearInput.type = "number";
    graduationYearInput.maxlength = 4;
    graduationYearInput.value = 2022;
    graduationYearInput.name = "gradYear_" + number;
    graduationYearInput.id = "gradYear_" + number;
    graduationYearInput.className = "w3-input w3-border";
    parent.appendChild(graduationYearInput);

    var deleteInputFieldButton = document.createElement("input");
    deleteInputFieldButton.className = "w3-button w3-lime w3-padding-16 w3-right";
    deleteInputFieldButton.type = "button";
    deleteInputFieldButton.value = "Remove Degree";
    deleteInputFieldButton.id = "deleteButton_" + number;
    deleteInputFieldButton.onclick = function() {
        console.log("deleting: " + number);
        removeField(number);
    };
    parent.appendChild(deleteInputFieldButton);

    container.appendChild(parent);

    document.getElementById("numDegs").value = number + 1;
}

function createWork() {
    var container = document.getElementById("work");

    var parent = document.createElement("div");
    parent.className = "WorkSection";
    parent.id = "WorkSection";

    var placeOfEmployment = document.createElement("input");
    placeOfEmployment.type = "text";
    placeOfEmployment.maxlength = 50;
    placeOfEmployment.value = "";
    placeOfEmployment.placeholder = "Name of Business";
    placeOfEmployment.name = "businessName";
    placeOfEmployment.id = "businessName";
    placeOfEmployment.className = "w3-input w3-border";
    parent.appendChild(placeOfEmployment);

    var jobTitle = document.createElement("input");
    jobTitle.type = "text";
    jobTitle.maxlength = 50;
    jobTitle.value = "";
    jobTitle.placeholder = "Job Title";
    jobTitle.name = "jobTitle";
    jobTitle.id = "jobTitle";
    jobTitle.className = "w3-input w3-border";
    parent.appendChild(jobTitle);

    container.appendChild(parent);
}

function init() {
    addField();
    createWork();
}