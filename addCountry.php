<?php
    require_once "database.php";

    if(isset($_POST['add'])){
        $countryName = trim($_POST['addCountry']);

        addCountry($countryName);
        header('Location: index.php');
        die;
    } elseif (isset($_POST['edit'])) {
        $oldName = trim($_POST['country']);
        $newName = trim($_POST['editCountry']);

        editCountry($oldName, $newName);
        header('Location: index.php');
        die;
    } elseif (isset($_POST['delete'])) {
        $countryName = trim($_POST['country']);

        deleteCountry($countryName);
        header('Location: index.php');
        die;
    }

?>

<div id="addCountryModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('addCountryModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Add Country </h2>
        </header>
        <form method = 'post' action="addCountry.php" class="w3-container">
            <h1>Add new Country</h1>
            <p>
                <label>Country Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="addCountry" id="addCountry" />

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "submit" name = "add">Add Country
                <i class="fa fa-check"></i>
            </button>
        </form>

        <form method = 'post' action = "addCountry.php" class = "w3-container">
            <h1>Edit a Country</h1>
            <p>
                <label>Select Country to Edit</label>
            </p>

            <select class="w3-select w3-border" name="country">
                <?php print listCountries(); ?>
            </select>

            <p>
                <label>Enter New Country Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="editCountry" id="editCountry" />

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit" name="edit">Edit
                <i class="fa fa-check"></i>
            </button>

            <p>
                <label>Or Delete Selected Country</label>
            </p>
            <button class="w3-button w3-red w3-section" type = "submit" name = "delete">Delete
                <i class="fa fa-remove"></i>
            </button>



        </form>
    </div>
</div>
