﻿<?php

include("funktionen.php");

/* 

Eine Übersicht zu den verfügbaren Funktionen findet sich unter ../dvg/Docs/Übersicht Datenbankfunktionen.xlsx

*/

#***************************************************************************************************************
#*************************************************** ACCOUNT ***************************************************
#***************************************************************************************************************


#---------------------------------------------- SELECT account.id ----------------------------------------------
# 	-> account.login (str)
#	<- account.id (int)

function get_account_id($login)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id 
			FROM 	account 
			WHERE 	login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAccount_id abgeholt für: [" . $login . "]<br />\n";
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_account_id()<br />\n";
		return false;
	}	
}


#---------------------------------------------- SELECT account.* ----------------------------------------------
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
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	account 
			WHERE 	login = ?")){
		$stmt->bind_param('s', $login);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAnmeldedaten abgeholt für: [" . $login . "]<br />\n";
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung()<br />\n";
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
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	* 
			FROM 	account 
			WHERE 	email = ?")){
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nAnmeldedaten abgeholt für: [" . $email . "]<br />\n";
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_anmeldung_email()<br />\n";
		return false;
	}	
}


#---------------------------------------------- INSERT account.* ----------------------------------------------
# 	-> account.login (str)
# 	-> account.passwort (str)
# 	-> account.email (str)
#	<- true/false

function insert_registrierung($login, $passwort, $email)
{
	global $debug;
	global $connect_db_dvg;
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
		return true;
	} else {
		echo "<br />\nQuerryfehler in insert_registrierung<br />\n";
		return false;
	}
}





#***************************************************************************************************************
#*************************************************** AKTION ****************************************************
#***************************************************************************************************************


#--------------------------------------------- SELECT aktion.dauer ---------------------------------------------
# 	-> aktion.titel (int)
#	<- aktion.dauer (str)

function get_aktion_dauer($aktion_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	dauer
			FROM 	aktion
			WHERE 	titel = ?")){
		$stmt->bind_param('d', $aktion_titel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_aktion_dauer()<br />\n";
		return false;
	}	
}


#------------------------------------------- INSERT aktion_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> aktion.titel (int)
#	?-> any_id_1 (int) - default = 0
#	?-> any_id_2 (int) - default = 0
#	<- true/false

function insert_aktion_spieler($spieler_id, $aktion_titel, $any_id_1 = 0, $any_id_2 = 0)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($aktion_titel != "kampf"){
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO aktion_spieler(
				spieler_id, 
				aktion_id, 
				start, 
				ende,
				status,
				any_id_1,
				any_id_2) 
			VALUES (?, (SELECT id FROM aktion WHERE titel = ?), NOW(), ADDTIME(NOW(), (SELECT dauer FROM aktion WHERE titel = ?)), 'gestartet', ?, ?)")){
			$stmt->bind_param('dssdd', $spieler_id, $aktion_titel, $aktion_titel, $any_id_1, $any_id_2);
			$stmt->execute();
			if ($debug) echo "<br />\nNeue Aktion begonnen: [" . $spieler_id . " | " . $aktion_titel . "]<br />\n";
			return true;
		} else {
			echo "<br />\nQuerryfehler in insert_aktion_spieler()<br />\n";
			return false;
		}
	} else {
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO aktion_spieler(
				spieler_id, 
				aktion_id, 
				start, 
				ende,
				status,
				any_id_1,
				any_id_2) 
			VALUES (?, (SELECT id FROM aktion WHERE titel = ?), NOW(), '2037-12-31 23-59-59', 'gestartet', ?, ?)")){
			$stmt->bind_param('dsdd', $spieler_id, $aktion_titel, $any_id_1, $any_id_2);
			$stmt->execute();
			if ($debug) echo "<br />\nNeue Aktion begonnen: [" . $spieler_id . " | " . $aktion_titel . "]<br />\n";
			return true;
		} else {
			echo "<br />\nQuerryfehler in insert_aktion_spieler()<br />\n";
			return false;
		}
	}
}


#---------------------------------------- SELECT aktion_spieler (Spieler) --------------------------------------
# 	-> spieler.id (int)
#	Array mit Aktions-Daten [Position]
#	<- [0] aktion.titel (str)
#	<- [1] aktion.text (str)
#	<- [2] aktion.art (str)
#	<- [3] aktion.beschreibung (str)
#	<- [4] aktion_spieler.start (str)
#	<- [5] aktion_spieler.ende (str)
#	<- [6] aktion.statusbild (str)
#	<- [7] aktion_spieler.status (str)
#	<- [8] aktion_spieler.any_id_1 (str)
#	<- [9] aktion_spieler.any_id_2 (str)

function get_aktion_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				aktion.titel,
				aktion.text,
				aktion.art,
				aktion.beschreibung,
				aktion_spieler.start,
				aktion_spieler.ende,
				aktion.statusbild,
				aktion_spieler.status,
				aktion_spieler.any_id_1,
				aktion_spieler.any_id_2
			FROM
				aktion_spieler
				JOIN aktion ON aktion.id = aktion_spieler.aktion_id
			WHERE
				spieler_id = ?
				AND aktion_spieler.status != 'abgeschlossen'
			ORDER BY
				aktion_spieler.ende ASC")){
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_aktion_spieler<br />\n";
		return false;
	}	
}


#---------------------------------------- SELECT aktion_spieler (Spieler) --------------------------------------
# 	-> spieler.id (int)
#	<- true/false

function get_aktion_spieler_aktiv($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	
				aktion_spieler.id
			FROM
				aktion_spieler
			WHERE
				spieler_id = ?
				AND NOW() > aktion_spieler.start
				AND NOW() < aktion_spieler.ende")){
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_aktion_spieler_aktiv<br />\n";
		return false;
	}	
}


#------------------------------------------- UPDATE aktion_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> aktion.text (str)
#	<- true/false

function update_aktion_spieler($spieler_id, $aktion_text)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE aktion_spieler
			SET status = 'abgeschlossen'
			WHERE
				spieler_id = ?
				AND aktion_id IN (SELECT id FROM aktion WHERE text = ?)")){
		$stmt->bind_param('ds', $spieler_id, $aktion_text);
		$stmt->execute();
		if ($debug) echo "<br />\nAktion: [" . $aktion_text . " von Spieler " . $spieler_id . "] wurde abgeschlossen<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in update_aktion_spieler()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** BILDER ****************************************************
#***************************************************************************************************************


#----------------------------------------- SELECT bilder.pfad (Gebiet) -----------------------------------------
# 	-> gebiet.id (int)
#	<- bild.pfad(str)

function get_bild_zu_gebiet($gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad 
			FROM 	bilder
				JOIN gebiet ON gebiet.bilder_id = bilder.id
			WHERE 	gebiet.id = ?")){
		$stmt->bind_param('d', $gebiet_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_gebiet()<br />\n";
		return false;
	}	
}


#----------------------------------------------- SELECT bilder.* ----------------------------------------------
# 	-> bilder.id (int)
#	Array mit Bilderdaten [Position]
#	<- [0] id
#	<- [1] titel
#	<- [2] pfad
#	<- [3] beschreibung

function get_bilder($bilder_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	* 
			FROM 	bilder
			WHERE 	id = ?")){
		$stmt->bind_param('d', $bilder_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bilder()<br />\n";
		return false;
	}
}


#----------------------------------------------- SELECT bilder.pfad (titel) ----------------------------------------------
# 	-> bilder.titel (str)
#	<- bild.pfad(str)

function get_bild_zu_titel($titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad 
			FROM 	bilder
			WHERE 	bilder.titel = ?")){
		$stmt->bind_param('s', $titel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_titel()<br />\n";
		return false;
	}
}


#----------------------------------------------- SELECT bilder.pfad (id) ----------------------------------------------
# 	-> bilder.id (int)
#	<- bild.pfad(str)

function get_bild_zu_id($bilder_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad
			FROM 	bilder
			WHERE 	bilder.id = ?"))
			{
		$stmt->bind_param('d', $bilder_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_id()<br />\n";
		return false;
	}
}


#----------------------------------------- SELECT bilder.id (Gattung, Level) -----------------------------------------
# 	-> gattung.id (int)
#	-> level.id (int)
#	<- bilder.id (int)

function get_bild_zu_gattung_level($gattung_id, $level_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	bilder_id 
			FROM 	level_bilder
			WHERE 	gattung_id = ?
				and level_id = ?")){
		$stmt->bind_param('dd', $gattung_id, $level_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_gebiet()<br />\n";
		return false;
	}	
}



#***************************************************************************************************************
#*************************************************** GATTUNG ***************************************************
#***************************************************************************************************************

#--------------------------------------- SELECT gattung.id + gattung.start_* -----------------------------------
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
	global $connect_db_dvg;
	
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
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_start_gattung()<br />\n";
		return false;
	}	
}


#-------------------------------------------- SELECT gattung.titel ---------------------------------------------
# 	-> gattung.id (int)
#	<- gattung.titel (str)

function get_gattung_titel($gattung_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	titel
			FROM 	gattung 
			WHERE 	id = ?")){
		$stmt->bind_param('d', $gattung_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGattungsname abgeholt für: [" . $gattung . "]<br />\n";
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_gattung_titel()<br />\n";
		return false;
	}	
}


#***************************************************************************************************************
#*************************************************** GEBIET ****************************************************
#***************************************************************************************************************


#---------------------------------------------- SELECT gebiet.id ----------------------------------------------
# 	-> gebiet.titel (str)
#	<- gebiet.id (int)

function get_gebiet_id($gebiet_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id 
			FROM 	gebiet 
			WHERE 	titel = ?")){
		$stmt->bind_param('s', $gebiet_titel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGebiet_id abgeholt für: [" . $gebiet_titel . "]<br />\n";
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_gebiet_id()<br />\n";
		return false;
	}	
}



#---------------------------------------------- SELECT gebiet.* ----------------------------------------------
# 	-> gebiet.id (int)
#	Array mit allen Gebietsdaten [Position]
#	<- [0] id
#	<- [1] bilder_id
#	<- [2] titel
#	<- [3] beschreibung

function get_gebiet($gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	* 
			FROM 	gebiet 
			WHERE 	id = ?")){
		$stmt->bind_param('d', $gebiet_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGebietsdaten abgeholt für: [" . $gebiet_id . "]<br />\n";
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_gebiet()<br />\n";
		return false;
	}	
}


#----------------------------------- SELECT gebiet_gebiet.* (alle Verbindungen) ---------------------------
# 	-> von_gebiet.id (int)
#	Array mit allen Gebietsverlinkungen ausgehend vom übergebenen Gebiet (1 Datensatz pro Verlinkung) [Position]
#	<- [0] id
#	<- [1] von_gebiet_id
#	<- [2] nach_gebiet_id
#	<- [3] nach_gebiet_titel
#	<- [4] pos_x
#	<- [5] pos_y

function get_gebiet_gebiet($von_gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT gebiet_gebiet.id, 
				gebiet_gebiet.von_gebiet_id, 
				gebiet_gebiet.nach_gebiet_id, 
				gebiet.titel AS nach_gebiet_titel,
				gebiet_gebiet.pos_x,
				gebiet_gebiet.pos_y
			FROM gebiet_gebiet 
				LEFT JOIN gebiet ON (gebiet_gebiet.nach_gebiet_id = gebiet.id) 
			WHERE gebiet_gebiet.von_gebiet_id = ?")){
		$stmt->bind_param('d', $von_gebiet_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_gebiet_gebiet()<br />\n";
		return false;
	}	
}

#-------------------------------- SELECT gebiet_gebiet.* (Verbindung vorhanden?) --------------------------
# 	-> von_gebiet.id (int)
# 	-> nach_gebiet.id (int)
#	<- true/false


function exist_gebiet_gebiet($von_gebiet_id, $nach_gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	count(id)
			FROM 	gebiet_gebiet
			WHERE 	von_gebiet_id = ?
				AND nach_gebiet = ?")){
		$stmt->bind_param('dd', $von_gebiet_id, $nach_gebiet_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($debug and $row) echo "<br />\nGebietsverlinkung getestet für: [" . $von_gebiet_id . " -> " . $von_gebiet_id . "]<br />\n";
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in exist_gebiet_gebiet()<br />\n";
		return false;
	}	
}


#***************************************************************************************************************
#**************************************************** ITEMS ****************************************************
#***************************************************************************************************************


#-------------------------------- SELECT items.* (NPC) --------------------------------
# 	-> npc.id (int)
#	Array mit Items [Position]
#	<- [0] id
#	<- [1] titel
#	<- [2] beschreibung
#	<- [3] typ
#	<- [4] wahrscheinlichkeit
#	<- [5] anzahl_min
#	<- [6] anzahl_max

function get_items_npc($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				items.id,
				items.titel,
				items.beschreibung,
				items.typ,
				npc_items.wahrscheinlichkeit,
				npc_items.anzahl_min,
				npc_items.anzahl_max
			FROM
				items
				JOIN npc_items ON items.id = npc_items.items_id
			WHERE
				npc_items.npc_id = ?
			ORDER BY items.titel"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		if ($debug) echo "<br />\nItems für: [npc_id=" . $npc_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_items_npc()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT items.* (Spieler) -----------------------------------
# 	-> spieler.id (int)
#	Array mit Items [Position]
#	<- [0] id
#	<- [1] titel
#	<- [2] beschreibung
#	<- [3] typ
#	<- [4] anzahl
#	<- [5] bilder_id

function get_all_items_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				items.id,
				items.titel,
				items.beschreibung,
				items.typ,
				items_spieler.anzahl,
				items.bilder_id
			FROM
				items
				JOIN items_spieler ON items.id = items_spieler.items_id
			WHERE
				items_spieler.spieler_id = ?
			ORDER BY items.titel"))
	{
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		if ($debug) echo "<br />\nItems für: [spieler_id=" . $spieler_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_all_items_spieler()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT item (Spieler) -----------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	<- Anzahl des Items

function get_items_spieler($spieler_id, $items_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				anzahl
			FROM
				items_spieler
			WHERE
				spieler_id = ?
				AND items_id = ? "))
	{
		$stmt->bind_param('dd', $spieler_id, $items_id);
		$stmt->execute();
		if ($debug) echo "<br />\nAnzahl von Item " . $items_id . " für Spieler " . $spieler_id . " geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($row)
		{
			return $row[0];
		} else {
			return 0;
		}
	} else {
		echo "<br />\nQuerryfehler in get_items_spieler()<br />\n";
		return false;
	}
}


#------------------------------------------- INSERT items_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> anzahl (int)
#	<- true/false

function insert_items_spieler($spieler_id, $items_id, $anzahl)
{
	
	# ToDo:
	#	* Bei negativer Anzahl prüfen, ob ausreichend Items vorhanden
	#	* Wenn Item-Anzahl = negativer Anzahl -> Datensatz löschen
	
	global $debug;
	global $connect_db_dvg;
	
	if(get_items_spieler($spieler_id, $items_id) == 0)
	{
		if ($stmt = $connect_db_dvg->prepare("
				INSERT INTO items_spieler(
					items_id,
					spieler_id, 
					anzahl) 
				VALUES (?, ?, ?)")){
			$stmt->bind_param('ddd', $items_id, $spieler_id, $anzahl);
			$stmt->execute();
			if ($debug) echo "<br />\nItem: [" . $itmes_id . " wurde Spieler " . $spieler_id . "]<br />\n";
			$result = $stmt->get_result();
			return $result;
		} else {
			echo "<br />\nQuerryfehler in insert_items_spieler()<br />\n";
			echo "<br />\n" . $spieler_id . " | " . $items_id . " | " . $anzahl . "<br />\n";
			return false;
		}
	} else {
		update_items_spieler($spieler_id, $items_id, $anzahl);
	}
}


#------------------------------------------- UPDATE items_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> anzahl (int)
#	<- true/false

function update_items_spieler($spieler_id, $items_id, $anzahl)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE items_spieler
			SET anzahl = anzahl + ?
			WHERE
				items_id = ?
				AND spieler_id = ?")){
		$stmt->bind_param('ddd', $anzahl, $items_id, $spieler_id);
		$stmt->execute();
		if ($debug) echo "<br />\nItem: [" . $itmes_id . " wurde Spieler " . $spieler_id . "]<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in update_items_spieler()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#***************************************************** NPC *****************************************************
#***************************************************************************************************************


#----------------------------------- SELECT npc.* (im Gebiet) -----------------------------------
# 	-> npc_gebiet.gebiet_id (int)
#	-> npc.typ (str)
#	Array mit npc-Daten [Position]
#	<- [0] id
#	<- [1] titel
#	<- [2] beschreibung
#	<- [3] wahrscheinlichkeit
#	<- [4] bilder_id

function get_npcs_gebiet($gebiet_id, $npc_typ)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	npc.id,
				npc.titel,
				npc.beschreibung,
				npc_gebiet.wahrscheinlichkeit,
				npc.bilder_id
			FROM 	npc
				JOIN npc_gebiet ON npc.id = npc_gebiet.npc_id
			WHERE 	npc_gebiet.gebiet_id = ?
				AND npc.typ = ?
			ORDER BY npc.titel"))
	{
		$stmt->bind_param('ds', $gebiet_id, $npc_typ);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Daten für: [gebiet_id=" . $gebiet_id . "] und [npc_typ=" . $npc_typ . "]geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npcs_gebiet()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT npc.* (einzel) -----------------------------------
# 	-> npc.id (int)
#	Array mit npc-Daten [Position]
#	<- [0] id
#	<- [1] titel
#	<- [2] beschreibung

function get_npc($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	npc.id,
				npc.titel,
				npc.beschreibung
			FROM 	npc
			WHERE 	npc.id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Daten für: [npc_id=" . $npc_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npc()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** SPIELER ***************************************************
#***************************************************************************************************************

#----------------------------------- SELECT spieler.* (nur für Loginbereich) -----------------------------------
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

function get_spieler_login($login)
{
	global $debug;
	global $connect_db_dvg;
	
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
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spieler_login()<br />\n";
		return false;
	}
}


#----------------------------------------------- SELECT spieler.* ----------------------------------------------
# 	-> spieler.id (str)
#	Array mit Spielerdaten [Position]
#	<- [0] id,
#	<- [1] account_id, 
#	<- [2] bilder_id, 
#	<- [3] gattung_id, 
#	<- [4] level_id, 
#	<- [5] gebiet_id, 
#	<- [6] name, 
#	<- [7] geschlecht, 
#	<- [8] staerke, 
#	<- [9] intelligenz, 
#	<- [10] magie, 
#	<- [11] element_feuer, 
#	<- [12] element_wasser, 
#	<- [13] element_erde, 
#	<- [14] element_luft, 
#	<- [15] gesundheit, 
#	<- [16] max_gesundheit, 
#	<- [17] energie, 
#	<- [18] max_energie, 
#	<- [19] balance,
#	<- [20] zuletzt_gespielt

function get_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	spieler.*
			FROM 	spieler
			WHERE 	spieler.id = ?"))
	{
		$stmt->bind_param('s', $spieler_id);
		$stmt->execute();
		if ($debug) echo "<br />\nSpielerdaten für: [spieler_id=" . $spieler_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_spieler()<br />\n";
		return false;
	}
}


#----------------------------------------------- INSERT spieler.* ----------------------------------------------
# 	-> account.login (str)
#	-> gebiet.titel (str) - gewähltes Startgebiet
#	-> gattung.titel (str) - gewählte Gattung
#	-> name (str) - gewählter Spielername
#	-> geschlecht (str) - gewähltes Geschlecht
#	<- true/false

function insert_spieler($login, $gebiet, $gattung, $name, $geschlecht)
{
	global $debug;
	global $connect_db_dvg;
	
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
			VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)")){
		if (! $account_id = get_account_id($login))
		{
			echo "<br />\nLogin nicht gefunden<br />\n";
			return false;
		}
		if (! $gebiet_id = get_gebiet_id($gebiet))
		{
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
			echo "<br />\nGattung nicht gefunden<br />\n";
			return false;
		}
		$max_gesundheit = berechne_max_gesundheit($start_staerke, $start_intelligenz, $start_magie);
		$max_energie = berechne_max_energie($start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft);
		
		$bilder_id = get_bild_zu_gattung_level($gattung_id, 1);
		
		$stmt->bind_param('ddddssddddddddddd', $account_id, $bilder_id, $gattung_id, $gebiet_id, $name, $geschlecht, $start_staerke, $start_intelligenz, $start_magie, $start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft, $max_gesundheit, $max_gesundheit, $max_energie, $max_energie);
		$stmt->execute();
		if ($debug) echo "<br />\nNeuer Spieler gespeichert: [" . $account_id . " | " . $name . " | " . $geschlecht . " | " . $gattung . " | " . $gebiet . "]<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in insert_spieler()<br />\n";
		return false;
	}
}

#----------------------------------------------- DELETE spieler.* ----------------------------------------------
# 	-> spieler.id (str)
#	<- true/false

function delete_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if (!$spieler_zum_loeschen = get_spieler($spieler_id))
	{
		echo "<br />\nLogin nicht gefunden<br />\n";
		return false;
	}
		# ToDo: Lösche Querverweise von Spieler (Items, Quests, ...)
	if ($stmt = $connect_db_dvg->prepare("
			DELETE
			FROM 	spieler
			WHERE 	spieler.id = ?;
			"))
	{
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		echo "<br />\nSpieler: " . $spieler_zum_loeschen[6] . " wurde gelöscht.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in delete_spieler()<br />\n";
		return false;
	}
}

#----------------------------------- UPDATE spieler.gebiet_id (Gebietswechsel) -----------------------------------
# 	-> spieler.id
#	-> gebiet.id (Zielgebiet)
#	<- true/false

function gebietswechsel($spieler_id, $gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE spieler
			SET gebiet_id = ?
			WHERE id = ?"))
	{
		$stmt->bind_param('ss', $gebiet_id, $spieler_id);
		$stmt->execute();
		if ($debug) echo "<br />\nSpieler [" . $spieler_id . "] ist nun im Gebiet [" . $gebiet_id . "].<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in gebietswechsel()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** ZAUBER ****************************************************
#***************************************************************************************************************


#----------------------------------------- SELECT zauberart.* (Hauptelement) -----------------------------------------
# 	-> element.titel (str)
#	<- zauberart.id (int)
#	<- zauberart.titel (str)

function get_zauberarten_zu_hauptelement($hauptelement_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauberart.id,
				zauberart.titel
			FROM
				zauber
				join zauberart on zauberart.id = zauber.zauberart_id
				join element on element.id = zauber.hauptelement_id
			WHERE
				element.titel = ?")){
		$stmt->bind_param('s', $hauptelement_titel);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_zauberarten_zu_hauptelement()<br />\n";
		return false;
	}	
}


#----------------------------------------- SELECT element.* (Hauptelement, Zauberart) -----------------------------------------
# 	-> element.titel (str)
#	-> zauberart.titel (str)
#	<- element.titel (str)

function get_nebenelement_zu_hauptelement_zauberart($hauptelement_titel, $zauberart)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				element2.titel
			FROM
				zauber
				join zauberart on zauberart.id = zauber.zauberart_id
				join element element1 on element1.id = zauber.hauptelement_id
				join element element2 on element2.id = zauber.nebenelement_id
			WHERE
				element1.titel = ?
				and zauberart.titel = ?
			ORDER BY
				element2.titel")){
		$stmt->bind_param('ss', $hauptelement_titel, $zauberart);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_nebenelement_zu_hauptelement_zauberart()<br />\n";
		return false;
	}	
}


#----------------------------------------- SELECT zauber.* (Hauptelement, Nebenelment, Zauberart) -----------------------------------------
# 	-> element1.titel (str)
# 	-> element2.titel (str)
#	-> zauberart.titel (str)
#	<- zauber.id (int)
#	<- zauber.bilder_id (int)
#	<- zauber.titel (str)
#	<- zauber.feuer/wasser/erde/luft (int) Hauptelement
#	<- zauber.feuer/wasser/erde/luft (int) Nebenelement
#	<- zauber.beschreibung

function get_zauber_zu_hauptelement_nebenelement_zauberart($hauptelement_titel, $nebenelement_titel, $zauberart)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber.id,
				zauber.bilder_id,
				zauber.titel,
				CASE element1.id
					WHEN 2 THEN zauber.feuer
					WHEN 3 THEN zauber.wasser
					WHEN 4 THEN zauber.erde
					WHEN 5 THEN zauber.luft
				END AS hauptelement_wert,
				CASE element2.id
					WHEN 2 THEN zauber.feuer
					WHEN 3 THEN zauber.wasser
					WHEN 4 THEN zauber.erde
					WHEN 5 THEN zauber.luft
				END AS nebenelement_wert,
				zauber.beschreibung
			FROM
				zauber
				join zauberart on zauberart.id = zauber.zauberart_id
				join element element1 on element1.id = zauber.hauptelement_id
				join element element2 on element2.id = zauber.nebenelement_id
			WHERE
				element1.titel = ?
				and element2.titel = ?
				and zauberart.titel = ?
			ORDER BY
				hauptelement_wert,
				nebenelement_wert")){
		$stmt->bind_param('sss', $hauptelement_titel, $nebenelement_titel, $zauberart);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_nebenelement_zu_hauptelement_zauberart()<br />\n";
		return false;
	}	
}



#----------------------------------------- SELECT zauber.* (Spieler) -----------------------------------------
# 	-> spieler.id (int)
# 	<- zauber.id (int)
#	<- zauber.bilder_id (int)
#	<- zauber.titel (str)
#	<- zauber.beschreibung

function get_zauber_von_spieler($spieler)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber.id,
				zauber.bilder_id,
				zauber.titel,
				zauber.beschreibung
			FROM
				zauber
				join zauber_spieler on zauber_spieler.zauber_id = zauber.id
			WHERE
				zauber_spieler.spieler_id = ?
			ORDER BY
				zauber.id")){
		$stmt->bind_param('d', $spieler);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_zauber_von_spieler()<br />\n";
		return false;
	}	
}

?>