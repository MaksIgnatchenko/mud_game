<?php

function random_create_objects($loc_objects, $userPdo, $locSize, $charPos){
    if (count($loc_objects) < 15){
        if (rand(1, 5) == 1){
            $query = $userPdo -> prepare("select id_obj_type from npc_loc where id_loc = :cur_loc order by rand() limit 1");
            $query -> execute(['cur_loc' => $charPos['cur_loc']]);
            $randomObjType = intval($query -> fetch()['id_obj_type']);
            $randPosX = rand(0, $locSize['sizeX']);
            $randPosY = rand(0, $locSize['sizeY']);
            $create = $userPdo -> prepare("insert into objects select null, o.id_type, o.name, o.health, o.armor, o.attack, o.inviolability, :cur_loc, :posX, :posY from objects_type o where id_type = :id_obj_type");
            $create -> execute(['cur_loc' => $charPos['cur_loc'], 'posX' => $randPosX, 'posY' => $randPosY, 'id_obj_type' => $randomObjType]);
        }
    }
}


