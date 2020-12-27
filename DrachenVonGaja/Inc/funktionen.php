<?php

############################
### ALLGEMEINES / SYSTEM ###
############################

global $konfig;

# Zeitzone setzen
date_default_timezone_set("Europe/Berlin");


# Zeitstempel erzeugen
function timestamp(){
	$time_unix = time();
	$tstamp = date("Y-m-d",$time_unix) . " " . date("H:i:s",$time_unix);
	return $tstamp;
}


# Zeitumrechnung
function time_to_timestamp($time_unix){
	$tstamp = date("Y-m-d",$time_unix) . " " . date("H:i:s",$time_unix);
	return $tstamp;
}


# Wahrscheinlichkeitsberechnung
function check_wkt($wkt){
	$zufall = mt_rand(1,100);
	return ($zufall <= $wkt);
}


# Wahrscheinlichkeitsberechnung zwischen 0,001 und 100,000
function check_wkt_spezial($wkt){
	$zufall = mt_rand(1,100000);
	return ($zufall <= $wkt * 1000);
}


# Schneidet analog zu floor alle Nachkommastellen bis zur angegebenen Stelle ab
function floor_x($zahl,$nachkommastellen=3){
	return floor($zahl*pow(10,$nachkommastellen))/pow(10,$nachkommastellen);
}


function ausgabeProzent($zahl, $nachkommastellen=2) {
	$prozent = sprintf("%.3f", $zahl * 100);
	$prozent = explode(".", $prozent, 2);
	$shortCount = strlen($prozent[1]) - $nachkommastellen;
	return $prozent[0].",".substr($prozent[1], 0, (($shortCount <= 0) ? strlen($prozent[1]) : -$shortCount))." %";
}


# Funktion zum Einlesen aller neuen oder verschobenen Bilder inklusive ihrer Pfade in die Datenbank
function scanneNeueBilder($ordner){
	global $endungen_bilder, $neue_dateien;
	$alle_dateien = scandir($ordner); # array für alle Dateien im Ordner
	foreach ($alle_dateien as $datei)
	{
		$dateiinfo = pathinfo($ordner."/".$datei); # Dateiinfos holen
		$titel = utf8_encode($dateiinfo['filename']); # ersten Bildtitel aus Dateiname erzeugen
		$pfad = utf8_encode($dateiinfo['dirname'])."/".utf8_encode($dateiinfo['basename']); # kompletter Dateipfad für Datenbank
		if(array_key_exists('extension', $dateiinfo))
			$endung = $dateiinfo['extension']; # Dateiendung der aktuellen Datei
		else $endung = 'none';
		# Datei merken, wenn Dateipfad noch nicht in DB bekannt und Endung einer Bilddatei entspricht
		if (!check_bild_vorhanden($pfad))
		{	
			if (in_array($endung, $endungen_bilder))
				$neue_dateien[] = array('titel' => $titel, 'pfad' => $pfad);
			elseif (is_dir($ordner."/".$titel) && $titel<>"." && $titel<>"")
				scanneNeueBilder($ordner."/".$titel);
		}
	}
}


# Entfernen des 1. Zeichens von Bilderpfaden (notwendig für Pfadangaben im Style-Bereich)
function pfad_fuer_style($pfad){
	return substr($pfad,1);	
}


# Anzeige von Attributnamen korrigieren
function anzeige_attribut($attr){
	switch($attr){
		case "gesundheit": return "Gesundheit";
		case "zauberpunkte": return "Zauberpunkte";
		case "staerke": return "Stärke";
		case "intelligenz": return "Intelligenz";
		case "magie": return "Magie";
		case "element_feuer": return "Feuer";
		case "element_wasser": return "Wasser";
		case "element_erde": return "Erde";
		case "element_luft": return "Luft";
		case "timer": return "Timer";
		case "initiative": return "Initiative";
		case "ausweichen": return "Ausweichen";
		case "abwehr": return "Abwehr";
		case "spezial": return "Spezial";
		default: return "Fehler beim umkodieren von Attribut [".$attr."]";
	}
}


######################################
### STARTWERTE SPIELER / BALANCING ###
######################################
if(isset($konfig)){
# Allgemeine Parameter
$balance_aktiv = $konfig->get("balance_aktiv");
$anzeige_gaja_3d = $konfig->get("anzeige_gaja_3d");

# Kampfparameter
$gew_elem = $konfig->get("gew_elem"); # Gewichtung von Elementen
$gew_attr = $konfig->get("gew_attr"); # Gewichtung von Attributen
$max_abwehr_standard = $konfig->get("max_abwehr_standard"); # Maximal abgewehrter Schaden bei Standardangriffen
$max_abwehr_zauber = $konfig->get("max_abwehr_zauber"); # Maximal abgewehrter Schaden bei Zaubern

$anzeige_npc_zauber = $konfig->get("anzeige_npc_zauber"); # Im Kampf werden die Angriffe/Zauber der NPCs angezeigt
$anzeige_breite_zauber = $konfig->get("anzeige_breite_zauber"); # Maximale Breite der angezeigten Zauber im Kampf in Pixel (px)
$kampf_detail = $konfig->get("kampf_detail"); # Im Kampf angezeigte Parameter (0-2)
$kampf_log_detail = $konfig->get("kampf_log_detail"); # Im Kampf-Log angezeigte Details (0-2)

# Parameter für Gewinne im Kampf
$k_bonus_patzer = $konfig->get("k_bonus_patzer"); # Multiplikator wenn Zauber/Angriff fehl schlägt (ausgenommen Zielfehler)
$k_bonus_ausweichen = $konfig->get("k_bonus_ausweichen"); # Multiplikator wenn Ziel ausweicht
$k_bonus_abwehr = $konfig->get("k_bonus_abwehr"); # Multiplikator wenn Ziel abwehrt
$k_bonus_erfolg = $konfig->get("k_bonus_erfolg"); # Multiplikator bei Treffer
$k_bonus_staerke = $konfig->get("k_bonus_staerke"); # Grundgewinn Stärke
$k_bonus_intelligenz = $konfig->get("k_bonus_intelligenz"); # Grundgewinn Intelligenz
$k_bonus_magie = $konfig->get("k_bonus_magie"); # Grundgewinn Magie
$k_bonus_elemente = $konfig->get("k_bonus_elemente"); # Multiplikator für Elementpunkte allgemein
$k_bonus_hauptelement = $konfig->get("k_bonus_hauptelement"); # Grundgewinn für Hauptelement Zauber
$k_bonus_nebenelement = $konfig->get("k_bonus_nebenelement"); # Grundgewinn für Nebenelement Zauber
$k_bonus_gegenelement = $konfig->get("k_bonus_gegenelement"); # Grundgewinn für Gegenelement (Hauptzauber) zum Zauber
$k_bonus_zauberpunkte = $konfig->get("k_bonus_zauberpunkte"); # Multiplikator für Zauberpunkte allgemein

# Parameter für Gewinne beim Sammeln
$s_bonus_neu_staerke = $konfig->get("s_bonus_neu_staerke"); # Grundgewinn Stärke wenn Pflanze zum ersten Mal geerntet
$s_bonus_neu_intelligenz = $konfig->get("s_bonus_neu_intelligenz"); # Grundgewinn Intelligenz wenn Pflanze zum ersten Mal geerntet
$s_bonus_neu_magie = $konfig->get("s_bonus_neu_magie"); # Grundgewinn Magie wenn Pflanze zum ersten Mal geerntet
$s_bonus_staerke = $konfig->get("s_bonus_staerke"); # Grundgewinn Stärke
$s_bonus_intelligenz = $konfig->get("s_bonus_intelligenz"); # Grundgewinn Intelligenz
$s_bonus_magie = $konfig->get("s_bonus_magie"); # Grundgewinn Magie
}

# Maximale Gesundheit
function berechne_max_gesundheit($akteur){
	global $gew_attr;
	return intval(floor($gew_attr*(5*$akteur->staerke + 3*$akteur->intelligenz + 1*$akteur->magie)*(1+$akteur->bonus_proz->max_gesundheit/100) + $akteur->bonus_pkt->max_gesundheit));
}


# Maximale Energie
function berechne_max_energie($akteur){
	global $gew_attr;
	return intval(floor($gew_attr*(1*$akteur->staerke + 1*$akteur->intelligenz + 1*$akteur->magie)*(1+$akteur->bonus_proz->max_energie/100) + $akteur->bonus_pkt->max_energie));
}


# Maximale Zauberpunkte
function berechne_max_zauberpunkte($akteur){
	global $gew_attr, $gew_elem;
	$summe_elemente = ($akteur->element_feuer + $akteur->element_wasser + $akteur->element_erde + $akteur->element_luft);
	return intval(floor(($gew_attr*(1*$akteur->intelligenz + 2*$akteur->magie) + $gew_elem*($summe_elemente))*(1+$akteur->bonus_proz->max_zauberpunkte/100) + $akteur->bonus_pkt->max_zauberpunkte));
}


# Balance
function berechne_balance($spieler){
	if ($werte = get_spieler_statistik_balance($spieler)){
		if ($werte["angreifbar"] >= $werte["sammelbar"]){
			return floor_x((2 - ($werte["angreifbar"] + 100) / ($werte["sammelbar"] + 100)) * 100, 3);
		} else {
			return floor_x((2 - ($werte["sammelbar"] + 100) / ($werte["angreifbar"] + 100)) * 100, 3);
		}
	} else {
		return 100;
	}
}


######################
### KAMPFGESCHEHEN ###
######################

# Aktualisiert den Kampftimer beim KampfTeilnehmer
function berechne_initiative($obj){
	if ($obj == null or $obj->initiative == null or $obj->initiative < 0) $wert = 0;
	if ($obj->initiative == 0) $wert = 9999999;
		else $wert = floor_x((10000/$obj->initiative),3);
	return $wert;
}


# Bestimmt den Erfolg eines Angriffs/Zaubers (Ausführung)
function berechne_angriff_erfolg($kt){
	if (check_wkt(95)) return 1;
	else return 0;
}


# Bestimmt den Erfolg des Ausweichens
function berechne_ausweichen_erfolg($kt, $kt_ziel, $zauber){
	if ($zauber->ist_art("verteidigung")){
		$ausweichchance = 0;
	} else {
		if (!$zauber->ist_zauber()){
			# Ausweichchance bei Standardangriffen = Ausweichchance Ziel
			$ausweichchance = $kt_ziel->ausweichen;
		} else {
			# Ausweichchance bei Zaubern um 50% reduziert und zusätzlich um Malus (Ziel->Intelligenz >= Angreifer->Intelligenz dann kein Malus)
			if ($kt->intelligenz > 0) $malus = floor_x($kt_ziel->intelligenz / $kt->intelligenz, 3);
				else $malus = 1;
			if ($malus > 1) $malus = 1;
			$ausweichchance = 0.5 * $kt_ziel->ausweichen * $malus;
		}
	}
	if (check_wkt_spezial($ausweichchance)) return 1;
	else return 0;
}


# Bestimmt den Erfolg der Abwehr eines Angriffs/Zaubers
function berechne_abwehr_erfolg($kt, $kt_ziel, $zauber){
	if ($zauber->ist_art("verteidigung")){
		$abwehrchance = 0;
	} else {
		if (!$zauber->ist_zauber()){
			# Abwehrchance bei Standardangriffen = Abwehrchance Ziel
			$abwehrchance = $kt_ziel->abwehr;
		} else {
			# Abwehrchance bei Zaubern um Malus reduziert (Ziel->Element_Wert >= Angreifer->Element_Wert dann kein Malus)
			$element = $zauber->attribut_bez("hauptelement");
			$gegenelement = $zauber->attribut_bez("gegenelement");
			if ($element AND $kt->$element > 0) $malus = floor_x($kt_ziel->$gegenelement / $kt->$element, 3);
				else $malus = 1;
			if ($malus > 1) $malus = 1;
			$abwehrchance = $kt_ziel->abwehr * $malus;
		}
	}
	if (check_wkt_spezial($abwehrchance)) return 1;
	else return 0;
}


# Bestimmt die verbrauchten Zauberpunkte für einen Zauber
function berechne_zauberpunkte_verbrauch($zauber){
	return $zauber->verbrauch;
}


# Bestimmt den Zeitbedarf für die Ausführung eines Angriffs/Zaubers
function berechne_timer_verbrauch($kt){
	return berechne_initiative($kt);
}


# Bestimmt nächsten Kampfteilnehmer
function naechster_kt($kt_all){
	$init = false;
	foreach ($kt_all as $kt){
		$tot = $kt->ist_tot();
		if (!$tot AND !$init){
			$kt_next = $kt;
			$timer_min = $kt->timer;
			$init = true;
		}
		if (!$tot AND $timer_min > $kt->timer){
			$kt_next = $kt;
			$timer_min = $kt->timer;
		}
	}
	return $kt_next;
}


# Bestimmt Gewinner - 0/1 = Seite, 2 = offen
function ist_kampf_beendet($kt_all){
	$kt_lebend_0 = 0;
	$kt_lebend_1 = 0;
	$gewinner = 0;
	foreach ($kt_all as $kt){
		if ($kt->gesundheit > 0){
			if ($kt->seite == 0 AND $kt->typ == "spieler"){
				$kt_lebend_0 = $kt_lebend_0 + 1;
			}
			if ($kt->seite == 1){
				$kt_lebend_1 = $kt_lebend_1 + 1;
				$gewinner = 1;
			}
		}
	}
	if ($kt_lebend_0 > 0 AND $kt_lebend_1 > 0){
		$gewinner = 2;
	}
	return $gewinner;
}


# Ermittelt NPC-KI und liefert Angriff/Zauber sowie Ziel als Array zurück
function ki_ausfuehren($kt, $alle_zauber){
	global $kt_0, $kt_1;
	$ki = get_ki($kt->ki_id);	
	switch ($ki->name){
		case "spezielle KI":
			break;
		default: # "Standard_wkt"
			# Keine passende KI für Kampfteilnehmer gefunden gefunden. Nutze "Standard_wkt"
			############################
			##### Zauber bestimmen #####
			############################
			# Array mit Wahrscheinlichkeiten der Zauber vorbereiten
			$i = 0;
			$wkt_von = 1;
			$wkt_bis = 0;
			foreach ($alle_zauber as $zauber){
				if ($kt->zauberpunkte < berechne_zauberpunkte_verbrauch($zauber)){
					$alle_zauber_wkt[$i] = [$zauber, 0, 0];
				} else {
					$wkt_bis = $wkt_bis + $zauber->wahrscheinlichkeit;
					$alle_zauber_wkt[$i] = [$zauber, $wkt_von, $wkt_bis];
					$wkt_von = $wkt_bis + 1;
				}
				$i = $i + 1;
			}
			# Passende Zufallszahl bestimmen
			$zufall_1 = mt_rand(1, $wkt_bis);
			# Welcher Zauber wurde ausgewählt?
			foreach ($alle_zauber_wkt as $zauber_wkt){
				if ($zufall_1 >= $zauber_wkt[1] AND $zufall_1 <= $zauber_wkt[2]){
					$zauber = $zauber_wkt[0]; # -> ermittelter Angriff/Zauber
					break;
				}
			}
			##########################
			##### Ziel bestimmen #####
			##########################
			# Array mit möglichen Zielen vorbereiten
			switch ($zauber->zaubereffekte[0]->art){
				case "angriff":
					if ($kt->seite == 1) $kt_ziele = $kt_0;
						else $kt_ziele = $kt_1;
					break;
				case "verteidigung":
					if ($kt->seite == 1) $kt_ziele = $kt_1;
						else $kt_ziele = $kt_0;
					break;
				default:
					$kt_ziele = null;
			}
			$i = 0;
			$wkt_von = 1;
			$wkt_bis = 0;
			foreach ($kt_ziele as $kt_ziel){
				if ($kt_ziel->gesundheit <= 0){
					$kt_ziele_wkt[$i] = [$kt_ziel, 0, 0];
				} else {
					$wkt_bis = $wkt_bis + 100;
					$kt_ziele_wkt[$i] = [$kt_ziel, $wkt_von, $wkt_bis];
					$wkt_von = $wkt_bis + 1;
				}
				$i = $i + 1;
			}
			# Passende Zufallszahl bestimmen
			$zufall_2 = mt_rand(1, $wkt_bis);
			# Welcher Kampfteilnehmer wurde ausgewählt?
			foreach ($kt_ziele_wkt as $ziel_wkt){
				if ($zufall_2 >= $ziel_wkt[1] AND $zufall_2 <= $ziel_wkt[2]){
					$kt_ziel = $ziel_wkt[0]; # -> ermitteltes Ziel
					break;
				}
			}
			break;
	}
	if ($zauber AND $kt_ziel){
		return [$zauber, $kt_ziel];
	} else {
		echo "Angriff/Zauber und/oder Ziel konnten nicht ermittelt werden.<br />";
		return false;
	}
}


# Anzeige von Attributnamen korrigieren
function berechne_effekt_wert($kt, $kt_ziel, $zauber, $effekt, $abwehr){
	global $max_abwehr_standard;
	global $max_abwehr_zauber;
	$element = $zauber->attribut_bez("hauptelement");
	if ($element){
		$c1 = 1;
		$gegenelement = $zauber->attribut_bez("gegenelement");
	} else {
		$c1 = 0;
		$gegenelement = false;
	}
	if ($abwehr) $c2 = 1;
		else $c2 = 0;
	$check = $c1.$c2;
	switch($effekt->attribut){
		case "gesundheit": 
		case "zauberpunkte":
		case "staerke":
		case "intelligenz":
		case "magie":
		case "element_feuer":
		case "element_wasser":
		case "element_erde":
		case "element_luft":
		case "timer":
		case "initiative":
		case "ausweichen":
		case "abwehr":
			switch ($check){
				# Standardangriff ohne Abwehr
				case 00: 
					$effekt->wert = floor_x($effekt->wert * (pow($kt->staerke, 1/2)/2), 0);
					break;
				# Standardangriff mit Abwehr
				case 01:
					if ($kt->staerke == 0 OR $kt->staerke <= $kt_ziel->staerke) $malus = 1;
						else $malus = floor_x($kt_ziel->staerke / $kt->staerke, 3);
					$effekt->wert = floor_x($effekt->wert * (pow($kt->staerke, 1/2)/2) * (1 - ($malus * $max_abwehr_standard)), 0);
					break;
				# Zauber ohne Abwehr
				case 10:
					$effekt->wert = floor_x($effekt->wert * ((pow($kt->magie, 1/4) * pow($kt->$element, 1/4)) /2), 0);
					break;
				# Zauber mit Abwehr
				case 11:
					if ($kt->$element == 0 OR $kt->$element <= $kt_ziel->$gegenelement) $malus = 1;
						else $malus = floor_x($kt_ziel->$gegenelement / $kt->$element, 3);
					$effekt->wert = floor_x($effekt->wert * ((pow($kt->magie, 1/4) * pow($kt->$element, 1/4)) /2) * (1 - ($malus * $max_abwehr_zauber)), 0);
					break;
				default:
					break;
			}
			break;
		case "spezial": break;
		default:
			return false;
	}
	return true;
}


# Anzeige von Attributnamen korrigieren
function kt_array_korrigieren($kt_id, $param){
	global $kt_0;
	global $kt_1;
	global $kt_all;
	$kt = get_kampf_teilnehmer_by_id($kt_id);
	
	switch ($param){
		case "hinzufügen":
			if ($kt->seite == 0) array_push($kt_0, $kt);
				else array_push($kt_1, $kt);
			break;
		case "entfernen":
			$counter = 0;
			if ($kt->seite == 0){
				foreach ($kt_0 as $kt_temp){
					if ($kt_temp->kt_id == $kt_id){
						unset($kt_0[$counter]);
						array_values($kt_0);
						break;
					}
					$counter = $counter + 1;
				}
			} else {
				foreach ($kt_1 as $kt_temp){
					if ($kt_temp->kt_id == $kt_id){
						unset($kt_1[$counter]);
						array_values($kt_1);
						break;
					}
					$counter = $counter + 1;
				}
			}
			break;
		default: break;
	}
	$kt_all = array_merge($kt_0, $kt_1);
}



##############################
### ALLGEMEINES / ANZEIGEN ###
##############################

# Passendes Drachenbild zum Level des Spielers ermitteln
function bild_zu_spielerlevel($bilder_id){
	return get_bild_zu_id($bilder_id);
}


# Hintergrundbild: Bilderpfad (großes Bild) auf Bilderpfad (kleines Bild) ändern
function hintergrundbild_klein($gebiet_id){
	return str_replace(array("/Gross/",".jpg"), array("/Klein/","_klein.jpg"), get_bild_zu_gebiet($gebiet_id));
}


# Aufbau des Hintergrundbildes mit Gebietslinks
function zeige_hintergrundbild($gebiet_id, $aktion_text=false, $bewusstlos=false){
	?>
	<!-- # Links Zielgebiete -->
	<div id="hintergrundbild">
		<img src="<?php echo get_bild_zu_gebiet($gebiet_id) ?>" style="max-width: 100%;" height="100%" alt=""/>
		<div id="hintergrundbild_gebietslinks">
			<?php zeige_gebietslinks($gebiet_id); ?>
		</div>
	</div>
	<!-- Beschreibungstext Hintergrundbild -->
	<div id="hintergrundbild_text" align="center">
		<p style="font-size:14pt;">
			<?php echo get_gebiet($gebiet_id)[3]; ?>
		</p>
		<?php
		# Hinweis, falls Spieler eine noch nicht abgeschlossene Aktion hat
		$aktion_verboten = 0;
		if($aktion_text) $aktion_verboten = 1;
		if($bewusstlos and !isset($_POST["button_ausruhen"])) $aktion_verboten = 2;
		if($bewusstlos and isset($_POST["button_ausruhen"]) and $_POST["button_ausruhen"]!="ausruhen_voll") $aktion_verboten = 3;
		
		if ($aktion_verboten > 0){
			switch($aktion_verboten){
				case 1:	?>
					<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
						<font color="red">Ihr seid noch beschäftigt!</font><br />
					</p>
					<?php
					break;
				case 2:	?>
					<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
						<font color="red">Ihr seid bewusslos und solltet erst einmal wieder eure Lebensgeister erwecken!</font><br />
					</p>
					<?php
					break;
				case 3:	?>
					<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
						<font color="red">Ihr seid bewusslos. Eine kurze Ruhepause wird nicht genügen, damit ihr wieder zu Kräften kommt. Ihr müsst "Erneut Erwachen"!</font><br />
					</p>
					<?php
					break;
				default: ?>
					<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
						<font color="red">Das Starten von Aktionen ist euch aktuell verboten. Warum weiß keiner.
						-> Admin fragen!</font><br />
					</p>
					<?php
					break;
			}?>
			<p align="center">
				<button class="button_standard" type="submit" name="zurueck" value="zurück">zurück</button>
			</p>
		<?php
		}?>
	</div><?php
}


# Unterfunktion für einzeln positionierte Gebietslinks auf Hintergrundbild
function zeige_gebietslinks($gebiet_id){
	if ($zielgebiete = get_gebiet_gebiet($gebiet_id))
	{
		while($row = $zielgebiete->fetch_array(MYSQLI_NUM))
		{
	?>			
		<div id="gebietslinks" style="
			background-image:url(<?php echo hintergrundbild_klein($row[2]) ?>);
			background-repeat:no-repeat;
			background-size:contain;
			position:absolute;
			left:<?php echo $row[4] ?>%;
			top:<?php echo $row[5] ?>%;
			width:12%;
			height:12%;">
			<span title="<?php echo $row[3]; ?>" >
				<input type="submit" name="button_zum_zielgebiet" value="<?php echo $row[3]; ?>" style="width:100%; height:100%; opacity:0.0;" />
			</span>
		</div>
	
	<?php
		}
	}
	else{
		echo "<br />\nKeine Zielgebiete gefunden.<br />\n";
	}
}


# Aufbau der Seite mit erhaltenen Items
function zeige_erbeutete_items($spieler, $npc_ids, $npc_typ){
	if (!is_array($npc_ids)) $npc_ids = array($npc_ids);
	?>
	<p align="center" style="margin-top:5%; margin-bottom:0px; font-size:14pt;">
		<?php 
		switch ($npc_typ){
			case "Tiere": echo "Folgende Tiere wurden besiegt:<br /><br />"; break;
			case "Pflanzen": echo "Folgende Pflanzen wurden geerntet:<br /><br />"; break;
			default: echo "Ups da ist was schief gegangen.<br /><br />"; break;
		}
		?>
	</p>
	<?php
	$erfahrung = 0;
	foreach ($npc_ids as $npc_id){
		$npc = get_npc($npc_id);
		echo "* ".$npc->name."<br />";
		$erfahrung = $erfahrung + $npc->erfahrung;
	}
	$items = get_items_npc($npc_id)
	?>
	<table class="tabelle" align="center" style="margin-top:5%;" width="500px" cellpadding="5px">
		<tr class="tabelle_kopf">
			<td>Item</td>
			<td>Beschreibung</td>
			<td align="right">Anzahl</td>
		</tr>
		<?php
		$counter = 0;
		if (isset($items[0])){
			foreach ($items as $item){
				if(check_wkt($item->fund->wahrscheinlichkeit)){
					$item_anzahl = rand($item->fund->anzahl_min, $item->fund->anzahl_max);
					if ($item_anzahl > 0) $counter = $counter + 1;
					items_spieler_aendern($spieler->id, $item->id, $item_anzahl);
					?>
					<tr class="tabelle_inhalt">
						<td><?php echo $item->name ?></td>
						<td><?php echo $item->beschreibung ?></td>
						<td align="right"><?php echo $item_anzahl ?></td>
					</tr>
					<?php
				}
			}
		}
		if (!isset($items[0]) OR $counter == 0){
			?>
			<tr>
				<td colspan=3>Hehe ... nix gefunden. :P</td>
			</tr>
			<?php
		}
		?>
	</table>		
	<?php
	return $erfahrung;
}


# Kontrollierter Start von Aktionen (genügend Energie? Textausgabe)
function beginne_aktion($spieler, $aktion_titel, $id_1=0, $id_2=0){
	$neue_aktion = get_aktion($aktion_titel);
	if ($spieler->energie >= $neue_aktion->energiebedarf){
		insert_aktion_spieler($spieler->id, $neue_aktion->titel, $id_1, $id_2);
		$spieler->attribut_aendern("energie", -$neue_aktion->energiebedarf, 0, $spieler->max_energie);
		$spieler->db_update();
	} else {
		?>
		<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
			Ihr habt nicht genügend Energie für diese Aktion.<br />
		</p>
		<p align="center" style="padding-top:10pt;">
			<button class="button_standard" type="submit" name="zurueck" value="zurück">zurück</button>
		</p>
		<?php
	}
}


# Aufbau der Seite für Elementbäume
function elemente_anzeigen($hauptelement, $hintergrundfarbe, $spieler){
	?><div id="zauber_tabelle" style="background-color:#<?php echo $hintergrundfarbe ?>;"> <!-- Hintergundfarbe wird mit übergeben und gesetzt -->
		<input type="hidden" id="button_name_id" name="anzeige_element">
		<table>
			<?php
			if (isset($_POST["button_zauber"])){
				switch_zauber_spieler($spieler->id, $_POST["button_zauber"]);
			}			
			$alle_zauber = get_zauber_von_objekt($spieler);
			# Für Tabellenstruktur Anzeigedaten nacheinander abholen
			# 1. Alle veschiedenen Zauberarten zum gewählten Element (Schaden, Heilung, usw. )
			$zauberarten = get_zauberarten_zu_hauptelement($hauptelement);
			while($row = $zauberarten->fetch_array(MYSQLI_NUM))
			{
				$zauberart = $row[1];
				?>
				<tr><td align="left" style="padding-top:10px">
					<p><b><?php echo $zauberart ?></b></p>
					<table cellspacing="5px">
						<?php
						# 2. Alle Nebenelemente die bei dem gewählten Element und der Zauberart vorkommen (relevant für Anzahl Zeilen pro Zauberart)
						$nebenelemente = get_nebenelement_zu_hauptelement_zauberart($hauptelement, $zauberart);
						while($row = $nebenelemente->fetch_array(MYSQLI_NUM))
						{
							$nebenelement = $row[0];
							?>
								<tr>
									<?php
									# 3. Alle Zauber zum gewählten Element und aktuell betrachteter Zauberart und Nebenelement
									$zauber = get_zauber_zu_hauptelement_nebenelement_zauberart($hauptelement, $nebenelement, $zauberart);
									while($row = $zauber->fetch_array(MYSQLI_NUM))
									{
										$zauber_id = $row[0];
										$zauber_titel = $row[2];
										$zauber_beschreibung = $row[5];
										$zauber_bilder_id = $row[1];
										$inaktiv = true;
										if (isset($alle_zauber[0])){
											foreach ($alle_zauber as $z){
												if ($z->id == $zauber_id){
													$inaktiv = false;
													break;
												}
											}
										}
										?>
										<td style="background-image:url(<?php echo get_bild_zu_id($zauber_bilder_id) ?>); background-repeat:no-repeat; background-size:contain; <?php if($inaktiv) echo "border:3px red solid;"; else echo "border:3px green solid;";?>" align="left">
											<top_mover>
												<mover>
													<?php
														echo $zauber_titel.'<br />';
														
													?>
												</mover>
												<input onclick="set_button('<?php echo $hauptelement ?>', 'egal')" type="submit" name="button_zauber" value="<?php echo $zauber_id ?>" style="height:60px; width:60px; opacity:0.0;">
											</top_mover>
										</td>
										<?php
									}
									?>
								</tr>
							<?php
						}
						?>
					</table>
				</td></tr>
				<?php
			}
			?>
		</table>
	</div><?php
}


# Prüfung der Bedingungen zum übergebenen Element
function bedingung_pruefen($tabelle, $tabelle_id){
	$kn_id = get_bed_einstieg($tabelle, $tabelle_id);
	# Wenn keine Bedingung gefunden oder Fehler aufgetreten, dann WAHR sonst Bedingung prüfen
	if($kn_id == false){
		return true;
	} else {
		return bedingung_knoten_pruefen($kn_id, 0);
	}
}


# Prüfung eines Bedingungsknotens; ruft sich ggf. rekursiv selbst auf
function bedingung_knoten_pruefen($kn_id, $ebene, $nr=null){
	global $debug;
	$knoten = new BedingungKnoten($kn_id);
	# Knoten korrekt erzeugt und Teilbedingungen oder -knoten vorhanden?
	if($knoten == false OR $knoten->anz_kinder == 0){
		return true;
	# Rückgabewert für den Fall dass alle Teilbedingungen und -knoten durchlaufen wurden
	} else {
		switch($knoten->operator){
			# UND: wahr wenn alle Bedingungen wahr
			case "UND":
				$check = true;
				break;
			# ODER: falsch wenn alle Bedingungen falsch
			case "ODER":
				$check = false;
				break;
			# Andere Werte dürfte es nicht geben
			default:
				$check = false;
				break;
		}
		if($debug){
			if($nr == null) $ebene_nr = $ebene;
				else $ebene_nr = $ebene."-".$nr;
			echo "<br \>Ebene ".$ebene_nr." | Operator = ".$knoten->operator;
		}
	}
	if(isset($knoten->bed_teil)){
		if($debug) echo "<br \>Ebene ".$ebene_nr." | Teilbedingungen prüfen";
		$tb_nr = 0;
		foreach ($knoten->bed_teil as $teilbedingung_id){
			$tb_nr = $tb_nr + 1;
			if($debug) echo "<br \>".$ebene_nr.".".$tb_nr." = ";
			$test = $knoten->bedingung_teil_pruefen($teilbedingung_id);
			if($knoten->operator == "UND" AND $test == false){
				if($debug) echo "<br \>Ebene ".$ebene_nr." Abbruch : Rückgabewert = FALSCH";
				return false;
			}
			if($knoten->operator == "ODER" AND $test == true){
				if($debug) echo "<br \>Ebene ".$ebene_nr." Abbruch : Rückgabewert = WAHR";
				return true;
			}
		}
		if($debug) echo "<br \>Ebene ".$ebene_nr." | Teilbedingungen abgearbeitet";
	}
	$ebene_neu = $ebene + 1;
	$kn_nr = 0;
	if(isset($knoten->bed_knoten)){
		if($debug) echo "<br \>Ebene ".$ebene_nr." | Teilknoten prüfen";
		foreach ($knoten->bed_knoten as $teilknoten_id){
			$kn_nr = $kn_nr + 1;
			$test = bedingung_knoten_pruefen($teilknoten_id, $ebene_neu, $kn_nr);
			if($knoten->operator == "UND" AND $test == false){
				if($debug) echo "<br \>Ebene ".$ebene_nr." Abbruch : Rückgabewert = FALSCH";
				return false;
			}
			if($knoten->operator == "ODER" AND $test == true){
				if($debug) echo "<br \>Ebene ".$ebene_nr." Abbruch : Rückgabewert = WAHR";
				return true;
			}
		}
		if($debug) echo "<br \>Ebene ".$ebene_nr." | Teilknoten abgearbeitet";
	}
	if($debug){
		if($check == true) echo "<br \>Ebene ".$ebene_nr." nach Durchlauf = WAHR";
			else echo "<br \>Ebene ".$ebene_nr." nach Durchlauf = FALSCH";
	}
	return $check;
}

?>


<script>
	/* Berechnung der Zeitanzeige Aktionen */
	function client_times()	{
		/* Lokalzeit Client */
		var now = new Date();
		document.getElementById("clientzeit").innerHTML = convert_to_datetime(now);
		
		var timeset;
		if (sommerzeit()) timeset = "GMT+0200";
		else timeset = "GMT+0100";
		
		/* Zeiten für Aktionen */
		var aktion_titel = document.getElementById("titel_temp").value;
		if (!aktion_titel) {
			set_ladebalken('-', '-', '-');
		} else {
			var aktion_start = new Date(document.getElementById("startzeit_temp").value); /* Start */
			var aktion_ende = new Date(document.getElementById("endezeit_temp").value); /* Ende */
			var aktion_diffvs = now - new Date(document.getElementById("startzeit_temp").value); /* Zeit von Start  + timeset*/
			var aktion_diffbe = new Date(document.getElementById("endezeit_temp").value) - now; /* Zeit bis Ende  + timeset*/
			var aktion_gesamt = aktion_ende - aktion_start; /* Gesamtzeit */
			set_ladebalken(aktion_diffvs, aktion_diffbe, aktion_gesamt);
		}
	}
	
	/* Runde Zahl x auf n Nachkommastellen */
	function runde(x, n) {
		var e = Math.pow(10, n);
		var k = (Math.round(x * e) / e).toString();
		if (k.indexOf('.') == -1) k += '.';
		k += e.toString().substring(1);
		return k.substring(0, k.indexOf('.') + n+1);
	}
	
	/* Rundet Zahl x ab und auf Ganzzahl */
	function runde_ab(x) {
		return Math.round(x - 0.5);
	}
	
	/* Konvertiert eine Zeitangabe in Millisekunden in xxx Tag(e) hh:mm:ss */
	function convert_to_time(wert) {
		if (wert < 0) return "00:00:00";
		var s = runde_ab(wert/1000);
		var m = runde_ab(s/60);
		var h = runde_ab(m/60);
		var d = runde_ab(h/24);
        var str_s = s % 60;
        var str_m = m % 60;
		var str_h = h % 24;
		var str_d = d;
		if (str_s < 10) str_s = '0' + str_s.toString();
		if (str_m < 10) str_m = '0' + str_m.toString();
		if (str_h < 10) str_h = '0' + str_h.toString();
		if (str_d < 1) str_d = '';
		if (str_d > 1) str_d = str_d.toString() + 'Tage ';
		if (str_d == 1) str_d = str_d.toString() + 'Tag ';
		return str_d + str_h + ':' + str_m + ':' + str_s;
    }
	
	/* Konvertiert eine Zeitangabe in Millisekunden in yyyy-MM-dd hh:mm:ss */
	function convert_to_datetime(wert) {
		return (wert.getFullYear() + '-' + ((wert.getMonth() < 9) ? ("0" + (wert.getMonth() + 1)) : (wert.getMonth() + 1)) + '-' + (wert.getDate()) + " " + wert.getHours() + ':' + ((wert.getMinutes() < 10) ? ("0" + wert.getMinutes()) : (wert.getMinutes())) + ':' + ((wert.getSeconds() < 10) ? ("0" + wert.getSeconds()) : (wert.getSeconds())));
    }
	
	/* Ermittelt den Status der Sommerzeit: Wenn Sommerzeit aktiv dann true, sonst false */
	function sommerzeit() {
		var d = new Date();
		var heute = new Date(d.getFullYear(), d.getMonth(), d.getDate());
		var utc = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
		var one_hour = 3600000;
		if ((Math.abs(utc -heute) / one_hour) == 2){
			return true;
		} else  {
			return false;
		}
	}
	
	/* Aktualisiert den Fortschritt einer Aktion (Ladebalken, Animation Status) */
	function set_ladebalken(zeit_temp, zeit_ende, zeit_gesamt) {
		var aktion_beendet = false;
		var aktion_statusbild = document.getElementById("statusbild_temp").value;
		if (zeit_temp == '-' || zeit_ende == '-' || zeit_gesamt == '-'){
			document.getElementById('balken').style.width = "0.0%";
    	    document.getElementById('prozent').innerHTML = "- nichts zu tun -";
			set_aktion_status_bild('Drache_wartend');
		} else {
			if (zeit_temp > zeit_gesamt) {
				aktion_beendet = true;
			}
			if (!aktion_beendet) {
				var zeit_prozent = (zeit_temp / zeit_gesamt) * 100;
    		    /* Wenn Aktionsdauer länger als 1 Jahr dann Text anzeigen statt Zeit bis Ende */
				if (zeit_ende - zeit_temp > 32000000000) {
					document.getElementById('balken').style.width = "0.0%";
					document.getElementById('prozent').innerHTML = "Offenes Ende";
				} else {
					document.getElementById('balken').style.width = runde(zeit_prozent, 1).toString() + "%";
					document.getElementById('prozent').innerHTML = convert_to_time(zeit_ende);
				}
				switch (aktion_statusbild)
				{
				case 'laufend':
					set_aktion_status_bild('Drache_laufend');
					break;
				case 'kaempfend':
					set_aktion_status_bild('Drache_kaempfend');
					break;
				case 'wartend':
					set_aktion_status_bild('Drache_wartend');
					break;
				}
			} else {
				document.getElementById('prozent').style.display = 'none';
				document.getElementById('button_abschluss').style.display = 'block';
				set_aktion_status_bild('Drache_wartend');
			}
		}
	}

	/* Setzt das Aktions-Status Bild anhand des übergebenen Status */
	function set_aktion_status_bild(titel) {
		var a = document.getElementById('aktion_status_wartend');
		var b = document.getElementById('aktion_status_kaempfend');
		var c = document.getElementById('aktion_status_laufend');
		switch(titel)
		{
		case 'Drache_wartend':
			a.style.display='block';
			b.style.display='none';
			c.style.display='none';
			break;
		case 'Drache_kaempfend':
			a.style.display='none';
			b.style.display='block';
			c.style.display='none';
			break;
		case 'Drache_laufend':
			a.style.display='none';
			b.style.display='none';
			c.style.display='block';
			break;
		default:
			a.style.display='none';
			b.style.display='none';
			c.style.display='none';
		}
	}
	
	/* Löschfunktion für Spieler */
	function buttonwechsel(spieler_id) {
		elem_1 = document.getElementById("b_sp_loe_" + spieler_id + "_1");
		elem_2a = document.getElementById("b_sp_loe_" + spieler_id + "_2a");
		elem_2b = document.getElementById("b_sp_loe_" + spieler_id + "_2b");
		elem_2c = document.getElementById("b_sp_loe_" + spieler_id + "_2c");
		if(elem_2a.style.visibility=="hidden"){
			elem_1.style.visibility="hidden";
			elem_2a.style.visibility="visible";
			elem_2b.style.visibility="visible";
			elem_2c.style.visibility="visible";
		} else {
			elem_1.style.visibility="visible";
			elem_2a.style.visibility="hidden";
			elem_2b.style.visibility="hidden";
			elem_2c.style.visibility="hidden";
		}
	}
	
	/* Setzt einen Button ohne dass dieser gedrückt wurde */
	function set_button(button_name, button_value="") {
		document.getElementById("button_name_id").value=button_name;
		if(button_value != "egal") document.getElementById("button_value_id").value=button_value;
	}
	
	/* Setzt Werte zur Weitergabe an POST und lädt Seite (dvg_admin) neu */
	function set_button_submit(button_name, button_value="") {
		document.getElementById("button_name_id").value=button_name;
		document.getElementById("button_value_id").value=button_value;
		document.getElementById("dvg_admin").submit();
	}
	
	/* Setzt rowspan für Bedingunsbaum korrekt */
	function set_rowspan() {
		var elemente = document.getElementsByClassName("bed_set_rowspan");
		var i, elem_id, neues_element;
		for (i = 0; i < elemente.length; i++) {
			elem_id = elemente[i].id.split("__")[0];
			neues_element = document.getElementById(elem_id);
			neues_element.setAttribute("rowspan", elemente[i].innerHTML);
		}
	}
	
	/* Setzt Werte zur Weitergabe an POST und lädt Seite (drachenvongaja) neu */
	function zaubern(kt_id, kt_id_ziel, zauber_id){
		document.getElementById("kt_id_value").value=kt_id;
		document.getElementById("kt_id_ziel_value").value=kt_id_ziel;
		document.getElementById("zauber_id_value").value=zauber_id;
		document.getElementById("drachenvongaja").submit();
	}

	/* Bestimme Position eines Elements */
	function getPosition(elem) {
		var tagname="", tagid="", top=0, left=0;
		while ((typeof(elem)=="object")&&(typeof(elem.tagName)!="undefined")){
			top+=elem.offsetTop;
			left+=elem.offsetLeft;
			tagname=elem.tagName.toUpperCase();
			if (tagname=="BODY")
			elem=0;
			if (typeof(elem)=="object")
			if (typeof(elem.offsetParent)=="object")
				elem=elem.offsetParent;
		}
		return [top-document.getElementById("obere_Leiste").offsetHeight, left-document.getElementById("mitte_zentral").offsetLeft];
	}
	
	/* Bestimme Position eines Elements(1) relativ zum definierten übergeordneten Element(2) */
	function getRange(elementId, element2Id) {
		var elem=document.getElementById(elementId);
		var elem2=document.getElementById(element2Id);
		var xmin=0, ymin=0
		var xmax=elem.offsetWidth;
		var ymax=elem.offsetHeight;
		while (typeof(elem)=="object"){
			xmin+=elem.offsetLeft;
			ymin+=elem.offsetTop;
			if (elem==elem2)
				elem=0;
			if (typeof(elem)=="object")
				if (typeof(elem.offsetParent)=="object")
					elem=elem.offsetParent;
		}
		return [xmin, xmin+xmax, ymin, ymin+ymax];
	}
	
	/* Bestimme Breite der aktuellen Tabellenzelle */
	function getBreite(elementId) {
		var elem=document.getElementById(elementId);
		alert(elem.offsetWidth);
		return elem.offsetWidth;
	}
	
	/* Dynamische Balkenanzeige */
	function balkenanzeige(balken_div, inhalt_div, farbe, breite, prozent) {
		var balken = document.getElementById(balken_div);
		var inhalt = document.getElementById(inhalt_div);
		balken.style.width = breite * prozent;
		balken.style.background = farbe;
		inhalt.style.width = breite;
	}
	
	
	
</script>