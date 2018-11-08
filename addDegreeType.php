<?php
    require_once "database.php";

    require_once "session.php";

    if($type < 3){
        header("Location:index.php");
        die;
    }

    if(isset($_POST['add'])){
        $degreeTypeName = trim($_POST['addDegreeType']);

        addDegreeType($degreeTypeName);

        header("Location:index.php");
        die;
    }
    elseif (isset($_POST['edit'])) {
        $oldName = trim($_POST['degreeType']);
        $newName = trim($_POST['editDegreeType']);

        editDegreeType($oldName, $newName);

        header("Location:index.php");
        die;
    }
    elseif (isset($_POST['delete'])) {
        $degreeTypeName = trim($_POST['degreeType']);

        deleteDegreeType($degreeTypeName);

        header("Location:index.php");
        die;
    }

?>

<div id="addDegreeModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-lime w3-center w3-padding-32">
            <span onclick="document.getElementById('addDegreeModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
            <h2 class="w3-wide"><i class="w3-margin-right"></i>Add Country </h2>
        </header>
        <form method = 'post' action="addDegreeType.php" class="w3-container">
            <h1>Add new Degree Type</h1>
            <p>
                <label>Degree Type Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="addDegreeType" id="addDegreeType" />

            <button class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" type = "submit" name = "add">Add DegreeType
                <i class="fa fa-check"></i>
            </button>
        </form>

        <form method = 'post' action = "addDegreeType.php" class = "w3-container">
            <h1>Edit a Degree Type</h1>
            <p>
                <label>Select Degree Type to Edit</label>
            </p>

            <select class="w3-select w3-border" name="degreeType">
                <?php print listDegreeTypes(); ?>
            </select>

            <p>
                <label>Enter New Degree Type Name</label>
            </p>
            <input class="w3-input w3-border" type="text" maxlength="50" value="" name="editDegreeType" id="editDegreeType" />
            <p>
                <label>Or Delete Selected Degree Type</label>
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
