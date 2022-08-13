<?php

$serverName = "sql102.epizy.com";
$dBUserName = "epiz_31690286";
$dBPassword = "atVkAgEL5BWXxw";
$dBName = "epiz_31690286_parker3750";

$conn = mysqli_connect($serverName, $dBUserName, $dBPassword, $dBName);

 if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}