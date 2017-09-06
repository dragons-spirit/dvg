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

function bild_zu_spielerlevel($level){
	switch ($level) {
    case 1:
        $bild = "Babydrache.png";
        break;
    case 2:
        $bild = "Drachenkind.png";
        break;
    case 3:
        $bild = "JugendlicherDrache.png";
        break;
	case 4:
        $bild = "ErwachsenerDrache.png";
        break;
	case 5:
        $bild = "AusgewachsenerDrache.png";
        break;
	case 6:
        $bild = "ErfahrenerDrache.png";
        break;
	case 7:
        $bild = "AlterDrache.png";
        break;
	}
	echo $bild;
	return;
}


# Wahrscheinlichkeitsberechnung
function check_wkt($wkt)
{
	$zufall = rand(1,100);
	return ($zufall <= $wkt);
}


function zeige_hintergrundbild($gebiet_id)
{
	?>
	<p align="center" style="margin-top:75px; margin-bottom:0px; font-size:14pt;">
		<img src="<?php echo get_bild_zu_gebiet($gebiet_id) ?>" width="100%" height="60%" alt=""/><br><br>
		<?php echo get_gebiet($gebiet_id)[3]; ?>
	</p> 
	<?php
}


function zeige_gebietslinks($gebiet_id)
{
	if ($zielgebiete = get_gebiet_gebiet($gebiet_id))
	{		
		while($row = $zielgebiete->fetch_array(MYSQLI_NUM))
		{
	?>			
		<input style="height: 25px; width: 80px;"  type="submit" name="button_zum_zielgebiet" value="<?php echo $row[3]; ?>"/><br>
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
</script>