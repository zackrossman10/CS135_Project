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
      echo "Welcome $name!<br/>";
      //start a session for the user
      $exists_user->execute(["$username"]);
      $userid = $exists_user->fetchColumn(0);
      startSession($userid);
      include "draftPrep.php";
      //go to view page
    }else{
      echo "Incorrect username/password combination";
    }
  }else{
    echo "Incorrect username/password combination";
  }
}
?>
