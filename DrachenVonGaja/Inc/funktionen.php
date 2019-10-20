<?php

############################
### ALLGEMEINES / SYSTEM ###
############################

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
		default: return "Fehler beim umkodieren von Attribut";
	}
}


######################################
### STARTWERTE SPIELER / BALANCING ###
######################################

# Kampfparameter
$gew_elem = 0.2; # Gewichtung von Elementen
$gew_attr = 0.5; # Gewichtung von Attributen
$max_abwehr_standard = 0.75; # Maximal abgewehrter Schaden bei Standardangriffen
$max_abwehr_zauber = 0.75; # Maximal abgewehrter Schaden bei Zaubern

$anzeige_npc_zauber = true; # Im Kampf werden die Angriffe/Zauber der NPCs angezeigt
$kampf_detail = 2; # Im Kampf angezeigte Parameter (0-2)
$kampf_log_detail = 2; # Im Kampf-Log angezeigte Details (0-2)


# Maximale Gesundheit
function berechne_max_gesundheit($akteur){
	global $gew_attr;
	return intval(floor($gew_attr*(5*$akteur->staerke + 3*$akteur->intelligenz + 1*$akteur->magie)));
}


# Maximale Energie
function berechne_max_energie($akteur){
	global $gew_attr;
	return intval(floor($gew_attr*(1*$akteur->staerke + 1*$akteur->intelligenz + 1*$akteur->magie)));
}


# Maximale Zauberpunkte
function berechne_max_zauberpunkte($akteur){
	global $gew_attr, $gew_elem;
	$summe_elemente = ($akteur->element_feuer + $akteur->element_wasser + $akteur->element_erde + $akteur->element_luft);
	return intval(floor($gew_attr*(1*$akteur->intelligenz + 2*$akteur->magie) + $gew_elem*($summe_elemente)));
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
	if (!$zauber->ist_angriff()){
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
	if (!$zauber->ist_angriff()){
		$abwehrchance = 0;
	} else {
		if (!$zauber->ist_zauber()){
			# Abwehrchance bei Standardangriffen = Abwehrchance Ziel
			$abwehrchance = $kt_ziel->abwehr;
		} else {
			# Abwehrchance bei Zaubern um Malus reduziert (Ziel->Element_Wert >= Angreifer->Element_Wert dann kein Malus)
			$element = $zauber->hauptelement_attribut_bez();
			$gegenelement = $zauber->gegenelement_attribut_bez();
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
	$kt_next = $kt_all[0];
	$timer_min = $kt_next->timer;
	foreach ($kt_all as $kt){
		if ($timer_min > $kt->timer AND !$kt->ist_tot()){
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
			if ($kt->seite == 0){
				$kt_lebend_0 = $kt_lebend_0 + 1;
			} else {
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
		case "Standard_wkt":
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
					$kt_ziele = $kt_0;
					break;
				case "verteidigung":
					$kt_ziele = $kt_1;
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
		default:
			echo "Keine passende KI für ".$kt->name." gefunden.<br>";
			break;
	}
	if ($zauber AND $kt_ziel){
		return [$zauber, $kt_ziel];
	} else {
		echo "Angriff/Zauber und/oder Ziel konnten nicht ermittelt werden.<br>";
		return false;
	}
}


# Anzeige von Attributnamen korrigieren
function berechne_effekt_wert($kt, $kt_ziel, $zauber, $effekt, $abwehr){
	global $max_abwehr_standard;
	global $max_abwehr_zauber;
	$element = $zauber->hauptelement_attribut_bez();
	if ($element){
		$c1 = 1;
		$gegenelement = $zauber->gegenelement_attribut_bez();
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
		default:
			return false;
	}
	return true;
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
function zeige_hintergrundbild($gebiet_id, $aktion_titel=false){
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
		if ($aktion_titel)
		{?>
			<p align="center" style="margin-top:20px; margin-bottom:0px; font-size:14pt;">
				Ihr seid noch beschäftigt!<br>
			</p>
			<p align="center">
				<input type="submit" name="zurueck" value="zurück">
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
		<div style="background-image:url(<?php echo hintergrundbild_klein($row[2]) ?>);
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
function zeige_erbeutete_items($spieler_id, $npc_id, $text1, $text2){
	if ($npc = get_npc($npc_id)){		
		while($row = $npc->fetch_array(MYSQLI_NUM)){
			?>
			<p align="center" style="margin-top:5%; margin-bottom:0px; font-size:14pt;">
				<?php echo $text1 . $row[1] . $text2; ?>
			</p>
			<?php
		}
	} else {
		echo "<br />\nNPC mit id=[" . $npc_id . "] nicht gefunden.<br />\n";
	}
	
	if ($items = get_items_npc($npc_id)){		
		$counter = 0;
		?>
		<table border="1px" border-color="white" align="center" style="margin-top:5%;" width="500px" >
			<tr>
				<td>Item</td>
				<td>Beschreibung</td>
				<td>Anzahl</td>
			</tr>
		<?php
		while($row = $items->fetch_array(MYSQLI_NUM)){
			$item_wkt = $row[4];
			if(check_wkt($item_wkt)){
				$item_id = $row[0];
				$item_titel = $row[1];
				$item_beschreibung = $row[2];
				$item_anzahl = rand($row[5], $row[6]);
				$counter = $counter + 1;
				insert_items_spieler($spieler_id, $item_id, $item_anzahl);
				?>
				<tr>
					<td><?php echo $item_titel ?></td>
					<td><?php echo $item_beschreibung ?></td>
					<td><?php echo $item_anzahl ?></td>
				</tr>
				<?php
			}
		}
		if($counter == 0){
			?>
			<tr>
				<td colspan=3>Hehe ... nix gefunden. :P</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	} else {
		echo "<br />\nItems zum NPC mit id=[" . $npc_id . "] konnten nicht abgerufen werden.<br />\n";
	}
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
			Ihr habt nicht genügend Energie für diese Aktion.<br>
		</p>
		<p align="center" style="padding-top:10pt;">
			<input type="submit" name="zurueck" value="zurück">
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
										foreach ($alle_zauber as $z){
											if ($z->id == $zauber_id){
												$inaktiv = false;
												break;
											}
										}
										?>
										<td style="background-image:url(<?php echo get_bild_zu_id($zauber_bilder_id) ?>); background-repeat:no-repeat; background-size:contain; <?php if($inaktiv) echo "border:3px red solid;"; else echo "border:3px green solid;";?>" align="left">
											<span title="<?php echo $zauber_titel ?>" >
												<input onclick="set_button('<?php echo $hauptelement ?>', 'egal')" type="submit" name="button_zauber" value="<?php echo $zauber_id ?>" style="height:60px; width:60px; opacity:0.0;">
											</span>
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
	
	/* Anzeige Elemente umschalten */
	function sichtbar_elemente(wert) {
		switch(wert)
		{
			case 'menü':		
				document.getElementById('spielmenü_2').style.display = 'block';
			break;
		}
	}
	
	/* Löschfunktion für Spieler */
	function buttonwechsel(spieler_id) {
		document.getElementById("b_sp_loe_" + spieler_id + "_1").style.visibility="hidden";
		document.getElementById("b_sp_loe_" + spieler_id + "_2").style.visibility="visible";
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
	
</script>