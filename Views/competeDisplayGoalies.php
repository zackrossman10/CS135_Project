<?php
  //display goalie
  $gid = $results['gid'];
  $select_goalie_info = $pdo->prepare("SELECT * FROM GOALIES WHERE goalieid = $gid");
  $select_goalie_info->execute();
  $info = $select_goalie_info->fetch();
  $cap_num = $info['cap_num'];
  $player_name = $info['name'];
  $team = $info['team'];
  $goalsAllowed = $info['goals_allowed'];
  $saves = $info['saves'];
  $steals = $info['steals'];

  //display goalie info
  echo "<tr class = 'playerInfo'>
          <td class = 'name'>$player_name</td>
          <td class = 'team'>$team</td>
          <td class = 'goalsAllowed'>$goalsAllowed</td>
          <td class = 'saves'>$saves</td>
          <td class = 'steals'>$steals</td>
         </tr>";

  $sum1 += -1*$goalsAllowed+$saves+$steals;
?>
