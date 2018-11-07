<?php
    require_once "session.php";
    require_once "database.php";
    if(!isset($type)){
        header("Location: index.php");
        die;
    }
    if($type <= 2){
      header("Location: index.php");
      die;
    }

    if(isset($_POST['upgrade'])){
      $username = $_POST["username"];
      $newType = $_POST["type"];
      $id = getAccountIDFromUsername($username);

      $oldType = getAccountTypeFromAccountID($id);

      if($newType != $oldType && $_SESSION['account_ID'] != $id){

        if($oldType > $newType){
          if($type > $oldType){
            editAccountType($id, $newType);
          }
        }
        else{
          if($type > $newType){
            editAccountType($id, $newType);
          }
        }
      }
    //  header("Location: index.php");
    //  die;
    }
?>

<div id="upgradeModal" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-4">
      <header class="w3-container w3-lime w3-center w3-padding-32">
         <span onclick="document.getElementById('upgradeModal').style.display='none'" class="w3-button w3-lime w3-xlarge w3-display-topright">×</span>
         <h2 class="w3-wide">
            <i class="w3-margin-right"></i>Upgrade Account
         </h2>
      </header>
      <form id="upgradeAcc" class="w3-container" method="post">
         <?php
            // CONNECT to DB & read in MYSQL DATA

            /*******Associative Arrays use a name/value pair to access a value **********/

            //assign values directly
            $types = array(
                1 => "User",
                2 => "Coordinator",
                3 => "Admin",
            );
            /*
            $type[1] = "User";
            $type[2] = "Coordinator";
            $type[3] = "Admin";
            $type[4] = "Super Admin";
            */
            ?>
         <!--Username-->
         <p>
            <label><i class="fa fa-user"></i> Username</label>
         </p>
         <input type="text" id="username" placeholder="" name="username" class="w3-input w3-border" required autofocus>
         <br>
         <select id="type" class="w3-select w3-border" name="type">
         <?php

            //using foreach to assign the label to a variable and the value to a variable
            $list = "";

            //foreach ($type as $pos => $value) {
            $rank = $type;


            for($x = 1; $x < 4 && $x < $rank; $x++){
                $list = $list . '<option value = "'.$x.'">'.$types[$x].'</option>';
            }
            print $list;
            ?>
         </select>
         <!-- Submit -->
         <button type="submit" class="w3-button w3-block w3-lime w3-padding-16 w3-section w3-right" name="upgrade" id="upgrade">Upgrade</button>
      </form>
   </div>
</div>
