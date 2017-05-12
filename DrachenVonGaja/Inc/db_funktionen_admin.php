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

#----------------------------------------------- Check bilder.pfad (pfad) ----------------------------------------------
# 	-> bilder.pfad (str)
#	true/false

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
				beschreibung, 
				pfad) 
			VALUES (?, ?, ?)"))
		{
			$stmt->bind_param('sss', $bild['titel'], $bild['beschreibung'], $bild['pfad']);
			$stmt->execute();
			$anz_ok = $anz_ok + 1;
		} else {
			echo "<br />\nQuerryfehler in get_bild_zu_titel()<br />\n";
			close_connection($connect_db_dvg);
			return false;
		}
	}
	close_connection($connect_db_dvg);
	return $anz_ok."/".$anz_gesamt." Bildern erfolgreich eingefügt";
}

/*

Ein seltsam anmutendes Gewächs. Recht groß, orange und mit ziemlich harter Schale. Was euch wohl im Inneren erwartet?

*/
#***************************************************************************************************************
#*************************************************** GATTUNG ***************************************************
#***************************************************************************************************************



#***************************************************************************************************************
#*************************************************** GEBIET ****************************************************
#***************************************************************************************************************



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