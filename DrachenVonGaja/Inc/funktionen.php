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

# Maximale Gesundheit berechnen
function berechne_max_gesundheit($start_staerke, $start_intelligenz, $start_magie){
	return (4*$start_staerke + 4*$start_intelligenz + 4*$start_magie);
}

# Maximale Energie berechnen
function berechne_max_energie($start_element_feuer, $start_element_wasser, $start_element_erde, $start_element_luft){
	return ($start_element_feuer + $start_element_wasser + $start_element_erde + $start_element_luft);	
}

function bild_zu_spielerlevel($level){
	$bild = "buchstabe_E.png";
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


?>