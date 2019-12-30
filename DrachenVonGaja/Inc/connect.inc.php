<?php

# Testumgebung auf bei Bedarf auf localhost
$umgebung_localhost = true;

$debug = false;
$debug_connection = false;


if ($umgebung_localhost) $default_host = "localhost";
else $default_host = "192.168.22.49";

$default_user = "dragons";
$default_pswd = "ti19nahend88rik";
$default_db = "db_dvg";


/**********************************/
/* Datenbankverbindung herstellen */
/**********************************/
function open_connection($user, $pswd, $host, $db)
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

?>