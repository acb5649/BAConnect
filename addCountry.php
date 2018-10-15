<?php
    require_once "functions.php";

    if(isset($_POST['add'])){
        $countryName = trim($_POST['addCountry']);

        AddCountry($countryName);
    }

?>
<form method = 'post' action="addCountry.php" class="w3-container">
    <h1>Add new Country</h1>
    <p>
    <label>Country Name</label>
    </p>
    <input class="w3-input w3-border" type="text" maxlength="50" value="" name="addCountry" id="addCountry" />

    <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "submit" name = "add">Add Country
</form>
