<?php

//Set up parameters for db connection
$host = 'localhost';
$db   = 'TEST_SCIAC';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

//connect to db
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//set up pdo
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

//create pdo for preparing and executing statements
$pdo = new PDO($dsn, "root", "root", $opt);

?>
