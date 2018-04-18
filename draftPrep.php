<?php

//manage session vars for the field player columns
$slots = Array('fp1id', 'fp2id', 'fp3id', 'fp4id', 'fp5id', 'fp6id');
foreach($slots as $val){
  if(isset($_SESSION[$val])){
    unset($_SESSION[$val]);
  }
}

//unset a
if(isset($_SESSION['allDrafted'])){
  unset($_SESSION['allDrafted']);
}
if(isset($_SESSION['allFPDrafted'])){
  unset($_SESSION['allFPDrafted']);
}

include "connectDB.php";
//create a new team if there is none already associated with user
$userid = $_SESSION['userid'];

 ?>
