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
?>

<script>
	function client_times()
	{
		/* Lokalzeit Client */
		var now = new Date();
		document.getElementById("clientzeit").innerHTML = (now.getFullYear() + '-' + ((now.getMonth() < 9) ? ("0" + (now.getMonth() + 1)) : (now.getMonth() + 1)) + '-' + (now.getDate()) + " " + now.getHours() + ':' + ((now.getMinutes() < 10) ? ("0" + now.getMinutes()) : (now.getMinutes())) + ':' + ((now.getSeconds() < 10) ? ("0" + now.getSeconds()) : (now.getSeconds())));
		
		/* Countdown bis laufende Aktion beendet */
		/*document.getElementById("endezeit_temp").style.display='none';*/
		var now_next = document.getElementById("endezeit_temp").value - runde_ab(now / 1000);
		if (now_next < 0) now_next = 0;
		var now_h = runde_ab(now_next / 3600);
		var now_rest = now_next - (now_h * 3600);
		var now_m = runde_ab(now_rest / 60);
		var now_s = now_rest - (now_m * 60);
		document.getElementById("endezeit_div").innerHTML = ((now_h < 10) ? ("0" + now_h) : (now_h)) + ' : ' + ((now_m < 10) ? ("0" + now_m) : (now_m)) + ' : ' + ((now_s < 10) ? ("0" + now_s) : (now_s));
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
</script>