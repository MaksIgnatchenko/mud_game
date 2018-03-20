<?php

function game($charachter, $userPdo){
    $charPos = position($charachter, $userPdo);
    $locSize = getsizeloc($charPos, $userPdo);
    $loc_objects = location_objects($charPos, $userPdo, $locSize);
    display_location($loc_objects, $charPos, $userPdo, $locSize);
    command_interpreter($charPos, $userPdo, $loc_objects);
    random_create_objects($loc_objects, $userPdo, $locSize, $charPos);
}

    