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

//delete an existing team if the user chooses to draft again
$delete_team = $pdo->prepare("DELETE FROM TEAMS WHERE teamid = ?");
$delete_team->execute([$userid]);

//create a team from blank slate
$create_team = $pdo->prepare("INSERT INTO TEAMS (teamid, name, fp1id, fp2id, fp3id, fp4id, fp5id, fp6id, gid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$create_team->execute([$userid, NULL, 0,0,0,0,0,0,0]);

$_SESSION['allDrafted'] = False;

 ?>
