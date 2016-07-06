<?php

include("connect.inc.php");
include("funktionen.php");


/************************/
/* Account_id von login */
/************************/
function get_account_id($login)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("SELECT id FROM account WHERE login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		if ($debug) echo "<br />\nAccount_id abgeholt für: [" . $login . "]<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		close_connection($connect_db_dvg);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}

/***********************************/
/* Gattungsdaten von Gattungstitel */
/**********************************/
function get_start_gattung($gattung)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("SELECT id, start_staerke, start_intelligenz, start_magie, start_element_feuer, start_element_wasser, start_element_erde, start_element_luft FROM gattung WHERE titel = ?")){
		$stmt->bind_param('s', $gattung);
		$stmt->execute();
		if ($debug) echo "<br />\nGattungsdaten abgeholt für: [" . $gattung . "]<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		close_connection($connect_db_dvg);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}


/**********************************************/
/* Neuen Spieler in Datenbank speichern */
/**********************************************/
function insert_spieler($login, $gattung, $name, $geschlecht)
{
	global $debug;
	$connect_db_dvg = open_connection();
		
	# Querry absetzen
	if ($stmt = $connect_db_dvg->prepare("INSERT INTO spieler (account_id, bilder_id, gattung_id, level_id, position_id, name, geschlecht, staerke, intelligenz, magie, element_feuer, element_wasser, element_erde, element_luft, gesundheit, max_gesundheit, energie, max_energie, balance) VALUES (?, 1, ?, 1, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)")){
		if (! $account_id = get_account_id($login)){
			close_connection($connect_db_dvg);
			return false;
		}
		if ($gattung_data = get_start_gattung($gattung)){
			$gattung_id = $gattung_data[0];
			$start_staerke = $gattung_data[1];
			$start_intelligenz = $gattung_data[2];
			$start_magie = $gattung_data[3];
			$start_element_feuer = $gattung_data[4];
			$start_element_wasser = $gattung_data[5];
			$start_element_erde = $gattung_data[6];
			$start_element_luft = $gattung_data[7];
		}
		else{
			close_connection($connect_db_dvg);
			return false;
		}
		$max_gesundheit = berechne_max_gesundheit($start_staerke, $start_intelligenz, $start_magie);
		$max_energie = berechne_max_energie($start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft);
		
		$stmt->bind_param('ddssddddddddddd', $account_id, $gattung_id, $name, $geschlecht, $start_staerke, $start_intelligenz, $start_magie, $start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft, $max_gesundheit, $max_gesundheit, $max_energie, $max_energie);
		$stmt->execute();
		if ($debug) echo "<br />\nNeuer Spieler gespeichert: [" . $name . " | " . $geschlecht . " | " . $account_id . " | " . $gattung . "]<br />\n";
		close_connection($connect_db_dvg);
		return true;
	} else {
		echo "<br />\nQuerryfehler in <br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}





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
		close_connection($connect_db_dvg);
		return true;
	} else {
		echo "<br />\nQuerryfehler in <br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
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
		close_connection($connect_db_dvg);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}

#insert_spieler('hendrik', 'Vulkandrache', 'Theobald', 'M');


?>