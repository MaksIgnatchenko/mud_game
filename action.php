<?php

// analyzes the received command and calls the corresponding function for a particular action
function commandInterpreter(&$charInfo, $locationInfo, $userPdo, $locationMonsters, $help)
{       
    echo "--- Ваши действия ---> ";
    $answer = readconsole();
    $command = strtolower($answer[0]);
    if (isset($answer[1])){
        $spec = intval($answer[1]);
    }
    else $spec = 0;
    if (strpbrk($command, "h")){
        echo $help;
        return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
    }
    if (strpbrk($command, "e")){
        displayProperty(checkProperty($charInfo, $userPdo));        
        return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
    }
    if (strpbrk($command, "m")){
        displayLocation ($locationMonsters);
        return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
    }
    if (strpbrk($command, "wasd") && (strlen($answer) == 2) && ($spec <= 5)) running($command, $spec, $charInfo,  $locationInfo,  $userPdo, $locationMonsters, $help);
    if (strpbrk($command, "f")) fight($command, $spec, $charInfo, $userPdo, $locationMonsters);
    if (strpbrk($command, "exit")) exit();
}

function running($command, $spec, &$charInfo, $locationInfo,  $userPdo, $locationMonsters, $help)
{    
    if ($command == "s"){
        $wall = $charInfo['posY'] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти назад на $spec метров, - граница локации\n";
            return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
        }
        $charInfo['posY'] -= $spec;
    }
    if ($command == "w"){
        $wall = $charInfo["posY"] + $spec;
        if ($wall > $locationInfo['sizeY']){
            echo "\nВы не можете пройти вперед на $spec метров, - граница локации\n";
            return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
        }
        $charInfo["posY"] += $spec;
    }
    if ($command == "a"){
        $wall = $charInfo["posX"] - $spec;
        if ($wall < 0){
            echo "\nВы не можете пройти влево на $spec метров, - граница локации\n";
            return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
        }
        $charInfo["posX"] -= $spec;
    }
    if ($command == "d"){
        $wall = $charInfo["posX"] + $spec;
        if ($wall > $locationInfo['sizeX']){
            echo "\nВы не можете пройти вправо на $spec метров, - граница локации\n";
            return commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);
        }
        $charInfo["posX"] += $spec;
    }   
}

function fight($command, $spec, &$charInfo, $userPdo, $locationMonsters){
    if ($locationMonsters[$spec - 1]['distance'] > $charInfo["attack_range"]){
        echo "\nВы не достаете до обьета для атаки. (Дистанция до обьекта ". $locationMonsters[$spec - 1]['name'] . " м) (Ваш радиус атаки " . $charInfo["attack_range"] . " м)\n";
        return commandInterpreter($charInfo, $userPdo, $locationMonsters, $help);
    }
    $attack = $charInfo["attack"] * $charInfo["basic_attack"];
    $npcCurHealth = $locationMonsters[$spec - 1]['health'];
    $npcNewHealth =  $npcCurHealth - $attack;
    if ($npcNewHealth > 0){
        echo "\n" . $locationMonsters[$spec - 1]['name'] . " Получил " . $attack . " урона от Вас\n";
        $queryF = $userPdo -> prepare("update objects set cur_health = :health where id_obj = :id_monster");
        $queryF -> execute([":health" => $npcNewHealth, ":id_monster" => $locationMonsters[$spec - 1]['id_monster']]);
    }
    else {
        echo "\n Вы уничтожили " . $locationMonsters[$spec - 1]['name'] . "\n";
        echo " Получено " . $locationMonsters[$spec - 1]['exp'] . " опыта" . " и " . $locationMonsters[$spec - 1]['gold'] . " золота\n";
        $charInfo['cur_exp'] += $locationMonsters[$spec - 1]['exp'];
        $charInfo['gold'] += $locationMonsters[$spec - 1]['gold'];
        $queryD = $userPdo -> prepare("delete from objects where id_obj = :id_monster");
        $queryD -> execute([":id_monster" => $locationMonsters[$spec - 1]['id_monster']]);
    }
}
