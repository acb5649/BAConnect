<?php
require_once "session.php";
require_once "database.php";
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
                    <img src="https://soulcore.com/wp-content/uploads/2018/01/profile-placeholder.png" style="width:100%" alt="Avatar">
                    <div class="w3-display-bottomleft w3-container w3-text-black">
                        <h2><?php echo getName($_SESSION["account_ID"]) ?></h2>
                    </div>
                </div>
                <div class="w3-container">
                    <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo getStatus($_SESSION["account_ID"]) ?></p>
                    <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo getApproximateLocation($_SESSION["account_ID"]) ?></p>
                    <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo getEmail($_SESSION["account_ID"]) ?></p>
                    <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-lime"></i><?php echo getPhoneNumber($_SESSION["account_ID"]) ?></p>
                    <hr>

                    <button class="w3-button w3-block w3-dark-grey">+ Connect</button>
                    <hr>

                    <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-lime"></i>Skills</b></p>
                    <p>Adobe Photoshop</p>
                    <div class="w3-light-grey w3-round-xlarge w3-small">
                        <div class="w3-container w3-center w3-round-xlarge w3-lime" style="width:90%">90%</div>
                    </div>
                    <p>Photography</p>
                    <div class="w3-light-grey w3-round-xlarge w3-small">
                        <div class="w3-container w3-center w3-round-xlarge w3-lime" style="width:80%">
                            <div class="w3-center w3-text-white">80%</div>
                        </div>
                    </div>
                    <p>Illustrator</p>
                    <div class="w3-light-grey w3-round-xlarge w3-small">
                        <div class="w3-container w3-center w3-round-xlarge w3-lime" style="width:75%">75%</div>
                    </div>
                    <p>Media</p>
                    <div class="w3-light-grey w3-round-xlarge w3-small">
                        <div class="w3-container w3-center w3-round-xlarge w3-lime" style="width:50%">50%</div>
                    </div>
                    <br>

                    <p class="w3-large w3-text-theme"><b><i class="fa fa-globe fa-fw w3-margin-right w3-text-lime"></i>Languages</b></p>
                    <p>English</p>
                    <div class="w3-light-grey w3-round-xlarge">
                        <div class="w3-round-xlarge w3-lime" style="height:24px;width:100%"></div>
                    </div>
                    <p>Spanish</p>
                    <div class="w3-light-grey w3-round-xlarge">
                        <div class="w3-round-xlarge w3-lime" style="height:24px;width:55%"></div>
                    </div>
                    <p>German</p>
                    <div class="w3-light-grey w3-round-xlarge">
                        <div class="w3-round-xlarge w3-lime" style="height:24px;width:25%"></div>
                    </div>
                    <br>
                </div>
            </div><br>

            <!-- End Left Column -->
        </div>

        <!-- Right Column -->
        <div class="w3-twothird">

            <div class="w3-container w3-card w3-white w3-margin-bottom">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Work Experience</h2>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>Front End Developer / w3schools.com</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Jan 2015 - <span class="w3-tag w3-lime w3-round">Current</span></h6>
                    <p>Lorem ipsum dolor sit amet. Praesentium magnam consectetur vel in deserunt aspernatur est reprehenderit sunt hic. Nulla tempora soluta ea et odio, unde doloremque repellendus iure, iste.</p>
                    <hr>
                </div>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>Web Developer / something.com</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Mar 2012 - Dec 2014</h6>
                    <p>Consectetur adipisicing elit. Praesentium magnam consectetur vel in deserunt aspernatur est reprehenderit sunt hic. Nulla tempora soluta ea et odio, unde doloremque repellendus iure, iste.</p>
                    <hr>
                </div>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>Graphic Designer / designsomething.com</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Jun 2010 - Mar 2012</h6>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p><br>
                </div>
            </div>

            <div class="w3-container w3-card w3-white">
                <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-lime"></i>Education</h2>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>W3Schools.com</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Forever</h6>
                    <p>Web Development! All I need to know in one place</p>
                    <hr>
                </div>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>London Business School</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2013 - 2015</h6>
                    <p>Master Degree</p>
                    <hr>
                </div>
                <div class="w3-container">
                    <h5 class="w3-opacity"><b>School of Coding</b></h5>
                    <h6 class="w3-text-lime"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2010 - 2013</h6>
                    <p>Bachelor Degree</p><br>
                </div>
            </div>

            <!-- End Right Column -->
        </div>

        <!-- End Grid -->
    </div>

    <!-- End Page Container -->
</div>
<!-- End Page Content -->
</body>
</html>
