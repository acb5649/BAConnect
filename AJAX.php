<?php
require_once "database.php";
if(!isset($_GET["action"])){
    echo "";
    die();
}

if($_GET["action"] == "refreshState"){
    if(!isset($_GET["country"])){
        header("Location:index.php");
    }
    $countryID = $_GET["country"];
    $options = getStatesList($countryID);

    echo $options;
}

if($_GET["action"] == "getDegrees"){
    echo listDegreeTypes();
}

if($_GET["action"] == "getUsernames" && $type > 1){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `Account` where username LIKE '%".$_GET["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}
if($_GET["action"] == "getMentees" && $type > 1){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MenteeOptions` where username LIKE '%".$_GET["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}
if($_GET["action"] == "getMentors" && $type > 1){
    if (isset($_GET["matching"])) {
        $con = Connection::connect();
        $stmt = $con->prepare("select username from `MentorOptions` where username LIKE '%".$_GET["matching"]."%'");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = "";
        foreach($row as $rw) {
            $result .= '<option> ' . $rw["username"] . ' </option> ';
        }
        echo $result;
    } else {
        echo "";
    }
}

if($_REQUEST["action"] == "endMentorship"){ //profile.php
  $mentorship_ID = $_REQUEST['id'];
  $account_ID = $_REQUEST['account'];
  $report = endMentorship($account_ID, $mentorship_ID);

  $_SESSION['title'] = $report->title;
  $_SESSION['msg'] = $report->msg;
  $_SESSION['nextModal'] = $report->nextModal;
  $_SESSION['success'] = $report->success;
  $_SESSION['inputs'] = $report->inputs;

  if($report->success == TRUE){
    echo "<?php formatMentorships(); ?>";
  }
  else{
    echo "";
  }
}

if($_REQUEST['action'] == "revokeMentorship"){
  $account_ID = $_REQUEST['account'];
  $mentorship_ID = $_REQUEST['id'];
  $report = endMentorship($account_ID, $mentorship_ID);

  $_SESSION['title'] = $report->title;
  $_SESSION['msg'] = $report->msg;
  $_SESSION['nextModal'] = $report->nextModal;
  $_SESSION['success'] = $report->success;
  $_SESSION['inputs'] = $report->inputs;

  if ($report->success) {
      //$report = new Report("Mentorship Has Been Revoked Successfully", "Emails have been sent to both parties notifying them of the change.", "", TRUE);
      echo "formatMentorships()";
  }
  else{
    echo "";
  }
}

?>
