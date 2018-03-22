<?php

function displayTurn ($charInfo, $levelInfo, $locationInfo)
{
    echo "\n\v\v_____________________________________________________________________________\n\v";
    displayCharInfo ($charInfo, $levelInfo, $locationInfo);
    echo "\v??? Для получения игровой справки введите букву < h >, а затем нажмите клавишу ENTER\n";
}

function displayCharInfo ($charInfo, $levelInfo, $locationInfo)
{
    echo "Персонаж " . $charInfo['name'] . " Уровень " . $levelInfo['level'] . " ( набрано для следующего уровня " . $levelInfo['percent'] . " %)\n";
    echo "Локация <" . $locationInfo['name_loc'] . "> Размер <" . $locationInfo['sizeX'] . " x " . $locationInfo['sizeY'] . "> м " . "Координаты персонажа " . $charInfo['posX'] . " : " . $charInfo['posY'] . "\n";
    echo "Здоровье " . $charInfo['cur_health'] . " из " . $charInfo['max_health'] . " (" . round(($charInfo['cur_health'] / $charInfo['max_health']) * 100, 0) . " %)\n";
    echo "Золото " . $charInfo['gold'] . " монет\n";    
}

function displayLocation ($locationMonsters)
{    
    $i = 1;
    echo "\n---Номер |  Монстр                   |  Здоровье   |  Координаты  |  Дистанция до Вас |  \n";
    foreach ($locationMonsters as $monster){
        echo "\n---" . $i . add($i, 9) . $monster['name'] . add($monster['name'], 28) . $monster['health'] . add($monster['health'], 14) . $monster['posX'] . "x" . $monster['posY'] . add($monster['posX'] . "x" . $monster['posY'], 15) . $monster['distance'] . " м" . add($monster['distance'] . " м", 20);
        $i++;
    }
    echo "\n\v";
}

function displayProperty($property)
{
    echo "Вы одеты в такое снаряжение:\n";
    echo "---Оружие  <" . $property['equip']['weapon']['name'] . "> Атака " . $property['equip']['weapon']['attack'] . " Дистанция атаки " . $property['equip']['weapon']['range'] . " м\n";
    echo "---Броня <" . $property['equip']['armor']['name'] . "> Защита " . $property['equip']['armor']['armor'] . "\n";
    echo "Содержимое рюкзака :\n";
    if ($property['bag']){
        foreach ($property['bag'] as $item){
            echo "---" . $item['name'] . "\n";
        }
    } else echo " Ваш рюкзак пуст\n";
}
