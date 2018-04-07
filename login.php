<?php
//include php to connect to db and set up PDO ($pdo)
include_once 'connectDB.php';

//get ready for querying and entering users
$exists_user = $pdo->prepare("SELECT * FROM USERS WHERE name = ?");
$create_user = $pdo->prepare("INSERT INTO USERS (userid, password, name) VALUES (?, ?, ?)");

//function to start a session for a given userid after they logged in
function startSession($userid){
  session_start();
  echo "Session started for userid: $userid";

  //assign this userid to the session variable
  $_SESSION['userid'] = $userid;
  echo '<p id = "draft"><a href="draft.php">Go to draft page</a></p>';
}
?>

<!DOCTYPE html>

<head>
  <title>1v1 Fantasy</title>
  <link rel = "stylesheet" href = "style.css">
</head>
<body>
  <!-- containers to hold login/"or"/new user fields-->
  <div id = "container">
    <div class = "section" id = "new">
      <!-- form to create new user account -->
      <p class = "loginhead"><strong>CREATE NEW USER</strong></p>
      <form class = "form" action = "login.php" method = "POST">
        <p><span>Username</span>
        <input type = "text" name = "createusername"/></p><br/>
        <p><span>Password</span>
        <input type = "text" name = "createpassword"/></p><br/>
        <input class = "button" type = "submit" name = "submit" value = "Create New User"/>
      </form>
      <!-- verify the new user info with php code-->
      <p class = 'error'>
        <?php
          include 'verifyNewUser.php';
        ?></p>
    </div>


    <div class = "section" id = "or">
      <p><strong>OR</strong></p>
    </div>

    <div class = "section" id = "existing">
      <!-- form to log in to an existing user account-->
      <p class = "loginhead"><strong>LOG IN</strong></p>
      <form class = "form" action = "login.php" method = "POST">
        <p><span>Username</span>
        <input type = "text" name = "checkusername"/></p><br/>
        <p><span>Password</span>
        <input type = "text" name = "checkpassword"/></p><br/>
        <input class = "button" type = "submit" name = "submit" value = "Log In"/>
      </form>
      <!-- verify the login info with php code-->
      <p class = 'error'>
        <?php
          include 'verifyLogIn.php';
        ?></p>
    </div>
  </div>
</body>
