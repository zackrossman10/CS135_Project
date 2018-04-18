<?php
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
$result = "SELECT name FROM TEAMS WHERE userid = $uid"; // select the name from userid
$name = fetchInfo($conn, $result, 'name'); // get name association

$result = "SELECT name FROM USERS WHERE userid = $uid"; // select the name from userid
$result2 = mysqli_query($conn, $result); //
$name = mysqli_fetch_assoc($result2)['name']; // get name association
$_SESSION['name'] = $name;

//include code for "scrape" function
include "scrapeGame.php";
include "validateHomeForms.php";
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <title>Home Page</title>
  <link rel="stylesheet" type="text/css" href="styleHome.css">
  <link href="https://fonts.googleapis.com/css?family=Bungee+Inline" rel="stylesheet">
</head>
<body>
  <!--Header for Agro-Draft-->
  <div class= 'blueline'>
    <p>no display</p>
  </div>
  <div class = 'title'>
    <h1>Agro-Draft</h1>
    <p>1v1 Fantasy Water Polo</p>
  </div>
  <a id = 'logout' href="login.php">Log Out</a>
  <div class= 'blueline'>
    <p>no display</p>
  </div>
  <h2>Welcome <?php echo $name;?></h2>
  <div class = 'container'>
    <!--view the user's team-->
    <div class = 'section'>
    <?php
      include "homeViewTeam.php";
    ?>
    </div>
    <div class = 'section'>
      <div id = "comp">
        <p class = 'question'><u class = 'question'>Want to compete?</u></p>
        <div class = "section">
            <form name="compete" method="post" action="homePage.php">
              <legend for="oppName">Opponents Username:
              <input type="text" name="oppName"> </legend>
              <!--display any errors with user input-->
              <span style = 'color: red'> <?php echo $oppNameErr;?></span>
              <p><input type="submit" value="Compete!"/></p>
            </form>
        </div>
      </div>
      <div id = "fosh">
        <p class = 'question'><u class = 'question'> Want to update stats?</u></p>
        <div class = "section">
        <form name="update" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <legend for="foshURL">FOSH Box Score URL:
          <input type="text" name="foshURL" value=""> </legend>
          <!--display any errors with user input-->
          <span style = 'color: red'> <?php echo $foshErr;?></span>
          <span style = 'color: #284fb6'><?php echo $foshComplete;?></span>
          <p><input type="submit" value="Update!"/></p>
          <br></br>
        </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
