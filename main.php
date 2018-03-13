<?php
require_once("functions.php");
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
$pdo = null;                    // Закрывает администраторское соединение
$user = getUser($userPdo);      // Получает логин аккаунта
room($userPdo, $user, $delimiter);


