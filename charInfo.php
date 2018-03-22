<?php

function getCharInfo($charName, $userPdo)
{
    $query1 = $userPdo -> prepare("select cur_loc, posX, posY, name, basic_attack, id, cur_health, cur_exp, gold, max_health, level from charachters where name = :name");
    $query1 -> execute([':name' => $charName]);
    $charInfo1 = $query1 -> fetch();
    $query2 = $userPdo -> prepare("select sum(it.attack) as 'attack', sum(it.attack_range) as 'attack_range', sum(it.armor) as 'armor' from items_type it inner join items i on it.id_type = i.id_type where owner = :id and storage = 'c'");
    $query2 -> execute([':id' => $charInfo1['id']]);
    $charInfo2 =  $query2 -> fetch();
    $charInfo = array_merge($charInfo1, $charInfo2);
    return $charInfo;
}

function updateCharInfo($charInfo, $userPdo)    
{    
    $query = $userPdo -> prepare("update charachters set cur_loc = :new_cur_loc, posX = :new_posX, posY = :new_posY, basic_attack = :new_basic_attack, cur_health = :new_cur_health, cur_exp = :new_cur_exp, gold = :new_gold, level = :new_level where name = :name");
    $query -> execute([':new_cur_loc' => $charInfo['cur_loc'], ':new_posX' => $charInfo['posX'], ':new_posY' => $charInfo['posY'], ':new_basic_attack' => $charInfo['basic_attack'], ':new_cur_health' => $charInfo['cur_health'], ':new_cur_exp' => $charInfo['cur_exp'], ':new_gold' => $charInfo['gold'], ':new_level' => $charInfo['level'], ':name' => $charInfo['name']]); 
}


function levelInfo($charInfo, $needExperience)
{
    $level = 0;
    $curExp = $charInfo['cur_exp'];    
    $i = 0;    
    foreach ($needExperience as $mark){
        if ($curExp < $mark){
            $level = $i;
            break;
        }
        $i++;
    }
    $percentOfLevel = round((($curExp - $needExperience[$level - 1]) / ($needExperience[$level] - $needExperience[$level - 1])) * 100, 1);
    $levelInfo = ['level' => $level - 1, 'percent' => $percentOfLevel];
    return $levelInfo;
}

function checkProperty($charInfo, $userPdo)
{
    $queryEquipment = $userPdo -> prepare("select it.type, it.name, attack, armor, attack_range from items_type it inner join items i on i.id_type = it.id_type where i.owner = :id and i.storage = 'c'");
    $queryEquipment -> execute([":id" => $charInfo["id"]]);
    $charEquipment = $queryEquipment -> fetchAll();
    $weapon = [];
    $armor = [];
    foreach ($charEquipment as $item){
        if ($item['type'] == 'weapon'){
            $weapon = ['name' => $item['name'], 'attack' => $item['attack'], 'range' => $item['attack_range']];
        }
        if ($item['type'] = 'armor'){
            $armor = ['name' => $item['name'], 'armor' => $item['armor']];
        }
    }
    $equip = ['weapon' => $weapon, 'armor' => $armor];
    $queryBag = $userPdo -> prepare("select it.type, it.name from items_type it inner join items i on i.id_type = it.id_type where i.owner = :id and i.storage = 'i'");
    $queryBag -> execute([':id' => $charInfo['id']]);
    $charBag = $queryBag -> fetchAll();
    $property = ['equip' => $equip, 'bag' => $charBag];
    return $property;
}

