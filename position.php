<?php

function position($name, $userPdo){    // Возвращает массив с нормером текущей локации и координат персонажа
    $charPos = [];
    $query = $userPdo -> prepare("select cur_loc, posX, posY, name, basic_attack, id, cur_health from charachters where name = :name");
    $query -> execute([":name" => $name]);    
    $charPos = $query -> fetch();
    $queryIndic = $userPdo -> prepare("select sum(it.attack) as 'attack', sum(it.attack_range) as 'attack_range', sum(it.armor) as 'armor' from items_type it inner join items i on it.id_type = i.id_type where owner = :id and storage = 'c'");
    $queryIndic -> execute([":id" => $charPos["id"]]);
    $charInfo =  $queryIndic -> fetch();
    $charPos = array_merge($charPos, $charInfo);
    print_r($charPos);
    print_r($charInfo);
    return $charPos;
}


//-----------------------------------------------------------------------------------//

function location_objects($charPos, $userPdo){  // Возвращает массив обьектов находящихся в локации
    $objects = [];
    $loc_objects = [];
    $query = $userPdo -> prepare("select * from objects where cur_loc = :loc");
    $query -> execute([":loc" => $charPos["cur_loc"]]);
    $objects = $query -> fetchAll();
    foreach ($objects as $object){
        $x = $charPos["posX"] - $object["posX"];
        $y = $charPos["posY"] - $object["posY"];
        $distance = sqrt($x**2 + $y**2);
        $name = $object["name"];
        $health = $object["cur_health"];
        $idObj = $object["id_obj"];
        $loc_objects[] = [$y, $x, $distance, $name, $health, $idObj];
    }
    usort($loc_objects, function($a, $b) {
        return $a[2] <=> $b[2];
    });
    return $loc_objects;        
}


//----------------------------------------------------------------------------------//

function display_location($loc_objects, $charPos, $userPdo){
    $locSize = getsizeloc($charPos, $userPdo);
    
    static $is_desc = 1;
    $message_desc = "";
    if ($is_desc == 1) $message_desc = $locSize['description_loc'] . "\n";
    $is_desc++;
    global $delimiter;
    $locSize = getsizeloc($charPos, $userPdo);
    $locName = $locSize['name_loc'];
    $locDesc = $locSize['description_loc'];
    $sizeX = $locSize['sizeX'];
    $sizeY = $locSize['sizeY'];
    $left = $charPos['posX'];
    $right = $sizeX - $left;    
    $top = $charPos['posY'];
    $bottom = $sizeY - $top;    
    $locMessage = <<<LOC
\n____________________________________________________________________________        
                                                                              
Вы находитесь в локации $locName\n$message_desc\nРазмеры локации $sizeX x $sizeY м
Ваше расположение до (верхней границы - $top м) (до нижней $bottom м) (левой - $left м) (до правой $right м)                                 
   _____________________________________________________________________________\n                                                                       
    
LOC;
    echo $delimiter;
    echo $locMessage;
    echo $delimiter;
    echo "Что находится в локации: \n";
    $i = 1;
    foreach ($loc_objects as $rel){
        $union1 = "";
        $union2 = " ";
        $union3 = "";
        if ($rel[0] > 0) $messageX = abs($rel[0]) . " метров впереди";
        if ($rel[0] < 0) $messageX = abs($rel[0]) . " метров сзади";
        if ($rel[0] == 0) $messageX = "";
        if ($rel[1] > 0) $messageY = abs($rel[1]) . " метров левее";
        if ($rel[1] < 0) $messageY = abs($rel[1]) . " метров правее";
        if ($rel[1] == 0) $messageY = "";
        if ($rel[2] == 0) $union3 = "рядом с Вами";
        if ($rel[2] != 0){
            $union1 = "на ";
            $union4 = " от Вас";
        }
        if (($rel[0] != 0) && ($rel[1] != 0)) $union2 = " и ";
        echo "\n $i " . $rel[3] . " (Здоровье - $rel[4])" . " находится " . $union1 . $messageX . $union2 . $messageY . $union3 . $union4 . " (до цели " . round($rel[2], 1) . " метров)";
        $i++;
    }
    echo $delimiter;    
}

function getsizeloc($charPos, $userPdo){
    $query = $userPdo -> prepare("select name_loc, description_loc, sizeX, sizeY from locations where id_loc = :cur_loc");
    $query -> execute([":cur_loc" => $charPos["cur_loc"]]);
    return $query -> fetch();
}