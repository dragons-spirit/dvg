<?php

# Zeitzone
date_default_timezone_set("Europe/Berlin");

# Anmeldedaten Datenbank
$host = "localhost";
$user = "dragons";
$pswd = "ti19nahend88rik";
$db = "db_dvg";





function registrierung($login, $passwort, $email)
{
	# Aktuelle Zeit ausgeben
	echo "<br />\n" . timestamp() . "<br />\n";

		
	# Verbindungsaufbau zur Datenbank
	global $host, $user, $pswd, $db;
	$connection_db_dvg = new mysqli($host, $user, $pswd, $db);
	if (mysqli_connect_error()) {
		die("Verbindungsfehler (" . mysqli_connect_errno() . ") "
				. mysqli_connect_error());
	}
	echo "Verbunden mit Datenbank db_dvg... " . $connection_db_dvg->host_info . "<br />\n";
		
	
	# Querry absetzen
	if ($stmt = $connection_db_dvg->prepare("INSERT INTO account (login, passwort, email, aktiv) VALUES (?, ?, ?, true)")){
		echo "<br />\nQuerry okay<br />\n";	
		$stmt->bind_param('sss', $login, $passwort, $email);
		$stmt->execute();
	} else {
		echo "<br />\nQuerry fehlerhaft<br />\n";
	}

    $ausgabe = "<br />\nÜbergebene Parameter:<br />\n login = " . $login . "<br />\n passwort = " . $passwort . "<br />\n email = " . $email . "<br />\n";
	echo $ausgabe;
	
	$connection_db_dvg->close();
	
	return $ausgabe . "<br />\nEnde";
}

$log = "user1";
$pwd = "xyz";
$mail = "user1@test.com";

registrierung($log, $pwd, $mail);


# Zeitstempel
function timestamp()
{
	$time_unix = time();
	$tstamp = date("Y-m-d",$time_unix) . " " . date("H:i:s",$time_unix);
	return $tstamp;
}


?>