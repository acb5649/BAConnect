<?php

include_once "database.php";

function createCard($account_id) {
    // get database entries for user
    // final product looks like this: https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_cards_buttons2

    $name = getName($account_id);

    $imageSrc = file_get_contents("http://corsair.cs.iupui.edu:22891/courseproject/image.php?account_id=" . $account_id);
    $degrees = getDegrees($account_id);
    $jobs = getJobs($account_id);
    $userGender = getGender($account_id);
    $userCity = getApproximateLocation($account_id);

    return '<div class="w3-card-4 w3-margin-bottom">
  <header class="w3-container w3-pale-red">
    <h3>'.$name.'</h3>
  </header>
  <div class="w3-container w3-text-grey w3-white">
  <div class="w3-row-padding">
    <div class="w3-third">
        <div style="position: relative; top: 50%; /*transform: translateY(-50%); -webkit-transform: translateY(-50%); -moz-transform: translateY(-50%);*/">
                <img class="w3-circle w3-border" src="data:image/jpeg;base64,' . $imageSrc . '" style="width: 100%;" alt="Avatar">
        </div>
    </div>
    <div class="w3-twothird w3-small">
        <p>'.$degrees[0][2].'</p>
        <p>'.$degrees[0][1].'</p>
        <p>'.$degrees[0][0].'</p>
        <p>'.$jobs[0][0] . " at " . $jobs[0][1].'</p>
    </div>
  </div>
  <hr>
  <p>'.$userGender.' '.$userCity.'</p>
  </div>
  <a class="w3-button w3-block w3-dark-grey" href="profile.php?user=' . $account_id . '">+ Connect</a>
  </div>
  ';
};
