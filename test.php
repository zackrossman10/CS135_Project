<?php

  #Set up db connection and PDO
  $host = 'localhost';
  $db   = 'TEST_SCIAC';
  $user = 'root';
  $pass = 'root';
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

  $opt = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  $pdo = new PDO($dsn, "root", "root", $opt);

  #if user submits a request to enter game data
  if(isset($_POST['address'])){

    //check that the game has not already been inputed
    $unique_game = $pdo->prepare("SELECT * FROM GAMES WHERE urls = ?");
    $unique_game->execute([$_POST['address']]);

    if($unique_game->rowCount() == 0){
      echo "Data uploading, please wait...<br/>";
      #if game has not been entered, enter it into GAMES table...
      $insert = $pdo->prepare("INSERT INTO GAMES (urls) VALUES (?)");
      $insert->execute([$_POST['address']]);

      #...then run the query, scraping data into db
      exec('/usr/bin/python /Users/zackrossman/Documents/test.py '.$_POST['address'], $output);

      #print updates of progree
      foreach($output as $line){
        echo $line."<br/>";
      }
      echo "<br />";
    }else{
      #if game has already been entered, don't scrape the data again
      echo "Game alreay entered into database<br />";
    }
  }
?>

<!DOCTYPE html>

<head>
  <title>test</title>
</head>
<body>
  <form action = "test.php" method = "POST">
    <strong>The Fosh</strong> Box Score Address<br/>
    <input type = "address" name = "address"/>
    <input type = "submit" name = "submit" value = "submit"/>
  </form>
</body>
