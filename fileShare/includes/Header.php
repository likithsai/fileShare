<?php
    include 'Config.php';
    include 'Database.php';
    $db = new Database();
    $db->openConnection(HOST, USER, PASSWORD, DATABASE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../fileShare/assets/css/bootstrap.min.css" rel="stylesheet">
</head>