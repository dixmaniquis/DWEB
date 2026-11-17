<?php

date_default_timezone_set('Asia/Manila');

$storeName = 'Campus Canteen';
$maxQty = 10;
$bulkQty = 5;
$bulkRate = .03;
$maxRate = .25;
$comboValue = 10;

switch (date('l')) {
    case 'Monday':
    case 'Wednesday':
        $specialName = 'Meal Madness';
        $specialCategory = 'food';
        $specialRate = .05;
        break;
    case 'Tuesday':
    case 'Thursday':
        $specialName = 'Drink Deal';
        $specialCategory = 'drink';
        $specialRate = .10;
        break;
    case 'Friday':
        $specialName = 'Friday Feast';
        $specialCategory = 'all';
        $specialRate = .07;
        break;
    default:
        $specialName = 'No special today';
        $specialCategory = 'none';
        $specialRate = 0;
}