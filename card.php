<?php

include_once "database.php";

function createCard($account_id) {
    // get database entries for user
    // final product looks like this: https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_cards_buttons2
    $firstName = "Fakey";
    $lastName = "McPerson";
    $userPicture = "https://soulcore.com/wp-content/uploads/2018/01/profile-placeholder.png";
    $educationHistory = "2001-2005 BS in Computer Science at IUPUI";
    $workHistory = "2005-2015 Sales Associate at Store";
    $userAge = "30";
    $userGender = "Male";
    $userCity = "Indianapolis";

    return '<div class="w3-card-4 w3-margin-bottom">
  <header class="w3-container w3-pale-red">
    <h3>'.$firstName.' '.$lastName.'</h3>
  </header>
  <div class="w3-container">
  <img src="'.$userPicture.'" alt="Avatar" class="w3-left w3-circle" style="width:10%">
  <p>'.$educationHistory.'</p>
  <p>'.$workHistory.'</p>
  <hr>
  <p>'.$userAge.' '.$userGender.' '.$userCity.'</p>
  </div>
  <button class="w3-button w3-block w3-dark-grey">+ Connect</button>
  </div>
  ';
};
