<?php
//include php to connect to db and set up PDO ($pdo)

function scrape(){
  $message = '';
  include 'connectDB.php';
  //check that the game has not already been inputed
  $unique_game = $pdo->prepare("SELECT * FROM GAMEURLS WHERE urls = ?");
  $unique_game->execute([$_SESSION['foshURL']]);

  if($unique_game->rowCount() == 0){
    //if game has not been entered, enter it into GAMES table...
    $insert = $pdo->prepare("INSERT INTO GAMEURLS (gameid, urls) VALUES (?, ?)");
    $insert->execute([0, $_SESSION['foshURL']]);

    //...then run the query, scraping data into db
    exec('/usr/bin/python /Users/zackrossman/Documents/test.py '.$_SESSION['foshURL'], $output);

    #print updates of progree
    foreach($output as $line){
      $message = $line;
    }
    //if successful upload, return 1
    return True;
  }else{
    //if game has already been entered, return 0
    return False;
  }
}
?>
