<?php

#***************************************************************************************************************
#************************************************* ALLGEMEINES *************************************************
#***************************************************************************************************************

#----------------------------------- Tabellennamen -----------------------------------
# 	-> 
#	<- Tabellennamen (irgendwie)

function get_tabellen()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SHOW TABLES"))
	{
		$stmt->execute();
		if ($debug) echo "<br />\nTabellennamen sollen angezeigt werden.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_tabellen()<br />\n";
		return false;
	}
}

#----------------------------------- Spaltennamen -----------------------------------
# 	-> Tabellenname (str)
#	<- Spaltennamen (irgendwie)

function get_spalten($tabellenname)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($debug) echo $tabellenname . "<br />\n";
	$querry = "SHOW COLUMNS FROM " . $tabellenname . "";
	
	if ($stmt = $connect_db_dvg->prepare($querry))
	{
		$stmt->execute();
		if ($debug) echo "<br />\nSpaltennamen sollen angezeigt werden.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spalten()<br />\n";
		return false;
	}
}



#***************************************************************************************************************
#*************************************************** ACCOUNT ***************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#************************************************* BEDINGUNGEN *************************************************
#***************************************************************************************************************

#----------------------------------- SELECT bedingungen (auswahl) -----------------------------------
#	-> bedgingung_knoten.titel (str)
#	-> bedgingung_knoten.beschreibung (str)
#	-> bedingung_link.tabelle (str)
#	-> bedgingung_knoten.id (str) (ja/nein)
#	<- alle_bedingungen (array [Bedingung(obj)])

function suche_bedingungen($titel, $beschreibung, $zuordnung, $teilknoten){
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT bknot.*,
				group_concat(distinct bteil.id),
				group_concat(distinct bknot2.id),
				COUNT(distinct bteil.id) + COUNT(distinct bknot2.id),
				GROUP_CONCAT(distinct concat(blink.id, '#', blink.tabelle, '#', blink.id)) AS zuordnung
			FROM bedingung_knoten bknot
				LEFT JOIN bedingung_teil bteil ON bteil.bedingung_knoten_id = bknot.id
				LEFT JOIN bedingung_knoten bknot2 ON bknot2.bedingung_knoten_id = bknot.id
				LEFT JOIN bedingung_link blink ON blink.bedingung_knoten_id = bknot.id
			GROUP BY bknot.id
			HAVING ((bknot.titel IS NULL AND '' LIKE ?) OR bknot.titel LIKE ?)
				AND ((bknot.beschreibung IS NULL AND '' LIKE ?) OR bknot.beschreibung LIKE ?)
				AND ((zuordnung IS NULL AND '' LIKE ?) OR zuordnung LIKE ?)
				AND ((bknot.bedingung_knoten_id IS NULL AND 'nein' = ?)
					OR (bknot.bedingung_knoten_id IS NOT NULL AND 'ja' = ?)
					OR ('' = ?))
			ORDER BY bknot.titel;")){
		$stmt->bind_param('sssssssss', $titel, $titel, $beschreibung, $beschreibung, $zuordnung, $zuordnung, $teilknoten, $teilknoten, $teilknoten);
		$stmt->execute();
		$counter = 0;
		if ($bedingung_all = $stmt->get_result()){
			while($bedingung_data = $bedingung_all->fetch_array(MYSQLI_NUM)){
				$bedingung = new Bedingung($bedingung_data);
				if($bedingung){
					$alle_bedingungen[$counter] = $bedingung;
					$counter = $counter + 1;
				}
			}
		}
		if ($debug) echo "<br />\Bedingungen geladen.<br />\n";
		if (isset($alle_bedingungen[0])) return $alle_bedingungen;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in suche_bedingungen()<br />\n";
		return false;
	}
}

#----------------------------------- SELECT bedingung (einzel) -----------------------------------
#	-> bedgingung_knoten.id (int)
#	<- Bedingung (obj)

function get_bedingung_by_id($bedingung_id, $ebene=0){
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT bknot.*,
				group_concat(distinct bteil.id),
				group_concat(distinct bknot2.id),
				COUNT(distinct bteil.id) + COUNT(distinct bknot2.id),
				GROUP_CONCAT(distinct concat(blink.id, '#', blink.tabelle, '#', blink.id)) AS zuordnung
			FROM bedingung_knoten bknot
				LEFT JOIN bedingung_teil bteil ON bteil.bedingung_knoten_id = bknot.id
				LEFT JOIN bedingung_knoten bknot2 ON bknot2.bedingung_knoten_id = bknot.id
				LEFT JOIN bedingung_link blink ON blink.bedingung_knoten_id = bknot.id
			WHERE bknot.id = ?
			GROUP BY bknot.id;")){
		$stmt->bind_param('d', $bedingung_id);
		$stmt->execute();
		if ($bedingung_data = $stmt->get_result()->fetch_array(MYSQLI_NUM)){
			$bedingung = new Bedingung($bedingung_data, $ebene);
		}
		if ($debug) echo "<br />\Bedingung geladen.<br />\n";
		if (isset($bedingung)) return $bedingung;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_bedingung_by_id()<br />\n";
		return false;
	}
}


#----------------------------------------------- Kombinationen für Selects ----------------------------------------------
#	Array mit Kobinationen für Selects
#	<- ["id"] id
#	<- ["text"] titel - topic

function get_kombis(){
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT id, concat(titel, ' - ', topic) as kombi
			FROM bedingung_kombi
			ORDER BY kombi")){
		$stmt->execute();
		$count = 0;
		if ($kombi_all = $stmt->get_result()){
			while($kombi_data = $kombi_all->fetch_array(MYSQLI_NUM)){
				$kombis[$count] = array("id"=>$kombi_data[0], "text"=>$kombi_data[1]);
				$count = $count + 1;
			}
		}
		if (isset($kombis[0])) return $kombis;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_kombis()<br />\n";
		return false;
	}
}


#----------------------------------------------- Operatoren für Selects ----------------------------------------------
#	Array mit Operatoren für Selects
#	<- ["id"] id
#	<- ["text"] symbol

function get_operatoren(){
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT id, symbol
			FROM bedingung_operator
			ORDER BY id")){
		$stmt->execute();
		$count = 0;
		if ($operator_all = $stmt->get_result()){
			while($operator_data = $operator_all->fetch_array(MYSQLI_NUM)){
				$operatoren[$count] = array("id"=>$operator_data[0], "text"=>$operator_data[1]);
				$count = $count + 1;
			}
		}
		if (isset($operatoren[0])) return $operatoren;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_operatoren()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** BILDER ****************************************************
#***************************************************************************************************************


#----------------------------------------------- Bildernamen ----------------------------------------------
#	-> pfad (str) - nur relevante Bilder
#	Array mit Bilder-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_bilder_titel($pfad)
{
	global $debug;
	global $connect_db_dvg;
	
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
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_bilder_titel()<br />\n";
		return false;
	}
}


#----------------------------------------------- Check bilder.pfad (pfad) ----------------------------------------------
# 	-> bilder.pfad (str)
#	<- true/false

function check_bild_vorhanden($pfad)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	pfad
			FROM 	bilder
			WHERE 	bilder.pfad = ?")){
		$stmt->bind_param('s', $pfad);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if($row[0]) return true;
		else return false;
	} else {
		echo "<br />\nQuerryfehler in get_bild_zu_titel()<br />\n";
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
	global $connect_db_dvg;
	
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
			return false;
		}
	}
	return $anz_ok;
}


#*****************************************************************************************************************
#*************************************************** ELEMENTE ****************************************************
#*****************************************************************************************************************

#----------------------------------------------- Elementenamen ----------------------------------------------
#	Array mit Element-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_elemente_titel()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, titel
			FROM 	element
			ORDER BY titel")){
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_elemente_titel()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#*************************************************** GATTUNG ***************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#*************************************************** GEBIET ****************************************************
#***************************************************************************************************************

#----------------------------------------------- Gebietsnamen ----------------------------------------------
#	Array mit Gebiet-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_gebiete_titel()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, titel, titel_intern
			FROM 	gebiet
			ORDER BY titel")){
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_gebiet_titel()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#**************************************************** ITEM *****************************************************
#***************************************************************************************************************

#----------------------------------- SELECT items.* (einzel) -----------------------------------
#	-> items.id (int)
#	<- Item (obj)

function get_item_by_id($item_id)
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
				0,
				slots.id,
				slots.titel,
				0,
				slots.max
			FROM 	items
				LEFT JOIN slots ON slots.id = items.slot_id
			WHERE 	items.id = ?"))
	{
		$stmt->bind_param('d', $item_id);
		$stmt->execute();
		if ($item_row = $stmt->get_result()){
			$item_array = $item_row->fetch_array(MYSQLI_NUM);
			$item_data = array_slice($item_array, 0, 22);
			$slot_data = array_slice($item_array, 22);
			$item = new Item("Ausrüstung", $item_data, $slot_data);
		}
		if ($debug) echo "<br />\nItem-Daten für: [item_id=" . $item_id . "] geladen.<br />\n";
		if (isset($item)) return $item;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_item_by_id()<br />\n";
		return false;
	}
}

#-------------------------------------------------- Itemnamen --------------------------------------------------
#	Array mit Item-Daten [Position]
#	<- [0] id
#	<- [1] titel

function get_items_titel()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, titel
			FROM 	items
			ORDER BY titel")){
		$stmt->execute();
		$count = 0;
		if ($items_all = $stmt->get_result()){
			while($items_data = $items_all->fetch_array(MYSQLI_NUM)){
				$items[$count] = array("id"=>$items_data[0], "text"=>$items_data[1]);
				$count = $count + 1;
			}
		}
		if (isset($items[0])) return $items;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_items_titel()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT items.* (auswahl) -----------------------------------
#	-> items.titel (str)
#	-> items.beschreibung (str)
#	-> slots.titel (str)
#	-> items.essbar (str)
#	-> items.ausruestbar (str)
#	-> items.verarbeitbar (str)
#	<- alle_items (array [Item])

function suche_items($titel, $beschreibung, $slot, $essbar, $ausruestbar, $verarbeitbar)
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
				0,
				slots.id,
				slots.titel,
				0,
				slots.max
			FROM 	items
				LEFT JOIN slots ON slots.id = items.slot_id
			WHERE 	items.titel like ?
				and items.beschreibung like ?
				and slots.titel like ?
				and items.essbar like ?
				and items.ausruestbar like ?
				and items.verarbeitbar like ?
			ORDER BY items.titel"))
	{
		$stmt->bind_param('ssssss', $titel, $beschreibung, $slot, $essbar, $ausruestbar, $verarbeitbar);
		$stmt->execute();
		$counter = 0;
		if ($items_all = $stmt->get_result()){
			while($item = $items_all->fetch_array(MYSQLI_NUM)){
				$item_data = array_slice($item, 0, 22);
				$slot_data = array_slice($item, 22);
				$alle_items[$counter] = new Item("Ausrüstung", $item_data, $slot_data);
				$counter = $counter + 1;
			}
		}
		if ($debug) echo "<br />\nItems geladen.<br />\n";
		if (isset($alle_items[0])) return $alle_items;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in suche_items()<br />\n";
		return false;
	}
}



#----------------------------------------------- Typennamen ----------------------------------------------
#	<- titel (str)

function get_slots_titel()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT id, titel
			FROM 	slots
			ORDER BY titel")){
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_slots_titel()<br />\n";
		return false;
	}
}


#----------------------------------- UPDATE item -----------------------------------
#	-> Item (obj)
#	<- true/false

function updateItem($item)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE items
			SET bilder_id = ?,
				titel = ?,
				beschreibung = ?,
				essbar = ?,
				ausruestbar = ?,
				verarbeitbar = ?,
				gesundheit = ?,
				energie = ?,
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
				prozent = ?,
				slot_id = ?
			WHERE id = ?")){
		$stmt->bind_param('dssddddddddddddddddddd', 
			$item->bilder_id,
			$item->name,
			$item->beschreibung,
			$item->essbar,
			$item->ausruestbar,
			$item->verarbeitbar,
			$item->gesundheit,
			$item->energie,
			$item->zauberpunkte,
			$item->staerke,
			$item->intelligenz,
			$item->magie,
			$item->element_feuer,
			$item->element_wasser,
			$item->element_erde,
			$item->element_luft,
			$item->initiative,
			$item->abwehr,
			$item->ausweichen,
			$item->prozent,
			$item->slot->id,
			$item->id);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo "<br>\nQuerryfehler in updateItem()<br>\n";
		return false;
	}
}


#----------------------------------- SELECT slot -----------------------------------
#	-> slot.id (int)
#	<- Slot (obj)

function get_slot($slot_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				slots.id,
				slots.titel,
				0,
				slots.max
			FROM slots
			WHERE id = ?")){
		$stmt->bind_param('d', $slot_id);
		$stmt->execute();
		if ($slot_data = $stmt->get_result()){
			$slot = new Slot($slot_data->fetch_array(MYSQLI_NUM));
		}
		if (isset($slot)) return $slot;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_slot()<br />\n";
		return false;
	}
}


#----------------------------------- INSERT item -----------------------------------
#	Item (obj)
#	<- true/false

function insertItem($item)
{
	global $debug;
	global $connect_db_dvg;
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO items (
				bilder_id,
				titel,
				beschreibung,
				essbar,
				ausruestbar,
				verarbeitbar,
				gesundheit,
				energie,
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
				prozent,
				slot_id)				
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param('dssdddddddddddddddddd', 
			$item->bilder_id,
			$item->name,
			$item->beschreibung,
			$item->essbar,
			$item->ausruestbar,
			$item->verarbeitbar,
			$item->gesundheit,
			$item->energie,
			$item->zauberpunkte,
			$item->staerke,
			$item->intelligenz,
			$item->magie,
			$item->element_feuer,
			$item->element_wasser,
			$item->element_erde,
			$item->element_luft,
			$item->initiative,
			$item->abwehr,
			$item->ausweichen,
			$item->prozent,
			$item->slot->id);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo $connect_db_dvg->error;
		echo "<br />\nQuerryfehler in insertItem()<br />\n";
		return false;
	}
}


#***************************************************************************************************************
#***************************************************** KI ******************************************************
#***************************************************************************************************************

#----------------------------------------------- KI-Namen ----------------------------------------------
#	Array mit KI-Daten [Position]
#	<- [0] id
#	<- [1] name

function get_ki_namen()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id, name
			FROM 	ki
			ORDER BY name")){
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_ki_namen()<br />\n";
		return false;
	}
}


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
#	<- [14] zauberpunkte
#	<- [15] initiative
#	<- [16] abwehr
#	<- [17] ausweichen
#	<- [18] beschreibung
#	<- [19] typ
#	<- [20] ki_id

function get_npc_by_id($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	npc
			WHERE 	npc.id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Daten für: [npc_id=" . $npc_id . "] geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npc_by_id()<br />\n";
		return false;
	}
}



#----------------------------------- SELECT npc.id (einzel) -----------------------------------
# 	-> npc.titel (str)
#	<- npc.id (int)

function get_npc_id_by_titel($npc_titel)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id
			FROM 	npc
			WHERE 	npc.titel = ?"))
	{
		$stmt->bind_param('s', $npc_titel);
		$stmt->execute();
		if ($debug) echo "<br />\nNPC-Id für: [npc_titel=" . $npc_titel . "] geladen.<br />\n";
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	} else {
		echo "<br />\nQuerryfehler in get_npc_by_id()<br />\n";
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
#	<- [14] zauberpunkte
#	<- [15] initiative
#	<- [16] abwehr
#	<- [17] ausweichen
#	<- [18] beschreibung
#	<- [19] typ
#	<- [20] ki_id

function suche_npcs($titel, $familie, $beschreibung, $typ)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	*
			FROM 	npc
			WHERE 	npc.titel like ?
				and npc.familie like ?
				and npc.beschreibung like ?
				and npc.typ like ?
			ORDER BY titel"))
	{
		$stmt->bind_param('ssss', $titel, $familie, $beschreibung, $typ);
		$stmt->execute();
		if ($debug) echo "<br />\nNPCs geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in suche_npcs()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT npc.* (gebiete) -----------------------------------
# 	-> npc_id (int)
#	<- gebiet_id (int)
#	<- wahrscheinlichkeit (int)

function get_npc_gebiete($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	gebiet_id,
					wahrscheinlichkeit
			FROM 	npc_gebiet
			WHERE 	npc_id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npc_gebiete()<br />\n";
		return false;
	}
}


#----------------------------------- SELECT npc.* (items) -----------------------------------
# 	-> npc_id (int)
#	<- item_id (int)
#	<- wahrscheinlichkeit (int)
#	<- anzahl_min (int)
#	<- anzahl_max (int)

function get_npc_items($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	items_id,
					wahrscheinlichkeit,
					anzahl_min,
					anzahl_max
			FROM 	npc_items
			WHERE 	npc_id = ?"))
	{
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_npc_items()<br />\n";
		return false;
	}
}


#----------------------------------- get npc_gebiet.id -----------------------------------
# 	-> npc_id (int)
#	-> gebiet_id (int)
#	<- npc_gebiet.id (wenn vorhanden)

function get_npc_gebiet_id($npc_id, $gebiet_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id
			FROM 	npc_gebiet
			WHERE 	npc_id = ?
				and gebiet_id = ?"))
	{
		$stmt->bind_param('dd', $npc_id, $gebiet_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if($row) return $row[0];
		else return null;
	} else {
		echo "<br />\nQuerryfehler in get_npc_gebiet_id()<br />\n";
		return false;
	}
}


#----------------------------------- get npc_items.id -----------------------------------
# 	-> npc_id (int)
#	-> items_id (int)
#	<- npc_items.id (wenn vorhanden)

function get_npc_items_id($npc_id, $items_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	id
			FROM 	npc_items
			WHERE 	npc_id = ?
				and items_id = ?"))
	{
		$stmt->bind_param('dd', $npc_id, $items_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array(MYSQLI_NUM);
		if($row) return $row[0];
		else return null;
	} else {
		echo "<br />\nQuerryfehler in get_npc_items_id()<br />\n";
		return false;
	}
}


#----------------------------------------------- Typennamen ----------------------------------------------
#	<- titel (str)

function get_npc_typen_titel()
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT DISTINCT	typ
			FROM 	npc
			ORDER BY typ")){
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_typen_titel()<br />\n";
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
#	-> [14] zauberpunkte
#	-> [15] initiative
#	-> [16] abwehr
#	-> [17] ausweichen
#	-> [18] beschreibung
#	-> [19] typ
#	<- true/false

function updateNPC($npc_daten)
{
	global $debug;
	global $connect_db_dvg;
	
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
				zauberpunkte = ?,
				initiative = ?,
				abwehr = ?,
				ausweichen = ?,
				beschreibung = ?,
				typ = ?,
				ki_id = ?
			WHERE id = ?")){
		$stmt->bind_param('sssssssssssssssssssss', 
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
			$npc_daten["npc_zauberpunkte"],
			$npc_daten["npc_initiative"],
			$npc_daten["npc_abwehr"],
			$npc_daten["npc_ausweichen"],			
			$npc_daten["npc_beschreibung"], 
			$npc_daten["npc_typ"],
			$npc_daten["npc_ki"],
			$npc_daten["npc_id"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo "<br>\nQuerryfehler in updateNPC()<br>\n";
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
#	-> [14] zauberpunkte
#	-> [15] initiative
#	-> [16] abwehr
#	-> [17] ausweichen
#	-> [18] beschreibung
#	-> [19] typ
#	-> [20] ki_id
#	<- true/false

function insertNPC($npc_daten)
{
	global $debug;
	global $connect_db_dvg;
	
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
				zauberpunkte,
				initiative,
				abwehr,
				ausweichen,
				beschreibung,
				typ,
				ki_id)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param('ssssssssssssssssssss', 
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
			$npc_daten["npc_zauberpunkte"],
			$npc_daten["npc_initiative"],
			$npc_daten["npc_abwehr"],
			$npc_daten["npc_ausweichen"],
			$npc_daten["npc_beschreibung"], 
			$npc_daten["npc_typ"],
			$npc_daten["npc_ki"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if ($npc_daten["npc_typ"] == "angreifbar"){
			$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM npc");
			$stmt->execute();
			$npc_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			insertNPCzauberStandard($npc_id);
		}
		if($result == 1)
			return true;
		else
			return false;
	} else {
		echo $connect_db_dvg->error;
		echo "<br />\nQuerryfehler in insertNPC()<br />\n";
		return false;
	}
}

#----------------------------------- UPDATE npc_gebiet -----------------------------------
#	Array mit npc_gebiet-Daten [Position]
#	-> [0] id
#	-> [1] npc_id
#	-> [2] gebiet_id
#	-> [3] wkt
#	<- true/false

function updateNPCgebiet($npc_gebiet_daten)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE npc_gebiet
			SET npc_id = ?,
				gebiet_id = ?,
				wahrscheinlichkeit = ?				
			WHERE id = ?")){
		$stmt->bind_param('dddd', 
			$npc_gebiet_daten["npc_id"], 
			$npc_gebiet_daten["gebiet_id"], 
			$npc_gebiet_daten["wkt"],
			$npc_gebiet_daten["id"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return 1;
		else
			return 0;
	} else {
		echo "<br>\nQuerryfehler in updateNPCgebiet()<br>\n";
		return false;
	}
}


#----------------------------------- INSERT npc_gebiet -----------------------------------
#	Array mit npc_gebiet-Daten [Position]
#	-> [0] id (ist null)
#	-> [1] npc_id
#	-> [2] gebiet_id
#	-> [3] wkt
#	<- true/false

function insertNPCgebiet($npc_gebiet_daten)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO npc_gebiet (
				npc_id,
				gebiet_id,
				wahrscheinlichkeit)				
			VALUES (?, ?, ?)")){
		$stmt->bind_param('ddd', 
			$npc_gebiet_daten["npc_id"], 
			$npc_gebiet_daten["gebiet_id"], 
			$npc_gebiet_daten["wkt"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return 1;
		else
			return 0;
	} else {
		echo "<br>\nQuerryfehler in insertNPCgebiet()<br>\n";
		return false;
	}
}


#----------------------------------- DELETE npc_gebiet -----------------------------------
#	-> npc_id
#	<- Anzahl gelöschter Datensätze

function deleteNPCgebiete($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			DELETE FROM npc_gebiet
			WHERE npc_id = ?")){
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		$result = $stmt->affected_rows;
		return $result;
	} else {
		echo "<br>\nQuerryfehler in deleteNPCgebiete()<br>\n";
		return false;
	}
}


#----------------------------------- UPDATE npc_items -----------------------------------
#	Array mit npc_item-Daten [Position]
#	-> [0] id
#	-> [1] npc_id
#	-> [2] items_id
#	-> [3] wkt
#	-> [4] min
#	-> [5] max
#	<- true/false

function updateNPCitem($npc_item_daten)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			UPDATE npc_items
			SET npc_id = ?,
				items_id = ?,
				wahrscheinlichkeit = ?,
				anzahl_min = ?,
				anzahl_max = ?
			WHERE id = ?")){
		$stmt->bind_param('dddddd', 
			$npc_item_daten["npc_id"], 
			$npc_item_daten["items_id"], 
			$npc_item_daten["wkt"],
			$npc_item_daten["min"],
			$npc_item_daten["max"],
			$npc_item_daten["id"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return 1;
		else
			return 0;
	} else {
		echo "<br>\nQuerryfehler in updateNPCitem()<br>\n";
		return false;
	}
}


#----------------------------------- INSERT npc_items -----------------------------------
#	Array mit npc_item-Daten [Position]
#	-> [0] id (ist null)
#	-> [1] npc_id
#	-> [2] gebiet_id
#	-> [3] wkt
#	-> [4] min
#	-> [5] max
#	<- true/false

function insertNPCitem($npc_item_daten)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO npc_items (
				npc_id,
				items_id,
				wahrscheinlichkeit,
				anzahl_min,
				anzahl_max)				
			VALUES (?, ?, ?, ?, ?)")){
		$stmt->bind_param('ddddd', 
			$npc_item_daten["npc_id"], 
			$npc_item_daten["items_id"], 
			$npc_item_daten["wkt"],
			$npc_item_daten["min"],
			$npc_item_daten["max"]);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return 1;
		else
			return 0;
	} else {
		echo "<br>\nQuerryfehler in insertNPCitem()<br>\n";
		return false;
	}
}


#----------------------------------- DELETE npc_items -----------------------------------
#	-> npc_id
#	<- Anzahl gelöschter Datensätze

function deleteNPCitems($npc_id)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			DELETE FROM npc_items
			WHERE npc_id = ?")){
		$stmt->bind_param('d', $npc_id);
		$stmt->execute();
		$result = $stmt->affected_rows;
		return $result;
	} else {
		echo "<br>\nQuerryfehler in deleteNPCitems()<br>\n";
		return false;
	}
}


#----------------------------------- INSERT zauber_npc -----------------------------------
#	-> npc_id
#	-> zauber_id (default = 77 -> Biss)
#	-> wkt (deafult = 100)
#	<- true/false

function insertNPCzauberStandard($npc_id, $zauber_id=77, $wkt=100)
{
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO zauber_npc (
				npc_id,
				zauber_id,
				wahrscheinlichkeit)
			VALUES (?, ?, ?)")){
		$stmt->bind_param('ddd', $npc_id, $zauber_id, $wkt);
		$stmt->execute();
		$result = $stmt->affected_rows;
		if($result == 1)
			return 1;
		else
			return 0;
	} else {
		echo "<br>\nQuerryfehler in insertNPCzauberStandard()<br>\n";
		return false;
	}
}


#----------------------------------------------- NPC für Select ----------------------------------------------
#	Array mit NPC für Selects
#	<- ["id"] id
#	<- ["text"] symbol

function get_npcs_titel(){
	global $debug;
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT id, titel
			FROM npc
			ORDER BY titel")){
		$stmt->execute();
		$count = 0;
		if ($npc_all = $stmt->get_result()){
			while($npc_data = $npc_all->fetch_array(MYSQLI_NUM)){
				$npcs[$count] = array("id"=>$npc_data[0], "text"=>$npc_data[1]);
				$count = $count + 1;
			}
		}
		if (isset($npcs[0])) return $npcs;
			else return false;
	} else {
		echo "<br />\nQuerryfehler in get_npcs_titel()<br />\n";
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
	global $connect_db_dvg;
	
	if ($stmt = $connect_db_dvg->prepare("
			SELECT 	spieler.*
			FROM 	spieler"))
	{
		#$stmt->bind_param('s', $login);
		$stmt->execute();
		if ($debug) echo "<br />\nSpieler wurden geladen.<br />\n";
		$result = $stmt->get_result();
		return $result;
	} else {
		echo "<br />\nQuerryfehler in get_spieler_all()<br />\n";
		return false;
	}
}

?>