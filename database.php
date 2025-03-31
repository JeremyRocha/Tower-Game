<?php
$host = "localhost"; //Variable for local hose
$dbname = "towerGame"; //Variable for database name
$username = "root"; //username for database
$password = ""; //Pass word for database


//Try to connect to database or catches exception
try{
    $pdo = new PDO("mysql:host=$host;port=3308;dbname=$dbname", $username, $password); //Connection to database
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Set the error mode to exception
}catch (PDOException $e){
    die("Connection failed: " . $e->getMessage()); //Display error
}
