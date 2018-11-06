<?php

include_once "database.php";

if (isset($_GET["id"])) {
    echo createCard($_GET["id"]);
}

function formatDegreesAndJobs($degrees, $jobs) {
    $result = "";

    foreach($degrees as $degree) {
        $result = $result . "<p style='margin:0.5em;'><span class='w3-text-lime'><b>" . $degree[2] . "-" .$degree[3] . "</b></span> " . $degree[1] ."</p>";
        $result = $result . "<p style='margin:0.5em;'>" . $degree[0] . "</p>";
    }

    if (count($jobs) == 0) {
        return $result;
    }

    $result = $result . "<hr>";
    foreach($jobs as $job) {
        $result = $result . "<p style='margin:0.5em;'><span class='w3-text-lime'><b>" . $job[3] . "-" .$job[2] . "</b></span> " . $job[1] ."</p>";
        $result = $result . "<p style='margin:0.5em;'>" . $job[0]. "</p>";
    }
    return $result;
}

function createCard($account_id) {
    // get database entries for user
    // final product looks like this: https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_cards_buttons2

    $user = User::fromID($account_id);
    //$imageSrc = file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $account_id);
    //$imageSrc = "http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $account_id;

    return '<span class="w3-container" style="display: inline-block; text-align: center; vertical-align: middle;">
  <div class="w3-card-4 w3-margin-bottom">
  <header class="w3-container w3-pale-red">
    <h3>'.$user->formatName().'</h3>
  </header>
  <div class="w3-container w3-text-grey w3-white">
  <div class="w3-row-padding">
    <div class="w3-third">
        <div class = "w3-padding-32" style="position: relative; top: 50%;">
                <img id="' . $account_id . '" class="w3-circle w3-border" src="" style="width: 100%;" alt="Avatar">
        </div>
    </div>
    <div class="w3-twothird w3-small" style="text-align: left;">' . formatDegreesAndJobs(getDegrees($account_id), getJobs($account_id)) . '</div>
  </div>
  <hr>
  <p>' . getUserMentorshipPreference($account_id) . ' / ' . $user->formatGender() . ' / ' . $user->formatCityAndState() . '</p>
  </div>
  <a class="w3-button w3-block w3-dark-grey" href="profile.php?user=' . $account_id . '">+ Connect</a>
  </div></span>';
}