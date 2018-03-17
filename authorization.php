<?php
$delimiter = "\n---------------------------------------------------------------\n";
function readconsole(){
    global $delimiter;
    echo $delimiter;
    $handle = fopen("php://stdin", "r");    
    $answer = trim(fgets($handle));
    if (preg_match("/[^A-Za-z0-9]/", $answer)) return strtolower(readconsole());
        else return $answer; 
    
}

function main_choose($pdo){
    echo $delimiter;
    echo "\nДобро пожаловать в игру!!!\nДля входа в акканут нажмите  -  L\nДля регистрации нажмите  -  R\nИспользуйте только латинские символы и цифры при вводе и избегайте пробелов\n";
    $answer = readconsole();
    if ($answer == "r"){
        registration($pdo);
        return main_choose($pdo);  
    }
    elseif ($answer == "l") {
        return login();
    }
    else {
        echo "Некорректный ввод попробуйте еще\n";
        return main_choose($pdo);        
    }
}

function login(){
    echo $delimiter;    
    echo "\nВведите Ваш логин\n";
    $un = readconsole();
    echo "\nВведите Ваш пароль:\n";
    $pw = readconsole();
    $hn = "localhost";
    $db = "game";
    try{
        $pdo = new PDO("mysql:host=$hn;dbname=$db", $un, $pw);
    }
    catch (PDOException $e) {
        echo "не удалось подключиться. Неверный логин или пароль ...\n";
        return login();
    }
    if ($pdo){
        echo "\nВы зашли в свою учетную запись";
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
    echo "Введите желаемый логин...\n";
    $login = readconsole();    
    if (login_check($pdo, $login)){
        echo "This account is already exist. Choose another login\n";
        return registration($pdo);
    }
    echo "\nВведите пароль\n";
    $password = readconsole();    
    $verU = $pdo -> prepare("create user :login@localhost'");
    $verU -> execute([":login" => $login]);
    $verR = $pdo -> prepare("grant select, insert, update, delete on *.* to :login@'localhost' identified by :password");
    $verR -> execute([":login" => $login, ":password" => $password]);
    $verF = $pdo->query("flush privileges");
    $verF -> execute();
    if (login_check($pdo, $login)) echo "Account has been created!\n"; 
}
function getUser($userPdo){
    $query = "SELECT SUBSTRING_INDEX(USER(),'@',1);";
    $var = $userPdo -> query($query);
    $result = $var -> fetch();
    return $result[0];
    
}
function room($userPdo, $user){
    global $delimiter;
    echo $delimiter;    
    $charachtersList = "";
    $query = "select c.name, l.name_loc, c.level from charachters c inner join locations l on c.cur_loc = l.id_loc where account=" . "'$user';";
    $tableList = $userPdo -> query($query);
    if ($tableList){
        $i = 1;
        $names = [];
        while ($charachter = $tableList -> fetch()){
            $charachtersList .= "\n" . $i . " Nickname  " . $charachter['name'];
            $charachtersList .= " || Находится в " . $charachter['name_loc'];
            $charachtersList .= " || Уровень " . $charachter['level'] . "\n";
            $names[$i] = $charachter['name'];
            $i++;
        }
    }
    if ($charachtersList == "") $charachtersList = "\nУ Вас пока нет персонажей\n";
    echo $charachtersList;
    echo $delimiter;
    echo "Введите ник персонажа которым хотите играть\n      или\n";
    echo "Создайте нового персонажа введите команду  - C\n";    
    $answer = readconsole();
    if ($answer == "c"){ 
        createCharachter($userPdo, $user);
        return room($userPdo, $user);
    }
    if (chooseChar($names, $answer)){
        return chooseChar($names, $answer);
    }
        else {
            echo "\nУ Вас нету такого персонажа\n Выберите из имеющихся персонажей или создайте нового\n";
            return room($userPdo, $user);
        }
}

function createCharachter($userPdo, $user){    
    echo "\nКакой ник Вы хотите присвоить персонажу?\n";
    $name = readconsole();
    if (nick_check($userPdo, $name)){       
            echo "Такой персонаж уже существует выберите другой ник ... \n";
            return createCharachter($userPdo, $user);
    }
    $query = $userPdo -> prepare("insert into charachters (name, cur_loc, account, level, posX, posY, basic_attack, id_weapon) values (:name, :cur_loc, :user, 1, 50, 50, 1, 1)");
    $query -> execute([":name" => $name, ":cur_loc" => 1, ":user" => $user]);   
}



function chooseChar($chars, $answer){
    foreach ($chars as $char){
        if ($answer == $char) return $char;
    }    
    return false;
}



