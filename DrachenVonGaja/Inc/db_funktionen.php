<?php

include("connect.inc.php");
include("funktionen.php");




/**********************************************/
/* Registrierungsdaten in Datenbank speichern */
/**********************************************/
function insert_registrierung($login, $passwort, $email)
{
	global $debug;
	
	# Verbindungsaufbau zur Datenbank
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("INSERT INTO account (login, passwort, email, aktiv) VALUES (?, ?, ?, true)")){
		$stmt->bind_param('sss', $login, $passwort, $email);
		$stmt->execute();
		if ($debug) echo "<br />\nRegistrierungsdaten gespeichert: [" . $login . " | " . $passwort . " | " . $email . "]<br />\n";
	} else {
		echo "<br />\nQuerryfehler in <br />\n";
	}
	
	close_connection($connect_db_dvg);
	return true;
}



/**************************************/
/* Anmeldedaten aus Datenbank abholen */
/**************************************/
function get_anmeldung($login)
{
	global $debug;
	
	# Verbindungsaufbau zur Datenbank
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("SELECT * FROM account WHERE login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		if ($debug) echo "<br />\nAnmeldedaten abgeholt für: [" . $login . "]<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
	}
	
	# Verbindungsabbbau zur Datenbank
	close_connection($connect_db_dvg);
	
	return $row;
}


#insert_registrierung('hendrik','rofl','zen@jamaika.be');
/*
$ergebnis = get_anmeldung('hendrik');
if (!$ergebnis){
	print "Benutzer existiert nicht!";
}
else{
	foreach ($ergebnis as $column){
		print "[ $column ] ";
	}
}
print "<br />\n";
*/
?>