<?php
require_once "session.php";
require_once "database.php";

if (isset($_POST['submit'])) {

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    $file_name = $_FILES['profile']['name'];
    $file_size = $_FILES['profile']['size'];
    $file_tmp = $_FILES['profile']['tmp_name'];
    $file_type = $_FILES['profile']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['profile']['name'])));

    $target = $image_dir_path . DIRECTORY_SEPARATOR . $file_name;
    move_uploaded_file($file_tmp, $target);

    registerNewPicture($_SESSION["account_ID"], $target);
    header("location: profile.php");
}

if (isset($_GET['user'])) {
    if (isset($_SESSION["account_ID"])) {
        if ($_SESSION["account_ID"] == $_GET['user']) {
            header("location: profile.php");
        }
    }
    $account_id = $_GET['user'];
    $allowEdit = FALSE;
} elseif (isset($_SESSION["account_ID"])) {
    $account_id = $_SESSION["account_ID"];
    $allowEdit = TRUE;
} else {
    header("location: index.php");
}

function makeEditable($allowEdit, $id) {
    if ($allowEdit) {
        return ' <a class="w3-button" onclick="enterEditState(\'' . $id . '\');"><i class="fa fa-pencil fa-fw w3-large w3-text-lime w3-opacity"></i></a>';
    } else {
        return "";
    }
}

function putItInASpan($thing) {
    return "<span>" . $thing . "</span>";
}

function formatDegrees($degrees) {
    $result = "";
    foreach($degrees as $degree) {
        $result .= '<div class="w3-container"><h5 class="w3-opacity"><b>';
        $result .= $degree[1] . " / " . $degree[0];
        $result .= '</b></h5><h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>';
        $result .= $degree[3] . " - " . $degree[2];
        $result .= '</h6><hr></div>';
    }
    return $result;
}

function formatJobs($jobs) {
    $result = "";
    foreach ($jobs as $job) {
        $result .= '<div class="w3-container"><h5 class="w3-opacity"><b>';
        $result .= $job[1] . " / " . $job[0];
        $result .= '</b></h5><h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>';
        $result .= $job[2] . " - " . $job[3];
        $result .= '</h6><hr></div>';
    }
    return $result;
}
?>
<!-- template from: https://www.w3schools.com/w3css/w3css_templates.asp -->
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAConnect Profile</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/closeModals.js"></script>
    <script>
        // Used to toggle the menu on small screens when clicking on the menu button
        function toggleNav() {
            let x = document.getElementById("navMobile");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        
        function init() {

        }

        function cancel() {
            window.location.reload(true);
            return false;
        }

        function enterEditState(id) {
            document.getElementById(id).className += "w3-cell-row";
            if (id == "gender") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i>Gender:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="gender" id="gender">
                        <option value="0"> Male </option>
                        <option value="1"> Female </option>
                        <option value="2"> Nonbinary/Other </option>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Gender</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            } else if (id == "status") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i>Status:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="status" id="status">
                        <option value="0"> Student </option>
                        <option value="1"> Working Professional </option>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Status</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            } else if (id == "email") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i>Email:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="text" maxlength="50" value="<?php echo getEmail($account_id); ?>" name="email" id="email"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Email</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            } else if (id == "phone") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i>Phone Number:</p>
                    <form method="post" action="updateProfile.php">
                    <input class="w3-input w3-border w3-cell" type="tel" value="<?php echo getPhoneNumber($account_id); ?>" name="phone"/>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Phone</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            } else if (id == "location") {
                document.getElementById(id).innerHTML = `
                    <form method="post" action="updateProfile.php">
                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 1:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine1($account_id); ?>" name="addr1"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Address Line 2:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getAddressLine2($account_id); ?>" name="addr2"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>City:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getCity($account_id); ?>" name="city"/>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>State:</p>
                    <select class="w3-select w3-border" name="state" id="state">
                        <?php echo getStatesList(getCountryID($account_id), $account_id); ?>
                    </select>

                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i>Post code:</p>
                    <input class="w3-input w3-border" type="text" value="<?php echo getPostCode($account_id); ?>" name="postcode"/>

                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Location</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            } else if (id == "country") {
                document.getElementById(id).innerHTML = `
                    <p><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i>Country:</p>
                    <form method="post" action="updateProfile.php">
                    <select class="w3-select w3-border w3-cell" name="country" id="country">
                        <?php echo listCountries($account_id) ?>
                    </select>
                    <button class="w3-button w3-half w3-lime w3-cell w3-margin-top" type="submit" name="submit">Edit Country</button>
                    <button class="w3-button w3-half w3-red w3-cell w3-margin-top" type="button" onclick="cancel();">Cancel</button>
                    </form>`;
            }
        }
    </script>
</head>

<body class="w3-light-grey" onload="init();">
<!-- Navbar -->
<?php include "header.php"; ?>
<!-- Page content -->

<div class="w3-content" style="max-width:1400px;">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <!-- Left Column -->
        <div class="w3-third">

            <div class="w3-white w3-text-grey w3-card-4">
                <div class="w3-display-container">
                    <img src="data:image/jpeg;base64,<?php echo file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $account_id); ?>" style="width:100%;" alt="Avatar">
                    <div class="w3-display-middle w3-display-hover w3-xlarge">
                        <?php if ($allowEdit) { echo "<button class=\"w3-button w3-black\" onclick=\"document.getElementById('uploadPicModal').style.display='block'\">Change Picture...</button>";} ?>
                    </div>
                    <div class="w3-display-bottomleft w3-container w3-text-black">
                        <h2 class="w3-text-white" style="text-shadow:1px 1px 0 #444"><?php echo getName($account_id) ?></h2>
                    </div>
                </div>
                <div class="w3-container">

                    <p id="gender"><i class="fa fa-user fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getGender($account_id)) . makeEditable($allowEdit, "gender")?></p>
                    <p id="status"><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getStatus($account_id)) . makeEditable($allowEdit, "status")?></p>
                    <p id="country"><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getCountry($account_id)) . makeEditable($allowEdit, "country")?></p>
                    <p id="location"><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getApproximateLocation($account_id)) . makeEditable($allowEdit, "location")?></p>
                    <p id="email"><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getEmail($account_id)) . makeEditable($allowEdit, "email")?></p>
                    <p id="phone"><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo putItInASpan(getPhoneNumber($account_id)) . makeEditable($allowEdit, "phone")?></p>
                    <hr>

                    <button class="w3-button w3-block w3-dark-grey">+ Connect</button>
                    <br>
                </div>
            </div><br>

            <!-- End Left Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-twothird">

            <div class="w3-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>
                <?php echo formatJobs(getJobs($account_id)); ?>
            </div>

            <div class="w3-container w3-card w3-white">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>
                <?php echo formatDegrees(getDegrees($account_id)); ?>
            </div>

            <!-- End Right Column -->
        </div>

        <!-- End Grid -->
    </div>

<?php if ($allowEdit) { echo "
<div id=\"uploadPicModal\" class=\"w3-modal\">
        <div class=\"w3-modal-content w3-animate-top w3-card-4\">
            <header class=\"w3-container w3-lime w3-center w3-padding-32\">
            <span onclick=\"document.getElementById('uploadPicModal').style.display='none'\"
                  class=\"w3-button w3-lime w3-xlarge w3-display-topright\">×</span>
                <h2 class=\"w3-wide\"><i class=\"w3-margin-right\"></i>Change Profile Picture </h2>
            </header>
            <form method=\"post\" action=\"profile.php\" enctype='multipart/form-data' class=\"w3-container\">
                <p>
                    <label>
                        <i class=\"fa fa-user\"></i> New picture:
                    </label>
                </p>
                <input class=\"w3-input w3-border\" type=\"file\" placeholder=\"\" name=\"profile\" id=\"profile\" accept=\"image/png, image/jpeg\">
                <button class=\"w3-button w3-block w3-lime w3-padding-16 w3-section w3-right\" type=\"submit\" name=\"submit\">
                    Submit New Photo
                    <i class=\"fa fa-check\"></i>
                </button>
                <button type=\"button\" class=\"w3-button w3-red w3-section\"
                        onclick=\"document.getElementById('uploadPicModal').style.display='none'\">Close
                    <i class=\"fa fa-remove\"></i>
                </button>
            </form>
        </div>
    </div>";} ?>

    <!-- End Page Container -->
</div>
<!-- End Page Content -->
</body>
</html>
