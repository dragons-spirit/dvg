<?php

include("connect.inc.php");
include("funktionen.php");




/**********************************************/
/* Registrierungsdaten in Datenbank speichern */
/**********************************************/
function insert_registrierung($login, $passwort, $email)
{
	# Verbindungsaufbau zur Datenbank
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("INSERT INTO account (login, passwort, email, aktiv) VALUES (?, ?, ?, true)")){
		$stmt->bind_param('sss', $login, $passwort, $email);
		$stmt->execute();
		echo "<br />\nRegistrierungsdaten gespeichert: [" . $login . " | " . $passwort . " | " . $email . "]<br />\n";
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
	# Verbindungsaufbau zur Datenbank
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("SELECT * FROM account WHERE login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		echo "<br />\nAnmeldedaten abgeholt für: [" . $login . "]<br />\n";
		$result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            foreach ($row as $r)
            {
                print "$r ";
            }
            print "\n";
        }
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
	}
	
	close_connection($connect_db_dvg);
	return true;
}


#insert_registrierung('hendrik','abc123','h.m@xmg.de');

get_anmeldung('hendrik');


?>