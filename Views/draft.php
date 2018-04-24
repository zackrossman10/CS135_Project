<?php
session_start();
//connect to db and set up pdo
include_once "connectDB.php";

$userid = $_SESSION['userid'];
$exists_team_query= $pdo->prepare("SELECT * FROM TEAMS WHERE teamid = $userid");
$exists_team_query->execute();
$rows = $exists_team_query->rowCount();

if ($rows == 0){
  //if team doesn't already exist, enter an empty team to be drafted into
  $create_team = $pdo->prepare("INSERT INTO TEAMS (teamid, name, fp1id, fp2id, fp3id, fp4id, fp5id, fp6id, gid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $create_team -> bind_param("isiiiiiii",$userid, $name, $fp1id, $fp2id, $fp3id, $fp4id, $fp5id, $fp6id, $gid);
  $create_team->execute([$userid, NULL, 0,0,0,0,0,0,0]);
  $_SESSION['allDrafted'] = False;
}

//get the username associated with userid
$exists_user = $pdo->prepare("SELECT * FROM USERS WHERE userid = $userid");
$exists_user->execute();
$username = $exists_user->fetchColumn(2);

//find the name of which column to insert field players into
function findIndex(){
  //array keeping track of the slots that field players are inserted in
  $slots = Array('fp1id', 'fp2id', 'fp3id', 'fp4id', 'fp5id', 'fp6id');
  //inspect each column of the team to see if this player has been drafted
  $try_index = 0;
  while(isset($_SESSION[$slots[$try_index]])){
    $try_index+=1;
    if($try_index>5){
      //once all field players are drafted, stop finding an index
      break;
    }
  }
  //set a session variable to reflect the drafting of this player
  $_SESSION[$slots[$try_index]] = 'SET';
  $index = $slots[$try_index];
  //return the name of the column which was just drafted
  return $index;
}

#METHOD WHICH HANDLES DRAFTING OF A PLAYER
//if a field player is drafted, put them in the user's team
if(isset($_POST['playerid'])){
  $teamid = $_SESSION['userid'];
  //find which column to insert the player in
  $insert_column = findIndex();
  $playerid = $_POST['playerid'];
  //update the user's team
  $draft = $pdo->prepare("UPDATE TEAMS SET ".$insert_column."= $playerid WHERE teamid = $teamid");
  $draft->execute();
}else if(isset($_POST['goalieid'])){
  //if a goalie is drafted
  $teamid = $_SESSION['userid'];
  $goalieid = $_POST['goalieid'];
  $draft = $pdo->prepare("UPDATE TEAMS SET gid = $goalieid WHERE teamid = $teamid");
  $draft->execute();
}else if(isset($_POST['teamname'])){
  //if team name is entered
  $teamid = $_SESSION['userid'];
  $teamname = $_POST['teamname'];
  $changeName = $pdo->prepare("UPDATE TEAMS SET name = '".$teamname."' WHERE teamid = $teamid");
  $changeName->execute();
}
?>

<!DOCTYPE html>
<head>
  <title>1v1 Fantasy Draft</title>
  <link rel = "stylesheet" href="styleDraft.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
  <div class = 'header'>
  <?php
    //if user has finished drafting...
    if($_SESSION['allDrafted']){
      if(!isset($_POST['teamname'])){
        //prompt them to name their team
        echo "<p>You drafted your team! Now give it a name</p>";
        echo "<form method = 'POST' action = 'draft.php'>";
        echo "<p>Team Name</p><input type = 'text' name = 'teamname'></form>";
      }else{
        //then prompt them to go to home page
        $userid = $_SESSION['userid'];
        $getName = $pdo->prepare("SELECT name FROM TEAMS WHERE teamid = $userid");
        $getName->execute();
        $teamName = $changeName->fetchColumn(1);
        echo '<p>Great! Your team name is "'.$teamname.'"</p><br/>';
        echo "<a href='homePage.php'>Go to team home page</a>";
      }
    }else{
      echo "<p>Currently <span class = 'green'>drafting</p>";
    }
  ?>
  </div>

  <div id = 'container'>
    <!--display all the possible players that can be drafted-->
    <div id = 'undrafted' class = "draft_table" >
      <h4>Undrafted</h4>
      <table id = "undrafted">
        <thead>
          <tr>
            <th class = 'position'><u>Action</u></th>
            <th class = 'cap_num'><u>Number</u></th>
            <th class = 'name'><u>Player</u></th>
            <th class = 'team'><u>Team</u></th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(!$_SESSION['allDrafted']){
              //display all the undrafted players if all players have not been drafted
              include "displayUndrafted.php";
            }
          ?>
        </tbody>
       </table>
    </div>

    <!--display all the players that the user has drafted-->
    <div id = 'drafted' class = "draft_table">
      <?php
      //change the header for the "drafted" table if all players have been drafted
        if(isset($_SESSION['allFPDrafted'])){
          echo "<h4><span class = 'green'>YOUR TEAM!</span><h4>";
        }else{
          echo "<h4>Drafted</h4>";
        }
       ?>
      <table id = "drafted">
        <thead>
          <tr>
            <th class = 'position'><u>Position</u></th>
            <th class = 'cap_num'><u>Number</u></th>
            <th class = 'name'><u>Player</u></th>
            <th class = 'team'><u>Team</u></th>
          </tr>
        </thead>
        <tbody>
        <?php
          //display all the drafted players
          include "displayDrafted.php";
        ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
<script>
  $(document).ready(function(){
    //draft the field player whose button was clicked
    $('.fieldPlayerDraft').click(function(){
      //get the playerid associated with the delete button
      var variety = $(this).attr('name');
      var carturl = 'draft.php';
      var data = {"playerid": variety}
      $.post(carturl, data);
    });

    //draft the goalies
    $('.goalieDraft').click(function(){
      //get the playerid associated with the delete button
      var variety = $(this).attr('name');
      var carturl = 'draft.php';
      var data = {"goalieid": variety}
      $.post(carturl, data);
    })
  });
</script>
