<?php
// Home page after login

//function to start a session for a given userid after they logged in
session_start();

//query the db for a given value
function fetchInfo($db, $queryResult, $value){
  $r2 =0;
  if($r = mysqli_query($db, $queryResult)){
    $r2 = mysqli_fetch_assoc($r)[$value]; //
    return $r2;
  }
}

// reports.php by Ethan Lewis
define('DBHOST',"localhost");
define('DBNAME', "TEST_SCIAC");
define('DBUSER',"root");
define('DBPASS',"root");
// establish a new connection to the db
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
  die($conn->connect_error);
}

// grab the user id from the login page
$uid = $_SESSION['userid'];
if (!isset($_POST['userid'])){
  $uid = $_SESSION['userid']; // grab the user id from the session
}

$result = "SELECT name FROM USERS WHERE userid = $uid"; // select the name from userid
$result2 = mysqli_query($conn, $result); //
$name = mysqli_fetch_assoc($result2)['name']; // get name association
$_SESSION['name'] = $name;
?>

<!DOCTYPE html>

<html lang="en">

<head>
<title>Home Page</title>
<link rel="stylesheet" type="text/css" href="styleCompete.css">
<link href="https://fonts.googleapis.com/css?family=Bungee+Inline" rel="stylesheet">

</head>

<body>

  <div class= 'blueline'>
    <p>no display</p>
  </div>
  <div class = 'title'>
    <h1>Agro-Draft</h1>
    <p>1v1 Fantasy Water Polo</p>
  </div>
  <div class= 'blueline'>
    <p>no display</p>
  </div>

<h2>Welcome <?php
  $name = $_SESSION['name'];
  echo $name;?></h2>
<?php
// if the count variable from php above the html is zero, which means they haven't drafted a team
// let the user know and direct them to the draft page link
if (!$_SESSION['allDrafted']){
  echo "It seems you haven't drafted a team! Click the 'Draft' link below to select your team!";
  echo '<p><a href="draft.php">Draft</a></p>';
}else{
  $rowTeam = "SELECT * FROM TEAMS WHERE teamid = $uid"; // select the userid
  $teamName = fetchInfo($conn, $rowTeam, 'name');
  echo "Your team: ".$teamName;
  // grab all team members of a specific team.
  $fp1id = fetchInfo($conn, $rowTeam, 'fp1id');
  $p1 = "SELECT name FROM FIELD_PLAYERS WHERE $fp1id = playerid";
  $p1Name = fetchInfo($conn, $p1, 'name');
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
  $g = "SELECT name FROM GOALIES WHERE gid = $gid";
  $gName = fetchInfo($conn, $g, 'name');
  echo"<table style='width:100%; border: 1px solid black'>
          <tr>
              <td class = 'header'>Field Player 1</td>
              <td>$p1Name</td>
          </tr>
          <tr>
              <td class = 'header'>Field Player 2</td>
              <td>$p2Name</td>
          </tr>
          <tr>
              <td class = 'header'>Field Player 3</td>
              <td>$p3Name</td>
          </tr>
          <tr>
              <td class = 'header'>Field Player 4</td>
              <td>$p4Name</td>
          </tr>
          <tr>
              <td class = 'header'>Field Player 5</td>
              <td>$p5Name</td>
          </tr>
          <tr>
              <td class = 'header'>Field Player 6</td>
              <td>$p6Name</td>
          </tr>
          <tr>
              <td class = 'header'>Goalie</td>
              <td>$gName</td>
          </tr>
        </table> ";
  }
  ?>


<?php
// validation for compete form
$test = 'test';
$validCompete = true;
$oppNameErr = $teamNameErr  = $foshErr= "";

if(isset($_POST["oppName"])){

}
if (empty($_POST["oppName"])) { // if the field element name is empty
  $oppNameErr = "Opponent's Username is required"; // define the name error variable with the error message
  $validCompete = false;
  } else {
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
if ($validCompete == true){
  //$_SESSION["teamName"] = $teamName;
  $_SESSION["oppName"] = $oppName;
  // if form is okay, print a link to the compete page
  echo '<p><a href="compete.php">Click Here to see match!</a></p>';
}
// Fosh url form validattion
$validFOSH = true;
if (isset($_POST['update'])){
  if (empty($_POST["foshURL"])) { // if the field element name is empty
    $foshErr = "FOSH URL is required"; // define the name error variable with the error message
    $validFOSH = false;
  } else {
    $foshURL = $_POST["foshURL"]; // if the element is not empty, set $name to the value
    // verify by writing queries into db! Do this next!
    //$foshErr = "Not a valid fosh URL!";
  }
  // if a correct FOSH URL was added, set the session variable to local variable
  if ($validFOSH == true){
    $_SESSION["foshURL"] = $foshURL;
  }
}
?>
<fieldset>

<div class = "comp">
  <p>Want to compete? </p>
  <div class = "section">
      <form name="compete" method="post" action="compete.php">
        <legend for="oppName">Opponents Username:
        <input type="text" name="oppName"> </legend>
        <span style = 'color: red'> <?php echo $oppNameErr;?></span>
        <br></br>
        <p><input type="submit" value="Compete!"/></p>
      </form>
  </div>
</div>
<div class = "fosh">
  <p> Want to update stats?</p>
  <div class = "section">
  <form name="update" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <legend for="foshURL">FOSH Box Score URL:
    <input type="text" name="foshURL" value=""> </legend>
    <span style = 'color: red'> <?php echo $foshErr;?></span>
    <p><input type="submit" value="Update!"/></p>
    <br></br>
  </form>
  </div>
</div>

</fieldset>

</body>

</html>
