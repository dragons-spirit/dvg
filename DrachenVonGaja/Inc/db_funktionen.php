<?php

include("connect.inc.php");
include("funktionen.php");


/* Funktionsübersicht


get_account_id($login)
get_anmeldung($login)
get_anmeldung_email($email)
insert_registrierung($login, $passwort, $email)

get_start_gattung($gattung)

get_gebiet_id($gebiet_titel)

get_spieler($login)
insert_spieler($login, $gattung, $name, $geschlecht)

*/


#***************************************************************************************************************
#*************************************************** ACCOUNT ***************************************************
#***************************************************************************************************************


#---------------------------------------------- SELECT Account.id ----------------------------------------------
# 	-> account.login (str)
#	<- account.id (int)

function get_account_id($login)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id 
			FROM 	account 
			WHERE 	login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAccount_id abgeholt für: [" . $login . "]<br />\n";
		close_connection($connect_db_dvg);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}


#---------------------------------------------- SELECT Account.* ----------------------------------------------
# 	-> account.login (str)
#	Array mit Accountdaten [Position]
#	<- [0] id
#	<- [1] login
#	<- [2] passwort
#	<- [3] email
#	<- [4] aktiv
#	<- [5] rolle
#	<- [6] letzter_login

function get_anmeldung($login)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	account 
			WHERE 	login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAnmeldedaten abgeholt für: [" . $login . "]<br />\n";
		close_connection($connect_db_dvg);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


# 	-> account.email (str)
#	Array mit Accountdaten [Position]
#	<- [0] id
#	<- [1] login
#	<- [2] passwort
#	<- [3] email
#	<- [4] aktiv
#	<- [5] rolle
#	<- [6] letzter_login

function get_anmeldung_email($email)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	* 
			FROM 	account 
			WHERE 	email = ?")){
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAnmeldedaten abgeholt für: [" . $email . "]<br />\n";
		close_connection($connect_db_dvg);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}


#---------------------------------------------- INSERT Account.* ----------------------------------------------
# 	-> account.login (str)
# 	-> account.passwort (str)
# 	-> account.email (str)
#	<- true/false

function insert_registrierung($login, $passwort, $email)
{
	global $debug;
	$connect_db_dvg = open_connection();
	$log = false;
	
	if ($account_existiert = get_anmeldung($login)){
		if ($account_existiert[1] == $login){
			echo $account_existiert[1];
			echo $login;
			echo "Nutzer schon vorhanden<br />\n";
			$log = true;
		}
	}
	if ($account_existiert = get_anmeldung_email($email)){
		if ($account_existiert[3] == $email){
			echo $account_existiert[3];
			echo $email;
			echo "Email schon in Benutzung<br />\n";
			$log = true;
		}
	}
	if ($log){
		close_connection($connect_db_dvg);
		return false;
	}
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO account (
				login, 
				passwort, 
				email, 
				aktiv, 
				rolle) 
			VALUES (?, ?, ?, true, 'Spieler')")){
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


#***************************************************************************************************************
#*************************************************** GATTUNG ***************************************************
#***************************************************************************************************************


#--------------------------------------- SELECT Gattung.id + Gattung.start_* -----------------------------------
# 	-> gattung.titel (str)
#	Array mit id und Startwerten zur Gattung [Position]
#	<- [0] id
#	<- [1] start_staerke
#	<- [2] start_intelligenz
#	<- [3] start_magie
#	<- [4] start_element_feuer
#	<- [5] start_element_wasser
#	<- [6] start_element_erde
#	<- [7] start_element_luft

function get_start_gattung($gattung)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, 
					start_staerke, 
					start_intelligenz, 
					start_magie, 
					start_element_feuer, 
					start_element_wasser, 
					start_element_erde, 
					start_element_luft 
			FROM 	gattung 
			WHERE 	titel = ?")){
		$stmt->bind_param('s', $gattung);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGattungsdaten abgeholt für: [" . $gattung . "]<br />\n";
		close_connection($connect_db_dvg);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}


#***************************************************************************************************************
#*************************************************** GEBIET ****************************************************
#***************************************************************************************************************


#---------------------------------------------- SELECT Gebiet.id ----------------------------------------------
# 	-> gebiet.titel (str)
#	<- gebiet.id (int)

function get_gebiet_id($gebiet_titel)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id 
			FROM 	gebiet 
			WHERE 	titel = ?")){
		$stmt->bind_param('s', $gebiet_titel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGebiet_id abgeholt für: [" . $gebiet_titel . "]<br />\n";
		close_connection($connect_db_dvg);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}	
}



#***************************************************************************************************************
#*************************************************** SPIELER ***************************************************
#***************************************************************************************************************


#----------------------------------------------- SELECT Spieler.* ----------------------------------------------
# 	-> account.login (str)
#	Array mit Spielerdaten [Position]
#	<- [0] id
#	<- [1] account_id
#	<- [2] bilder_id
#	<- [3] gattung.titel
#	<- [4] level_id
#	<- [5] gebiet_id
#	<- [6] name
#	<- [7] geschlecht

function get_spieler($login)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	spieler.id,
					spieler.account_id,
					spieler.bilder_id,
					gattung.titel,
					spieler.level_id,
					gebiet.titel,
					spieler.name,
					spieler.geschlecht
			FROM 	spieler
					JOIN account ON spieler.account_id = account.id 
					JOIN gattung ON spieler.gattung_id = gattung.id
					JOIN gebiet ON spieler.gebiet_id = gebiet.id
			WHERE 	account.login = ?"))
	{
		$stmt->bind_param('s', $login);
		$stmt->execute();
		if ($debug) echo "<br />\nSpieler für: [" . $login . "] geladen.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spieler()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------------------- INSERT Spieler.* ----------------------------------------------
# 	-> account.login (str)
#	-> gebiet.titel (str) - gewähltes Startgebiet
#	-> gattung.titel (str) - gewählte Gattung
#	-> name (str) - gewählter Spielername
#	-> geschlecht (str) - gewähltes Geschlecht
#	<- true/false

function insert_spieler($login, $gebiet, $gattung, $name, $geschlecht)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO spieler (
				account_id, 
				bilder_id, 
				gattung_id, 
				level_id, 
				gebiet_id, 
				name, 
				geschlecht, 
				staerke, 
				intelligenz, 
				magie, 
				element_feuer, 
				element_wasser, 
				element_erde, 
				element_luft, 
				gesundheit, 
				max_gesundheit, 
				energie, 
				max_energie, 
				balance) 
			VALUES (?, 1, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)")){
		if (! $account_id = get_account_id($login))
		{
			close_connection($connect_db_dvg);
			echo "<br />\nLogin nicht gefunden<br />\n";
			return false;
		}
		if (! $gebiet_id = get_gebiet_id($gebiet))
		{
			close_connection($connect_db_dvg);
			echo "<br />\nGebiet nicht gefunden<br />\n";
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
			echo "<br />\nGattung nicht gefunden<br />\n";
			return false;
		}
		$max_gesundheit = berechne_max_gesundheit($start_staerke, $start_intelligenz, $start_magie);
		$max_energie = berechne_max_energie($start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft);
		
		$stmt->bind_param('dddssddddddddddd', $account_id, $gattung_id, $gebiet_id, $name, $geschlecht, $start_staerke, $start_intelligenz, $start_magie, $start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft, $max_gesundheit, $max_gesundheit, $max_energie, $max_energie);
		$stmt->execute();
		if ($debug) echo "<br />\nNeuer Spieler gespeichert: [" . $account_id . " | " . $name . " | " . $geschlecht . " | " . $gattung . " | " . $gebiet . "]<br />\n";
		close_connection($connect_db_dvg);
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in insert_spieler()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}

#***************************************************************************************************************
#************************************************ TEST-BEREICH *************************************************
#***************************************************************************************************************

#echo insert_registrierung('hugo', '123456', 'hugo@gmx.de');
#echo insert_registrierung('balduin', 'xyzzyx', 'balduin@gmail.com');
#echo insert_registrierung('klaus_trophobie', 'zuckerwatte', 'register@klaustrophobie.de');

#insert_spieler('hugo', 'Klippendrache', 'Hathor', 'M');
#insert_spieler('balduin', 'Eisdrache', 'Skadi', 'W');
#insert_spieler('klaus_trophobie', 'Kristalldrache', 'Wyrm', 'W');

#echo insert_registrierung('mustafa', 'kyrillisch', 'afatsum@mustafa.ru');

?>