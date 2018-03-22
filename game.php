<?php

function game($charName, $userPdo, $needExperience, $help)
{    
    $charInfo = getCharInfo($charName, $userPdo);
    $levelInfo = levelInfo($charInfo, $needExperience);
    $locationInfo = getLocationInfo($charInfo, $userPdo);
    $locationMonsters = getLocationMonsters($charInfo, $userPdo);
    displayTurn ($charInfo, $levelInfo, $locationInfo);    
    commandInterpreter($charInfo, $locationInfo, $userPdo, $locationMonsters, $help);   
    randomCreateMonsters($locationInfo, $locationMonsters, $userPdo);    
    updateCharInfo($charInfo, $userPdo);
}