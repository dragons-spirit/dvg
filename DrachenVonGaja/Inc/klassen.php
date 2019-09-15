<?php

class LoginSpieler {
	public $id;
	public $account_id;
	public $bilder_id;
	public $gattung;
	public $level_id;
	public $startgebiet;
	public $name;
	public $geschlecht;

	public function __construct($ds=null) {
		$this->set($ds);
	}
	
	public function set($ds) {
		$this->id = $ds[0];
		$this->account_id = $ds[1];
		$this->bilder_id = $ds[2];
		$this->gattung = $ds[3];
		$this->level_id = $ds[4];
		$this->startgebiet = $ds[5];
		$this->name = $ds[6];
		$this->geschlecht = $ds[7];
	}
}


class Spieler {
	public $id;
	public $account_id; 
	public $bilder_id; 
	public $gattung_id; 
	public $level_id; 
	public $gebiet_id; 
	public $name; 
	public $geschlecht; 
	public $staerke; 
	public $intelligenz; 
	public $magie; 
	public $element_feuer; 
	public $element_wasser; 
	public $element_erde; 
	public $element_luft; 
	public $gesundheit; 
	public $max_gesundheit; 
	public $energie; 
	public $max_energie; 
	public $zauberpunkte; 
	public $max_zauberpunkte; 
	public $balance;
	public $initiative;
	public $abwehr;
	public $ausweichen;
	public $zuletzt_gespielt;

	public function __construct($ds=null) {
		$this->id = $ds[0];
		$this->account_id = $ds[1];
		$this->bilder_id = $ds[2];
		$this->gattung_id = $ds[3];
		$this->level_id = $ds[4];
		$this->gebiet_id = $ds[5];
		$this->name = $ds[6];
		$this->geschlecht = $ds[7];
		$this->staerke = $ds[8];
		$this->intelligenz = $ds[9];
		$this->magie = $ds[10];
		$this->element_feuer = $ds[11];
		$this->element_wasser = $ds[12];
		$this->element_erde = $ds[13];
		$this->element_luft = $ds[14];
		$this->gesundheit = $ds[15];
		$this->max_gesundheit = $ds[16];
		$this->energie = $ds[17];
		$this->max_energie = $ds[18];
		$this->zauberpunkte = $ds[19];
		$this->max_zauberpunkte = $ds[20];
		$this->balance = $ds[21];
		$this->initiative = $ds[22];
		$this->abwehr = $ds[23];
		$this->ausweichen = $ds[24];
		$this->zuletzt_gespielt = $ds[25];
	}
}


class NPC {
	public $id;
	public $bilder_id;
	public $element_id;
	public $name;
	public $familie;
	public $staerke;
	public $intelligenz;
	public $magie;
	public $element_feuer;
	public $element_wasser;
	public $element_erde;
	public $element_luft;
	public $gesundheit;
	public $energie;
	public $zauberpunkte;
	public $initiative;
	public $abwehr;
	public $ausweichen;
	public $beschreibung;
	public $typ;

	public function __construct($ds=null) {
		if ($ds == null) $this->set_null();
			else $this->set($ds);
	}
	
	public function set($ds) {
		$this->id = $ds[0];
		$this->bilder_id = $ds[1];
		$this->element_id = $ds[2];
		$this->name = $ds[3];
		$this->familie = $ds[4];
		$this->staerke = $ds[5];
		$this->intelligenz = $ds[6];
		$this->magie = $ds[7];
		$this->element_feuer = $ds[8];
		$this->element_wasser = $ds[9];
		$this->element_erde = $ds[10];
		$this->element_luft = $ds[11];
		$this->gesundheit = $ds[12];
		$this->energie = $ds[13];
		$this->zauberpunkte = $ds[14];
		$this->initiative = $ds[15];
		$this->abwehr = $ds[16];
		$this->ausweichen = $ds[17];
		$this->beschreibung = $ds[18];
		$this->typ = $ds[19];
	}
	
	public function set_null() {
		$this->id = null;
		$this->bilder_id = null;
		$this->element_id = null;
		$this->name = "kein NPC gefunden";
		$this->familie = null;
		$this->staerke = 0;
		$this->intelligenz = 0;
		$this->magie = 0;
		$this->element_feuer = 0;
		$this->element_wasser = 0;
		$this->element_erde = 0;
		$this->element_luft = 0;
		$this->gesundheit = 0;
		$this->energie = 0;
		$this->zauberpunkte = 0;
		$this->initiative = 100;
		$this->abwehr = 0;
		$this->ausweichen = 0;
		$this->beschreibung = "kein NPC gefunden";
		$this->typ = null;
	}
}


class AktionSpieler {
	public $titel;
	public $text;
	public $art;
	public $beschreibung;
	public $start;
	public $ende;
	public $statusbild;
	public $status;
	public $any_id_1;
	public $any_id_2;

	public function __construct($ds=null) {
		if ($ds = null) $this->set_null();
			else $this->set($ds);
	}
	
	public function set($ds) {
		$this->titel = $ds[0];
		$this->text = $ds[1];
		$this->art = $ds[2];
		$this->beschreibung = $ds[3];
		$this->start = $ds[4];
		$this->ende = $ds[5];
		$this->statusbild = $ds[6];
		$this->status = $ds[7];
		$this->any_id_1 = $ds[8];
		$this->any_id_2 = $ds[9];
	}
	
	public function set_null() {
		$this->titel = null;
		$this->text = "keine aktuelle Aktion";
		$this->art = null;
		$this->beschreibung = null;
		$this->start = 0;
		$this->ende = 0;
		$this->statusbild = null;
		$this->status = null;
		$this->any_id_1 = 0;
		$this->any_id_2 = 0;
	}
}


class KampfTeilnehmer {
	public $name;
	public $bilder_id;
	public $id;
	public $typ;
	public $seite;
	public $gesundheit;
	public $gesundheit_max;
	public $zauberpunkte;
	public $zauberpunkte_max;
	public $staerke;
	public $intelligenz;
	public $magie;
	public $element_feuer;
	public $element_wasser;
	public $element_erde;
	public $element_luft;
	public $initiative;
	public $abwehr;
	public $ausweichen;
	public $timer;
	public $kt_id;
	
	public function __construct($ds=null, $typ=null, $seite=null) {
		if ($ds == null AND $typ == null AND $seite == null) $this->set_null();
		if ($ds != null AND $typ == null AND $seite == null) $this->set($ds);
		if ($ds != null AND $typ != null AND $seite > -1) $this->init($ds, $typ, $seite);
	}
	
	# Initialisierung bei Kampfbeginn
	public function init($ds, $typ, $seite) {
		$this->name = $ds->name;
		$this->bilder_id = $ds->bilder_id;
		$this->id = $ds->id;
		$this->typ = $typ;
		$this->seite = $seite;
		$this->gesundheit = $ds->gesundheit;
		if ($typ == "npc") {$this->gesundheit_max = $ds->gesundheit;
			} else {$this->gesundheit_max = $ds->max_gesundheit;}
		$this->zauberpunkte = $ds->zauberpunkte;
		if ($typ == "npc") {$this->zauberpunkte_max = $ds->zauberpunkte;
			} else {$this->zauberpunkte_max = $ds->max_zauberpunkte;}
		$this->staerke = $ds->staerke;
		$this->intelligenz = $ds->intelligenz;
		$this->magie = $ds->magie;
		$this->element_feuer = $ds->element_feuer;
		$this->element_wasser = $ds->element_wasser;
		$this->element_erde = $ds->element_erde;
		$this->element_luft = $ds->element_luft;
		$this->initiative = $ds->initiative;
		$this->abwehr = $ds->abwehr;
		$this->ausweichen = $ds->ausweichen;
		$this->timer = berechne_initiative($ds);
		$this->kt_id = null;
	}
	
	# Kampfteilnehmer mit Datensatz aus DB erstellen
	public function set($ds) {
		$this->name = $ds[0];
		$this->bilder_id = $ds[1];
		$this->id = $ds[2];
		$this->typ = $ds[3];
		$this->seite = $ds[4];
		$this->gesundheit = $ds[5];
		$this->gesundheit_max = $ds[6];
		$this->zauberpunkte = $ds[7];
		$this->zauberpunkte_max = $ds[8];
		$this->staerke = $ds[9];
		$this->intelligenz = $ds[10];
		$this->magie = $ds[11];
		$this->element_feuer = $ds[12];
		$this->element_wasser = $ds[13];
		$this->element_erde = $ds[14];
		$this->element_luft = $ds[15];
		$this->initiative = $ds[16];
		$this->abwehr = $ds[17];
		$this->ausweichen = $ds[18];
		$this->timer = $ds[19];
		$this->kt_id = $ds[20];
	}
	
	# Initialisierung mit NULL
	public function set_null(){
		$this->name = null;
		$this->bilder_id = null;
		$this->id = null;
		$this->typ = null;
		$this->seite = null;
		$this->gesundheit = null;
		$this->gesundheit_max = null;
		$this->zauberpunkte = null;
		$this->zauberpunkte_max = null;
		$this->staerke = null;
		$this->intelligenz = null;
		$this->magie = null;
		$this->element_feuer = null;
		$this->element_wasser = null;
		$this->element_erde = null;
		$this->element_luft = null;
		$this->initiative = null;
		$this->abwehr = null;
		$this->ausweichen = null;
		$this->timer = null;
		$this->kt_id = null;
	}
	
	public function erhoehe_timer($wert){
		$this->timer = $this->timer + $wert;
	}
	
	public function ausgabe(){
		echo "name : " . $this->name . "<br>";
		echo "bilder_id : " . $this->bilder_id . "<br>";
		echo "id : " . $this->id . "<br>";
		echo "typ : " . $this->typ . "<br>";
		echo "seite : " . $this->seite . "<br>";
		echo "gesundheit : " . $this->gesundheit . "<br>";
		echo "gesundheit_max : " . $this->gesundheit_max . "<br>";
		echo "zauberpunkte : " . $this->zauberpunkte . "<br>";
		echo "zauberpunkte_max : " . $this->zauberpunkte_max . "<br>";
		echo "staerke : " . $this->staerke . "<br>";
		echo "intelligenz : " . $this->intelligenz . "<br>";
		echo "magie : " . $this->magie . "<br>";
		echo "element_feuer : " . $this->element_feuer . "<br>";
		echo "element_wasser : " . $this->element_wasser . "<br>";
		echo "element_erde : " . $this->element_erde . "<br>";
		echo "element_luft : " . $this->element_luft . "<br>";
		echo "initiative : " . $this->initiative . "<br>";
		echo "abwehr : " . $this->abwehr . "<br>";
		echo "ausweichen : " . $this->ausweichen . "<br>";
		echo "timer : " . $this->timer . "<br>";
		echo "kt_id : " . $this->kt_id . "<br>";
	}
	
	public function ausgabe_kampf(){
		echo "Gesundheit : " . $this->gesundheit . "/" . $this->gesundheit_max . "<br>";
		echo "Zauberpunkte : " . $this->zauberpunkte . "/" . $this->zauberpunkte_max . "<br>";
		echo "Staerke : " . $this->staerke . "<br>";
		echo "Intelligenz : " . $this->intelligenz . "<br>";
		echo "Magie : " . $this->magie . "<br>";
		echo "Feuer : " . $this->element_feuer . "<br>";
		echo "Wasser : " . $this->element_wasser . "<br>";
		echo "Erde : " . $this->element_erde . "<br>";
		echo "Luft : " . $this->element_luft . "<br>";
		echo "Initiative : " . $this->initiative . "<br>";
		echo "Abwehr : " . $this->abwehr . "<br>";
		echo "Ausweichen : " . $this->ausweichen . "<br>";
		echo "Timer : " . $this->timer . "<br>";
	}
}


class KampfZauber {
	public $id;
	public $titel;
	public $bilder_id;
	public $zauberart_id;
	public $zauberart;
	public $hauptelement_id;
	public $nebenelement_id;
	public $verbrauch;
	public $beschreibung;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->titel = $ds[1];
		$this->bilder_id = $ds[2];
		$this->zauberart_id = $ds[3];
		$this->zauberart = $ds[4];
		$this->hauptelement_id = $ds[5];
		$this->nebenelement_id = $ds[6];
		$this->verbrauch = $ds[7];
		$this->beschreibung = $ds[8];
	}
}


class KampfZauberEffekt {
	public $id;
	public $zauber_id;
	public $art;
	public $attribut;
	public $wert;
	public $runden;
	public $jede_runde;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->zauber_id = $ds[1];
		$this->art = $ds[2];
		$this->attribut = $ds[3];
		$this->wert = $ds[4];
		$this->runden = $ds[5];
		$this->jede_runde = $ds[6];
	}
}

?>