<?php

$default_host = "localhost";
$default_user = "dragons";
$default_pswd = "ti19nahend88rik";
$default_db = "db_dvg";

$debug = false;
$debug_connection = false;

/**********************************/
/* Datenbankverbindung herstellen */
/**********************************/
function open_connection($user = "dragons", $pswd = "ti19nahend88rik", $host = "localhost", $db = "db_dvg")
{
	global $debug_connection;
	
	# Aktuelle Zeit ausgeben
	if ($debug_connection) echo "<br />\n" . timestamp() . "<br />\n";
	
	# Verbindungsaufbau zur Datenbank
	$connection = new mysqli($host, $user, $pswd, $db);
	if (mysqli_connect_error()) {
		die("Verbindungsfehler (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
	}
	if ($debug_connection)  echo "Verbunden mit Datenbank " . $db . " ... " . $connection->host_info . "<br />\n";
	
	mysqli_set_charset($connection, 'utf8');
	return $connection;
}

/*******************************/
/* Datenbankverbindung trennen */
/*******************************/
function close_connection($connection)
{
	global $debug_connection;
	
	# Aktuelle Zeit ausgeben
	if ($debug_connection)  echo "<br />\n" . timestamp() . "<br />\n";
	
	# Verbindung zur Datenbank trennen
	$connection->close();
	if ($debug_connection)  echo "Verbindung zur Datenbank getrennt ... <br />\n";
	return true;
}


/*
$host = "localhost";
$user ="1t9i8na8";
$pswd ="d04m08k19u97b";
$db = "browsergame";

$conn = mysql_connect($host, $user, $pswd) or die ("Verbindung zum Server fehlgeschlagen !");

mysql_select_db($db, $conn) or die("Verbindung zur Datenbank fehlgeschlagen !");
*/

?>