<?php
//display all undrafted players (DRAFT ALL FIELD PLAYERS BEFORE GOALIES)
if(isset($_SESSION['allFPDrafted'])){
  //select goalies for display only once all field players have been drafted
  $select_players = $pdo->prepare("SELECT * FROM GOALIES ORDER BY name, cap_num DESC");
  $select_players->execute();
  for($i =0; $i<$select_players->rowCount(); $i++){
    $row = $select_players->fetch();
    $goalieid = $row["goalieid"];
    $cap_num = $row["cap_num"];
    $player_name = $row["name"];
    $team = $row["team"];
    echo "<tr class = 'playerInfo'>
            <td class = 'position'>
            <form method = 'POST' action = 'draft.php'>
            <input class = 'goalieDraft' type ='submit' value ='DRAFT' name ='$goalieid'/></form></td>
            <td class = 'cap_num'>$cap_num</td>
            <td class = 'name'>$player_name</td>
            <td class = 'team'>$team</td>
         </tr>";
  }
}else{
  //while not all 6 field players have been drafted, select field players THAT HAVEN'T BEEN DRAFTED for display 
  $userid = $_SESSION['userid'];
  $select_players = $pdo->prepare("SELECT * FROM FIELD_PLAYERS WHERE playerid NOT IN (SELECT fp1id FROM TEAMS WHERE teamid = $userid) AND playerid NOT IN (SELECT fp2id FROM TEAMS WHERE teamid = $userid) AND playerid NOT IN (SELECT fp3id FROM TEAMS WHERE teamid = $userid) AND playerid NOT IN (SELECT fp4id FROM TEAMS WHERE teamid = $userid) AND playerid NOT IN (SELECT fp5id FROM TEAMS WHERE teamid = $userid) AND playerid NOT IN (SELECT fp6id FROM TEAMS WHERE teamid = $userid) ORDER BY name, cap_num DESC");
  $select_players->execute();
  for($i =0; $i<$select_players->rowCount(); $i++){
    $row = $select_players->fetch();
    $playerid = $row["playerid"];
    $cap_num = $row["cap_num"];
    $player_name = $row["name"];
    $team = $row["team"];
    echo "<tr class = 'playerInfo'>
            <td class = 'position'>
            <form method = 'POST' action = 'draft.php'>
            <input class = 'fieldPlayerDraft' type ='submit' value ='DRAFT' name ='$playerid'/></form></td>
            <td class = 'cap_num'>$cap_num</td>
            <td class ='name'>$player_name</td>
            <td class = 'team'>$team</td>
         </tr>";
  }
}
 ?>
