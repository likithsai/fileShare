<?php
    include 'Config.php';
    include 'Database.php';
    include 'Crypto.php';
    include 'Sessions.php';
    include 'Compress.php';

    $session = new SessionManager(30);
    $crypto = new Crypto();
    $db = new Database();
    $db->openConnection(HOST, USER, PASSWORD, DATABASE);
    $compress = new CompressManager();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../fileShare/assets/css/bootstrap-icon/bootstrap-icons.css" />
    <link rel="stylesheet" href="../fileShare/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../fileShare/assets/css/bootstrap-icon/bootstrap-icons.css" />
    <link rel="stylesheet" href="../fileShare/assets/css/style.css" />
</head>