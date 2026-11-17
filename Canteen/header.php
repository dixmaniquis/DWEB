<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Canteen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        main {
            max-width: 680px;
            padding: 24px;
            margin: auto;
            background: white;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input, select, button {
            width: 100%;
            padding: 9px;
            margin: 6px 0 12px;
            box-sizing: border-box;
        }

        button {
            background: #165b43;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        nav a {
            color: #165b43;
            text-decoration: none;
            font-weight: bold;
        }

        .banner {
            background: #e8f4ee;
            border-left: 5px solid #165b43;
            padding: 10px 14px;
            margin: 12px 0;
        }

        .note {
            color: #555;
            font-size: 0.9em;
            margin: 2px 0;
        }

        .success {
            color: #216d45;
        }

        .error {
            color: #ad2532;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Campus Canteen</h1>
    <p>Student Ordering and Discount System</p>
    <hr>
