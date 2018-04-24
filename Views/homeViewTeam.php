<?php
// if the count variable from php above the html is zero, which means they haven't drafted a team
// let the user know and direct them to the draft page link
$userid = $_SESSION['userid'];
$exists_team_query = "SELECT * FROM TEAMS WHERE teamid = $userid";

if (fetchInfo($conn, $exists_team_query, "fp1id") == 0){
  echo "Draft a team!";
  echo '<p><a href="draft.php">Draft</a></p>';
  $_SESSION['allDrafted'] = false;
}else{
  $rowTeam = "SELECT * FROM TEAMS WHERE teamid = $uid"; // select the userid
  $teamName = fetchInfo($conn, $rowTeam, 'name');
  // grab all team members of a specific team.
  $fp1id = fetchInfo($conn, $rowTeam, 'fp1id');
  $p1 = "SELECT name FROM FIELD_PLAYERS WHERE $fp1id = playerid";
  $p1Name = fetchInfo($conn, $p1, 'name');
  $fp2id = fetchInfo($conn, $rowTeam, 'fp2id');
  $p2 = "SELECT name FROM FIELD_PLAYERS WHERE $fp2id = playerid";
  $p2Name = fetchInfo($conn, $p2, 'name');
  $fp3id = fetchInfo($conn, $rowTeam, 'fp3id');
  $p3 = "SELECT name FROM FIELD_PLAYERS WHERE $fp3id = playerid";
  $p3Name = fetchInfo($conn, $p3, 'name');
  $fp4id = fetchInfo($conn, $rowTeam, 'fp4id');
  $p4 = "SELECT name FROM FIELD_PLAYERS WHERE $fp4id = playerid";
  $p4Name = fetchInfo($conn, $p4, 'name');
  $fp5id = fetchInfo($conn, $rowTeam, 'fp5id');
  $p5 = "SELECT name FROM FIELD_PLAYERS WHERE $fp5id = playerid";
  $p5Name = fetchInfo($conn, $p5, 'name');
  $fp6id = fetchInfo($conn, $rowTeam, 'fp6id');
  $p6 = "SELECT name FROM FIELD_PLAYERS WHERE $fp6id = playerid";
  $p6Name = fetchInfo($conn, $p6, 'name');
  $gid = fetchInfo($conn, $rowTeam, 'gid');
  $g = "SELECT name FROM GOALIES WHERE $gid = goalieid";
  $gName = fetchInfo($conn, $g, 'name');
  echo "<p class = 'question'><u>Team Name: $teamName</u></p>";
  echo"<table id = 'team'>
          <tr>
              <td class = 'header'><u>Field Player 1</u></td>
              <td class = 'info'>$p1Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Field Player 2</u></td>
              <td class = 'info'>$p2Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Field Player 3</u></td>
              <td class = 'info'>$p3Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Field Player 4</u></td>
              <td class = 'info'>$p4Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Field Player 5</u></td>
              <td class = 'info'>$p5Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Field Player 6</u></td>
              <td class = 'info'>$p6Name</td>
          </tr>
          <tr>
              <td class = 'header'><u>Goalie</u></td>
              <td class = 'info'>$gName</td>
          </tr>
        </table> ";
  }
?>
