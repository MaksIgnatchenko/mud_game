<?php

function position($name, $userPdo){    // Возвращает массив с нормером текущей локации и координат персонажа
    $charPos = [];
    $query = $userPdo -> prepare("select cur_loc, posX, posY, name, basic_attack, id_weapon from charachters where name = :name");
    $query -> execute([":name" => $name]);    
    $charPos = $query -> fetch();    
    return $charPos;
}


//-----------------------------------------------------------------------------------//

function location_objects($charPos, $userPdo){  // Возвращает массив обьектов находящихся в локации
    $npcPos = [];
    $query = $userPdo -> prepare("select * from objects where cur_loc = :loc");
    $query -> execute([":loc" => $charPos["cur_loc"]]);
    $npcPos = $query -> fetchAll();
    return $npcPos;      
}


//----------------------------------------------------------------------------------//

function relativepos($charPos, $npcPos){ // возвращает массив с относительным положением игровх обьектов относительно игрока и их характеристиками
     
    $relativePos = [];
    foreach ($npcPos as $npc){
        $x = $charPos["posX"] - $npc["posX"];
        $y = $charPos["posY"] - $npc["posY"];
        $distance = sqrt($x**2 + $y**2);
        $name = $npc["name"];
        $health = $npc["cur_health"]; 
        $idObj = $npc["id_obj"];        
        $relativePos[] = [$y, $x, $distance, $name, $health, $idObj];
    }
    usort($relativePos, function($a, $b) {
        return $a[2] <=> $b[2];
    });
    return $relativePos;
}

function display_location($relativePos, $charPos, $userPdo){
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
    foreach ($relativePos as $rel){
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