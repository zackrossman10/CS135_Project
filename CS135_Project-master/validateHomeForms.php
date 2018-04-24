<?php
$oppNameErr = $teamNameErr  = $foshErr= $foshComplete=  "";

if(isset($_POST["oppName"])){
  if (empty($_POST["oppName"])) {
    // if the field element name is empty
    $oppNameErr = "Opponent's Username is required"; // define the name error variable with the error message
  }else{
    //if oppName is not empty
    $oppName = $_POST["oppName"];
    //checks to see if the opponents name is in the database
    $getTeamId = "SELECT userid FROM USERS WHERE name = '".$oppName."'";
    $result = mysqli_query($conn, $getTeamId);
    if($result){
      //if user exists in db, get their userid
      $userid = mysqli_fetch_assoc($result)['userid'];
      $getTeamName = "SELECT name FROM TEAMS WHERE teamid = $userid";
      $teamName = fetchInfo($conn, $getTeamName, 'name');
      if($teamName != NULL){
        $_SESSION["oppName"] = $oppName;
        $_SESSION["oppID"] = $userid;
        //go to compete page!!!! immediately
        header("Location: http://localhost:8888/cs135_MAMP/CS135_Project_MAMP/compete.php");
        exit();
        //echo '<p><a href="compete.php">Click Here to see match!</a></p>';
      }else{
        $oppNameErr = "That does seem to be a valid team, sorry!";
      }
    }else{
      $oppNameErr = "That doesn't seem to be a valid team, sorry!";
    }
  }
}

// Fosh url form validattion
if (isset($_POST['foshURL'])){
  if (empty($_POST["foshURL"])) { // if the field element name is empty
    $foshErr = "FOSH URL is required"; // define the name error variable with the error message
  } else {
      $foshURL = $_POST["foshURL"];
      $val_subset = substr($foshURL, 0, -4);
      if($val_subset == "http://www.thefosh.net/livestats.php?gameid="){
        $_SESSION["foshURL"] = $foshURL;
        if(scrape()){
          $foshComplete = 'Sucessful upload!';
        }else{
          $foshErr = 'Game already entered';
        }
      }else{
        $foshErr = 'Not a valid URL';
      }

  }
}
?>
