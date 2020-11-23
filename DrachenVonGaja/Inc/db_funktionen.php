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
#	<- [5] rolle_id
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
#	<- [5] rolle_id
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
				aktiv) 
			VALUES (?, ?, ?, true)")){
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

#--------------------------------------------- SELECT aktion.* ---------------------------------------------
# 	-> aktion.titel (str)
#	<- Aktion (obj)

function get_aktion($aktion_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	aktion
			WHERE 	titel = ?")){
		$stmt->bind_param('s', $aktion_titel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return new Aktion($row);
	} else {
		echo "<br />\nQuerryfehler in get_aktion()<br />\n";
		return false;
	}	
}


#------------------------------------------- INSERT aktion_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> aktion.titel (str)
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
#************************************************* BEDINGUNGEN *************************************************
#***************************************************************************************************************

#------------------------------------ SELECT bedingung.bedingung_knoten_id -------------------------------------
# 	-> bedingung_link.tabelle (str)
#	-> bedingung_link.tabelle_id (int)
#	<- bedingung_link.bedingung_knoten_id (int) ODER false

function get_bed_einstieg($tabelle, $tabelle_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT bedingung_link.bedingung_knoten_id
			FROM bedingung_link
			WHERE bedingung_link.tabelle = ?
				AND bedingung_link.tabelle_id = ?;")){
		$stmt->bind_param('sd', $tabelle, $tabelle_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		# Keine passende Bedingung gefunden
		if(!is_array($row)){
			if($debug) echo "<br />Keine aktive Bedingung zu ".$tabelle."(id=".$tabelle_id.") gefunden.";
			return false;
		}
		# 1. Knoten-Id sichern
		$bedingung_knoten_id = $row[0];
		$row = $result->fetch_array(MYSQLI_NUM);
		# Wenn kein weiterer Knoten verknüpft dann Rückgabe des gefundenen
		if(!is_array($row)){
			if($debug) echo "<br />Bedingung zu ".$tabelle."(id=".$tabelle_id.") gefunden. -> Knoten-Id=".$bedingung_knoten_id;
			return $bedingung_knoten_id;
		# Wenn mehr als 1 Knoten zugeordnet, dann Fehler
		} else {
			echo "<br />\nDem Objekt ".$tabelle."(id=".$tabelle_id.") ist mehr als ein Einstiegsknoten zugeordnet.";
			return false;
		}
	} else {
		echo "<br />\nQuerryfehler in get_bed_einstieg<br />\n";
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
#*************************************************** GEWINN ****************************************************
#***************************************************************************************************************

#----------------------------------------- SELECT gewinn.* (Gewinn allgemein) -----------------------------------------
# 	-> gewinn.id (int)
# 	<- Gewinn (obj)

function get_gewinn($gewinn_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT gewinn.*
			FROM gewinn
			WHERE id = ?")){
		$stmt->bind_param('d', $gewinn_id);
		$stmt->execute();
		$gewinn = new Gewinn($stmt->get_result()->fetch_array(MYSQLI_NUM));
		if ($debug) echo "<br />\nGewinn mit ID=".$gewinn_id." wurde geladen.<br />\n";
		return $gewinn;
	} else {
		echo "<br />\nQuerryfehler in get_gewinn()<br />\n";
		return false;
	}
}



#***************************************************************************************************************
#**************************************************** ITEMS ****************************************************
#***************************************************************************************************************

#-------------------------------- SELECT items.* (NPC) --------------------------------
# 	-> npc.id (int)
#	<- alle_items_npc (array [Item])

function get_items_npc($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				items.id,
				items.bilder_id,
				items.titel,
				items.beschreibung,
				items.essbar,
				items.ausruestbar,
				items.verarbeitbar,
				items.gesundheit,
				items.energie,
				items.zauberpunkte,
				items.staerke,
				items.intelligenz,
				items.magie,
				items.element_feuer,
				items.element_wasser,
				items.element_erde,
				items.element_luft,
				items.initiative,
				items.abwehr,
				items.ausweichen,
				items.prozent,
				npc_items.wahrscheinlichkeit,
				npc_items.anzahl_min,
				npc_items.anzahl_max
			FROM items
				JOIN npc_items ON items.id = npc_items.items_id
			WHERE npc_items.npc_id = ?
			ORDER BY items.titel")){
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		$counter = 0;
		if ($items_all = $stmt->get_result()){
			while($item = $items_all->fetch_array(MYSQLI_NUM)){
				$item_data = array_slice($item, 0, 21);
				$fund_data = array_slice($item, 21);
				$alle_items_npc[$counter] = new Item("Fund", $item_data, $fund_data);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle " . $counter . " Items für: [npc_id=" . $npc_id . "] wurden geladen.<br />\n";
		if (isset($alle_items_npc)) return $alle_items_npc;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_items_npc()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT items.* (Spieler) -----------------------------------
# 	-> spieler.id (int)
#	<- alle_items_spieler (array [Item])

function get_all_items_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				items.id,
				items.bilder_id,
				items.titel,
				items.beschreibung,
				items.essbar,
				items.ausruestbar,
				items.verarbeitbar,
				items.gesundheit,
				items.energie,
				items.zauberpunkte,
				items.staerke,
				items.intelligenz,
				items.magie,
				items.element_feuer,
				items.element_wasser,
				items.element_erde,
				items.element_luft,
				items.initiative,
				items.abwehr,
				items.ausweichen,
				items.prozent,
				items_spieler.anzahl,
				slots.id,
				slots.titel,
				items_spieler.angelegt,
				slots.max
			FROM items
				JOIN items_spieler ON items.id = items_spieler.items_id
				JOIN slots ON items.slot_id = slots.id
			WHERE items_spieler.spieler_id = ?
			ORDER BY items.titel")){
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		$counter = 0;
		if ($items_all = $stmt->get_result()){
			while($item = $items_all->fetch_array(MYSQLI_NUM)){
				$item_data = array_slice($item, 0, 22);
				$slot_data = array_slice($item, 22);
				$alle_items_spieler[$counter] = new Item("Ausrüstung", $item_data, $slot_data);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nItems für: [spieler_id=" . $spieler_id . "] geladen.<br />\n";
		if (isset($alle_items_spieler[0])) return $alle_items_spieler;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_all_items_spieler()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT item-anz (Spieler) -----------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	<- Anzahl des Items

function get_items_spieler_anz($spieler_id, $items_id)
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
				AND items_id = ?
				AND angelegt = 0"))
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
		echo "<br />\nQuerryfehler in get_items_spieler_anz()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT item-anz-angelegt (Spieler) -----------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	<- Anzahl der angelegten Items

function get_items_spieler_anz_angelegt($spieler_id, $items_id)
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
				AND items_id = ?
				AND angelegt = 1"))
	{
		$stmt->bind_param('dd', $spieler_id, $items_id);
		$stmt->execute();
		if ($debug) echo "<br />\nAngelegte Anzahl von Item " . $items_id . " für Spieler " . $spieler_id . " geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if ($row)
		{
			return $row[0];
		} else {
			return 0;
		}
	} else {
		echo "<br />\nQuerryfehler in get_items_spieler_anz_angelegt()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT item -----------------------------------
#	-> items.id (int)
#	<- Item (obj)

function get_item($item_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				items.id,
				items.bilder_id,
				items.titel,
				items.beschreibung,
				items.essbar,
				items.ausruestbar,
				items.verarbeitbar,
				items.gesundheit,
				items.energie,
				items.zauberpunkte,
				items.staerke,
				items.intelligenz,
				items.magie,
				items.element_feuer,
				items.element_wasser,
				items.element_erde,
				items.element_luft,
				items.initiative,
				items.abwehr,
				items.ausweichen,
				items.prozent
			FROM items
			WHERE id = ? ")){
		$stmt->bind_param('d', $item_id);
		$stmt->execute();
		if ($debug) echo "<br />\nItem " . $items_id . " geladen.<br />\n";
		if ($item_data = $stmt->get_result()){
			$item = new Item("ohne", $item_data->fetch_array(MYSQLI_NUM));
		}
		if (isset($item)) return $item;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_item()<br />\n";
		return false;
	}
}


#------------------------------------------- ÄNDERN items_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> anzahl (int)
#	-> items_spieler.id (int)
#	<- true/false

function items_spieler_aendern($spieler_id, $items_id, $anzahl, $parameter="standard")
{
	global $debug;
	global $connect_db_dvg;
		
	switch ($parameter){
		case "standard":
			$anzahl_aktuell = get_items_spieler_anz($spieler_id, $items_id);
			$anzahl_neu = $anzahl_aktuell + $anzahl;
			# Fehler bei Anzahl oder zu wenig Items vorhanden
			if($anzahl_aktuell < 0 OR $anzahl_neu < 0){
				if($anzahl_neu < 0){
					return true;
				} else {
					return false;
				}
			}
			# Neuen Datensatz Einfügen
			if($anzahl_aktuell == 0 AND $anzahl_neu > 0){
				if(insert_items_spieler($spieler_id, $items_id, $anzahl, 0)){
					return true;
				} else {
					return false;
				}
			}
			# Datensatz löschen da 0
			if($anzahl_aktuell > 0 AND $anzahl_neu == 0){
				if(delete_items_spieler($spieler_id, $items_id, 0)){
					return true;
				} else {
					return false;
				}
			}
			# Anzahl Items aktualisieren
			if($anzahl_aktuell > 0 AND $anzahl_neu > 0){
				if(update_items_spieler($spieler_id, $items_id, $anzahl, 0)){
					return true;
				} else {
					return false;
				}
			}
			break;
		case "anlegen":
			$anzahl_aktuell = get_items_spieler_anz($spieler_id, $items_id);
			$anzahl_neu = $anzahl_aktuell - 1;
			$anzahl_angelegt = get_items_spieler_anz_angelegt($spieler_id, $items_id);
			$anzahl_angelegt_neu = $anzahl_angelegt + 1;
			if($slot_data = get_slot_data($spieler_id, $items_id)){
				$anzahl_slot_aktuell = $slot_data[0];
				$anzahl_slot_neu = $anzahl_slot_aktuell + 1;
				$anzahl_slot_max = $slot_data[1];
			} else {
				$anzahl_slot_aktuell = 0;
				$anzahl_slot_neu = $anzahl_slot_aktuell + 1;
				$anzahl_slot_max = 0;
			}
			# Fehler bei Anzahl oder zu wenig Items vorhanden
			if($anzahl_aktuell < 0 OR $anzahl_neu < 0 OR $anzahl_angelegt < 0 OR $anzahl_slot_neu > $anzahl_slot_max){
				return false;
			}
			# Neuen Datensatz Einfügen
			if($anzahl_angelegt == 0){
				insert_items_spieler($spieler_id, $items_id, 1, 1);
			}
			# Datensatz löschen da 0
			if($anzahl_aktuell > 0 AND $anzahl_neu == 0){
				delete_items_spieler($spieler_id, $items_id, 0);
			}
			# Anzahl Items aktualisieren
			if($anzahl_aktuell > 0 AND $anzahl_neu > 0){
				update_items_spieler($spieler_id, $items_id, -1, 0);
			}
			if($anzahl_angelegt > 0 AND $anzahl_angelegt_neu > 0){
				update_items_spieler($spieler_id, $items_id, 1, 1);
			}
			return true;
			break;
		case "ablegen":
			$anzahl_aktuell = get_items_spieler_anz($spieler_id, $items_id);
			$anzahl_neu = $anzahl_aktuell + 1;
			$anzahl_angelegt = get_items_spieler_anz_angelegt($spieler_id, $items_id);
			$anzahl_angelegt_neu = $anzahl_angelegt - 1;
			# Fehler bei Anzahl oder zu wenig Items vorhanden
			if($anzahl_aktuell < 0 OR $anzahl_neu < 0 OR $anzahl_angelegt < 0 OR $anzahl_angelegt_neu < 0){
				return false;
			}
			# Neuen Datensatz Einfügen
			if($anzahl_aktuell == 0){
				insert_items_spieler($spieler_id, $items_id, 1, 0);
			}
			# Datensatz löschen da 0
			if($anzahl_angelegt > 0 AND $anzahl_angelegt_neu == 0){
				delete_items_spieler($spieler_id, $items_id, 1);
			}
			# Anzahl Items aktualisieren
			if($anzahl_aktuell > 0 AND $anzahl_neu > 0){
				update_items_spieler($spieler_id, $items_id, 1, 0);
			}
			if($anzahl_angelegt > 0 AND $anzahl_angelegt_neu > 0){
				update_items_spieler($spieler_id, $items_id, -1, 1);
			}
			return true;
			break;
		default:
			# Keine Ahnung was ich da machen soll!
			break;
	}
}


#------------------------------------------- INSERT items_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> anzahl (int)
#	-> angelegt (int)
#	<- true/false

function insert_items_spieler($spieler_id, $items_id, $anzahl, $angelegt)
{
	global $debug;
	global $connect_db_dvg;
		
	# Neuen Datensatz Einfügen
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO items_spieler(
				items_id,
				spieler_id, 
				anzahl,
				angelegt) 
			VALUES (?, ?, ?, ?)")){
		$stmt->bind_param('dddd', $items_id, $spieler_id, $anzahl, $angelegt);
		$stmt->execute();
		if ($debug) echo "<br />\nItem: [" . $items_id . " wurde Spieler " . $spieler_id . "]<br />\n";
		return true;
	} else {
		echo "<br />\nQuerryfehler in insert_items_spieler()<br />\n";
		echo "<br />\n" . $spieler_id . " | " . $items_id . " | " . $anzahl . " | " . $angelegt . "<br />\n";
		return false;
	}
}


#------------------------------------------- UPDATE items_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> anzahl (int)
#	-> angelegt (int)
#	<- true/false

function update_items_spieler($spieler_id, $items_id, $anzahl, $angelegt)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE items_spieler
			SET anzahl = anzahl + ?
			WHERE
				items_id = ?
				AND spieler_id = ?
				AND angelegt = ?")){
		$stmt->bind_param('dddd', $anzahl, $items_id, $spieler_id, $angelegt);
		$stmt->execute();
		if ($debug) echo "<br />\nItem " . $items_id . " wurde Spieler " . $spieler_id . " genau " . $anzahl . " mal hinzugefügt.<br />\n";
		return true;
	} else {
		echo "<br />\nQuerryfehler in update_items_spieler()<br />\n";
		return false;
	}
}


#------------------------------------------- UPDATE delete_spieler.* -------------------------------------------
# 	-> spieler.id (int)
#	-> items.id (int)
#	-> angelegt (int)
#	<- true/false

function delete_items_spieler($spieler_id, $items_id, $angelegt)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			DELETE FROM items_spieler
			WHERE
				items_id = ?
				AND spieler_id = ?
				AND angelegt = ?")){
		$stmt->bind_param('ddd', $items_id, $spieler_id, $angelegt);
		$stmt->execute();
		if ($debug) echo "<br />\nItem " . $items_id . " wurde bei Spieler " . $spieler_id . " entfernt.<br />\n";
		return true;
	} else {
		echo "<br />\nQuerryfehler in delete_items_spieler()<br />\n";
		return false;
	}
}


#------------------------------------------- SELECT slots.* (Slot Spieler) -------------------------------------------
#	-> spieler.id (int)
#	-> items.id (int)
#	<- aktuell (int)
#	<- max (int)

function get_slot_data($spieler_id, $item_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				sum(items_spieler.anzahl) AS anz,
				slot.max
			FROM items_spieler
				JOIN items ON items.id = items_spieler.items_id
				JOIN (
					SELECT
						slots.id,
						slots.max
					FROM items
						JOIN slots ON slots.id = items.slot_id
					WHERE items.id = ?
					) slot ON slot.id = items.slot_id
			WHERE 
				items_spieler.spieler_id = ?
				AND items_spieler.angelegt = 1
			GROUP BY slot.max, slot.id")){
		$stmt->bind_param('dd', $item_id, $spieler_id);
		$stmt->execute();
		$data = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		if ($debug) echo "<br />\nSlot-Daten von Item " . $items_id . " wurden geladen.<br />\n";
		if(isset($data)) return $data;
			else return array(0,1);
	} else {
		echo "<br />\nQuerryfehler in get_slot_data()<br />\n";
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


#--------------------------------------------- SELECT kampf.* ---------------------------------------------
# 	-> kampf.id (int)
#	<- Kampf (obj)

function select_kampf($kampf_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT *
			FROM kampf
			WHERE id = ?;")){
		$stmt->bind_param('d', $kampf_id);
		$stmt->execute();
		$kampf_row = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		$kampf = new Kampf($kampf_row);
		if ($debug) echo "<br />\nKampf [" . $kampf_id . "] im Gebiet [" . $kampf->gebiet_id . "] wurde geladen.<br />\n";
		return $kampf;
	} else {
		echo "<br />\nQuerryfehler in select_kampf()<br />\n";
		return false;
	}
}


#--------------------------------------------- UPDATE kampf.* ---------------------------------------------
# 	-> Kampf (obj)
#	<- true/false

function update_kampf($kampf)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE kampf
			SET gebiet_id = ?,
				log = ?
			WHERE id = ?;")){
		$stmt->bind_param('dsd', $kampf->gebiet_id, $kampf->log, $kampf->id);
		$stmt->execute();
		if ($debug) echo "<br />\nKampf [" . $kampf->id . "] wurde in Datenbank aktualisiert.<br />\n";
		return true;
	} else {
		echo "<br />\nQuerryfehler in update_kampf()<br />\n";
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
	global $kampf_log_detail;
	
	$return_wert = false;
	$keine_abwehr = false;
	if ($kt->gewinn_id == null) $gewinn = false;
		else $gewinn = get_gewinn($kt->gewinn_id);
	if ($kt->kt_id == $kt_ziel->kt_id AND $kampf_log_detail > 0) $ziel_name = "sich selbst";
		else $ziel_name = $kt_ziel->name;
	$zauberpunkte_verbrauch = berechne_zauberpunkte_verbrauch($zauber);
	$timer_verbrauch = berechne_timer_verbrauch($kt);
	
	foreach ($zauber->zaubereffekte as $zauber_effekt){
		# Ist der Angriff/Zauber sinnvoll?
		if (($kt->seite != $kt_ziel->seite AND $zauber_effekt->art == "verteidigung") OR ($kt->seite == $kt_ziel->seite AND $zauber_effekt->art == "angriff") OR $kt_ziel->ist_tot()){
			if ($kt_ziel->ist_tot()) $ausgabe = $zauber->ausgabe_log("ziel_tot", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
				else $ausgabe = $zauber->ausgabe_log("ziel_falsch", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
			$return_wert = [0, true, $ausgabe];
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
			$ausweichen_erfolg = berechne_ausweichen_erfolg($kt, $kt_ziel, $zauber);
			$abwehr_erfolg = berechne_abwehr_erfolg($kt, $kt_ziel, $zauber);
		}
		# Sind ausreichend Zauberpunkte vorhanden?
		if (($kt->zauberpunkte - $zauberpunkte_verbrauch) < 0){
			$ausgabe = $zauber->ausgabe_log("zauberpunkte", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
			$return_wert = [1, true, $ausgabe];
		}
	}
		
	if (!$return_wert){
		# War der Angriff erfolgreich?
		$angriff_check = $angriff_erfolg . $ausweichen_erfolg . $abwehr_erfolg;
		$ausgabe = false;
		switch ($angriff_check){
			# Angriff/Zauber fehlgeschlagen
			case 000:
			case 001:
			case 010:
			case 011:
				$ausgabe = $zauber->ausgabe_log("patzer", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
				if ($gewinn) $gewinn->erhoehen("patzer", $zauber);
				$return_wert = [2, false, $ausgabe];
				break;
			# Ziel ist ausgewichen
			case 110:
			case 111:
				$ausgabe = $zauber->ausgabe_log("ausweichen", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
				if ($gewinn) $gewinn->erhoehen("ausweichen", $zauber);
				$return_wert = [2, false, $ausgabe];
				break;
			# Ziel hat abgewehrt
			case 101:
				$ausgabe = $zauber->ausgabe_log("abwehr", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
				if ($gewinn) $gewinn->erhoehen("abwehr", $zauber);
				$return_wert = [3, false, $ausgabe];
				break;
			# Treffer
			default:
				break;
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
	
	if (!$return_wert OR $return_wert[0] > 2){	
		# Eintragen der Effekte in die Datenbank
		foreach ($zauber->zaubereffekte as $zauber_effekt){
			berechne_effekt_wert($kt, $kt_ziel, $zauber, $zauber_effekt, $return_wert[0] == 3);
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
		if (!$return_wert){
			$ausgabe = $zauber->ausgabe_log("erfolg", array("zaubernder"=>$kt->name, "zauberziel"=>$ziel_name, "zauber"=>$zauber->titel), $kampf_log_detail);
			if ($gewinn) $gewinn->erhoehen("erfolg", $zauber);
			$return_wert = [4, false, $ausgabe];
		}
	}
	if ($gewinn) $gewinn->db_update();
	return $return_wert;
}


#---------------------------------------- INSERT kampf_teilnehmer.* ----------------------------------------
# 	-> kampf.id (int)
# 	-> teilnehmer_id (int)
# 	-> teilnehmer_typ (str)
# 	-> seite (int)
#	<- kampf_teilnehmer.id (int)

function insert_kampf_teilnehmer($kampf_id, $teilnehmer_id, $teilnehmer_typ, $seite, $start_timer=0)
{
	global $debug;
	global $connect_db_dvg;
	
	switch($teilnehmer_typ){
		#################################################################
		case "spieler":
			$spieler_kt = new Spieler($teilnehmer_id);
			$kampf_teilnehmer = new KampfTeilnehmer($spieler_kt, $teilnehmer_typ, $seite);
			break;
		#################################################################
		case "npc":
			$npc = get_npc($teilnehmer_id);
			$kampf_teilnehmer = new KampfTeilnehmer($npc, $teilnehmer_typ, $seite);
			break;
		#################################################################
		default:
			echo "Kein Spielertyp übergeben?";
			break;
	}
	$timer = $kampf_teilnehmer->timer + $start_timer;
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO kampf_teilnehmer(
				kampf_id,
				teilnehmer_id,
				teilnehmer_typ,
				seite,
				gesundheit,
				gesundheit_max,
				zauberpunkte,
				zauberpunkte_max,
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
				timer,
				gewinn_id)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param('ddsddddddddddddddddd',
				$kampf_id,
				$kampf_teilnehmer->id,
				$kampf_teilnehmer->typ,
				$kampf_teilnehmer->seite,
				$kampf_teilnehmer->gesundheit,
				$kampf_teilnehmer->gesundheit_max,
				$kampf_teilnehmer->zauberpunkte,
				$kampf_teilnehmer->zauberpunkte_max,
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
				$timer,
				$kampf_teilnehmer->gewinn_id);
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

function get_all_kampf_teilnehmer($kampf_id, $seite, $lebendig=false)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($lebendig) $min_gesundheit = 0;
		else $min_gesundheit = -1;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				case when npc.id IS NULL then spieler.name ELSE npc.titel END AS name,
				case when npc.id IS NULL then spieler.bilder_id ELSE npc.bilder_id END AS bilder_id,
				case when npc.id IS NULL then spieler.id ELSE npc.id END AS id,
				kampf_teilnehmer.teilnehmer_typ AS typ,
				kampf_teilnehmer.seite AS seite,
				kampf_teilnehmer.gesundheit AS gesundheit,
				kampf_teilnehmer.gesundheit_max AS gesundheit_max,
				kampf_teilnehmer.zauberpunkte AS zauberpunkte,
				kampf_teilnehmer.zauberpunkte_max AS zauberpunkte_max,
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
				kampf_teilnehmer.id AS kt_id,
				case when npc.id IS NULL then 0 ELSE npc.ki_id END AS ki_id,
				kampf_teilnehmer.gewinn_id
			FROM kampf_teilnehmer
				LEFT JOIN npc ON npc.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'npc'
				LEFT JOIN spieler ON spieler.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'spieler'
			WHERE kampf_teilnehmer.kampf_id = ?
				AND kampf_teilnehmer.seite = ?
				AND kampf_teilnehmer.gesundheit >= ?
				AND kampf_teilnehmer.deaktiviert = 0
			ORDER BY typ, kampf_teilnehmer.id")){
		$stmt->bind_param('ddd', $kampf_id, $seite, $min_gesundheit);
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


#------------------------------------- SELECT kampf_teilnehmer.* (alle) -------------------------------------
# 	-> kampf.id (int)
#	<- alle_kampf_teilnehmer (array [kampf_teilnehmer])

function get_kampf_teilnehmer_by_id($kampfteilnehmer_id)
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
				kampf_teilnehmer.gesundheit_max AS gesundheit_max,
				kampf_teilnehmer.zauberpunkte AS zauberpunkte,
				kampf_teilnehmer.zauberpunkte_max AS zauberpunkte_max,
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
				kampf_teilnehmer.id AS kt_id,
				case when npc.id IS NULL then 0 ELSE npc.ki_id END AS ki_id,
				kampf_teilnehmer.gewinn_id
			FROM kampf_teilnehmer
				LEFT JOIN npc ON npc.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'npc'
				LEFT JOIN spieler ON spieler.id = kampf_teilnehmer.teilnehmer_id AND kampf_teilnehmer.teilnehmer_typ = 'spieler'
			WHERE kampf_teilnehmer.id = ?")){
		$stmt->bind_param('d', $kampfteilnehmer_id);
		$stmt->execute();
		$kampf_teilnehmer = new KampfTeilnehmer($stmt->get_result()->fetch_array(MYSQLI_NUM));
		if ($debug) echo "<br />\nKampfteilnehmer [id=".$kampfteilnehmer_id."] wurde geladen.<br />\n";
		return $kampf_teilnehmer;
	} else {
		echo "<br />\nQuerryfehler in get_kampf_teilnehmer_by_id()<br />\n";
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
				AND kampf_teilnehmer.teilnehmer_typ = 'npc'
				AND kampf_teilnehmer.seite = 1")){
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


#--------------------- SELECT kampf_effekt.* (alle aktiven zum Kampfteilnehmer) ---------------------
# 	-> KampfTeilnehmer (obj)
#	<- alle_kampf_effekte (array [kampf_effekt])
function select_kampf_effekte($kt, $param)
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
				AND kampf_effekt.art IN (?)
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
		if ($debug) echo "<br />\nAlle " . $counter . " aktiven Kampfeffekte zum Kampfteilnehmer '" . $kt->name . "' wurden geladen.<br />\n";
		if ($counter > 0) return $alle_kampf_effekte;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in select_kampf_effekte()<br />\n";
		return false;
	}
}


#--------------------- SELECT kampf_effekt.* (alle zur kampf_aktion) ---------------------
# 	-> kampf_aktion.id (int)
#	<- alle_kampf_effekte (array [kampf_effekt])
function select_kampf_effekte_spezial($kampf_aktion_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT kampf_effekt.id,
				'' AS zauber_name,
				kampf_effekt.art,
				kampf_effekt.attribut,
				kampf_effekt.wert,
				kampf_effekt.runden,
				kampf_effekt.runden_max,
				kampf_effekt.jede_runde,
				kampf_effekt.ausgefuehrt,
				kampf_effekt.beendet
			FROM kampf_effekt
			WHERE kampf_effekt.kampf_aktion_id = ?")){
		$stmt->bind_param('d', $kampf_aktion_id);
		$stmt->execute();
		$counter = 0;
		if ($kampf_effekte_all = $stmt->get_result()){
			while($kampf_effekt = $kampf_effekte_all->fetch_array(MYSQLI_NUM)){
				$alle_kampf_effekte[$counter] = new KampfEffekt($kampf_effekt);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle " . $counter . " Kampfeffekte zur Kampfaktion [" . $kampf_aktion_id . "] wurden geladen.<br />\n";
		if ($counter > 0) return $alle_kampf_effekte;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in select_kampf_effekte_spezial()<br />\n";
		return false;
	}
}


#--------------------- SELECT/UPDATE kampf_effekt.* (alle aktiven zum Kampfteilnehmer) ---------------------
# 	-> kampf_teilnehmer.id (int)
#	<- true/false
function kampf_effekte_ausführen($kt, $param, $kt_an_der_reihe=true)
{
	global $debug;
	global $connect_db_dvg;
	global $kampf;
	
	# Aktuell relevante KampfEffekte für den Kampfteilnehmer aus DB auslesen
	$alle_kampf_effekte = select_kampf_effekte($kt, $param);
	
	# Kampfeffekte verarbeiten
	if ($alle_kampf_effekte){
		foreach ($alle_kampf_effekte as $kampf_effekt){
			$runden_diff = $kampf_effekt->runden_max - $kampf_effekt->runden; # Wieviele Runden ist der Effekt noch aktiv?
			$effekt_anwenden = ($kampf_effekt->jede_runde == 1 OR $kampf_effekt->runden == 0); # Muss der Effekt angewendet werden? (nur 1. Runde oder jede Runde)
			$effekt_beenden = ($runden_diff == 0); # Muss der Effekt beendet werden? (Max Runden erreicht bzw. temporären Bonus wieder zurücksetzen)
			
			# Verarbeitungslogik für Angriffe sowie Verteidigung/Heilung
			$plus_runden = 0;
			$set_beendet = 0;
			
			if (($kampf_effekt->ausgefuehrt == 0 OR $runden_diff == 0) AND !$kt->ist_tot()){
				switch($kampf_effekt->attribut){
					# Gesundheit
					case "gesundheit":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0, $kt->gesundheit_max);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0, $kt->gesundheit_max);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						if ($kt->ist_tot())
							$kampf->log_tot($kt);
						break;
					# Zauberpunkte
					case "zauberpunkte":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0, $kt->zauberpunkte_max);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0, $kt->zauberpunkte_max);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Hauptattribute
					case "staerke":
					case "intelligenz":
					case "magie":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Elemente
					case "element_feuer":
					case "element_wasser":
					case "element_erde":
					case "element_luft":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Timer
					case "timer":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Initiative
					case "initiative":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Ausweichen
					case "ausweichen":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Abwehr
					case "abwehr":
						if ($effekt_anwenden){
							$kt->attribut_aendern($kampf_effekt->attribut, $kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							$kt->attribut_aendern($kampf_effekt->attribut, -$kampf_effekt->wert, 0);
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
						break;
					# Spezial
					case "spezial":
						if ($effekt_anwenden){
							switch ($kampf_effekt->spezial->art){
								case "Beschwörung":
									$kt_id = insert_kampf_teilnehmer($kampf->id, $kampf_effekt->spezial->spezial_id, $kampf_effekt->spezial->spezial_tabelle, $kt->seite, $kt->timer);
									kt_array_korrigieren($kt_id, "hinzufügen");
									break;
								default: break;
							}
							$kampf->log_effekt($kt, $kampf_effekt, false);
						}
						if ($effekt_beenden){
							switch ($kampf_effekt->spezial->art){
								case "Beschwörung":
									$kt_id = deaktiviere_kampf_teilnehmer($kampf->id, $kampf_effekt->spezial->spezial_tabelle, $kampf_effekt->spezial->spezial_id);
									kt_array_korrigieren($kt_id, "entfernen");
									break;
								default: break;
							}
							$kampf->log_effekt($kt, $kampf_effekt, true);
						}
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
				if (($kampf_effekt->jede_runde == 1 AND $runden_diff == 1) OR ($kampf_effekt->jede_runde == 0 AND $runden_diff == 0) OR $kt->ist_tot()) $set_beendet = 1;
					else $set_beendet = 0;
			}
			
			if ($kt->ist_tot()) $set_beendet = 1;
				
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
				$return_wert = true;
			} else {
				echo "<br />\nQuerryfehler in kampf_effekte_ausführen() - Update KampfEffekte Rundenzähler/beendet<br />\n";
				$return_wert = false;
			}
		}
	} else {
		$return_wert = false;
	}
	return $return_wert;
}


#------------------------------------- UPDATE kampf_teilnehmer.* (alle Kampfteilnehmer) -------------------------------------
# 	-> alle_kampf_teilnehmer (array [kampf_teilnehmer])
#	<- true/false
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
					gesundheit_max = ?,
					zauberpunkte = ?,
					zauberpunkte_max = ?,
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
			$stmt->bind_param('ddddddddddddddddd',
					$kt->seite,
					$kt->gesundheit,
					$kt->gesundheit_max,
					$kt->zauberpunkte,
					$kt->zauberpunkte_max,
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


#------------------------------------- DELETE kampf_teilnehmer.* (z.B. bei Beschwörungszaubern) -------------------------------------
# 	-> kampf_id (int)
#	-> teilnehmer_typ (str)
#	-> teilnehmer_id (int)
#	<- true/false
function deaktiviere_kampf_teilnehmer($kampf_id, $teilnehmer_typ, $teilnehmer_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT MIN(id)
			FROM kampf_teilnehmer
			WHERE kampf_id = ?
				AND teilnehmer_typ = ?
				AND teilnehmer_id = ?
				AND deaktiviert = 0")){
		$stmt->bind_param('dsd', $kampf_id, $teilnehmer_typ, $teilnehmer_id);
		$stmt->execute();
		$kampf_teilnehmer_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
	} else {
		echo "<br />\nQuerryfehler in delete_kampf_teilnehmer() - select kt_id<br />\n";
		return false;
	}
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE kampf_teilnehmer
			SET deaktiviert = 1
			WHERE id = ?")){
		$stmt->bind_param('d', $kampf_teilnehmer_id);
		$stmt->execute();
		return $kampf_teilnehmer_id;
	} else {
		echo "<br />\nQuerryfehler in delete_kampf_teilnehmer() - deaktiviere kt<br />\n";
		return false;
	}
}



#**************************************************************************************************************
#***************************************************** KI *****************************************************
#**************************************************************************************************************

#----------------------------------------- SELECT ki.* -----------------------------------------
# 	-> ki.id (int)
# 	<- ki (obj)

function get_ki($ki_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				ki.*
			FROM ki
			WHERE id = ?")){
		$stmt->bind_param('d', $ki_id);
		$stmt->execute();
		$ki_data = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		$ki = new KI($ki_data);
		if ($debug) echo "<br />\nKI '".$ki->name."' wurde geladen.<br />\n";
		return $ki;
	} else {
		echo "<br />\nQuerryfehler in get_ki()<br />\n";
		return false;
	}
}



#**************************************************************************************************************
#*************************************************** LEVEL ****************************************************
#**************************************************************************************************************

#----------------------------------------- SELECT level.* (Erfahrung) -----------------------------------------
# 	-> level.id (int)
# 	<- erfahrung_naechster_level (int)

function get_erfahrung_naechster_level($level_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT level.erfahrung_naechster_level
			FROM level
			WHERE id = ?")){
		$stmt->bind_param('d', $level_id);
		$stmt->execute();
		$erfahrung_naechster_level = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
		if ($debug) echo "<br />\nBenötigte Erfahrung für Level '".$level_id."' wurde geladen.<br />\n";
		return $erfahrung_naechster_level;
	} else {
		echo "<br />\nQuerryfehler in get_erfahrung_naechster_level()<br />\n";
		return false;
	}
}


#----------------------------------------- SELECT level.* (Gewinn Aufstieg) -----------------------------------------
# 	-> level.id (int)
# 	<- Gewinn (obj)

function get_gewinn_naechster_level($level_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT gewinn.*
			FROM level
				JOIN gewinn on gewinn.id = level.gewinn_id
			WHERE level.id = ?")){
		$stmt->bind_param('d', $level_id);
		$stmt->execute();
		$erfahrung_naechster_level = new Gewinn($stmt->get_result()->fetch_array(MYSQLI_NUM));
		if ($debug) echo "<br />\nGewinn bei Levelaufstieg für Level '".$level_id."' wurde geladen.<br />\n";
		return $erfahrung_naechster_level;
	} else {
		echo "<br />\nQuerryfehler in get_gewinn_naechster_level()<br />\n";
		return false;
	}
}



#***************************************************************************************************************
#***************************************************** NPC *****************************************************
#***************************************************************************************************************

#----------------------------------- SELECT npc.* (im Gebiet) -----------------------------------
# 	-> npc_gebiet.gebiet_id (int)
#	-> npc.typ (str)
#	<- alle_npc_im_gebiet (array [NPCFund])

function get_npcs_gebiet($gebiet_id, $npc_typ)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	npc.id,
				npc.titel,
				npc.beschreibung,
				npc_gebiet.wahrscheinlichkeit,
				npc.bilder_id,
				npc.typ
			FROM 	npc
				JOIN npc_gebiet ON npc.id = npc_gebiet.npc_id
			WHERE 	npc_gebiet.gebiet_id = ?
				AND npc.typ = ?
			ORDER BY npc.titel"))
	{
		$stmt->bind_param('ds', $gebiet_id, $npc_typ);
		$stmt->execute();
		$counter = 0;
		$alle_npc_im_gebiet=null;
		if ($npc_all = $stmt->get_result()){
			while($npc = $npc_all->fetch_array(MYSQLI_NUM)){
				$alle_npc_im_gebiet[$counter] = new NPCFund($npc);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nNPC-Daten für: [gebiet_id=" . $gebiet_id . "] und [npc_typ=" . $npc_typ . "]geladen.<br />\n";
		if ($counter > 0) return $alle_npc_im_gebiet;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_npcs_gebiet()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT npc.* (einzel) -----------------------------------
# 	-> npc.id (int)
# 	<- NPC (obj)

function get_npc($npc_id)
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
		$npc = new NPC($stmt->get_result()->fetch_array(MYSQLI_NUM));
		return $npc;
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
#	---> Viele viele Daten siehe SELECT

function get_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT
				spieler.id,
				spieler.account_id,
				spieler.bilder_id,
				spieler.gattung_id,
				spieler.level_id,
				spieler.gebiet_id,
				spieler.name,
				spieler.geschlecht,
				spieler.gesundheit,
				spieler.energie,
				spieler.zauberpunkte,
				spieler.balance,
				spieler.zuletzt_gespielt,
				spieler.erfahrung,
				round(spieler.staerke + bonus_pkt.staerke + spieler.staerke * bonus_proz.staerke * 0.01, 4),
				round(spieler.intelligenz + bonus_pkt.intelligenz + spieler.intelligenz * bonus_proz.intelligenz * 0.01, 4),
				round(spieler.magie + bonus_pkt.magie + spieler.magie * bonus_proz.magie * 0.01, 4),
				round(spieler.element_feuer + bonus_pkt.element_feuer + spieler.element_feuer * bonus_proz.element_feuer * 0.01, 4),
				round(spieler.element_wasser + bonus_pkt.element_wasser + spieler.element_wasser * bonus_proz.element_wasser * 0.01, 4),
				round(spieler.element_erde + bonus_pkt.element_erde + spieler.element_erde * bonus_proz.element_erde * 0.01, 4),
				round(spieler.element_luft + bonus_pkt.element_luft + spieler.element_luft * bonus_proz.element_luft * 0.01, 4),
				round(spieler.initiative + bonus_pkt.initiative + spieler.initiative * bonus_proz.initiative * 0.01, 0),
				round(spieler.abwehr + bonus_pkt.abwehr + spieler.abwehr * bonus_proz.abwehr * 0.01, 0),
				round(spieler.ausweichen + bonus_pkt.ausweichen + spieler.ausweichen * bonus_proz.ausweichen * 0.01, 0),
				spieler.staerke,
				spieler.intelligenz,
				spieler.magie,
				spieler.element_feuer,
				spieler.element_wasser,
				spieler.element_erde,
				spieler.element_luft,
				spieler.max_gesundheit,
				spieler.max_energie,
				spieler.max_zauberpunkte,
				spieler.initiative,
				spieler.abwehr,
				spieler.ausweichen,
				bonus_pkt.staerke,
				bonus_pkt.intelligenz,
				bonus_pkt.magie,
				bonus_pkt.element_feuer,
				bonus_pkt.element_wasser,
				bonus_pkt.element_erde,
				bonus_pkt.element_luft,
				bonus_pkt.max_gesundheit,
				bonus_pkt.max_energie,
				bonus_pkt.max_zauberpunkte,
				bonus_pkt.initiative,
				bonus_pkt.abwehr,
				bonus_pkt.ausweichen,
				bonus_proz.staerke,
				bonus_proz.intelligenz,
				bonus_proz.magie,
				bonus_proz.element_feuer,
				bonus_proz.element_wasser,
				bonus_proz.element_erde,
				bonus_proz.element_luft,
				bonus_proz.max_gesundheit,
				bonus_proz.max_energie,
				bonus_proz.max_zauberpunkte,
				bonus_proz.initiative,
				bonus_proz.abwehr,
				bonus_proz.ausweichen
			FROM
				spieler
				LEFT JOIN (
					SELECT
						CASE WHEN max_gesundheit > 0 THEN max_gesundheit ELSE 0 END AS max_gesundheit,
						CASE WHEN max_energie > 0 THEN max_energie ELSE 0 END AS max_energie,
						CASE WHEN max_zauberpunkte > 0 THEN max_zauberpunkte ELSE 0 END AS max_zauberpunkte,
						CASE WHEN staerke > 0 THEN staerke ELSE 0 END AS staerke,
						CASE WHEN intelligenz > 0 THEN intelligenz ELSE 0 END AS intelligenz,
						CASE WHEN magie > 0 THEN magie ELSE 0 END AS magie,
						CASE WHEN element_feuer > 0 THEN element_feuer ELSE 0 END AS element_feuer,
						CASE WHEN element_wasser > 0 THEN element_wasser ELSE 0 END AS element_wasser,
						CASE WHEN element_erde > 0 THEN element_erde ELSE 0 END AS element_erde,
						CASE WHEN element_luft > 0 THEN element_luft ELSE 0 END AS element_luft,
						CASE WHEN initiative > 0 THEN initiative ELSE 0 END AS initiative,
						CASE WHEN abwehr > 0 THEN abwehr ELSE 0 END AS abwehr,
						CASE WHEN ausweichen > 0 THEN ausweichen ELSE 0 END AS ausweichen
					FROM (
						SELECT
							sum(items_spieler.anzahl * items.gesundheit) AS max_gesundheit,
							sum(items_spieler.anzahl * items.energie) AS max_energie,
							sum(items_spieler.anzahl * items.zauberpunkte) AS max_zauberpunkte,
							sum(items_spieler.anzahl * items.staerke) AS staerke,
							sum(items_spieler.anzahl * items.intelligenz) AS intelligenz,
							sum(items_spieler.anzahl * items.magie) AS magie,
							sum(items_spieler.anzahl * items.element_feuer) AS element_feuer,
							sum(items_spieler.anzahl * items.element_wasser) AS element_wasser,
							sum(items_spieler.anzahl * items.element_erde) AS element_erde,
							sum(items_spieler.anzahl * items.element_luft) AS element_luft,
							sum(items_spieler.anzahl * items.initiative) AS initiative,
							sum(items_spieler.anzahl * items.abwehr) AS abwehr,
							sum(items_spieler.anzahl * items.ausweichen) AS ausweichen
						FROM items_spieler
							JOIN items ON items.id = items_spieler.items_id
						WHERE 1=1
							AND items_spieler.angelegt = 1
							AND items_spieler.spieler_id = ?
							AND items.prozent = 0
						) inner_bonus_pkt
					) bonus_pkt ON 1=1
				LEFT JOIN (
					SELECT
						CASE WHEN max_gesundheit > 0 THEN max_gesundheit ELSE 0 END AS max_gesundheit,
						CASE WHEN max_energie > 0 THEN max_energie ELSE 0 END AS max_energie,
						CASE WHEN max_zauberpunkte > 0 THEN max_zauberpunkte ELSE 0 END AS max_zauberpunkte,
						CASE WHEN staerke > 0 THEN staerke ELSE 0 END AS staerke,
						CASE WHEN intelligenz > 0 THEN intelligenz ELSE 0 END AS intelligenz,
						CASE WHEN magie > 0 THEN magie ELSE 0 END AS magie,
						CASE WHEN element_feuer > 0 THEN element_feuer ELSE 0 END AS element_feuer,
						CASE WHEN element_wasser > 0 THEN element_wasser ELSE 0 END AS element_wasser,
						CASE WHEN element_erde > 0 THEN element_erde ELSE 0 END AS element_erde,
						CASE WHEN element_luft > 0 THEN element_luft ELSE 0 END AS element_luft,
						CASE WHEN initiative > 0 THEN initiative ELSE 0 END AS initiative,
						CASE WHEN abwehr > 0 THEN abwehr ELSE 0 END AS abwehr,
						CASE WHEN ausweichen > 0 THEN ausweichen ELSE 0 END AS ausweichen
					FROM (
						SELECT
							sum(items_spieler.anzahl * items.gesundheit) AS max_gesundheit,
							sum(items_spieler.anzahl * items.energie) AS max_energie,
							sum(items_spieler.anzahl * items.zauberpunkte) AS max_zauberpunkte,
							sum(items_spieler.anzahl * items.staerke) AS staerke,
							sum(items_spieler.anzahl * items.intelligenz) AS intelligenz,
							sum(items_spieler.anzahl * items.magie) AS magie,
							sum(items_spieler.anzahl * items.element_feuer) AS element_feuer,
							sum(items_spieler.anzahl * items.element_wasser) AS element_wasser,
							sum(items_spieler.anzahl * items.element_erde) AS element_erde,
							sum(items_spieler.anzahl * items.element_luft) AS element_luft,
							sum(items_spieler.anzahl * items.initiative) AS initiative,
							sum(items_spieler.anzahl * items.abwehr) AS abwehr,
							sum(items_spieler.anzahl * items.ausweichen) AS ausweichen
						FROM items_spieler
							JOIN items ON items.id = items_spieler.items_id
						WHERE 1=1
							AND items_spieler.angelegt = 1
							AND items_spieler.spieler_id = ?
							AND items.prozent = 1
						) inner_bonus_proz
					) bonus_proz ON 1=1
			WHERE 1 = 1
				AND spieler.id = ?"))
	{
		$stmt->bind_param('ddd', $spieler_id, $spieler_id, $spieler_id);
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


#----------------------------------------------- SELECT spieler.* (nur für Test auf Vorhandensein) ----------------------------------------------
# 	-> spieler.id (int)
#	<- spieler.name (str)

function get_spieler_check($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	if ($stmt = $connect_db_dvg->prepare("
			SELECT name
			FROM spieler
			WHERE id = ?"))
	{
		$stmt->bind_param('d', $spieler_id);
		$stmt->execute();
		if ($debug) echo "<br />\nSpielerdaten für: [spieler_id=" . $spieler_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
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
			VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 100, 100, 10, 10)")){
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
			$spieler->basiswerte = new Werte();
			$spieler->basiswerte->staerke = $gattung_data[1];
			$spieler->basiswerte->intelligenz = $gattung_data[2];
			$spieler->basiswerte->magie = $gattung_data[3];
			$spieler->basiswerte->element_feuer = $gattung_data[4];
			$spieler->basiswerte->element_wasser = $gattung_data[5];
			$spieler->basiswerte->element_erde = $gattung_data[6];
			$spieler->basiswerte->element_luft = $gattung_data[7];
			$spieler->bonus_pkt = new Werte();
			$spieler->bonus_proz = new Werte();
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
		$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM spieler");
		$stmt->execute();
		$spieler_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
		insertSpielerZauberStandard($spieler_id, $spieler->gattung_id);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in insert_spieler()<br />\n";
		return false;
	}
}


#----------------------------------- INSERT zauber_spieler -----------------------------------
#	-> spieler_id
#	-> gattung_id
#	<- true/false

function insertSpielerZauberStandard($spieler_id, $gattung_id)
{
	global $debug;
	global $connect_db_dvg;
	$count = 0;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT zauber_id
			FROM gattung_zauber
			WHERE gattung_id = ?")){
		$stmt->bind_param('d', $gattung_id);
		$stmt->execute();
		if ($zauber_all = $stmt->get_result()){
			while($zauber_einzel = $zauber_all->fetch_array(MYSQLI_NUM)){
				$zauber[$count] = $zauber_einzel[0];
				$count = $count + 1;
			}
		}
		if ($debug) echo "<br />\nStartzauber für: [spieler_id=" . $spieler_id . " und gattung_id=" . $gattung_id . "] geladen.<br />\n";
	} else {
		echo "<br />\nQuerryfehler in insertNPCzauberStandard() - Select<br />\n";
		return false;
	}
	if ($count > 0){
		if ($stmt = $connect_db_dvg->prepare("
				INSERT INTO zauber_spieler (
					spieler_id,
					zauber_id)
				VALUES (?, ?)")){
			foreach ($zauber as $zauber_id){
				$stmt->bind_param('dd', $spieler_id, $zauber_id);
				$stmt->execute();
			}
		} else {
			echo "<br>\nQuerryfehler in insertNPCzauberStandard() - Insert<br>\n";
			return false;
		}
	}
	return true;
}


#----------------------------------------------- DELETE spieler.* ----------------------------------------------
# 	-> spieler.id (str)
#	<- true/false

function delete_spieler($spieler_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if (!$spieler_zum_loeschen = get_spieler_check($spieler_id))
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
		echo "<br />\nSpieler: " . $spieler_zum_loeschen . " wurde gelöscht.<br />\n";
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
#************************************************** STATISTIK **************************************************
#***************************************************************************************************************

#---------------------------------- SELECT npc_spieler_statistik.* (komplett) ----------------------------------
# 	-> spieler.id (int)
#	-> npc.id (int)
#	<- statistik (array [Statistik])

function get_npc_spieler_statistik($spieler_id=null, $npc_id=null)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($spieler_id == null){
		$sp_min = 0;
		$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM spieler");
		$stmt->execute();
		$sp_max = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
	} else {
		$sp_min = $spieler_id;
		$sp_max = $spieler_id;
	}
	if ($npc_id == null){
		$npc_min = 0;
		$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM npc");
		$stmt->execute();
		$npc_max = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
	} else {
		$npc_min = $npc_id;
		$npc_max = $npc_id;
	}
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT npc_spieler_statistik.id,
				npc_spieler_statistik.spieler_id,
				npc_spieler_statistik.npc_id,
				npc.titel,
				npc_spieler_statistik.anzahl,
				case npc.typ 
					when 'angreifbar' then 'besiegt'
					when 'sammelbar' then 'gesammelt'
					else 'unbekannt'
				end as wie
			FROM npc_spieler_statistik
				JOIN npc ON npc.id = npc_spieler_statistik.npc_id
			WHERE npc_spieler_statistik.spieler_id between ? and ?
				and npc_spieler_statistik.npc_id between ? and ?
			ORDER BY npc_spieler_statistik.spieler_id, npc.titel"))
	{
		$stmt->bind_param('dddd', $sp_min, $sp_max, $npc_min, $npc_max);
		$stmt->execute();
		$counter = 0;
		if ($statistik_all = $stmt->get_result()){
			while($statistik_einzel = $statistik_all->fetch_array(MYSQLI_NUM)){
				$statistik[$counter] = new Statistik($statistik_einzel);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nNPC-Spieler-Statistik für: [spieler_id=" . $spieler_id . " und npc_id=" . $npc_id . "] geladen.<br />\n";
		if ($counter > 0) return $statistik;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_npc_spieler_statistik()<br />\n";
		return false;
	}
}


#---------------------------------------- INSERT npc_spieler_statistik.* ----------------------------------------
# 	-> spieler.id (int)
#	-> alle_npcs (array [npc.id])
#	<- true/false

function add_npc_spieler_statistik($spieler_id, $alle_npcs)
{
	global $debug;
	global $connect_db_dvg;
	
	if (is_array($alle_npcs)) $alle_npc_ids = $alle_npcs;
		else $alle_npc_ids = [$alle_npcs];
	
	foreach ($alle_npc_ids as $npc_id){
		$statistik = get_npc_spieler_statistik($spieler_id, $npc_id);
		if ($statistik == false){
			if ($stmt = $connect_db_dvg->prepare("
					INSERT INTO npc_spieler_statistik(
						spieler_id,
						npc_id, 
						anzahl) 
					VALUES (?, ?, 1)")){
				$stmt->bind_param('dd', $spieler_id, $npc_id);
				$stmt->execute();
				if ($debug) echo "<br />\nNPC: [" . $npc_id . " wurde bei Spieler " . $spieler_id . "] zur Statistik hinzugefügt<br />\n";
				return 1;
			} else {
				echo "<br />\nQuerryfehler in add_npc_spieler_statistik() - Insert<br />\n";
				return false;
			}
		} else {
			if ($stmt = $connect_db_dvg->prepare("
					UPDATE npc_spieler_statistik
					SET anzahl = anzahl + 1
					WHERE spieler_id = ?
						AND npc_id = ?")){
				$stmt->bind_param('dd', $spieler_id, $npc_id);
				$stmt->execute();
				if ($debug) echo "<br />\nNPC: [" . $npc_id . " wurde bei Spieler " . $spieler_id . "] zur Statistik hinzugefügt<br />\n";
				return $statistik[0]->anzahl + 1;
			} else {
				echo "<br />\nQuerryfehler in add_npc_spieler_statistik() - Update<br />\n";
				return false;
			}
		}
	}
}


#---------------------------------- SELECT spieler_statistik_balance.*  ----------------------------------
# 	-> spieler.id (int)
#	<- statistik (array [Statistik])

function get_spieler_statistik_balance($spieler)
{
	global $debug;
	global $connect_db_dvg;
	if ($stmt = $connect_db_dvg->prepare("
		SELECT npc.typ,
			SUM(npc_spieler_statistik.anzahl) AS anz
		FROM npc_spieler_statistik
			JOIN npc ON npc.id = npc_spieler_statistik.npc_id
		WHERE npc_spieler_statistik.spieler_id = ?
			AND npc.typ IN ('angreifbar','sammelbar')
		GROUP BY npc.typ")){
		$stmt->bind_param('d', $spieler->id);
		$stmt->execute();
		$statistik = array("angreifbar" => 0, "sammelbar" => 0);
		if ($statistik_all = $stmt->get_result()){
			while($statistik_einzel = $statistik_all->fetch_array(MYSQLI_NUM)){
				$statistik[$statistik_einzel[0]] = $statistik_einzel[1];
			}
		}
		if ($debug) echo "<br />\nSpieler-Statistik-Balance für ".$spieler->name." wurde geladen.<br />\n";
		return $statistik;
	} else {
		echo "<br />\nQuerryfehler in get_spieler_statistik_balance()<br />\n";
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
				element.titel = ?
				AND zauber.nutzbar_von IN ('spieler','alle')")){
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
				element2.titel
				AND zauber.nutzbar_von IN ('spieler','alle')")){
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
				JOIN zauberart ON zauberart.id = zauber.zauberart_id
				JOIN element element1 ON element1.id = zauber.hauptelement_id
				JOIN element element2 ON element2.id = zauber.nebenelement_id
			WHERE
				element1.titel = ?
				AND element2.titel = ?
				AND zauberart.titel = ?
				AND zauber.nutzbar_von IN ('spieler','alle')
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
				zauber.beschreibung,
				CASE WHEN zauber_npc.id IS NULL then 100 ELSE zauber_npc.wahrscheinlichkeit END AS wkt,
				zauber.nutzbar_von,
				zauber.zauber_text_id
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
				$alle_zauber_zum_kampf_teilnehmer[$counter] = new KampfZauber($zauber, get_zauber_effekte($zauber[0]));
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
				zauber.beschreibung,
				100 AS wkt,
				zauber.nutzbar_von,
				zauber.zauber_text_id
			FROM zauber
				JOIN zauberart ON zauberart.id = zauber.zauberart_id
			WHERE zauber.id = ?")){
		$stmt->bind_param('d', $zauber_id);
		$stmt->execute();
		$zauber_row = $stmt->get_result()->fetch_array(MYSQLI_NUM);
		$zauber = new KampfZauber($zauber_row, get_zauber_effekte($zauber_id));
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


#----------------------------------------- SELECT zauber_effekt.* (Zusatzdaten für Spezialzauber aus separater Tabelle) -----------------------------------------
# 	-> zauber_effekt_spezial.id (int) entspricht zauber_effekt.wert
# 	<- zauber_effekt_spezial (array [zauber_effekt])

function get_zauber_effekt_spezial($zauber_effekt_spezial_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT
				zauber_effekt_spezial.id,
				zauber_effekt_spezial.art,
				zauber_effekt_spezial.spezial_tabelle,
				zauber_effekt_spezial.spezial_id,
				zauber_effekt_spezial.sql,
				zauber_effekt_spezial.text1,
				zauber_effekt_spezial.text2,
				zauber_effekt_spezial.text3
			FROM zauber_effekt_spezial
			WHERE zauber_effekt_spezial.id = ?")){
		$stmt->bind_param('d', $zauber_effekt_spezial_id);
		$stmt->execute();
		$zauber_effekt_spezial = new ZauberEffektSpezial($stmt->get_result()->fetch_array(MYSQLI_NUM));
		if ($debug) echo "<br />\nZaubereffekt (Spezial) [".$zauber_effekt_spezial_id."] zum Zauber wurde geladen.<br />\n";
		return $zauber_effekt_spezial;
	} else {
		echo "<br />\nQuerryfehler in get_zauber_effekt_spezial()<br />\n";
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


#----------------------------------------- SELECT zauber.* (alle auf Kampfteilnehmer wirkenden) -----------------------------------------
# 	-> KampfTeilnehmer (obj)
# 	<- alle_aktiven_zauber (array [zauber])

function get_zauber_aktiv($kt)
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
				zauber.beschreibung,
				0 AS wkt,
				zauber.nutzbar_von,
				zauber.zauber_text_id,
				kampf_aktion.id
			FROM zauber
				JOIN zauberart ON zauberart.id = zauber.zauberart_id
				LEFT JOIN kampf_aktion ON zauber.id = kampf_aktion.zauber_id
			WHERE kampf_aktion.id IN (
				SELECT DISTINCT kampf_aktion_id
				FROM kampf_effekt
				WHERE kampf_teilnehmer_id = ?
					AND beendet = 0
				)
			ORDER BY kampf_aktion.id")){
		$stmt->bind_param('d', $kt->kt_id);
		$stmt->execute();
		$counter = 0;
		$alle_aktiven_zauber=null;
		if ($zauber_all = $stmt->get_result()){
			while($zauber = $zauber_all->fetch_array(MYSQLI_NUM)){
				$alle_aktiven_zauber[$counter] = new KampfZauber($zauber, select_kampf_effekte_spezial($zauber[12]));
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nAlle ".$counter." aktiven Zauber auf ".$kt->name." wurden geladen.<br />\n";
		return $alle_aktiven_zauber;
	} else {
		echo "<br />\nQuerryfehler in get_zauber_aktiv()<br />\n";
		return false;
	}
}




#***************************************************************************************************************
#************************************************ VERSCHIEDENES ************************************************
#***************************************************************************************************************

#----------------------------------- SELECT rolle.name (einzel) -----------------------------------
# 	-> rolle.id (int)
# 	<- rolle.name (str)

function get_rolle($rolle_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	rolle.titel
			FROM 	rolle
			WHERE 	rolle.id = ?"))
	{
		$stmt->bind_param('d', $rolle_id);
		$stmt->execute();
		if ($debug) echo "<br />\nRollenname für: [rolle_id=" . $rolle_id . "] geladen.<br />\n";
		$rolle_name = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
		if ($rolle_name) return $rolle_name;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_rolle()<br />\n";
		return false;
	}
}















?>