<?php

// Create a random monster by chance 1/8 in the current location 
function randomCreateMonsters($locationInfo, $locationMonsters, $userPdo)
{
    if (count($locationMonsters) < 15){
        if (rand(1, 8) == 1){
            $query = $userPdo -> prepare("select id_obj_type from npc_loc where id_loc = :id_loc order by rand() limit 1");
            $query -> execute([':id_loc' => $locationInfo['id_loc']]);
            $randomMonsterType = intval($query -> fetch()['id_obj_type']);
            $randPosX = rand(0, $locationInfo['sizeX']);
            $randPosY = rand(0, $locationInfo['sizeY']);
            $queryCreate = $userPdo -> prepare("insert into objects select null, o.id_type, o.name, o.health, o.armor, o.attack, o.inviolability, :id_loc, :posX, :posY from objects_type o where id_type = :id_obj_type");
            $queryCreate -> execute(['id_loc' => $locationInfo['id_loc'], 'posX' => $randPosX, 'posY' => $randPosY, 'id_obj_type' => $randomMonsterType]);
        }
    }
}

// Return an array that contains monsters located in the location
function getLocationMonsters($charInfo, $userPdo)
{
    $monsters = [];
    $locationMonsters = [];
    $query = $userPdo -> prepare("select o.*, ot.gold, ot.exp from objects o inner join objects_type ot on o.id_type = ot.id_type where cur_loc = :loc");
    $query -> execute([":loc" => $charInfo["cur_loc"]]);
    $monsters = $query -> fetchAll();
    foreach ($monsters as $monster){
        $x = $charInfo["posX"] - $monster["posX"];
        $y = $charInfo["posY"] - $monster["posY"];
        $distance = sqrt($x**2 + $y**2);
        $monsterName = $monster['name'];
        $health = $monster['cur_health'];
        $idMonster = $monster['id_obj'];
        $locationMonsters[] = ['posX' => $monster["posX"], 'posY' => $monster["posY"], 'distance' =>  round($distance, 1), 'name' => $monsterName, 'health' => $health, 'id_monster' => $idMonster, 'exp' => $monster['exp'], 'gold' => $monster['gold']];        
    }   
    usort($locationMonsters, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
        return $locationMonsters;
}