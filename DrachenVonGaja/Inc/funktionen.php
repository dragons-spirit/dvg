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

/*
function bild_zu_startgebiet($gebiet_id)
{
	$gebietsbild = "vulkan.jpg";
	switch ($gebiet_id)
	{
		case 1: 	$gebietsbild = "sumpf.jpg";
					break;
		case 2: 	$gebietsbild = "vulkan.jpg";
					break;
		case 3: 	$gebietsbild = "eissee.jpg";
					break;
	    case 4: 	$gebietsbild = "dschungel.jpg";
					break;
	    case 5: 	$gebietsbild = "klippen.jpg";
					break;
	    case 6: 	$gebietsbild = "kristallhoehle.jpg";
					break;
		case 7: 	$gebietsbild = "wueste.jpg";
					break;
		case 8: 	$gebietsbild = "mammutbaum.jpg";
					break;
	    case 9: 	$gebietsbild ="wald.jpg";
					break;
		case 10: 	$gebietsbild ="oase.jpg";
					break;
		case 11: 	$gebietsbild ="steppe.jpg";
					break;
	}
	echo $gebietsbild;
	return;
}
*/

/*
function gebietwechseln($gebiet_id)
{
	switch($gebiet_id)
	{
		case 1:
			echo "<div id='dschungel'>Dschungel</div>";
			echo "<div id='wald'>Wald</div>";
			break;
		case 2:
			echo "<div id='klippen'>Klippen</div>";
			echo "<div id='kristall'>Kristallh&ouml;le</div>";
			break;
		case 3:
			echo "<div id='klippen'>Klippen</div>";
			echo "<div id='kristall'>Kristallh&ouml;hle</div>";
			break;
        case 4:
			echo "<div id='steppe'>Steppe</div>";
			echo "<div id='wald'>Wald</div>";
			echo "<div id='sumpf'>Sumpf</div>";
			break;
        case 5:
			echo "<div id='eissee'>Eissee</div>";
			echo "<div id='vulkan'>Vulkan</div>";
			echo "<div id='wueste'>W&uuml;ste</div>";
			break;
        case 6:
			echo "<div id='vulkan'>Vulkan</div>";
			echo "<div id='eissee'>Eissee</div>";
			echo "<div id='wald'>Wald</div>";
			break;
        case 7:
			echo "<div id='oase'>Oase</div>";
			echo "<div id='klippen'>Klippen</div>";
			echo "<div id='steppe'>Steppe</div>";
			break;
        case 8:
			echo "<div id='wald'>Wald</div>";
			echo "<div id='dschungel'>Dschungel</div>";
			break;
	}
}
*/
?>