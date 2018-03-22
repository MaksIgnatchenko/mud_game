<?php
require_once ("authorization.php");
require_once ("content.php");
require_once ("game.php");
require_once ("charInfo.php");
require_once ("location.php");
require_once ("monsters.php");
require_once ("display.php");
require_once ("action.php");

$un = "root";
$pw = "Delirium130";
$hn = "localhost";
$db = "";
try {
    $pdo = new PDO("mysql:host=$hn;dbname=$db", $un, $pw);
} catch (PDOException $e) {
    echo "Connection is not completed";
}
$userPdo = mainChoose($pdo); 
$user = getUser($userPdo); 
$charName = room($userPdo, $user); 
$pdo = null; 
while (true){
    game($charName, $userPdo, $needExperience, $help);
}


