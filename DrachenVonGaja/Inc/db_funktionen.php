<?php

include("funktionen.php");

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
#	-> aktion.titel (str)
#	<- true/false

function update_aktion_spieler($spieler_id, $aktion_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($aktion_titel == "kampf") {
		if ($stmt = $connect_db_dvg->prepare("
				UPDATE aktion_spieler
				SET ende = NOW()
				WHERE spieler_id = ?
					AND status <> 'abgeschlossen'
					AND aktion_id = (
						SELECT aktion.id
						FROM aktion
						WHERE aktion.titel = ?)")){
			$stmt->bind_param('ds', $spieler_id, $aktion_titel);
			$stmt->execute();
			if ($debug) echo "<br />\nEndzeit für Aktion: [" . $aktion_titel . "] von Spieler [" . $spieler_id . "] wurde gesetzt.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in update_aktion_spieler() - Endzeit setzen bei Kampf<br />\n";
		}
	}
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE aktion_spieler
			SET status = 'abgeschlossen'
			WHERE spieler_id = ?
				AND status <> 'abgeschlossen'
				AND aktion_id = (
					SELECT aktion.id
					FROM aktion
					WHERE aktion.titel = ?)")){
		$stmt->bind_param('ds', $spieler_id, $aktion_titel);
		$stmt->execute();
		if ($debug) echo "<br />\nAktion: [" . $aktion_titel . "] von Spieler [" . $spieler_id . "] wurde abgeschlossen<br />\n";
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
		if ($debug and $row) echo "<br />\nGattungsname abgeholt für: [" . $gattung_id . "]<br />\n";
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
			if ($debug) echo "<br />\nItem: [" . $items_id . " wurde Spieler " . $spieler_id . "]<br />\n";
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
		if ($debug) echo "<br />\nItem " . $items_id . " wurde Spieler " . $spieler_id . " genau " . $anzahl . " mal hinzugefügt.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in update_items_spieler()<br />\n";
		return false;
	}
}



#**************************************************************************************************************
#*************************************************** KAMPF ****************************************************
#**************************************************************************************************************

#--------------------------------------------- INSERT kampf.* ---------------------------------------------
# 	-> OPTIONAL gebiet.id (int)
#	<- kampf.id (int)

function insert_kampf($gebiet_id=null)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO kampf(
				gebiet_id) 
			VALUES (?)")){
		$stmt->bind_param('d', $gebiet_id);
		$stmt->execute();
		$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM kampf");
		$stmt->execute();
		$kampf_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
		if ($debug) echo "<br />\nKampf [" . $kampf_id . "] im Gebiet [" . $gebiet_id . "] wurde hinzugefügt.<br />\n";
		return $kampf_id;
	} else {
		echo "<br />\nQuerryfehler in insert_kampf()<br />\n";
		return false;
	}
}


#---------------------------------------- INSERT kampf_aktion.* UND kampf_effekt.*----------------------------------------
# 	-> kampf.id (int)
# 	-> kampf_teilnehmer (obj)
# 	-> kampf_teilnehmer Ziel (obj)
# 	-> zauber (obj)
#	<- array[0-3, Fehler(true/false) ,"Erläuterung"]
#		0 = Falsches Ziel
#		1 = Nicht genügend Zauberpunkte
#		2 = Angriff nicht erfolgreich
#		3 = Angriff erfolgreich

function insert_kampf_aktion($kampf_id, $kt, $kt_ziel, $zauber)
{
	global $debug;
	global $connect_db_dvg;
	
	$return_wert = false;
	$keine_abwehr = false;
	
	$alle_zauber_effekte = get_zauber_effekte($zauber->id);
	foreach ($alle_zauber_effekte as $zauber_effekt){
		# Ist der Angriff/Zauber sinnvoll?
		if (($kt->seite != $kt_ziel->seite AND $zauber_effekt->art == "verteidigung") OR ($kt->seite == $kt_ziel->seite AND $zauber_effekt->art == "angriff")){
			$return_wert = [0, true, "Angriffs-/Zauberziel ist nicht plausibel."];
		}
		# Muss ein Zauber abgewehrt werden? (Ziel = Verbündeter)
		if ($zauber_effekt->art == "verteidigung"){
			$keine_abwehr = true;
		}
	}
	
	if (!$return_wert){
		# Bestimmen der Aktionsparameter
		$angriff_erfolg = berechne_angriff_erfolg($kt);
		if ($keine_abwehr){
			$ausweichen_erfolg = 0;
			$abwehr_erfolg = 0;
		} else {
			$ausweichen_erfolg = berechne_ausweichen_erfolg($kt_ziel);
			$abwehr_erfolg = berechne_abwehr_erfolg($kt_ziel);
		}
		$zauberpunkte_verbrauch = berechne_zauberpunkte_verbrauch($zauber);
		$timer_verbrauch = berechne_timer_verbrauch($kt);
		# Sind ausreichend Zauberpunkte vorhanden?
		if (($kt->zauberpunkte - $zauberpunkte_verbrauch) < 0){
			$return_wert = [1, true, "Nicht genügend Zauberpunkte"];
		}
	}
		
	if (!$return_wert){
		# War der Angriff erfolgreich?
		if (!($angriff_erfolg == 1 AND $ausweichen_erfolg == 0 AND $abwehr_erfolg == 0)){
			$return_wert = [2, false, "Angriff/Zauber war nicht erfolgreich. (Angriff/Zauber=".$angriff_erfolg.", Ausweichen=".$ausweichen_erfolg.", Abwehr=".$abwehr_erfolg.")"];
		}
		# Eintragen der Aktion in die Datenbank
		if ($stmt = $connect_db_dvg->prepare("
				INSERT INTO kampf_aktion(
					kampf_id,
					timer,
					kampf_teilnehmer_id,
					ziel_kampf_teilnehmer_id,
					zauber_id,
					angriff_erfolg,
					ausweichen_erfolg,
					abwehr_erfolg,
					zauberpunkte_verbrauch,
					timer_verbrauch,
					zeitpunkt)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())")){
			$stmt->bind_param('dddddddddd',
					$kampf_id,
					$kt->timer,
					$kt->kt_id,
					$kt_ziel->kt_id,
					$zauber->id,
					$angriff_erfolg,
					$ausweichen_erfolg,
					$abwehr_erfolg,
					$zauberpunkte_verbrauch,
					$timer_verbrauch);
			$stmt->execute();
			$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM kampf_aktion");
			$stmt->execute();
			$kampf_aktion_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			if ($debug) echo "<br />\nEine Aktion [" . $kampf_aktion_id . "] im Kampf [" . $kampf_id . "] wurde hinzugefügt.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in insert_kampf_aktion() - kampf_aktion<br />\n";
		}
		# Timer + Zauberpunkte beim ausführenden Kampfteilnehmer aktualisieren
		$kt->zauberpunkte = $kt->zauberpunkte - $zauberpunkte_verbrauch;
		$kt->timer = $kt->timer + $timer_verbrauch;
	}
	
	if (!$return_wert){	
		# Eintragen der Effekte in die Datenbank
		$alle_zauber_effekte = get_zauber_effekte($zauber->id);
		foreach ($alle_zauber_effekte as $zauber_effekt){
			if ($stmt = $connect_db_dvg->prepare("
					INSERT INTO kampf_effekt(
						kampf_aktion_id,
						kampf_teilnehmer_id,
						art,
						attribut,
						wert,
						runden,
						runden_max,
						jede_runde,
						ausgefuehrt,
						beendet)
					VALUES (?, ?, ?, ?, ?, 0, ?, ?, 0, 0)")){
				$stmt->bind_param('ddssddd',
						$kampf_aktion_id,
						$kt_ziel->kt_id,
						$zauber_effekt->art,
						$zauber_effekt->attribut,
						$zauber_effekt->wert,
						$zauber_effekt->runden,
						$zauber_effekt->jede_runde);
				$stmt->execute();
				$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM kampf_effekt");
				$stmt->execute();
				$kampf_effekt_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
				if ($debug) echo "<br />\nEin KampfEffekt [" . $kampf_effekt_id . "] im Kampf [" . $kampf_id . "] wurde hinzugefügt.<br />\n";
			} else {
				echo "<br />\nQuerryfehler in insert_kampf_aktion() - kampf_effekt<br />\n";
			}
		}
		$return_wert = [3, false, "Angriff/Zauber war erfolgreich."];
	}
	return $return_wert;
}


#---------------------------------------- INSERT kampf_teilnehmer.* ----------------------------------------
# 	-> kampf.id (int)
# 	-> teilnehmer_id (int)
# 	-> teilnehmer_typ (str)
# 	-> seite (int)
#	<- kampf_teilnehmer.id (int)

function insert_kampf_teilnehmer($kampf_id, $teilnehmer_id, $teilnehmer_typ, $seite)
{
	global $debug;
	global $connect_db_dvg;
	
	switch($teilnehmer_typ){
		#################################################################
		case "spieler":
			$spieler = new Spieler(get_spieler($teilnehmer_id));
			$kampf_teilnehmer = new KampfTeilnehmer($spieler, $teilnehmer_typ, $seite);
			break;
		#################################################################
		case "npc":
			$npc = new NPC(get_npc_alldata($teilnehmer_id));
			$kampf_teilnehmer = new KampfTeilnehmer($npc, $teilnehmer_typ, $seite);
			break;
		#################################################################
		default:
			echo "Kein Spielertyp übergeben?";
			break;
	}
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO kampf_teilnehmer(
				kampf_id,
				teilnehmer_id,
				teilnehmer_typ,
				seite,
				gesundheit,
				zauberpunkte,
				staerke,
				intelligenz,
				magie,
				element_feuer,
				element_wasser,
				element_erde,
				element_luft,
				initiative,
				abwehr,
				ausweichen,
				timer)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param('ddsdddddddddddddd',
				$kampf_id,
				$kampf_teilnehmer->id,
				$kampf_teilnehmer->typ,
				$kampf_teilnehmer->seite,
				$kampf_teilnehmer->gesundheit,
				$kampf_teilnehmer->zauberpunkte,
				$kampf_teilnehmer->staerke,
				$kampf_teilnehmer->intelligenz,
				$kampf_teilnehmer->magie,
				$kampf_teilnehmer->element_feuer,
				$kampf_teilnehmer->element_wasser,
				$kampf_teilnehmer->element_erde,
				$kampf_teilnehmer->element_luft,
				$kampf_teilnehmer->initiative,
				$kampf_teilnehmer->abwehr,
				$kampf_teilnehmer->ausweichen,
				$kampf_teilnehmer->timer);
		$stmt->execute();
		$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM kampf_teilnehmer");
		$stmt->execute();
		$kampf_teilnehmer_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
		if ($debug) echo "<br />\nKampfteilnehmer " . $kampf_teilnehmer->name . " (id=" . $kampf_teilnehmer_id . ") wurde hinzugefügt.<br />\n";
		return $kampf_teilnehmer_id;
	} else {
		echo "<br />\nQuerryfehler in insert_kampf_teilnehmer()<br />\n";
		return false;
	}
}


#------------------------------------- SELECT kampf_teilnehmer.* (alle) -------------------------------------
# 	-> kampf.id (int)
#	<- alle_kampf_teilnehmer (array [kampf_teilnehmer])

function get_all_kampf_teilnehmer($kampf_id, $seite)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				case when npc.id IS NULL then spieler.name ELSE npc.titel END AS name,
				case when npc.id IS NULL then spieler.bilder_id ELSE npc.bilder_id END AS bilder_id,
				case when npc.id IS NULL then spieler.id ELSE npc.id END AS id,
				kampf_teilnehmer.teilnehmer_typ AS typ,
				kampf_teilnehmer.seite AS seite,
				kampf_teilnehmer.gesundheit AS gesundheit,
				case when npc.id IS NULL then spieler.max_gesundheit ELSE npc.gesundheit END AS gesundheit_max,
				kampf_teilnehmer.zauberpunkte AS zauberpunkte,
				case when npc.id IS NULL then spieler.max_zauberpunkte ELSE npc.zauberpunkte END AS zauberpunkte_max,
				kampf_teilnehmer.staerke AS staerke,
				kampf_teilnehmer.intelligenz AS intelligenz,
				kampf_teilnehmer.magie AS magie,
				kampf_teilnehmer.element_feuer AS element_feuer,
				kampf_teilnehmer.element_wasser AS element_wasser,
				kampf_teilnehmer.element_erde AS element_erde,
				kampf_teilnehmer.element_luft AS element_luft,
				kampf_teilnehmer.initiative AS initiative,
				kampf_teilnehmer.abwehr AS abwehr,
				kampf_teilnehmer.ausweichen AS ausweichen,
				kampf_teilnehmer.timer AS timer,
				kampf_teilnehmer.id AS kt_id
			FROM kampf_teilnehmer
				LEFT JOIN npc ON npc.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'npc'
				LEFT JOIN spieler ON spieler.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'spieler'
			WHERE kampf_teilnehmer.kampf_id = ?
				AND kampf_teilnehmer.seite = ?
			ORDER BY typ DESC, kampf_teilnehmer.id")){
		$stmt->bind_param('dd', $kampf_id, $seite);
		$stmt->execute();
		$counter = 0;
		if ($kt_all = $stmt->get_result()){
			while($kt = $kt_all->fetch_array(MYSQLI_NUM)){
				$alle_kampf_teilnehmer[$counter] = new KampfTeilnehmer($kt);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle " . $counter . " Kampfteilnehmer zum Kampf [" . $kampf_id . "] auf Seite [" . $seite . "] wurden geladen.<br />\n";
		return $alle_kampf_teilnehmer;
	} else {
		echo "<br />\nQuerryfehler in get_all_kampf_teilnehmer()<br />\n";
		return false;
	}
}


#------------------------------------- SELECT npc.id (alle zum Kampf) -------------------------------------
# 	-> kampf.id (int)
#	<- alle_npcs (array [npc.id])

function get_all_npcs_kampf($kampf_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT kampf_teilnehmer.teilnehmer_id
			FROM kampf_teilnehmer
			WHERE kampf_id = ?
				AND kampf_teilnehmer.teilnehmer_typ = 'npc'")){
		$stmt->bind_param('d', $kampf_id);
		$stmt->execute();
		$counter = 0;
		if ($all_npcs = $stmt->get_result()){
			while($npc = $all_npcs->fetch_array(MYSQLI_NUM)){
				$alle_npcs[$counter] = $npc[0];
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle " . $counter . " NPC-IDs zum Kampf [" . $kampf_id . "] wurden geladen.<br />\n";
		return $alle_npcs;
	} else {
		echo "<br />\nQuerryfehler in get_all_npcs_kampf()<br />\n";
		return false;
	}
}


#--------------------- SELECT/UPDATE kampf_effekt.* (alle aktiven zum Kampfteilnehmer) ---------------------
# 	-> kampf_teilnehmer.id (int)
#	<- alle_kampf_effekte (array [kampf_effekt])
function kampf_effekte_ausführen($kt, $param, $kt_an_der_reihe=true)
{
	global $debug;
	global $connect_db_dvg;
	
	# Aktuell relevante KampfEffekte für den Kampfteilnehmer aus DB auslesen
	if ($stmt = $connect_db_dvg->prepare("
			SELECT kampf_effekt.id,
				zauber.titel AS zauber_name,
				kampf_effekt.art,
				kampf_effekt.attribut,
				kampf_effekt.wert,
				kampf_effekt.runden,
				kampf_effekt.runden_max,
				kampf_effekt.jede_runde,
				kampf_effekt.ausgefuehrt,
				kampf_effekt.beendet
			FROM kampf_effekt
				JOIN kampf_aktion ON kampf_aktion.id = kampf_effekt.kampf_aktion_id
				JOIN zauber ON zauber.id = kampf_aktion.zauber_id
			WHERE kampf_effekt.kampf_teilnehmer_id = ?
				AND kampf_effekt.art = ?
				AND kampf_effekt.beendet = 0")){
		$stmt->bind_param('ds', $kt->kt_id, $param);
		$stmt->execute();
		$counter = 0;
		if ($kampf_effekte_all = $stmt->get_result()){
			while($kampf_effekt = $kampf_effekte_all->fetch_array(MYSQLI_NUM)){
				$alle_kampf_effekte[$counter] = new KampfEffekt($kampf_effekt);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle " . $counter . " aktiven Kampfeffekte (" . $param . ") zum Kampfteilnehmer '" . $kt->name . "' wurden geladen.<br />\n";
		$return_wert = $counter;
	} else {
		echo "<br />\nQuerryfehler in kampf_effekte_ausführen() - Select KampfEffekte<br />\n";
		$return_wert = false;
	}
	
	# Kampfeffekte verarbeiten
	if ($counter > 0){
		foreach ($alle_kampf_effekte as $kampf_effekt){
			$runden_diff = $kampf_effekt->runden_max - $kampf_effekt->runden; # Wieviele Runden ist der Effekt noch aktiv?
			$effekt_anwenden = ($kampf_effekt->jede_runde == 1 OR $kampf_effekt->runden == 0); # Muss der Effekt angewendet werden? (nur 1. Runde oder jede Runde)
			$effekt_beenden = ($runden_diff == 0); # Muss der Effekt beendet werden? (Max Runden erreicht bzw. temporären Bonus wieder zurücksetzen)
			
			# Verarbeitungslogik für Angriffe sowie Verteidigung/Heilung
			$plus_runden = 0;
			$set_beendet = 0;
			
			if ($kampf_effekt->ausgefuehrt == 0 OR $runden_diff == 0){
				switch($kampf_effekt->attribut){
					# Gesundheit
					case "gesundheit":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0, $kt->gesundheit_max);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0, $kt->gesundheit_max);
						break;
					# Zauberpunkte
					case "zauberpunkte":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0, $kt->zauberpunkte_max);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0, $kt->zauberpunkte_max);
						break;
					# Hauptattribute
					case "staerke":
					case "intelligenz":
					case "magie":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
						break;
					# Elemente
					case "element_feuer":
					case "element_wasser":
					case "element_erde":
					case "element_luft":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert);
						break;
					# Timer
					case "timer":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
						break;
					# Initiative
					case "initiative":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
						break;
					# Ausweichen
					case "ausweichen":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
						break;
					# Abwehr
					case "abwehr":
						if ($effekt_anwenden)
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
						if ($effekt_beenden)
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
						break;
					# Kein Attribut definiert?
					default:
						break;
				}
				
				### Updateparameter bestimmen ###
				# Rundenzähler weiter setzen
				if ($runden_diff > 0) $plus_runden = 1;
					else $plus_runden = 0;
				# Kampfeffekt auf beendet setzen
				if (($kampf_effekt->jede_runde == 1 AND $runden_diff == 1) OR ($kampf_effekt->jede_runde == 0 AND $runden_diff == 0)) $set_beendet = 1;
					else $set_beendet = 0;
			}
				
			# Wird der Effekt vorzeitig (KT nicht an der Reihe) ausgeführt, dann muss der Parameter gesetzt werden
			if ($kt_an_der_reihe) $set_ausgefuehrt = 0;
				else $set_ausgefuehrt = 1;
			
			# Rundenzähler, Beendet und Ausgeführt für aktuellen KampfEffekt in DB setzen
			if ($stmt = $connect_db_dvg->prepare("
					UPDATE kampf_effekt
					SET runden = runden + ?,
						ausgefuehrt = ?,
						beendet = ?
					WHERE kampf_effekt.id = ?")){
				$stmt->bind_param('dddd', $plus_runden, $set_ausgefuehrt, $set_beendet, $kampf_effekt->id);
				$stmt->execute();
				if ($debug) echo "<br />\nKampfeffekt [" . $kampf_effekt->id . "] zum Kampfteilnehmer '" . $kt->name . "' wurde aktualisiert.<br />\n";
			} else {
				echo "<br />\nQuerryfehler in kampf_effekte_ausführen() - Update KampfEffekte Rundenzähler/beendet<br />\n";
				$return_wert = false;
			}
		}
	}
	return $return_wert;
}


#------------------------------------- UPDATE kampf_teilnehmer.* (alle Kampfteilnehmer) -------------------------------------
# 	-> alle_kampf_teilnehmer (array [kampf_teilnehmer])
#	<- alle_kampf_effekte (array [kampf_effekt])
function update_kampf_teilnehmer($kt_all)
{
	global $debug;
	global $connect_db_dvg;
	
	$counter = 0;
	foreach ($kt_all as $kt){
		if ($stmt = $connect_db_dvg->prepare("
				UPDATE kampf_teilnehmer
				SET seite = ?,
					gesundheit = ?,
					zauberpunkte = ?,
					staerke = ?,
					intelligenz = ?,
					magie = ?,
					element_feuer = ?,
					element_wasser = ?,
					element_erde = ?,
					element_luft = ?,
					initiative = ?,
					abwehr = ?,
					ausweichen = ?,
					timer = ?
				WHERE id = ?")){
			$stmt->bind_param('ddddddddddddddd',
					$kt->seite,
					$kt->gesundheit,
					$kt->zauberpunkte,
					$kt->staerke,
					$kt->intelligenz,
					$kt->magie,
					$kt->element_feuer,
					$kt->element_wasser,
					$kt->element_erde,
					$kt->element_luft,
					$kt->initiative,
					$kt->abwehr,
					$kt->ausweichen,
					$kt->timer,
					$kt->kt_id);
			$stmt->execute();
			$return_wert = true;
		} else {
			echo "<br />\nQuerryfehler in update_kampf_teilnehmer()<br />\n";
			$return_wert = false;
		}
	}
	if ($debug) echo "<br />\nAlle " . $counter . " Kampfteilnehmer wurden aktualisiert.<br />\n";
	return $return_wert;
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


#----------------------------------- SELECT npc.* (einzel, Kopfdaten) -----------------------------------
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


#----------------------------------- SELECT npc.* (einzel, alle Daten) -----------------------------------
# 	-> npc.id (int)
#	Array mit npc-Daten [Position]
#	<- [0] id
#	<- [1] bilder_id
#	<- [2] element_id
#	<- [3] titel
#	<- [4] familie
#	<- [5] staerke
#	<- [6] intelligenz
#	<- [7] magie
#	<- [8] element_feuer
#	<- [9] element_wasser
#	<- [10] element_erde
#	<- [11] element_luft
#	<- [12] gesundheit
#	<- [13] energie
#	<- [14] zauberpunkte
#	<- [15] initiative
#	<- [16] abwehr
#	<- [17] ausweichen
#	<- [18] beschreibung
#	<- [19] typ

function get_npc_alldata($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	npc.*
			FROM 	npc
			WHERE 	npc.id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Daten für: [npc_id=" . $npc_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row;
	} else {
		echo "<br />\nQuerryfehler in get_npc_alldata()<br />\n";
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
#	<- [19] zauberpunkte,
#	<- [20] max_zauberpunkte,
#	<- [21] balance,
#	<- [22] initiative,
#	<- [23] abwehr,
#	<- [24] ausweichen,
#	<- [25] zuletzt_gespielt

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
				zauberpunkte,
				max_zauberpunkte,
				balance,
				initiative,
				abwehr,
				ausweichen) 
			VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 100, 10, 10)")){
		$spieler = new Spieler();
		if ($account_id = get_account_id($login)){
			$spieler->account_id = $account_id;
		} else {
			echo "<br />\nLogin nicht gefunden<br />\n";
			return false;
		}
		if ($gebiet_id = get_gebiet_id($gebiet)){
			$spieler->gebiet_id = $gebiet_id;
		} else {
			echo "<br />\nGebiet nicht gefunden<br />\n";
			return false;
		}
		if ($gattung_data = get_start_gattung($gattung)){
			$spieler->gattung_id = $gattung_data[0];
			$spieler->staerke = $gattung_data[1];
			$spieler->intelligenz = $gattung_data[2];
			$spieler->magie = $gattung_data[3];
			$spieler->element_feuer = $gattung_data[4];
			$spieler->element_wasser = $gattung_data[5];
			$spieler->element_erde = $gattung_data[6];
			$spieler->element_luft = $gattung_data[7];
		}
		else{
			echo "<br />\nGattung nicht gefunden<br />\n";
			return false;
		}
		$spieler->max_gesundheit = berechne_max_gesundheit($spieler);
		$spieler->max_energie = berechne_max_energie($spieler);
		$spieler->max_zauberpunkte = berechne_max_zauberpunkte($spieler);
		
		$spieler->bilder_id = get_bild_zu_gattung_level($spieler->gattung_id, 1);
		
		$stmt->bind_param('ddddssddddddddddddd', $spieler->account_id, $spieler->bilder_id, $spieler->gattung_id, $spieler->gebiet_id, $name, $geschlecht, $spieler->staerke, $spieler->intelligenz, $spieler->magie, $spieler->element_feuer, $spieler->element_wasser, $spieler->element_erde, $spieler->element_luft, $spieler->max_gesundheit, $spieler->max_gesundheit, $spieler->max_energie, $spieler->max_energie, $spieler->max_zauberpunkte, $spieler->max_zauberpunkte);
		$stmt->execute();
		if ($debug) echo "<br />\nNeuer Spieler gespeichert: [" . $spieler->account_id . " | " . $name . " | " . $geschlecht . " | " . $gattung . " | " . $gebiet . "]<br />\n";
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


#----------------------------------------- SELECT zauber.* (alle des Kampfteilnehmers) -----------------------------------------
# 	-> kampf_teilnehmer (obj)
# 	<- alle_zauber_zum_kampf_teilnehmer (array [zauber])

function get_zauber_von_objekt($obj)
{
	global $debug;
	global $connect_db_dvg;
		
	switch(get_class($obj)){
		case "KampfTeilnehmer":
			$typ = $obj->typ;
			$id = $obj->id;
			break;
		case "Spieler":
			$typ = "spieler";
			$id = $obj->id;
			break;
		default:
			$typ = "spieler";
			$id = 0;
			break;
	}
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber.id,
				zauber.titel,
				zauber.bilder_id,
				zauberart.id,
				zauberart.titel,
				zauber.hauptelement_id,
				zauber.nebenelement_id,
				zauber.verbrauch,
				zauber.beschreibung
			FROM zauber
				LEFT JOIN zauber_spieler ON zauber_spieler.zauber_id = zauber.id AND 'spieler' = ? AND zauber_spieler.spieler_id = ?
				LEFT JOIN zauber_npc ON zauber_npc.zauber_id = zauber.id AND 'npc' = ? AND zauber_npc.npc_id = ?
				JOIN zauberart ON zauberart.id = zauber.zauberart_id
			WHERE zauber_spieler.id IS NOT NULL OR zauber_npc.id IS NOT NULL
			ORDER BY zauber.hauptelement_id, zauber.nebenelement_id, zauber.id")){
		$stmt->bind_param('sdsd', $typ, $id, $typ, $id);
		$stmt->execute();
		$counter = 0;
		$alle_zauber_zum_kampf_teilnehmer=null;
		if ($zauber_all = $stmt->get_result()){
			while($zauber = $zauber_all->fetch_array(MYSQLI_NUM)){
				$alle_zauber_zum_kampf_teilnehmer[$counter] = new KampfZauber($zauber);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle ".$counter." Zauber von ".$obj->name." [".$id."|".$typ."] wurden geladen.<br />\n";
		return $alle_zauber_zum_kampf_teilnehmer;
	} else {
		echo "<br />\nQuerryfehler in get_zauber_von_kampfteilnehmer()<br />\n";
		return false;
	}
}


#----------------------------------------- SELECT zauber.* (einzel) -----------------------------------------
# 	-> zauber.id (int)
# 	<- zauber (obj)

function get_zauber($zauber_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber.id,
				zauber.titel,
				zauber.bilder_id,
				zauberart.id,
				zauberart.titel,
				zauber.hauptelement_id,
				zauber.nebenelement_id,
				zauber.verbrauch,
				zauber.beschreibung
			FROM zauber
				JOIN zauberart ON zauberart.id = zauber.zauberart_id
			WHERE zauber.id = ?")){
		$stmt->bind_param('d', $zauber_id);
		$stmt->execute();
		$zauber_row = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		$zauber = new KampfZauber($zauber_row);
		if ($debug) echo "<br />\nZauber '".$zauber->titel."' wurde geladen.<br />\n";
		return $zauber;
	} else {
		echo "<br />\nQuerryfehler in get_zauber()<br />\n";
		return false;
	}
}


#----------------------------------------- SELECT zauber_effekt.* (alle zum Zauber) -----------------------------------------
# 	-> zauber.id (int)
# 	<- alle_zauber_effekte (array [zauber_effekt])

function get_zauber_effekte($zauber_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber_effekt.id,
				zauber_effekt.zauber_id,
				zauber_effekt.art,
				zauber_effekt.attribut,
				zauber_effekt.wert,
				zauber_effekt.runden,
				zauber_effekt.jede_runde
			FROM zauber_effekt
			WHERE zauber_effekt.zauber_id = ?")){
		$stmt->bind_param('d', $zauber_id);
		$stmt->execute();
		$counter = 0;
		$alle_zauber_effekte = null;
		if ($zauber_effekt_all = $stmt->get_result()){
			while($zauber_effekt = $zauber_effekt_all->fetch_array(MYSQLI_NUM)){
				$alle_zauber_effekte[$counter] = new KampfZauberEffekt($zauber_effekt);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle ".$counter." Zaubereffekte zum Zauber wurden geladen.<br />\n";
		return $alle_zauber_effekte;
	} else {
		echo "<br />\nQuerryfehler in get_zauber_effekte()<br />\n";
		return false;
	}
}


#----------------------------------------------- INSERT/DELETE zauber_spieler.* ----------------------------------------------
# 	-> spieler.id (int)
#	-> zauber.id (int)
#	<- true/false

function switch_zauber_spieler($spieler_id, $zauber_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				spieler_id,
				zauber_id
			FROM zauber_spieler
			WHERE spieler_id = ?
				AND zauber_id = ?;")){
		$stmt->bind_param('dd', $spieler_id, $zauber_id);
		$stmt->execute();
		$zauber_row = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		if ($zauber_row) $zauber_aktiv = true;
			else $zauber_aktiv = false;
		if ($debug) echo "<br />\nZauber von Spieler geladen.<br />\n";
	} else {
		echo "<br />\nQuerryfehler in switch_zauber_spieler() - select<br />\n";
		return false;
	}
	if ($zauber_aktiv){	
		if ($stmt = $connect_db_dvg->prepare("
				DELETE
				FROM zauber_spieler
				WHERE spieler_id = ?
					AND zauber_id = ?;")){
			$stmt->bind_param('dd', $spieler_id, $zauber_id);
			$stmt->execute();
			if ($debug) echo "<br />\nZauber von Spieler gelöscht.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in switch_zauber_spieler() - delete<br />\n";
			return false;
		}
	} else {
		if ($stmt = $connect_db_dvg->prepare("
				INSERT INTO zauber_spieler (spieler_id, zauber_id)
				VALUES (?, ?);")){
			$stmt->bind_param('dd', $spieler_id, $zauber_id);
			$stmt->execute();
			if ($debug) echo "<br />\nZauber zu Spieler hinzugefügt.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in switch_zauber_spieler() - insert<br />\n";
			return false;
		}
	}
	return true;
}
?>