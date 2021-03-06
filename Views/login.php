 <?php
//include php to connect to db and set up PDO ($pdo)
include_once 'connectDB.php';

//get ready for querying and entering users
$exists_user = $pdo->prepare("SELECT * FROM USERS WHERE name = ?");
$exists_user -> bind_param("s", $name);//protect against sql injection

$create_user = $pdo->prepare("INSERT INTO USERS (userid, password, name) VALUES (?, ?, ?)");
$create_user -> bind_param("iss", $userid, $password, $name);//protect against sql injection



//function to start a session for a given userid after they logged in
function startSession($userid){
  session_start();
  //echo "Session started for userid: $userid";
  //assign this userid to the session variable
  $_SESSION['userid'] = $userid;
  header("Location: http://localhost:8888/cs135_MAMP/CS135_Project_MAMP/homePage.php");
  exit();
}

include 'verifyLogIn2.php';
?>

<!DOCTYPE html>

<head>
  <title>1v1 Fantasy</title>
  <link rel = "stylesheet" href = "styleLogin.css">
  <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Bungee+Inline" rel="stylesheet">
</head>
<body>
  <!--HEADER FOR AGRO-DRAFT-->
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
  <!-- containers to hold login/"or"/new user fields-->
  <div id = "container">
    <div class = "section" id = "new">
      <!-- form to create new user account -->
      <p class = "loginhead"><u>CREATE NEW USER</u></p>
      <form class = "form" action = "login.php" method = "POST">
        <p><span>Username</span>
        <input type = "text" name = "createusername"/></p><br/>
        <p><span>Password</span>
        <input type = "text" name = "createpassword"/></p><br/>
        <input class = "button" type = "submit" name = "submit" value = "Create New User"/>
      </form>
      <!-- verify the new user info with php code-->
    </div>

    <div class = "section" id = "existing">
      <!-- form to log in to an existing user account-->
      <p class = "loginhead"><u>LOG IN</u></p>
      <form class = "form" action = "login.php" method = "POST">
        <p><span>Username</span>
        <input type = "text" name = "checkusername"/></p><br/>
        <p><span>Password</span>
        <input type = "text" name = "checkpassword"/></p><br/>
        <input class = "button" type = "submit" name = "submit" value = "Log In"/>
      </form>
      <!-- verify the login info with php code-->
    </div>
  </div>
  <div class = 'error'><p><?php echo $loginerror ?></p></div>
</body>
