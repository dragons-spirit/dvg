<?php

$host = "localhost";
$user ="1t9i8na8";
$pswd ="d04m08k19u97b";
$db = "browsergame";

$conn = mysql_connect($host, $user, $pswd) or die ("Verbindung zum Server fehlgeschlagen !");

mysql_select_db($db, $conn) or die("Verbindung zur Datenbank fehlgeschlagen !");

?>