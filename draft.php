<?php
//connect to db and set up pdo
include_once "connectDB.php";

//start session
session_start();
//get the userid
$userid = $_SESSION['userid'];

//get the username associated with userid
$exists_user = $pdo->prepare("SELECT * FROM USERS WHERE userid = ?");
$exists_user->execute([$userid]);
$username = $exists_user->fetchColumn(2);
?>

<!DOCTYPE html>

<head>
  <title>1v1 Fantasy Draft</title>
</head>
<body>
  <div>
    <p><strong>Username: </strong><<?php
        echo $username;
       ?></p>
  </div>

</body>
