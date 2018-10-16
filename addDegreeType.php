degreeType<?php
    require_once "functions.php";

    if(isset($_POST['add'])){
        $degreeType = trim($_POST['addDegreeType']);

        AddDegreeType($degreeType);
    }

?>
<form method = 'post' action="addDegreeType.php" class="w3-container">
    <h1>Add new Degree Type</h1>
    <p>
    <label>Degree Type Name</label>
    </p>
    <input class="w3-input w3-border" type="text" maxlength="50" value="" name="addDegreeType" id="addDegreeType" />

    <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "submit" name = "add">Add Degree Type
</form>
