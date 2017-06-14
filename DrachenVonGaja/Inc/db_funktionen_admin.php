<?php

/* Funktionsübersicht

get_spieler()

*/


#***************************************************************************************************************
#************************************************* ALLGEMEINES *************************************************
#***************************************************************************************************************

#----------------------------------- Tabellennamen -----------------------------------
# 	-> 
#	<- Tabellennamen (irgendwie)

function get_tabellen()
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SHOW TABLES"))
	{
		$stmt->execute();
		if ($debug) echo "<br />\nTabellennamen sollen angezeigt werden.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_tabellen()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}

#----------------------------------- Spaltennamen -----------------------------------
# 	-> Tabellenname (str)
#	<- Spaltennamen (irgendwie)

function get_spalten($tabellenname)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($debug) echo $tabellenname . "<br />\n";
	$querry = "SHOW COLUMNS FROM " . $tabellenname . "";
	
	if ($stmt = $connect_db_dvg->prepare($querry))
	{
		$stmt->execute();
		if ($debug) echo "<br />\nSpaltennamen sollen angezeigt werden.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spalten()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}



#***************************************************************************************************************
#*************************************************** ACCOUNT ***************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#*************************************************** BILDER ****************************************************
#***************************************************************************************************************


#----------------------------------------------- SELECT bilder.pfad (id) ----------------------------------------------
# 	-> bilder.id (int)
#	<- bild.pfad(str)

function get_bild_zu_id($id)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad
			FROM 	bilder
			WHERE 	bilder.id = ?")){
		$stmt->bind_param('d', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		close_connection($connect_db_dvg);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_id()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------------------- Bildernamen ----------------------------------------------
#	-> pfad (str) - nur relevante Bilder
#	Array mit Bilder-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_bilder_titel($pfad)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	$pfad = $pfad."%";
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, titel
			FROM 	bilder
			WHERE	pfad like ?
				OR id = 1
			ORDER BY titel")){
		$stmt->bind_param('s', $pfad);
		$stmt->execute();
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_bilder_titel()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------------------- Check bilder.pfad (pfad) ----------------------------------------------
# 	-> bilder.pfad (str)
#	<- true/false

function check_bild_vorhanden($pfad)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad
			FROM 	bilder
			WHERE 	bilder.pfad = ?")){
		$stmt->bind_param('s', $pfad);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		close_connection($connect_db_dvg);
		if($row[0]) return true;
		else return false;
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_titel()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------------------- INSESRT bilder (multi) ----------------------------------------------
# 	-> Multidimensionales Array
#		 [0][titel] bilder.titel (str)
#		 [0][beschreibung] bilder.beschreibung (str)
#		 [0][pfad] bilder.pfad (str)
#	<- Anzahl geschriebener Datensätze

function insert_bilder($bilderdaten)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	$anz_gesamt = 0;
	if($bilderdaten)
		$anz_gesamt = max(array_keys($bilderdaten)) + 1;
	$anz_ok = 0;
	foreach($bilderdaten as $bild)
	{
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO bilder (
				titel,
				pfad) 
			VALUES (?, ?)"))
		{
			$stmt->bind_param('ss', $bild['titel'], $bild['pfad']);
			$stmt->execute();
			$anz_ok = $anz_ok + 1;
		} else {
			echo "<br />\nQuerryfehler in get_bild_zu_titel()<br />\n";
			close_connection($connect_db_dvg);
			return false;
		}
	}
	close_connection($connect_db_dvg);
	return $anz_ok;
}


#*****************************************************************************************************************
#*************************************************** ELEMENTE ****************************************************
#*****************************************************************************************************************

#----------------------------------------------- Elementenamen ----------------------------------------------
#	Array mit Bilder-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_elemente_titel()
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, titel
			FROM 	element
			ORDER BY titel")){
		$stmt->execute();
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_elemente_titel()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** GATTUNG ***************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#*************************************************** GEBIET ****************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#***************************************************** NPC *****************************************************
#***************************************************************************************************************

#----------------------------------- SELECT npc.* (einzel) -----------------------------------
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
#	<- [14] beschreibung
#	<- [15] typ

function get_npc_by_id($npc_id)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	npc
			WHERE 	npc.id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Daten für: [npc_id=" . $npc_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npc_by_id()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------- SELECT npc.* (auswahl) -----------------------------------
#	-> npc.titel (str)
#	-> npc.familie (str)
#	-> npc.beschreibung (str)
#	-> npc.typ (str)
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
#	<- [14] beschreibung
#	<- [15] typ

function suche_npcs($titel, $familie, $beschreibung, $typ)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	npc
			WHERE 	npc.titel like ?
				and npc.familie like ?
				and npc.beschreibung like ?
				and npc.typ like ?"))
	{
		$stmt->bind_param('ssss', $titel, $familie, $beschreibung, $typ);
		$stmt->execute();
		if ($debug) echo "<br />\nNPCs geladen.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in suche_npcs()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------------------- Typennamen ----------------------------------------------
#	<- titel (str)

function get_typen_titel()
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT	typ
			FROM 	npc
			ORDER BY typ")){
		$stmt->execute();
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_typen_titel()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------- UPDATE npc -----------------------------------
#	Array mit npc-Daten [Position]
#	-> [0] id
#	-> [1] bilder_id
#	-> [2] element_id
#	-> [3] titel
#	-> [4] familie
#	-> [5] staerke
#	-> [6] intelligenz
#	-> [7] magie
#	-> [8] element_feuer
#	-> [9] element_wasser
#	-> [10] element_erde
#	-> [11] element_luft
#	-> [12] gesundheit
#	-> [13] energie
#	-> [14] beschreibung
#	-> [15] typ
#	<- true/false

function updateNPC($npc_daten)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE npc
			SET bilder_id = ?,
				element_id = ?,
				titel = ?,
				familie = ?,
				staerke = ?,
				intelligenz = ?,
				magie = ?,
				element_feuer = ?,
				element_wasser = ?,
				element_erde = ?,
				element_luft = ?,
				gesundheit = ?,
				energie = ?,
				beschreibung = ?,
				typ = ?				
			WHERE id = ?")){
		$stmt->bind_param('ssssssssssssssss', 
			$npc_daten["npc_bild"], 
			$npc_daten["npc_element"], 
			$npc_daten["npc_titel"], 
			$npc_daten["npc_familie"], 
			$npc_daten["npc_staerke"], 
			$npc_daten["npc_intelligenz"], 
			$npc_daten["npc_magie"], 
			$npc_daten["npc_feuer"], 
			$npc_daten["npc_wasser"], 
			$npc_daten["npc_erde"], 
			$npc_daten["npc_luft"], 
			$npc_daten["npc_gesundheit"], 
			$npc_daten["npc_energie"], 
			$npc_daten["npc_beschreibung"], 
			$npc_daten["npc_typ"], 
			$npc_daten["npc_id"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		close_connection($connect_db_dvg);
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo "<br>\nQuerryfehler in updateNPC()<br>\n";
		close_connection($connect_db_dvg);
		return false;
	}
}


#----------------------------------- INSERT npc -----------------------------------
#	Array mit npc-Daten [Position]
#	-> [0] id
#	-> [1] bilder_id
#	-> [2] element_id
#	-> [3] titel
#	-> [4] familie
#	-> [5] staerke
#	-> [6] intelligenz
#	-> [7] magie
#	-> [8] element_feuer
#	-> [9] element_wasser
#	-> [10] element_erde
#	-> [11] element_luft
#	-> [12] gesundheit
#	-> [13] energie
#	-> [14] beschreibung
#	-> [15] typ
#	<- true/false

function insertNPC($npc_daten)
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO npc (
				bilder_id,
				element_id,
				titel,
				familie,
				staerke,
				intelligenz,
				magie,
				element_feuer,
				element_wasser,
				element_erde,
				element_luft,
				gesundheit,
				energie,
				beschreibung,
				typ)				
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param('sssssssssssssss', 
			$npc_daten["npc_bild"], 
			$npc_daten["npc_element"], 
			$npc_daten["npc_titel"], 
			$npc_daten["npc_familie"], 
			$npc_daten["npc_staerke"], 
			$npc_daten["npc_intelligenz"], 
			$npc_daten["npc_magie"], 
			$npc_daten["npc_feuer"], 
			$npc_daten["npc_wasser"], 
			$npc_daten["npc_erde"], 
			$npc_daten["npc_luft"], 
			$npc_daten["npc_gesundheit"], 
			$npc_daten["npc_energie"], 
			$npc_daten["npc_beschreibung"], 
			$npc_daten["npc_typ"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		close_connection($connect_db_dvg);
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo $connect_db_dvg->error;
		echo "<br />\nQuerryfehler in insertNPC()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}

#***************************************************************************************************************
#*************************************************** SPIELER ***************************************************
#***************************************************************************************************************


#----------------------------------- SELECT Spieler.* -----------------------------------
# 	-> 
#	<- Array mit Spielerdaten

function get_spieler_all()
{
	global $debug;
	$connect_db_dvg = open_connection();
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	spieler.*
			FROM 	spieler"))
	{
		#$stmt->bind_param('s', $login);
		$stmt->execute();
		if ($debug) echo "<br />\nSpieler wurden geladen.<br />\n";
		$result = $stmt->get_result();
		close_connection($connect_db_dvg);
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spieler_all()<br />\n";
		close_connection($connect_db_dvg);
		return false;
	}
}

?>