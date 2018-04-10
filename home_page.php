<?php
session_start();
// Home page after login
//function to start a session for a given userid after they logged in
function startSession($userid){
  session_start();
  echo "Session started for userid: $userid";

  if(!isset($_SESSION['userid'])) {
    //assign this userid to the session variable
    $_SESSION['userid'] = $userid;
  }
  session_unset();
  session_destroy();
}

  function fetchInfo($db, $queryResult, $value){
    $r = mysqli_query($db, $queryResult); //
    $r2 = mysqli_fetch_assoc($r)[$value]; //
    return $r2;
  }

  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

?>

<!DOCTYPE html>


<?php

// reports.php by Ethan Lewis
define('DBHOST',"localhost");
define('DBNAME', "TEST_SCIAC");
define('DBUSER',"root");
define('DBPASS',"root");

// establish a new connection to the db
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
  echo "** ERROR **";
  die($conn->connect_error);
}else{
  echo "Connected <br>";

}


$uid = $_SESSION['userid']; // grab the user id from the session
$result = "SELECT name FROM USERS WHERE userid = $uid"; // select the name from userid
$result2 = mysqli_query($conn, $result); //
$name = mysqli_fetch_assoc($result2)['name']; // get name association
//startSession($uid);

//print_r($uid);
// Check if the team is in the table
$isInTable = "SELECT count(*)  AS num FROM USERS WHERE teamid = $uid";
$result2 = mysqli_query($conn, $isInTable);
$count = mysqli_fetch_assoc($result2)['num']; // count should be one if one there if there
                                                                          // is one team instance


//print_r($count);

?>


<html lang="en">

<head>
<title>Home Page</title>
<link rel="stylesheet" type="text/css" href="homepageStyle.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>

<h2>Welcome <?php echo $name;?></h2>
<?php
// if the count variable from php above the html is zero, which means they haven't drafted a team
// let the user know and direct them to the draft page link
if ($count == 0){
  echo "It seems you haven't drafted a team! Click the 'Draft' link below to select your team!";
  echo"
          <table style='width:100%; border: 1px solid black'>
            <tr style = 'border: 1px solid black'>
                <th>Team Name </th>
                <th>Player 1  </th>
                <th>Player 2  </th>
                <th>Player 3  </th>
                <th>Player 4   </th>
                <th>Player 5   </th>
                <th>Player 6   </th>
                <th>Goalie      </th>
              </tr>
              <tr style = 'border: 1px solid black'>
                  <th>Blues </th>
                  <th>Ethan  </th>
                  <th>Zack  </th>
                  <th>Harry  </th>
                  <th>Charlie   </th>
                  <th>Noah   </th>
                  <th>Peter   </th>
                  <th>Brett      </th>
                </tr> </table>  ";
}else{
  // grab the row where the user id equals teamid
  $rowTeam = "SELECT * FROM TEAMS WHERE $uid = teamid"; // select the userid
  // echo the player name along with the team name
  $teamName = fetchInfo($conn, $rowTeam, 'name');
  echo $name ."'s " . $teamName;

  // grab all team members of a specific team.
  $fp1id = fetchInfo($conn, $rowTeam, 'fp1id');
  $p1 = "SELECT name FROM FIELD_PLAYERS WHERE $fp1id = playerid";
  $plName = fetchInfo($conn, $pl, 'name');

  $fp2id = fetchInfo($conn, $rowTeam, 'fp2id');
  $p2 = "SELECT name FROM FIELD_PLAYERS WHERE $fp2id = playerid";
  $p2Name = fetchInfo($conn, $p2, 'name');

  $fp3id = fetchInfo($conn, $rowTeam, 'fp3id');
  $p3 = "SELECT name FROM FIELD_PLAYERS WHERE $fp3id = playerid";
  $p3Name = fetchInfo($conn, $p3, 'name');

  $fp4id = fetchInfo($conn, $rowTeam, 'fp4id');
  $p4 = "SELECT name FROM FIELD_PLAYERS WHERE $fp4id = playerid";
  $p4Name = fetchInfo($conn, $p4, 'name');

  $fp5id = fetchInfo($conn, $rowTeam, 'fp5id');
  $p5 = "SELECT name FROM FIELD_PLAYERS WHERE $fp5id = playerid";
  $p5Name = fetchInfo($conn, $p5, 'name');

  $fp6id = fetchInfo($conn, $rowTeam, 'fp6id');
  $p6 = "SELECT name FROM FIELD_PLAYERS WHERE $fp6id = playerid";
  $p6Name = fetchInfo($conn, $p6, 'name');

  $gid = fetchInfo($conn, $rowTeam, 'gid');
  $g = "SELECT name FROM GOALIES WHERE $gid = playerid";
  $gName = fetchInfo($conn, $g, 'name');

  echo"
  <table style='width:100%; border: 1px solid black'>
    <tr style = 'border: 1px solid black'>
        <th>Team Name </th>
        <th>Player 1  </th>
        <th>Player 2  </th>
        <th>Player 3  </th>
        <th>Player 4   </th>
        <th>Player 5   </th>
        <th>Player 6   </th>
        <th>Goalie      </th>
      </tr>
      <tr style = 'border: 1px solid black'>
          <th>$teamName </th>
          <th>$p1Name  </th>
          <th>$p2Name  </th>
          <th>$p3Name  </th>
          <th>$p4Name   </th>
          <th>$p5Name   </th>
          <th>$p6Name   </th>
          <th>$gName      </th>
        </tr>
    </table> ";

  }
  ?>


<?php
// Next Step: DO THIS NEXT! Span elements have been added into html. Do the error 2 class
// look in checkout for the error 2 class
$validCompete = true;
$validFosh = true;
$oppNameErr = $teamNameErr  = $foshErr= "";

// find a way to check if it was a fosh update or compete press
// probably just check the post name
if (isset($_POST['compete'])) {
  // verify the compete entries
  if (empty($_POST["teamName"])) { // if the field element name is empty
   $teamNameErr = "Team Name is required"; // define the name error variable with the error message
   $validCompete = false;
  } else {
   $teamName = $_POST["teamName"]; // if the element is not empty, set $name to the value

   // This query checks to see if the users team name is in the database
   $isInTable = "SELECT count(*) as num FROM TEAMS WHERE name = $teamName";
   $count = fetchInfo($conn, $isInTable, 'num'); // count should be 1 if in database
   // verify by writing queries into db! Do this next!
   if ($count == 0){
     $teamNameErr = "That doesn't seem to be your team name, sorry!";
     $validCompete = false;
   }
  }

  if (empty($_POST["oppName"])) { // if the field element name is empty
    $oppNameErr = "Opponents Team Name is required"; // define the name error variable with the error message
    $validCompete = false;
  }else {
    $oppName = $_POST["oppName"]; // if the element is not empty, set $name to the value

    // This query checks to see if the opponents name is in the database
    $isInTable = "SELECT count(*) as num FROM TEAMS WHERE name = $oppName";
    $count = fetchInfo($conn, $isInTable, 'num'); // count should be 1 if in database

    // verify by writing queries into db! Do this next!
    if ($count == 0){
      $oppNameErr = "That doesn't seem to be a valid team, sorry!";
      $validCompete = false;
    }
  }
  // if the compete form is correct, set the session variables and direct to compete page
  if ($validCompete == true){
    $_SESSION["teamName"] = $teamName;
    $_SESSION["oppName"] = $oppName;
    header('Location: /Applications/MAMP/htdocs/Projects/Fantasy/compete.php');
    header('compete.php');
  }
}
if (isset($_POST["foshURl"])){
  $foshSet == true;
  if (empty($_POST["foshURL"])) { // if the field element name is empty
    $foshErr = "FOSH URL is required"; // define the name error variable with the error message
    $validFosh = false;
  } else {
    $foshURL = $_POST["foshURL"]; // if the element is not empty, set $name to the value

    // verify by writing queries into db! Do this next!
    $foshErr = "Not a valid fosh URL!";
  }
      // If the fosh URL works, update the player stats
      if ($validFosh == true){
        $_SESSION["foshURL"] = $foshURL;
      }
}


?>


<p><a href="draft.php">Draft Here!</a></p>

<p>Want to compete? </p>
<p> Simply enter your team name and your opponents then press compete</p>
<span><p> To update player stats enter a fosh URL and press update</p></span>
<fieldset>
<div class="section">
<form name="compete" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <legend for="teamName">Team Name:
  <input type="text" name="teamName" value=""> </legend>
  <span style = 'color: red'> <?php echo $teamNameErr;?></span>
  <br></br>

  <legend for="oppName">Opponents Team Name:
  <input type="text" name="oppName" value=""> </legend>
  <span style = 'color: red'> <?php echo $oppNameErr;?></span>
  <br></br>
  <p><input type="submit" value="Compete!"/></p>
  </form>
</div>

<div class="section">
<form name="foshUpdate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <legend for="foshURL">FOSH URL:
  <input type="text" name="foshURL" value=""> </legend>
  <span style = 'color: red'> <?php echo $foshErr;?></span>
  <p><input type="submit" value="Update!"/></p>
  <br></br>
</form>
</div>

</fieldset>



</body>

</html>
