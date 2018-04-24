<?php
  //variable to hold each individual's score
  $sum1=0;
  //display all field players
  for($i=1; $i<7; $i++){
    //get "fp1id" through "fp6" from the result query
    $playerid = $results['fp'.$i.'id'];
    $select_player_info = $pdo->prepare("SELECT * FROM FIELD_PLAYERS WHERE playerid = $playerid");
    $select_player_info->execute();
    $info = $select_player_info->fetch();
    $cap_num = $info['cap_num'];
    $player_name = $info['name'];
    $team = $info['team'];
    $shots = $info['shots'];
    $goals = $info['goals'];
    $assists = $info['assists'];
    $exclusions = $info['exclusions'];
    $drawnExc = $info['drawn_exc'];
    $steals = $info['steals'];

    //display information
    echo "<tr class = 'playerInfo'>
            <td class = 'name'>$player_name</td>
            <td class = 'team'>$team</td>
            <td class = 'shots'>$shots</td>
            <td class = 'goals'>$goals</td>
            <td class = 'assists'>$assists</td>
            <td class = 'exclusions'>$exclusions</td>
            <td class = 'drawnExc'>$drawnExc</td>
            <td class = 'steals'>$steals</td>
           </tr>";
    $sum1 += -.5*$shots+2*$goals+$assists-$exclusions+$drawnExc+$steals;
  }
?>
