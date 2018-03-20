<?php

function command_interpreter($charPos, $userPdo, $loc_objects){ //  Анализирует полученную строку и вызывает необходимую функцию
    $help = <<<MARKER
___________________________________________________________________________________________________________
|                                                                                                         |
|  Все команда состоят из 2 символов . Первый символ (буква) - вид действия, второй символ (цифра)        | 
|  ставится без пробела и означеает модификатор, тоесть на сколько пробежать, кого атаковать и тд .       |
|                                                                                                         |
|   Передвижение:                                                                                         |
|                                                                                                         |
|   w - бежать вперед                                                                                     |
|   s - бежать назад                                                                                      |
|   a - Бежать влево                                                                                      |
|   d - бежать впарво                                                                                     |     
|   w5 - что значит пробежать 5 метров вперед                                                             |
|                                                                                                         |
|   Атака:                                                                                                |
|                                                                                                         |
|   f - атаковать                                                                                         |
|   f3 - атаковать моба № 3 из списка обьектов                                                            | 
|   e - посмотреть экипировку                                                                             |
|                                                                                                         |
|   exit - выход из игры                                                                                  |
|                                                                                                         |
|_________________________________________________________________________________________________________|
MARKER;
    $answer = readconsole();
    $command = strtolower($answer[0]);
    if (isset($answer[1])){
        $spec = intval($answer[1]);        
    }
        else $spec = 0;
    if (strpbrk($command, "h")){
       echo $help;
       return command_interpreter($charPos, $userPdo, $loc_objects);
    }
    if (strpbrk($command, "e")){
        display_property(check_property($charPos, $userPdo));
        return command_interpreter($charPos, $userPdo, $loc_objects);
    }
    if (strpbrk($command, "wasd") && (strlen($answer) == 2) && ($spec <= 5)) running($command, $spec, $charPos, $userPdo);   
    if (strpbrk($command, "f")) fight($command, $spec, $charPos, $userPdo, $loc_objects);    
    if (strpbrk($command, "exit")) exit(); 
}

function running($command, $spec, $charPos,  $userPdo){
    $sizeLoc = getsizeloc($charPos, $userPdo);
    if ($command == "w"){
        $wall = $charPos["posY"] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти вперед на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $loc_objects);
        }
        $charPos["posY"] -= $spec;
    }
    if ($command == "s"){
        $wall = $charPos["posY"] + $spec;
        if ($wall > $sizeLoc['sizeY']){
            echo "\nВы не можете пройти назад на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $loc_objects);
        }
        $charPos["posY"] += $spec;
    }
    if ($command == "a"){
        $wall = $charPos["posX"] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти влево на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $loc_objects);
        }
        $charPos["posX"] -= $spec;
    }    
    if ($command == "d"){        
        $wall = $charPos["posX"] + $spec;
        if ($wall > $sizeLoc['sizeX']){
            echo "\nВы не можете пройти вправо на $spec метров, - граница локации\n";
            return command_interpreter($charPos, $userPdo, $loc_objects);           
        }
        $charPos["posX"] += $spec;
    }
    $query = $userPdo -> prepare("update charachters set posY = :posY, posX = :posX where name = :name");
    $query -> execute([":posY" => $charPos["posY"], ":posX" => $charPos["posX"], ":name" => $charPos["name"]]);       
}

function fight($command, $spec, $charPos, $userPdo, $loc_objects){     
    if ($loc_objects[$spec - 1][2] > $charPos["attack_range"]){
        echo "\nВы не достаете до обьета для атаки. (Дистанция до обьекта ". $loc_objects[$spec - 1][2] . " м) (Ваш радиус атаки " . $loc_objects["attack_range"] . " м)\n";
        return command_interpreter($charPos, $userPdo, $loc_objects);
    }
    $attack = $charPos["attack"] * $charPos["basic_attack"];
    $npcCurHealth = $loc_objects[$spec - 1][4];
    $npcNewHealth =  $npcCurHealth - $attack;
    if ($npcNewHealth > 0){
        echo "\n" . $loc_objects[$spec - 1][3] . " Получил " . $attack . " урона от Вас\n";
        $queryF = $userPdo -> prepare("update objects set cur_health = :health where id_obj = :id_obj");
        $queryF -> execute([":health" => $npcNewHealth, ":id_obj" => $loc_objects[$spec - 1][5]]);
    }
        else {
            echo "\n Вы уничтожили " . $loc_objects[$spec - 1][3] . "\n";
            $queryD = $userPdo -> prepare("delete from objects where id_obj = :obj");
            $queryD -> execute([":obj" => $loc_objects[$spec - 1][5]]);
        }
}

function display_property($equip){
    echo "Оружие  <" . $equip['weapon']['name'] . "> Атака " . $equip['weapon']['attack'] . " Дистанция атаки " . $equip['weapon']['range'] . " м\n";
    echo "Броня <" . $equip['armor']['name'] . "> Защита " . $equip['armor']['armor'] . "\n"; 
}


