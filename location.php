<?php

// Return an array that contains basic information about the location
function getLocationInfo($charInfo, $userPdo)
{
    $query = $userPdo -> prepare("select id_loc, name_loc, description_loc, sizeX, sizeY from locations where id_loc = :cur_loc");
    $query -> execute([':cur_loc' => $charInfo['cur_loc']]);
    return $query -> fetch();
}

