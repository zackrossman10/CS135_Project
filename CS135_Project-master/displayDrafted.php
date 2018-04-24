<?php
  //get the team id associated with the session, query this team
  $teamid = $_SESSION['userid'];
  $select_drafted_players = $pdo->prepare("SELECT * FROM TEAMS where teamid = $teamid");
  $select_drafted_players-> execute();
  $results = $select_drafted_players->fetch();

  //display all field players
  for($i=1; $i<7; $i++){
    //get "fp1id" through "fp6" from the result query
    $playerid = $results['fp'.$i.'id'];
    if($playerid==0){
      //if player hasn't been drafted yet, display "--" for information
      $cap_num = "--";
      $player_name = "--";
      $team = "--";
    }else{
      //if player has been drafted, get player info
      $select_player_info = $pdo->prepare("SELECT * FROM FIELD_PLAYERS WHERE playerid = $playerid");
      $select_player_info->execute();
      $info = $select_player_info->fetch();
      $cap_num = $info['cap_num'];
      $player_name = $info['name'];
      $team = $info['team'];
      if($i==6){
        //when we draft the 6th field player, set a session variable to reflect this
        $_SESSION['allFPDrafted'] = True;
      }
    }

    //name for the column header
    $position = "FP ".$i;
    //display information
    echo "<tr class = 'playerInfo'>
            <td class = 'position'>$position</td>
            <td class = 'cap_num'>$cap_num</td>
            <td class = 'name'>$player_name</td>
            <td class = 'team'>$team</td>
           </tr>";
  }

  //display goalie
  $gid = $results['gid'];
  if($gid ==0){
    //if player hasn't been drafted yet, display "--" for information
    $cap_num = "--";
    $player_name = "--";
    $team = "--";
  }else{
    //if goalie has been drafted, get goalie info from goalies table
    $select_goalie_info = $pdo->prepare("SELECT * FROM GOALIES WHERE goalieid = $gid");
    $select_goalie_info->execute();
    $info = $select_goalie_info->fetch();
    $cap_num = $info['cap_num'];
    $player_name = $info['name'];
    $team = $info['team'];
    //set session variable reflecting that all 7 players have been drafted
    $_SESSION['allDrafted'] = True;
  }

  //display goalie info
  echo "<tr class = 'playerInfo'>
          <td class = 'position'>Goalie</td>
          <td class = 'cap_num'>$cap_num</td>
          <td class = 'name'>$player_name</td>
          <td class = 'team'>$team</td>
         </tr>";
?>
