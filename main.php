<?php
require_once("authorization.php");
require_once("position.php");
require_once("actions.php");
require_once("game.php");
$un = "root";
$pw = "";
$hn = "localhost";
$db = "";
try{
    $pdo = new PDO("mysql:host=$hn;dbname=$db", $un, $pw);
}
catch (PDOException $e) {
    echo "Connection is not completed";
}
$userPdo = main_choose($pdo);   // Получает обьект соединения аккаунта пользователя с бд                 
$user = getUser($userPdo);      // Получает логин аккаунта
$charachter = room($userPdo, $user);
$pdo = null;                    // Закрывает администраторское соединение
while (true){
    game($charachter, $userPdo);
}
