<?php
    session_start();
    require_once "database.php";
?>
<div id="addStateModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('addStateModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Add Country </h2>
        </header>
        <form method = 'post' action = "addState.php" class = "w3-container">
            <p>
                <label>Select Country</label>
            </p>

            <select class="w3-select w3-border" name="country">
                <?php print listCountries(); ?>
            </select>

        </form>
        <form method = 'post' action="addCountry.php" class="w3-container">

            <h1>Add new State</h1>
            <p>
                <label>State Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="addState" id="addState" />

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "submit" name = "add">Add State
                <i class="fa fa-check"></i>
            </button>
        </form>

        <form method = 'post' action = "addState.php" class = "w3-container">
            <h1>Edit a State</h1>
            <p>
                <label>Select State to Edit</label>
            </p>

            <select class="w3-select w3-border" name="country">
                <?php// print getStates($countryID); ?>
            </select>

            <p>
                <label>Enter New State Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="editState" id="editState" />
            <p>
                <label>Or Delete Selected State</label>
            </p>
            <button type="button" class="w3-button w3-red w3-section" type = "submit" name = "delete">Delete
                <i class="fa fa-remove"></i>
            </button>

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type="submit" name="edit">Edit
                <i class="fa fa-check"></i>
            </button>

        </form>
    </div>
</div>
