<?php

# Zeitzone setzen
date_default_timezone_set("Europe/Berlin");

# Zeitstempel erzeugen
function timestamp()
{
	$time_unix = time();
	$tstamp = date("Y-m-d",$time_unix) . " " . date("H:i:s",$time_unix);
	return $tstamp;
}

# Zeitumrechnung
function time_to_timestamp($time_unix)
{
	$tstamp = date("Y-m-d",$time_unix) . " " . date("H:i:s",$time_unix);
	return $tstamp;
}

# Maximale Gesundheit berechnen
function berechne_max_gesundheit($start_staerke, $start_intelligenz, $start_magie){
	return (4*$start_staerke + 4*$start_intelligenz + 4*$start_magie);
}

# Maximale Energie berechnen
function berechne_max_energie($start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft){
	return ($start_element_feuer + $start_element_wasser + $start_element_erde + $start_element_luft);	
}

function bild_zu_spielerlevel($bilder_id)
{
	return get_bild_zu_id($bilder_id);
}


# Wahrscheinlichkeitsberechnung
function check_wkt($wkt)
{
	$zufall = rand(1,100);
	return ($zufall <= $wkt);
}


function zeige_hintergrundbild($gebiet_id)
{
	?><p align="center" style="margin-top:100px; margin-bottom:0px; font-size:14pt;">
		<img src="<?php echo get_bild_zu_gebiet($gebiet_id) ?>" width="100%" height="60%" alt=""/><br><br>
		<?php echo get_gebiet($gebiet_id)[3]; ?>
	</p><?php
}


function zeige_gebietslinks($gebiet_id)
{
	if ($zielgebiete = get_gebiet_gebiet($gebiet_id))
	{		
		while($row = $zielgebiete->fetch_array(MYSQLI_NUM))
		{
	?>			
		<input id="gebietslinks" type="submit" name="button_zum_zielgebiet" value="<?php echo $row[3]; ?>"/><br>
	<?php
		}
	}
	else{
		echo "<br />\nKeine Zielgebiete gefunden.<br />\n";
	}
}


function zeige_erbeutete_items($spieler_id, $npc_id, $text1, $text2)
{
	if ($npc = get_npc($npc_id))
	{		
		while($row = $npc->fetch_array(MYSQLI_NUM))
		{
			?>
			<p align="center" style="margin-top:75px; margin-bottom:0px; font-size:14pt;">
				<?php echo $text1 . $row[1] . $text2; ?>
			</p>
			<?php
		}
	} else {
		echo "<br />\nNPC mit id=[" . $npc_id . "] nicht gefunden.<br />\n";
	}
	
	if ($items = get_items_npc($npc_id))
	{		
		$counter = 0;
		?>
		<table border="1px" border-color="white" align="center" style="margin-top:75px;" width="500px" >
			<tr>
				<td>Item</td>
				<td>Beschreibung</td>
				<td>Anzahl</td>
			</tr>
		<?php
		while($row = $items->fetch_array(MYSQLI_NUM))
		{
			$item_wkt = $row[4];
			if(check_wkt($item_wkt))
			{
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
		if($counter == 0)
		{
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


function elemente_anzeigen($hauptelement)
{
	?><p align="center">
		<?php
			echo "Super! Das Hauptelement " . $hauptelement . " soll angezeigt werden, aber nichts passiert. <br /><br /> \n Echt toll!";
			?>
			<!-- Feuerelemente -->
			<div id="feuerinhalt" style="display:block;">
			
			<table id="zauberarten">
				<?php
				$zauberarten = get_zauberarten_zu_hauptelement($hauptelement);
				while($row = $zauberarten->fetch_array(MYSQLI_NUM))
				{
					$zauberart = $row[1];
					?>
					<tr><td align="left" style="padding-top:10px">
						<p><b><?php echo $zauberart ?></b></p>
						
						<table id="nebenelemente" border="1pt solid">
							<?php
							$nebenelemente = get_nebenelement_zu_hauptelement_zauberart($hauptelement, $zauberart);
							while($row = $nebenelemente->fetch_array(MYSQLI_NUM))
							{
								$nebenelement = $row[0];
								?>
									<tr>
										<?php
										$zauber = get_zauber_zu_hauptelement_nebenelement_zauberart($hauptelement, $nebenelement, $zauberart);
										while($row = $zauber->fetch_array(MYSQLI_NUM))
										{
											$zauber_id = $row[0];
											$zauber_titel = $row[2];
											$zauber_beschreibung = $row[5];
											$zauber_bilder_id = $row[1];
											?>
											<td style="background-image:url(<?php echo get_bild_zu_id($zauber_bilder_id) ?>); background-repeat:no-repeat; background-size:contain;" align="left">
												<span title="<?php echo $zauber_titel ?>" >
													<input type="button" name="button_zauber" value="<?php echo $zauber_id ?>" style="height:60px; width:60px; opacity:0.0;">
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
			
			
			<table id="elemente_tabelle" cellspacing="5px" style="padding-top:200px;">
				<tr>
					<td><span title="Asche"><input type="button" style="background:url(../Bilder/Elemente/Asche.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_asche" value=""></span></td>
					<td>---------------></td>
					<td><span title="Glas"><input type="button" style="background:url(../Bilder/Elemente/Glas.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_glas" value=""></span></td>
					<td><span title="Metall"><input type="button" style="background:url(../Bilder/Elemente/Metall.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_metall" value=""></span></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><span title="Alkohol"><input type="button" style="background:url(../Bilder/Elemente/Alkohol.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_alkohol" value=""></span></td>
					<td>---------------></td>
					<td><span title="S&auml;ure"><input type="button" style="background:url(../Bilder/Elemente/Saeure.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_saeure" value=""></span></td>
					<td>---------------></td>
					<td><span title="Gift"><input type="button" style="background:url(../Bilder/Elemente/Gift.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_gift" value=""></span></td>
					<td></td>
				</tr>
				<tr>
					<td><span title="Funke"><input type="button" style="background:url(../Bilder/Elemente/Funke.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_funke" value=""></td>
					<td><span title="Flamme"><input type="button" style="background:url(../Bilder/Elemente/Flamme.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_flamme" value=""></td>
					<td>---------------></td>
					<td><span title="Lichtstrahl"><input type="button" style="background:url(../Bilder/Elemente/Lichtstrahl.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_lichtstrahl" value=""></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>----------------</td>
					<td>----------------</td>
					<td>----------------</td>
					<td>----------------</td>
					<td>---------------></td>
					<td><span title="Explosion"><input type="button" style="background:url(../Bilder/Elemente/Explosion.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_explosion" value=""></span></td>
				</tr>
				<tr>
					<td><span title="Schwellbrand"><input type="button" style="background:url(../Bilder/Elemente/Schwelbrand.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_schwelbrand" value=""></span></td>
					<td><span title="Lava"><input type="button" style="background:url(../Bilder/Elemente/Lava.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_lava" value=""></span></td>
					<td>----------------</td>
					<td>---------------></td>
					<td><span title="Magma"><input type="button" style="background:url(../Bilder/Elemente/Magma.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_magma" value=""></span></td>
					<td></td>
				</tr>
				<tr>
					<td>----------------</td>
					<td>---------------></td>
					<td><span title="Feuerball"><input type="button" style="background:url(../Bilder/Elemente/Feuerball.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_feuerball" value=""></span></td>
					<td>---------------></td>
					<td><span title="Blitz"><input type="button" style="background:url(../Bilder/Elemente/Blitz.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_blitz" value=""></span></td>
					<td><span title="Feuersturm"><input type="button" style="background:url(../Bilder/Elemente/Feuersturm.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_feuersturm" value=""></span></td>
				</tr>
				<tr>
					<td>---------------></td>
					<td><span title="Glut"><input type="button" style="background:url(../Bilder/Elemente/Glut.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_glut" value=""></span></td>
					<td>---------------></td>
					<td><span title="&Ouml;lbrand"><input type="button" style="background:url(../Bilder/Elemente/Oelbrand.png);height:60px;width:60px;background-repeat:no-repeat;" name="button_oelbrand" value=""></span></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
			</table>
			</div>
		</p>
	<?php
}

function scanneNeueBilder($ordner)
{
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

function pfad_fuer_style($pfad)
{
	return substr($pfad,1);	
}

?>

<script>
	function client_times()
	{
		/* Lokalzeit Client */
		var now = new Date();
		document.getElementById("clientzeit").innerHTML = convert_to_datetime(now);
		
		var timeset;
		if (sommerzeit()) timeset = "GMT+0200";
		else timeset = "GMT+0100";
		
		/* Zeiten für Aktionen */
		var aktion_titel = document.getElementById("titel_temp").value;
		if (!aktion_titel) {
			/*
			document.getElementById("aktion_startzeit").innerHTML = "---";
			document.getElementById("aktion_endezeit").innerHTML = "---";
			document.getElementById("aktion_diffVonStart").innerHTML = "---";
			document.getElementById("aktion_diffBisEnde").innerHTML = "---";
			document.getElementById("aktion_gesamtzeit").innerHTML = "---";
			*/
			set_ladebalken('-', '-', '-');
		} else {
			var aktion_start = new Date(document.getElementById("startzeit_temp").value); /* Start */
			var aktion_ende = new Date(document.getElementById("endezeit_temp").value); /* Ende */
			var aktion_diffvs = now - new Date(document.getElementById("startzeit_temp").value); /* Zeit von Start  + timeset*/
			var aktion_diffbe = new Date(document.getElementById("endezeit_temp").value) - now; /* Zeit bis Ende  + timeset*/
			var aktion_gesamt = aktion_ende - aktion_start; /* Gesamtzeit */
			/*
			document.getElementById("aktion_startzeit").innerHTML = convert_to_datetime(aktion_start);
			document.getElementById("aktion_endezeit").innerHTML = convert_to_datetime(aktion_ende);
			document.getElementById("aktion_diffVonStart").innerHTML = convert_to_time(aktion_diffvs);
			document.getElementById("aktion_diffBisEnde").innerHTML = convert_to_time(aktion_diffbe);
			document.getElementById("aktion_gesamtzeit").innerHTML = convert_to_time(aktion_gesamt);
			*/
			set_ladebalken(aktion_diffvs, aktion_diffbe, aktion_gesamt);
		}
	}
	
	function Elemente()
	{
		var e = document.getElementById('elemente');
		switch(e.style.display)
		{
		case 'none':
			e.style.display='block';
			break;
		case 'block':
			e.style.display='none';
			break;
		default:
			e.style.display='block';
		}
	}
	
	/* Runde Zahl x auf n Nachkommastellen */
	function runde(x, n)
	{
		var e = Math.pow(10, n);
		var k = (Math.round(x * e) / e).toString();
		if (k.indexOf('.') == -1) k += '.';
		k += e.toString().substring(1);
		return k.substring(0, k.indexOf('.') + n+1);
	}
	
	/* Rundet Zahl x ab und auf Ganzzahl */
	function runde_ab(x)
	{
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
    		    document.getElementById('balken').style.width = runde(zeit_prozent, 1).toString() + "%";
				document.getElementById('prozent').innerHTML = convert_to_time(zeit_ende);
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
	function sichtbar_elemente(wert)
	{
		switch(wert)
		{
			case 1:
                document.getElementById('erdinhalt').style.display = 'block';
                document.getElementById('wasserinhalt').style.display = 'none';
                document.getElementById('feuerinhalt').style.display = 'none';
                document.getElementById('luftinhalt').style.display = 'none';
			break;
			case 2:
                document.getElementById('erdinhalt').style.display = 'none';
                document.getElementById('wasserinhalt').style.display = 'block';
                document.getElementById('feuerinhalt').style.display = 'none';
                document.getElementById('luftinhalt').style.display = 'none';     
			break;
			case 3:
                document.getElementById('erdinhalt').style.display = 'none';
                document.getElementById('wasserinhalt').style.display = 'none';
                document.getElementById('feuerinhalt').style.display = 'block';
                document.getElementById('luftinhalt').style.display = 'none';
			break;
			case 4:
                document.getElementById('erdinhalt').style.display = 'none';
                document.getElementById('wasserinhalt').style.display = 'none';
                document.getElementById('feuerinhalt').style.display = 'none';
                document.getElementById('luftinhalt').style.display = 'block';                                
			break;
			case 'false':		
				document.getElementById('erdinhalt').style.display = 'none';
                document.getElementById('wasserinhalt').style.display = 'none';
                document.getElementById('feuerinhalt').style.display = 'none';
                document.getElementById('luftinhalt').style.display = 'none';
			break;
			case 'menü':		
				document.getElementById('spielmenü_2').style.display = 'block';
			break;
			/*
			default:		
				document.getElementById('erdinhalt').style.display = 'none';
                document.getElementById('wasserinhalt').style.display = 'none';
                document.getElementById('feuerinhalt').style.display = 'none';
                document.getElementById('luftinhalt').style.display = 'none';
			break;
			*/
		}
	}
	
	/* Löschfunktion für Spieler */
	function buttonwechsel(spieler_id)
	{
		document.getElementById("b_sp_loe_" + spieler_id + "_1").style.visibility="hidden";
		document.getElementById("b_sp_loe_" + spieler_id + "_2").style.visibility="visible";
	}
	
	function set_button(button_name, button_value="")
	{
		document.getElementById("button_name_id").value=button_name;
		document.getElementById("button_value_id").value=button_value;
	}
	
	function set_button_submit(button_name, button_value="")
	{
		document.getElementById("button_name_id").value=button_name;
		document.getElementById("button_value_id").value=button_value;
		document.getElementById("dvg_admin").submit();
	}
	
	
	
	
	
</script>