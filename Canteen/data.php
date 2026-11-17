<?php

$products = [
    'M1' => ['name' => 'Chicken Adobo Meal', 'price' => 85, 'category' => 'food', 'stock' => 12],
    'M2' => ['name' => 'Pork Sisig Meal', 'price' => 100, 'category' => 'food', 'stock' => 8],
    'M3' => ['name' => 'Vegetable Meal', 'price' => 70, 'category' => 'food', 'stock' => 4],
    'D1' => ['name' => 'Bottled Water', 'price' => 20, 'category' => 'drink', 'stock' => 30],
    'D2' => ['name' => 'Iced Tea', 'price' => 35, 'category' => 'drink', 'stock' => 15]
];

$customerTypes = [
    'student' => 'Student',
    'employee' => 'Employee',
    'guest' => 'Guest'
];

$denominations = [1000, 500, 200, 100, 50, 20, 10, 5, 1];

$stages = ['Order received', 'Cooking', 'Plating', 'Ready for pickup'];