<?php
//if the user requests to log in
if(isset($_POST['checkusername'])){
  //check if username exists in db USERS
  $username = $_POST["checkusername"];
  $exists_user->execute(["$username"]);
  if($exists_user->rowCount() == 1){
    //if username exists, check password
    $result_pass = $exists_user->fetchColumn(1);
    //echo "$result_pass";
    if($result_pass == $_POST["checkpassword"]){
      //if password exists, admit to the site
      $name = $_POST['checkusername'];
      echo "<div class = 'pass'";
      echo "<p class = 'draft'>Welcome $name!</p>";
      //start a session for the user
      $exists_user->execute(["$username"]);
      $userid = $exists_user->fetchColumn(0);
      startSession($userid);
      include "draftPrep.php";
      echo "</div>";
      //go to view page
    }else{
      //password doesn't match
      echo "<div class = 'error'><p>Wrong login credentials</p></div>";
    }
  }else{
    //username doesn't match
    echo "<div class = 'error'><p>Wrong login credentials</p></div>";
  }
}else if(isset($_POST["createusername"])){
  //check that the username they entered is unique
  $username = $_POST["createusername"];
  $exists_user->execute(["$username"]);
  if($exists_user->rowCount() ==0){
    //create a new user with given username and password
    $username = $_POST['createusername'];
    $password = $_POST['createpassword'];
    $create_user->execute([0, "$password", "$username"]);
    echo "<div class = 'pass'";
    echo "<p class = 'draft'><span class = 'green'>New user created</span> -- Welcome <u>$username</u></p>";
    $exists_user->execute(["$username"]);
    $userid = $exists_user->fetchColumn(0);
    startSession($userid);
    include "draftPrep.php";
    echo "</div>";
  }else{
    echo "<div class = 'error'><p>Username already exists</p></div>";
  }
}
?>
