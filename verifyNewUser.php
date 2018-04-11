<?php
if(isset($_POST["createusername"])){
  //check that the username they entered is unique
  $username = $_POST["createusername"];
  $exists_user->execute(["$username"]);
  if($exists_user->rowCount() ==0){
    //create a new user with given username and password
    $username = $_POST['createusername'];
    $password = $_POST['createpassword'];
    $create_user->execute([0, "$password", "$username"]);
    echo "New user created -- Welcome $username<br/>";
    $exists_user->execute(["$username"]);
    $userid = $exists_user->fetchColumn(0);
    startSession($userid);
    include "draftPrep.php";
  }else{
    echo "Username already exists";
  }
}
?>
