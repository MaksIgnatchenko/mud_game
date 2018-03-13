<?php
$delimiter = "\n---------------------------------------------------------------\n";
function readconsole(){
    $handle = fopen("php://stdin", "r");
    echo "\nOnly latin characters and numbers\n";
    $answer = trim(fgets($handle));
    if (preg_match("/[^A-Za-z0-9]/", $answer)) return readconsole();
        else return $answer;
        
    
}

function main_choose($pdo){
    echo $delimiter;
    echo "\nWelcome to the game!!!\nWhat do you want?\n\nCreate new account   -  input !!!!! < r > !!!!! and press ENTER\nSign in   -   input !!!!! < l > !!!!! and press ENTER\n";
    $answer = readconsole();
    if ($answer == "r"){
        registration($pdo);
        return main_choose($pdo);  
    }
    elseif ($answer == "l") {
        return login();
    }
    else {
        echo "Incorrect choose ... Try again\n";
        return main_choose($pdo);        
    }
}

function login(){
    echo $delimiter;
    echo "\nSign in please:\n";
    echo "Your login\n";
    $un = readconsole();
    echo "\nYour password:\n";
    $pw = readconsole();
    $hn = "localhost";
    $db = "game";
    try{
        $pdo = new PDO("mysql:host=$hn;dbname=$db", $un, $pw);
    }
    catch (PDOException $e) {
        echo "Connection is not completed. Try again ...... (Check your login or password)\n";
        return login();
    }
    if ($pdo){
        echo "\nConnection complete";
        return $pdo;
    }
}

function login_check($pdo, $login){
    $query = "select user from mysql.user where user = " . "'$login';";
    $answer = $pdo -> query($query);
    $logins = $answer -> fetchAll();
    if ($logins) return true;
    else return false;
}

function nick_check($pdo, $name){
    $query = "select name from charachters where name = " . "'$name';";
    $answer = $pdo -> query($query);
    $names = $answer -> fetchAll();
    if ($names) return true;
    else return false;
}

function registration($pdo){
    echo $delimiter;
    echo "Input your login...\n";
    $login = readconsole();    
    if (login_check($pdo, $login)){
        echo "This account is already exist. Choose another login\n";
        return registration($pdo);
    }
    echo "\nInput your password\n";
    $password = readconsole();   
    $queryCreate = "create user " . "'$login'" . "@'localhost';";
    $queryRights = "grant select, insert, update on *.* to " . "'$login'" . "@'localhost' identified by " . "'$password';";
    $queryF = "flush privileges;";
    $verU = $pdo -> prepare($queryCreate);
    $verU -> execute();
    $verR = $pdo -> prepare($queryRights);
    $verR -> execute();
    $verF = $pdo->query($queryF);
    $verF -> execute();
    if (login_check($pdo, $login)) echo "Account has been created!\n"; 
}
function getUser($userPdo){
    $query = "SELECT SUBSTRING_INDEX(USER(),'@',1);";
    $var = $userPdo -> query($query);
    $result = $var -> fetch();
    return $result[0];
    
}
function room($userPdo, $user, $delimiter){
    echo $delimiter;    
    $charachtersList = "";
    $query = "select name, cur_loc, level from charachters where account=" . "'$user';";
    $tableList = $userPdo -> query($query);
    if ($tableList){
        $i = 1;
        while ($charachter = $tableList -> fetch()){
            $charachtersList .= "\n" . $i . " Nickname  " . $charachter['name'];
            $charachtersList .= " || Now he is in " . $charachter['cur_loc'];
            $charachtersList .= " || Level " . $charachter['level'] . "\n";
            $i++;
        }
    }
    if ($charachtersList == "") $charachtersList = "\nYou do not have any characters yet\n";
    echo $charachtersList;
    echo $delimiter;
    echo "Choose a charachter which you want play  - type < Nickname >\n";
    echo "Create a new charachter  - type < create >\n";
    $answer = readconsole();
    if ($answer == "create") createCharachter($userPdo, $user);
    return room($userPdo, $user, $delimiter);
}

function createCharachter($userPdo, $user){    
    echo "\nType name of your caharacte\n";
    $name = readconsole();
    if (nick_check($userPdo, $name)){       
            echo "This nickname is already exist. Choose another ... \n";
            return createCharachter($userPdo, $user);
    }
    $query = "insert into charachters (name, cur_loc, account, level) values (" . "'$name', " . "1, " . "'$user', " . "1);";
    $result = $userPdo -> query($query); 
    echo "\n" . $query;
}