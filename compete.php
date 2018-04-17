<?php
session_start();
include "connectDB.php";

$userid = $_SESSION['userid'];
$getName = $pdo->prepare("SELECT * FROM TEAMS WHERE teamid = $userid");
$getName->execute();
$result = $getName->fetch();
$teamName = $result['name'];

$opponentName = $_SESSION['oppName'];
//variable to aggregate the scores
$sum = 0;
 ?>

<!DOCTYPE html>

<head>
  <title>Agro-Draft</title>
  <link rel = "stylesheet" href="styleCompete.css">
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
  <!-- hold the two tables representing competing teams and stats-->
  <div id = "container">
    <!-- div for team1-->
    <div class = "section" id = "team1">
      <h4><u>
      <?php
        echo "$teamName";
      ?>
    </u></h4>
    <!--dsiplay team 1's field players-->
      <table>
        <p>Field Players</p>
        <tr class = 'header'>
          <td><u>Player</u></td>
          <td><u>Team</u></td>
          <td class = 'stat'><u>SH</u></td>
          <td class = 'stat'><u>G</u></td>
          <td class = 'stat'><u>ASS</u></td>
          <td class = 'stat'><u>EX</u></td>
          <td class = 'stat'><u>D/EX</u></td>
          <td class = 'stat'><u>ST</u></td>
        </tr>
        <tbody>
          <?php
          //get the team to display
          $teamid = $_SESSION['userid'];
          $select_drafted_players = $pdo->prepare("SELECT * FROM TEAMS where teamid = $teamid");
          $select_drafted_players-> execute();
          $results = $select_drafted_players->fetch();
          //display players and stats
          include "competeDisplayFP.php";
           ?>
         </tbody>
      </table>
      <!--display team 1's goalie-->
      <table class = 'goalies'>
        <p>Goalie</p>
        <tr class = 'header'>
          <td><u>Player</u></td>
          <td><u>Team</u></td>
          <td class = 'stat'><u>GA</u></td>
          <td class = 'stat'><u>SV</u></td>
          <td class = 'stat'><u>ST</u></td>
        </tr>
        <tbody>
          <?php
          include "competeDisplayGoalies.php";
          ?>
        </tbody>
      </table>
      <br/>
      <?php
      //display the total score
      echo "Total Score: $sum1";
      //add player 1's score
      $sum+=$sum1;
       ?>
    </div>

    <!-- "vs" div to separate teams-->
    <div class = "section" id = 'vs'>
      <p>Vs.</p>
    </div>

    <!--section to hold team 2-->
    <div class = "section" id = "team2">
      <h4><u>
      <?php
        echo $opponentName;
      ?>
    </u></h4>
    <!--display team 2's field players-->
      <table class = 'FP'>
        <p>Field Players</p>
        <tr class = 'header'>
          <td><u>Player</u></td>
          <td><u>Team</u></td>
          <td class = 'stat'><u>SH</u></td>
          <td class = 'stat'><u>G</u></td>
          <td class = 'stat'><u>AS</u></td>
          <td class = 'stat'><u>EX</u></td>
          <td class = 'stat'><u>D/EX</u></td>
          <td class = 'stat'><u>ST</u></td>
        </tr>
        <tbody>
        <?php
          //get the opponent's id
          $teamid = $_SESSION['oppID'];
          $select_drafted_players = $pdo->prepare("SELECT * FROM TEAMS where teamid = $teamid");
          $select_drafted_players-> execute();
          $results = $select_drafted_players->fetch();
          //display players and stats
          include "competeDisplayFP.php";
         ?>
       </tbody>
     </table>
     <!--display team 2's goalie-->
      <table class = 'goalies'>
        <p>Goalie</p>
        <tr class = 'header'>
          <td><u>Player</u></td>
          <td><u>Team</u></td>
          <td class = 'stat'><u>GA</u></td>
          <td class = 'stat'><u>SV</u></td>
          <td class = 'stat'><u>ST</u></td>
        </tr>
        <tbody>
        <?php
          include "competeDisplayGoalies.php";
        ?>
        </tbody>
      </table>
      <br/>
      <?php
        //display the total score
        echo "Total Score: $sum1";
        //compare the sums
        $sum-=$sum1;
       ?>
    </div>
    <?php
    //display graphics to reflect scoring
      if($sum==0){
        //if it's a tie
        echo  "<div class = 'left tie'> <p>tie</p></div>";
        echo  "<div class = 'right tie'> <p>tie</p></div>";
      }else if($sum>0){
        //if player1 wins
        echo  "<div class = 'left win'> <p>win</p></div>";
        echo  "<div class = 'right loss'> <p>loss</p></div>";
      }else{
        //if player2 (opponent) wins
        echo  "<div class = 'left loss'> <p>loss</p></div>";
        echo  "<div class = 'right win'> <p>win</p></div>";
      }
     ?>
  </div>
</body>
