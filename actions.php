<?php
function command_read(){
    global $delimiter;
    echo $delimiter;    
    $handle = fopen("php://stdin", "r");    
    $answer = trim(fgets($handle));
    if (preg_match("/[^A-Za-z0-9]/", $answer)) return readconsole();
    else return $answer; 
}
function command_interpreter($charPos, $userPdo, $charachter, $relativePos){ //  Анализирует полученную строку и вызывает необходимую функцию
    echo "Для получения игровой справки нажмите < h >";
    $help = <<<MARKER
___________________________________________________________________________________________________________
|   Передвижение:                                                                                         |
|                                                                                                         |
|   w - бежать вперед                                                                                     |
|   s - бежать назад                                                                                      |
|   a - Бежать влево                                                                                      |
|   d - бежать впарво                                                                                     |
|                                                                                                         |
|   Без пробела вторым симоволом ставится цифра на сколько метров Вы хотите продвинуться (1 - 5 метров)   |
|   Команда должна выглядеть например так :  < w5 >    -  что значит пробежать 5 метров вперед            |
|                                                                                                         |
|   Атака:                                                                                                |
|                                                                                                         |
|   f - атаковать                                                                                         |
|   Например:                                                                                                      |
|   < f3 > - атаковать моба № 3 из списка мобов                                                           |
|                                                                                                         |
|                                                                                                         |
|                                                                                                         |
|_________________________________________________________________________________________________________|
MARKER;
    $answer = command_read();
    $command = strtolower($answer[0]);
    $spec = intval($answer[1]);    
    if (strpbrk($command, "h")) echo $help;
    if (strpbrk($command, "wasd") && (strlen($answer) == 2) && ($spec <= 5)) running($command, $spec, $charPos, $userPdo);   
    if (strpbrk($command, "f")) fight($command, $spec, $charPos, $userPdo, $relativePos, $charachter);
}

function running($command, $spec, $charPos,  $userPdo){
    $sizeLoc = getsizeloc($charPos, $userPdo);
    if ($command == "w"){
        $wall = $charPos["posY"] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти вперед на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $charachter, $relativePos);
        }
        $charPos["posY"] -= $spec;
    }
    if ($command == "s"){
        $wall = $charPos["posY"] + $spec;
        if ($wall > $sizeLoc['sizeY']){
            echo "\nВы не можете пройти назад на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $charachter, $relativePos);
        }
        $charPos["posY"] += $spec;
    }
    if ($command == "a"){
        $wall = $charPos["posX"] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти влево на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $charachter, $relativePos);
        }
        $charPos["posX"] -= $spec;
    }    
    if ($command == "d"){        
        $wall = $charPos["posX"] + $spec;
        if ($wall > $sizeLoc['sizeX']){
            echo "\nВы не можете пройти вправо на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $charachter, $relativePos);           
        }
        $charPos["posX"] += $spec;
    }
    $query = $userPdo -> prepare("update charachters set posY = :posY, posX = :posX where name = :name");
    $query -> execute([":posY" => $charPos["posY"], ":posX" => $charPos["posX"], ":name" => $charPos["name"]]);       
}

function fight($command, $spec, $charPos, $userPdo, $relativePos, $charachter){    
    $query = $userPdo -> prepare("select it.attack, it.attack_range from items i inner join items_type it on i.id_type = it.id_type where i.id_item = :id_weapon");
    $query -> execute([":id_weapon" => $charPos["id_weapon"]]);
    $weapon_info = $query -> fetch();    
    if ($relativePos[$spec - 1][2] > $weapon_info["attack_range"]){
        echo "\nВы не достаете до обьета для атаки. (Дистанция до обьекта ". $relativePos[$spec - 1][2] . " м) (Ваш радиус атаки " . $weapon_info["attack_range"] . " м)\n";
        return command_interpreter($charPos, $userPdo, $charachter, $relativePos);
    }
    $attack = $weapon_info["attack"] * $charPos["basic_attack"];
    $npcCurHealth = $relativePos[$spec - 1][4];
    $npcNewHealth =  $npcCurHealth - $attack;
    if ($npcNewHealth > 0){
        echo "\n" . $relativePos[$spec - 1][3] . " Получил " . $attack . " урона от Вас\n";
        $queryF = $userPdo -> prepare("update objects set cur_health = :health where id_obj = :id_obj");
        $queryF -> execute([":health" => $npcNewHealth, ":id_obj" => $relativePos[$spec - 1][5]]);
    }
        else {
            echo "\n Вы уничтожили " . $relativePos[$spec - 1][3] . "\n";
            $queryD = $userPdo -> prepare("delete from objects where id_obj = :obj");
            $queryD -> execute([":obj" => $relativePos[$spec - 1][5]]);
        }
}


