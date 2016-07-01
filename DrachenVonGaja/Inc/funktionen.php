<?php

require("connect.inc.php");



/**********************************************/
/* Registrierungsdaten in Datenbank speichern */
/**********************************************/
function registrierung($login, $passwort, $email)
{
	# Verbindungsaufbau zur Datenbank
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("INSERT INTO account (login, passwort, email, aktiv) VALUES (?, ?, ?, true)")){
		echo "<br />\nQuerry okay<br />\n";	
		$stmt->bind_param('sss', $login, $passwort, $email);
		$stmt->execute();
	} else {
		echo "<br />\nQuerry fehlerhaft<br />\n";
	}
	
	#$res = $stmt->get_result();
	#while ($row = $result->fetch_row()) {
    #    printf ("%s (%s)\n", $row[0], $row[1]);
	
	#	printf("id = %s (%s)\n", $row['id'], gettype($row['id']));
	#	printf("login = %s (%s)\n", $row['login'], gettype($row['login']));
	#	printf("passwort = %s (%s)\n", $row['passwort'], gettype($row['passwort']));
	#	printf("email = %s (%s)\n", $row['email'], gettype($row['email']));
	#	printf("aktiv = %s (%s)\n", $row['aktiv'], gettype($row['aktiv']));
	#	printf("letzter_login = %s (%s)\n", $row['letzter_login'], gettype($row['letzter_login']));
	
	#}
    #$ausgabe = "<br />\nÜbergebene Parameter:<br />\n login = " . $login . "<br />\n passwort = " . $passwort . "<br />\n email = " . $email . "<br />\n";
	#echo $ausgabe;
	close_connection($connect_db_dvg);
	return true;
}





?>