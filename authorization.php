<?php

function readConsole()
{    
    $handle = fopen("php://stdin", "r");
    $answer = trim(fgets($handle));
    if (preg_match("/[^A-Za-z0-9]/", $answer)) return readconsole();
    else return strtolower($answer);
    
}

function mainChoose($pdo)
{    
    echo "\v\n--->Добро пожаловать в игру!!!\nДля входа в акканут нажмите  -  L\nДля регистрации нажмите  -  R\nИспользуйте только латинские символы и цифры при вводе и избегайте пробелов\n";
    $answer = readconsole();
    if ($answer == "r"){
        registration($pdo);
        return mainChoose($pdo);
    }
    elseif ($answer == 'l') {
        return login();
    }
    else {
        echo "!!!Некорректный ввод попробуйте еще\n";
        return mainChoose($pdo);
    }
}

function login()
{    
    echo "\n--->Введите Ваш логин\n";
    $un = readconsole();
    echo "\n--->Введите Ваш пароль:\n";
    $pw = readconsole();
    $hn = "localhost";
    $db = "game";
    try{
        $pdo = new PDO("mysql:host=$hn;dbname=$db", $un, $pw);
    }
    catch (PDOException $e) {
        echo "--->Не удалось подключиться. Неверный логин или пароль ...\n";
        return login();
    }
    if ($pdo){
        echo "\n--->Вы зашли в свою учетную запись";
        return $pdo;
    }
}

function loginCheck($pdo, $login)
{    
    $query = $pdo -> prepare("--->select user from mysql.user where user = :login");
    $query -> execute([':login' => $login]);
    $logins = $query -> fetchAll();
    if ($logins) return true;
        else return false;
}

function nickCheck($pdo, $name)
{       
    $query = $pdo -> prepare("select name from charachters where name = :name");
    $query -> execute([':name' => $name]);
    $names = $query -> fetchAll();
    if ($names) return true;
        else return false;
}

function registration($pdo)
{    
    echo "\n--->Введите желаемый логин...\n";
    $login = readconsole();
    if (loginCheck($pdo, $login)){
        echo "\n--->Такой аккаунт уже существует. Выберите другой логин\n";
        return registration($pdo);
    }
    echo "\nВведите пароль\n";
    $password = readconsole();
    $query1 = $pdo -> prepare("create user :login@'localhost' identified by :password");
    $query1 -> execute([':login' => $login, ':password' => $password]);
    $query2 = $pdo -> prepare("grant select, insert, update, delete on *.* to :login@'localhost' identified by :password");
    $query2 -> execute([':login' => $login, ':password' => $password]);
    $query3 = $pdo -> prepare("flush privileges");
    $query3 -> execute();
    if (loginCheck($pdo, $login)) echo "\n--->Аккаунт создан!\n";
}

function getUser($userPdo)
{    
    $query = $userPdo -> prepare("SELECT SUBSTRING_INDEX(USER(),'@',1)");
    $query -> execute();
    $result = $query -> fetch();
    return $result[0];    
}

function room($userPdo, $user)
{       
    $charachtersList = "";    
    $query = $userPdo -> prepare("select c.name, l.name_loc, c.level from charachters c inner join locations l on c.cur_loc = l.id_loc where account = :user");
    $query -> execute([':user' => $user]);
    $charachters = $query -> fetchAll();
    if ($charachters){
        $i = 1;
        $names = [];        
        foreach ($charachters as $charachter){
            $charachtersList .= "\n" . $i . " Nickname  " . $charachter['name'];
            $charachtersList .= " || Находится в " . $charachter['name_loc'];
            $charachtersList .= " || Уровень " . $charachter['level'] . "\n";
            $names[$i] = $charachter['name'];
            $i++;
        }
    } else $charachtersList = "\n--->У Вас пока нет персонажей\n";
    echo "\n\v------------- Ваши персонажи -------------\n";
    echo $charachtersList;    
    echo "\n--->Введите ник персонажа которым хотите играть или Создайте нового персонажа введите команду  - C\n";    
    $answer = readconsole();
    if ($answer == "c"){
        createCharachter($userPdo, $user);
        return room($userPdo, $user);
    }
    if (chooseChar($names, $answer)){
        return chooseChar($names, $answer);
    }
    else {
        echo "\n--->У Вас нету такого персонажа. Выберите из имеющихся персонажей или создайте нового\n";
        return room($userPdo, $user);
    }
}

function createCharachter($userPdo, $user)
{
    echo "\n--->Какой ник Вы хотите присвоить персонажу?\n";
    $name = readconsole();
    if (nickCheck($userPdo, $name)){
        echo "--->Такой персонаж уже существует выберите другой ник ... \n";
        return createCharachter($userPdo, $user);
    }
    $query = $userPdo -> prepare("insert into charachters (name, cur_loc, account, level, posX, posY, basic_attack, cur_health, max_health, cur_exp, gold) values (:name, :cur_loc, :user, 1, 50, 50, 1, 100, 100, 0, 0)");
    $query -> execute([':name' => $name, ':cur_loc' => 1, ':user' => $user]);
    $news_id = $userPdo -> lastInsertId();
    $queryEquip = "insert into items values (null, :id_item_type, :id_owner, :storage, null, null, null)";
    $queryEquipWeapon = $userPdo -> prepare($queryEquip);
    $queryEquipWeapon -> execute([':id_item_type' => 1, 'id_owner' => $news_id, ':storage' => 'c']);
    $queryEquipArmor = $userPdo -> prepare($queryEquip);
    $queryEquipArmor -> execute([':id_item_type' => 2, 'id_owner' => $news_id, ':storage' => 'c']);
}

function chooseChar($chars, $choice)
{
    foreach ($chars as $char){
        if ($choice == $char) return $char;
    }
    return false;
}

