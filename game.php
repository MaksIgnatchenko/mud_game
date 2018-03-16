<?php

function game($charachter, $userPdo){
    $charPos = position($charachter, $userPdo);
    $npcPos = location_objects($charPos, $userPdo);
    $relativePos = relativepos($charPos, $npcPos);
    display_location($relativePos, $charPos, $userPdo);
    command_interpreter($charPos, $userPdo, $charachter, $relativePos, $charachter);
}

