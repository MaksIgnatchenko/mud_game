<?php

function game($charachter, $userPdo){
    $charPos = position($charachter, $userPdo);   
    $loc_objects = location_objects($charPos, $userPdo);
    display_location($loc_objects, $charPos, $userPdo);
    command_interpreter($charPos, $userPdo, $charachter, $loc_objects);
}

    