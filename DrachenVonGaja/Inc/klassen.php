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
	public $erfahrung;

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
		$this->erfahrung = $ds[26];
	}
	
	public function gewinn_verrechnen($gewinn, $mit_balance) {
		global $balance_aktiv;
		if ($balance_aktiv AND $mit_balance){
			$this->staerke = $this->staerke + floor_x($gewinn->staerke * ($this->balance / 100), 10);
			$this->intelligenz = $this->intelligenz + floor_x($gewinn->intelligenz * ($this->balance / 100), 10);
			$this->magie = $this->magie + floor_x($gewinn->magie * ($this->balance / 100), 10);
			$this->element_feuer = $this->element_feuer + floor_x($gewinn->element_feuer * ($this->balance / 100), 10);
			$this->element_wasser = $this->element_wasser + floor_x($gewinn->element_wasser * ($this->balance / 100), 10);
			$this->element_erde = $this->element_erde + floor_x($gewinn->element_erde * ($this->balance / 100), 10);
			$this->element_luft = $this->element_luft + floor_x($gewinn->element_luft * ($this->balance / 100), 10);
			$this->gesundheit = $this->gesundheit + $gewinn->gesundheit;
			$this->energie = $this->energie + $gewinn->energie;
			$this->zauberpunkte = $this->zauberpunkte + $gewinn->zauberpunkte;
			$this->initiative = $this->initiative + floor_x($gewinn->initiative * ($this->balance / 100), 10);
			$this->abwehr = $this->abwehr + floor_x($gewinn->abwehr * ($this->balance / 100), 10);
			$this->ausweichen = $this->ausweichen + floor_x($gewinn->ausweichen * ($this->balance / 100), 10);
			$this->erfahrung = $this->erfahrung + floor_x($gewinn->erfahrung * ($this->balance / 100), 10);
		} else {
			$this->staerke = $this->staerke + $gewinn->staerke;
			$this->intelligenz = $this->intelligenz + $gewinn->intelligenz;
			$this->magie = $this->magie + $gewinn->magie;
			$this->element_feuer = $this->element_feuer + $gewinn->element_feuer;
			$this->element_wasser = $this->element_wasser + $gewinn->element_wasser;
			$this->element_erde = $this->element_erde + $gewinn->element_erde;
			$this->element_luft = $this->element_luft + $gewinn->element_luft;
			$this->gesundheit = $this->gesundheit + $gewinn->gesundheit;
			$this->energie = $this->energie + $gewinn->energie;
			$this->zauberpunkte = $this->zauberpunkte + $gewinn->zauberpunkte;
			$this->initiative = $this->initiative + $gewinn->initiative;
			$this->abwehr = $this->abwehr + $gewinn->abwehr;
			$this->ausweichen = $this->ausweichen + $gewinn->ausweichen;
			$this->erfahrung = $this->erfahrung + $gewinn->erfahrung;
		}
	}
	
	# Regeneration der Spielerwerte (Gesundheit, Energie, Zauberpunkte) um Prozent vom jeweiligen Maximum
	public function erholung_prozent($gesundheit_proz = 100, $energie_proz = 100, $zauberpunkte_proz = 100){
		$this->attribut_aendern("gesundheit", floor_x($this->max_gesundheit * ($gesundheit_proz/100), 0), 0, $this->max_gesundheit);
		$this->attribut_aendern("energie", floor_x($this->max_energie * ($energie_proz/100), 0), 0, $this->max_energie);
		$this->attribut_aendern("zauberpunkte", floor_x($this->max_zauberpunkte * ($zauberpunkte_proz/100), 0), 0, $this->max_zauberpunkte);
		$this->db_update();
	}
	
	# Ändert Attribut um Wert (beachtet übergebene Grenzwerte)
	public function attribut_aendern($attribut, $wert, $min=-9999999, $max=9999999){
		$this->$attribut = $this->$attribut + $wert;
		if ($this->$attribut < $min) $this->$attribut = $min;
		if ($this->$attribut > $max) $this->$attribut = $max;
	}
	
	# Übernimmt aktuelle Werte des Kampfteilnehmers
	public function uebernehme_kt_werte($kt){
		$this->gesundheit = $kt->gesundheit;
		if ($this->gesundheit > $this->max_gesundheit) $this->gesundheit = $this->max_gesundheit;
		$this->zauberpunkte = $kt->zauberpunkte;
		if ($this->zauberpunkte > $this->max_zauberpunkte) $this->zauberpunkte = $this->max_zauberpunkte;
	}
	
	# Spieler rekonfigurieren (Neuberechnung von Gesundheit, Energie, Zauberpunkte, Balance)
	public function neuberechnung(){
		$level_up = false;
		if ($this->erfahrung >= get_erfahrung_naechster_level($this->level_id)){
			$level_up = true;
			$this->level_up();
		}
		$this->max_gesundheit = berechne_max_gesundheit($this);
		$this->max_energie = berechne_max_energie($this);
		$this->max_zauberpunkte = berechne_max_zauberpunkte($this);
		$this->balance = berechne_balance($this);
		if ($level_up){
			$this->gesundheit = $this->max_gesundheit;
			$this->energie = $this->max_energie;
			$this->zauberpunkte = $this->max_zauberpunkte;
		}
		$this->db_update();
	}
	
	# Spielerlevel erhöhen und Belohnungen anrechnen
	public function level_up(){
		$belohnung = get_gewinn_naechster_level($this->level_id);
		$this->level_id = $this->level_id + 1;
		$this->bilder_id = get_bild_zu_gattung_level($this->gattung_id, $this->level_id);
		$this->gewinn_verrechnen($belohnung, false);
	}
	
	# Aktualisiert die Spielerdaten in der Datenbank
	public function db_update(){
		global $debug;
		global $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
				UPDATE spieler
				SET bilder_id = ?,
					level_id = ?,
					name = ?,
					staerke = ?,
					intelligenz = ?,
					magie = ?,
					element_feuer = ?,
					element_wasser = ?,
					element_erde = ?,
					element_luft = ?,
					gesundheit = ?,
					max_gesundheit = ?,
					energie = ?,
					max_energie = ?,					
					zauberpunkte = ?,
					max_zauberpunkte = ?,
					initiative = ?,
					abwehr = ?,
					ausweichen = ?,
					balance = ?,
					erfahrung = ?
				WHERE id = ?")){
			$stmt->bind_param('ddsddddddddddddddddddd',
					$this->bilder_id,
					$this->level_id,
					$this->name,
					$this->staerke,
					$this->intelligenz,
					$this->magie,
					$this->element_feuer,
					$this->element_wasser,
					$this->element_erde,
					$this->element_luft,
					$this->gesundheit,
					$this->max_gesundheit,
					$this->energie,
					$this->max_energie,
					$this->zauberpunkte,
					$this->max_zauberpunkte,
					$this->initiative,
					$this->abwehr,
					$this->ausweichen,
					$this->balance,
					$this->erfahrung,
					$this->id);
			$stmt->execute();
			if ($debug) echo "<br />\nSpieler wurde in Datenbank aktualisiert.<br />\n";
			return true;
		} else {
			echo "<br />\nQuerryfehler in spieler->db_update()<br />\n";
			return false;
		}
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
	public $ki_id;
	public $erfahrung;

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
		$this->ki_id = $ds[20];
		$this->erfahrung = $ds[21];
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
		$this->ki_id = 0;
		$this->erfahrung = 0;
	}
}


class Item {
	public $id;
	public $bilder_id;
	public $name;
	public $beschreibung;
	public $typ;
	public $fund;
	public $anzahl;

	public function __construct($ds=null) {
		if ($ds == null) $this->set_null();
			else $this->set($ds);
	}
	
	public function set($ds) {
		$this->id = $ds[0];
		$this->bilder_id = $ds[1];
		$this->name = $ds[2];
		$this->beschreibung = $ds[3];
		$this->typ = $ds[4];
		$this->fund = null;
		$this->anzahl = 0;
	}
	
	public function set_null() {
		$this->id = null;
		$this->bilder_id = null;
		$this->name = null;
		$this->beschreibung = null;
		$this->typ = null;
		$this->fund = null;
		$this->anzahl = 0;
	}
}


class ItemFund {
	public $wahrscheinlichkeit;
	public $anzahl_min;
	public $anzahl_max;
	
	public function __construct($ds) {
		$this->wahrscheinlichkeit = $ds[0];
		$this->anzahl_min = $ds[1];
		$this->anzahl_max = $ds[2];
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
	public $ki_id;
	public $gewinn_id;
		
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
		if ($typ == "npc") {$this->timer = berechne_initiative($ds);
			} else {$this->timer = 0;}
		$this->kt_id = null;
		if ($typ == "npc") {$this->ki_id = $ds->ki_id;
			} else {$this->ki_id = 0;}
		if ($typ == "npc") {$this->gewinn_id = null;
			} else {$temp = new Gewinn(); $this->gewinn_id = $temp->id;}
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
		$this->ki_id = $ds[21];
		$this->gewinn_id = $ds[22];
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
		$this->ki_id = null;
		$this->gewinn_id = null;
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
		echo "ki_id : " . $this->ki_id . "<br>";
		echo "gewinn_id : " . $this->gewinn_id . "<br>";
	}
	
	public function ausgabe_kampf($log_detail = 1){
		if ($log_detail >= 0){
			echo "Gesundheit : " . $this->gesundheit . "/" . $this->gesundheit_max . "<br>";
			echo "Zauberpunkte : " . $this->zauberpunkte . "/" . $this->zauberpunkte_max . "<br>";
		}
		if ($log_detail >= 1){
			echo "Initiative : " . $this->initiative . "<br>";
			echo "Abwehr : " . $this->abwehr . "<br>";
			echo "Ausweichen : " . $this->ausweichen . "<br>";
			echo "Timer : " . $this->timer . "<br>";
		}
		if ($log_detail >= 2){
			echo "Staerke : " . $this->staerke . "<br>";
			echo "Intelligenz : " . $this->intelligenz . "<br>";
			echo "Magie : " . $this->magie . "<br>";
			echo "Feuer : " . $this->element_feuer . "<br>";
			echo "Wasser : " . $this->element_wasser . "<br>";
			echo "Erde : " . $this->element_erde . "<br>";
			echo "Luft : " . $this->element_luft . "<br>";
		}
	}
	
	# Ändert Attribut um Wert (beachtet übergebene Grenzwerte)
	public function attribut_aendern($attribut, $wert, $min=-9999999, $max=9999999){
		$this->$attribut = $this->$attribut + $wert;
		if ($this->$attribut < $min) $this->$attribut = $min;
		if ($this->$attribut > $max) $this->$attribut = $max;
	}
	
	# Prüft Gesundheit und gibt true oder false zurück
	public function ist_tot(){
		if ($this->gesundheit <= 0) return true;
			else return false;
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
	public $wahrscheinlichkeit;
	public $zaubereffekte;
	public $nutzbar_von;
	public $zauber_text_id;

	public function __construct($ds, $zaubereffekte) {
		$this->id = $ds[0];
		$this->titel = $ds[1];
		$this->bilder_id = $ds[2];
		$this->zauberart_id = $ds[3];
		$this->zauberart = $ds[4];
		$this->hauptelement_id = $ds[5];
		$this->nebenelement_id = $ds[6];
		$this->verbrauch = $ds[7];
		$this->beschreibung = $ds[8];
		$this->wahrscheinlichkeit = $ds[9];
		$this->zaubereffekte = $zaubereffekte;
		$this->nutzbar_von = $ds[10];
		$this->zauber_text_id = $ds[11];
	}
	
	# Prüft ob es sich um einen Zauber handelt oder um einen Standardangriff
	public function ist_zauber(){
		if ($this->verbrauch > 0) return true;
		else return false;
	}
	
	# Prüft ob es sich um eine bestimmte Art von Zauber handelt
	public function ist_art($art){
		if ($this->zaubereffekte[0]->art == $art) return true;
		else return false;
	}
	
	# Prüft das Element des Zaubers und gibt die korrespondierende Attributbezeichnung für den Kampfteilnehmer zurück
	# Es muss das erwartete Element übergeben werden ("hauptelement", "nebenelement" oder "gegenelement" für das Gegenstück zum Hauptelement)
	public function attribut_bez($topic){
		switch ($topic){
			case "hauptelement": $id = $this->hauptelement_id; break;
			case "nebenelement": $id = $this->nebenelement_id; break;
			case "gegenelement": $id = $this->hauptelement_id; break;
			default: $id = false; break;
		}
		switch ($topic){
			case "hauptelement":
				switch ($id){
					case 2: $element = "element_feuer"; break;
					case 3: $element = "element_wasser"; break;
					case 4: $element = "element_erde"; break;
					case 5: $element = "element_luft"; break;
					default: $element = false; break;
				}
				break;
			case "nebenelement":
				switch ($id){
					case 2: $element = "element_feuer"; break;
					case 3: $element = "element_wasser"; break;
					case 4: $element = "element_erde"; break;
					case 5: $element = "element_luft"; break;
					default: $element = false; break;
				}
				break;
			case "gegenelement":
				switch ($id){
					case 2: $element = "element_luft"; break;
					case 3: $element = "element_erde"; break;
					case 4: $element = "element_feuer"; break;
					case 5: $element = "element_wasser"; break;
					default: $element = false; break;
				}
				break;
			default: $element = false; break;
		}
		return $element;
	}
	
	# Lädt weiterführende Zaubertexte für Kampflog und ersetzt Variablen im Text
	public function ausgabe_log($param, $arr, $log_detail){
		global $debug;
		global $connect_db_dvg;
		switch ($param){
			case "ziel_falsch": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT ziel_falsch_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT ziel_falsch_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "ziel_tot": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT ziel_tot_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT ziel_tot_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "zauberpunkte": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT zauberpunkte_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT zauberpunkte_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "patzer": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT patzer_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT patzer_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "ausweichen": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT ausweichen_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT ausweichen_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "abwehr": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT abwehr_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT abwehr_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			case "erfolg": 
				switch ($log_detail){
					case 0: $stmt = $connect_db_dvg->prepare("SELECT erfolg_kurz FROM zauber_text WHERE id = ?"); break;
					default: $stmt = $connect_db_dvg->prepare("SELECT erfolg_standard FROM zauber_text WHERE id = ?"); break;
				}
				break;
			default: return false;
		}
		if ($stmt){
			$stmt->bind_param('d', $this->zauber_text_id);
			$stmt->execute();
			$rohtext = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			if ($debug) echo "<br />\Rohtext für Zauber '".$this->titel."' wurde geladen.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in Zauber->ausgabe_log()<br />\n";
			return false;
		}
		$suche = array("§zaubernder", "§zauberziel", "§zauber");
		$variablen = array($arr["zaubernder"], $arr["zauberziel"], $arr["zauber"]);
		$text = str_replace($suche, $variablen, $rohtext);
		return $text;
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
	public $spezial;
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->zauber_id = $ds[1];
		$this->art = $ds[2];
		$this->attribut = $ds[3];
		$this->wert = $ds[4];
		$this->runden = $ds[5];
		$this->jede_runde = $ds[6];
		if ($this->attribut == "spezial") {$this->spezial = get_zauber_effekt_spezial($this->wert);
			} else {$this->spezial = false;}
	}
}


class ZauberEffektSpezial {
	public $id;
	public $art;
	public $spezial_tabelle;
	public $spezial_id;
	public $sql;
	public $text1;
	public $text2;
	public $text3;
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->art = $ds[1];
		$this->spezial_tabelle = $ds[2];
		$this->spezial_id = $ds[3];
		$this->sql = $ds[4];
		$this->text1 = $ds[5];
		$this->text2 = $ds[6];
		$this->text3 = $ds[7];
	}
}


class KampfEffekt {
	public $id;
	public $zauber_name;
	public $art;
	public $attribut;
	public $wert;
	public $runden;
	public $runden_max;
	public $jede_runde;
	public $ausgefuehrt;
	public $beendet;
	public $spezial;
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->zauber_name = $ds[1];
		$this->art = $ds[2];
		$this->attribut = $ds[3];
		$this->wert = $ds[4];
		$this->runden = $ds[5];
		$this->runden_max = $ds[6];
		$this->jede_runde = $ds[7];
		$this->ausgefuehrt = $ds[8];
		$this->beendet = $ds[9];
		if ($this->attribut == "spezial") {$this->spezial = get_zauber_effekt_spezial($this->wert);
			} else {$this->spezial = false;}
	}
}


class KI {
	public $id;
	public $name;
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->name = $ds[1];
	}
}


class Kampf {
	public $id;
	public $gebiet_id;
	public $log;	
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->gebiet = $ds[1];
		$this->log = $ds[2];
	}
	
	public function log_effekt($kt, $kampf_effekt, $zuruecksetzen=false){
		global $kampf_log_detail;
		if ($kampf_log_detail == 2){
			switch ($kampf_effekt->attribut){
				case "gesundheit": $attribut = "Gesundheit"; break;
				case "zauberpunkte": $attribut = "Zauberpunkte"; break;
				case "staerke": $attribut = "Stärke"; break;
				case "intelligenz": $attribut = "Intelligenz"; break;
				case "magie": $attribut = "Magie"; break;
				case "element_feuer": $attribut = "Feuer"; break;
				case "element_wasser": $attribut = "Wasser"; break;
				case "element_erde": $attribut = "Erde"; break;
				case "element_luft": $attribut = "Luft"; break;
				case "timer": $attribut = "Timer"; break;
				case "initiative": $attribut = "Initiative"; break;
				case "ausweichen": $attribut = "Ausweichen"; break;
				case "abwehr": $attribut = "Abwehr"; break;
				case "spezial": $attribut = "Spezial"; break;
				default: break;
			}
			if ($attribut <> "Spezial"){
				if ($zuruecksetzen){
					$this->log = $kt->name.": ".-$kampf_effekt->wert." ".$attribut." durch Beendigung von ".$kampf_effekt->zauber_name."<br>" . $this->log;
				} else {
					$this->log = $kt->name.": ".$kampf_effekt->wert." ".$attribut." durch ".$kampf_effekt->zauber_name."<br>" . $this->log;
				}
			}
		}
	}
	
	public function log_tot($kt){
		$this->log = "<font color='red'>" . $kt->name . " stirbt im Kampf.</font><br>" . $this->log;
	}
}


class Gewinn {
	public $id;
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
	public $erfahrung;

	public function __construct($ds=null) {
		if ($ds == null){
			$this->init_null();
		} else {
			$this->init($ds);
		}
	}
	
	public function init_null() {
		global $connect_db_dvg;
		global $debug;
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO gewinn
			VALUES ()")){
			$stmt->execute();
			$stmt = $connect_db_dvg->prepare("SELECT * FROM gewinn WHERE id=(SELECT MAX(id) FROM gewinn)");
			$stmt->execute();
			$gewinn = $stmt->get_result()->fetch_array(MYSQLI_NUM);
			if ($debug) echo "<br />\Gewinn (id=" . $gewinn_id . ") wurde hinzugefügt.<br />\n";
		} else {
			echo "<br />\nQuerryfehler in Gewinn->init_null()<br />\n";
		}
		$this->init($gewinn);
	}
	
	public function init($ds) {
		$this->id = $ds[0];
		$this->staerke = $ds[1];
		$this->intelligenz = $ds[2];
		$this->magie = $ds[3];
		$this->element_feuer = $ds[4];
		$this->element_wasser = $ds[5];
		$this->element_erde = $ds[6];
		$this->element_luft = $ds[7];
		$this->gesundheit = $ds[8];
		$this->energie = $ds[9];
		$this->zauberpunkte = $ds[10];
		$this->initiative = $ds[11];
		$this->abwehr = $ds[12];
		$this->ausweichen = $ds[13];
		$this->erfahrung = $ds[14];
	}
	
	# Fügt dem Gewinn je nach Fall entsprechende Werte hinzu
	public function erhoehen($param, $zauber){
		global $k_bonus_patzer;
		global $k_bonus_ausweichen;
		global $k_bonus_abwehr;
		global $k_bonus_erfolg;
		global $k_bonus_staerke;
		global $k_bonus_intelligenz;
		global $k_bonus_magie;
		global $k_bonus_elemente;
		global $k_bonus_hauptelement;
		global $k_bonus_nebenelement;
		global $k_bonus_gegenelement;
		global $k_bonus_zauberpunkte;
		# Vorlagen
		$hauptelement_attribut = $zauber->attribut_bez("hauptelement");
		$nebenelement_attribut = $zauber->attribut_bez("nebenelement");
		$gegenelement_attribut = $zauber->attribut_bez("gegenelement");
		# Gewinne für Zauber
		if ($zauber->ist_zauber()){
			$k_bonus_zp = $k_bonus_zauberpunkte * $zauber->verbrauch;
			$bonus_elem_zp = $k_bonus_elemente * $k_bonus_zp;
			switch ($param){
				case "patzer": 
					$this->magie = $this->magie + $k_bonus_patzer * $k_bonus_magie * $k_bonus_zp;
					$this->$hauptelement_attribut = $this->$hauptelement_attribut + $k_bonus_patzer * $bonus_elem_zp * $k_bonus_hauptelement;
					break;
				case "ausweichen": 
					$this->intelligenz = $this->intelligenz + $k_bonus_ausweichen * $k_bonus_intelligenz * $k_bonus_zp;
					$this->$hauptelement_attribut = $this->$hauptelement_attribut + $k_bonus_ausweichen * $bonus_elem_zp * $k_bonus_hauptelement;
					$this->$nebenelement_attribut = $this->$nebenelement_attribut + $k_bonus_ausweichen * $bonus_elem_zp * $k_bonus_nebenelement;
					$this->$gegenelement_attribut = $this->$gegenelement_attribut + $k_bonus_ausweichen * $bonus_elem_zp * $k_bonus_gegenelement;
					break;
				case "abwehr": 
					$this->magie = $this->magie + $k_bonus_abwehr * $k_bonus_magie * $k_bonus_zp;
					$this->intelligenz = $this->intelligenz + $k_bonus_abwehr * $k_bonus_intelligenz * $k_bonus_zp;
					$this->$hauptelement_attribut = $this->$hauptelement_attribut + $k_bonus_abwehr * $bonus_elem_zp * $k_bonus_hauptelement;
					$this->$nebenelement_attribut = $this->$nebenelement_attribut + $k_bonus_abwehr * $bonus_elem_zp * $k_bonus_nebenelement;
					$this->$gegenelement_attribut = $this->$gegenelement_attribut + $k_bonus_abwehr * $bonus_elem_zp * $k_bonus_gegenelement;
					break;
				case "erfolg": 
					$this->magie = $this->magie + $k_bonus_erfolg * $k_bonus_magie * $k_bonus_zp;
					$this->intelligenz = $this->intelligenz + $k_bonus_erfolg * $k_bonus_intelligenz * $k_bonus_zp;
					$this->$hauptelement_attribut = $this->$hauptelement_attribut + $k_bonus_erfolg * $bonus_elem_zp * $k_bonus_hauptelement;
					$this->$nebenelement_attribut = $this->$nebenelement_attribut + $k_bonus_erfolg * $bonus_elem_zp * $k_bonus_nebenelement;
					break;
				default: return false;
			}
		# Gewinne für Standardangriffe
		} else {
			$k_bonus_zp = $k_bonus_zauberpunkte * 1;
			switch ($param){
				case "patzer": 
					$this->staerke = $this->staerke + $k_bonus_patzer * $k_bonus_staerke;
					break;
				case "ausweichen": 
					$this->intelligenz = $this->intelligenz + $k_bonus_ausweichen * $k_bonus_intelligenz * $k_bonus_zp;
					break;
				case "abwehr": 
					$this->staerke = $this->staerke + $k_bonus_abwehr * $k_bonus_staerke;
					$this->intelligenz = $this->intelligenz + $k_bonus_abwehr * $k_bonus_intelligenz * $k_bonus_zp;
					break;
				case "erfolg": 
					$this->staerke = $this->staerke + $k_bonus_erfolg * $k_bonus_staerke;
					$this->intelligenz = $this->intelligenz + $k_bonus_erfolg * $k_bonus_intelligenz * $k_bonus_zp;
					break;
				default: return false;
			}
		}
	}
	
	# Schreibt Gewinndaten in DB zurück
	public function db_update(){
		global $debug;
		global $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
				UPDATE gewinn
				SET staerke = ?,
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
					erfahrung = ?
				WHERE id = ?")){
			$stmt->bind_param('ddddddddddddddd', 
					$this->staerke,
					$this->intelligenz,
					$this->magie,
					$this->element_feuer,
					$this->element_wasser,
					$this->element_erde,
					$this->element_luft,
					$this->gesundheit,
					$this->energie,
					$this->zauberpunkte,
					$this->initiative,
					$this->abwehr,
					$this->ausweichen,
					$this->erfahrung,
					$this->id);
			$stmt->execute();
			if ($debug) echo "<br />\nGewinn mit ID=".$gewinn_id." wurde in die Datenbank zurückgeschrieben.<br />\n";
			return true;
		} else {
			echo "<br />\nQuerryfehler in gewinn->update()<br />\n";
			return false;
		}
	}
}


class Statistik {
	public $id;
	public $spieler_id;
	public $npc_id;
	public $npc_name;
	public $anzahl;
	public $wie;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->spieler_id = $ds[1];
		$this->npc_id = $ds[2];
		$this->npc_name = $ds[3];
		$this->anzahl = $ds[4];
		$this->wie = $ds[5];
	}
}


class Aktion {
	public $id;
	public $titel;
	public $text;
	public $beschreibung;
	public $art;
	public $dauer;
	public $statusbild;
	public $energiebedarf;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->titel = $ds[1];
		$this->text = $ds[2];
		$this->beschreibung = $ds[3];
		$this->art = $ds[4];
		$this->dauer = $ds[5];
		$this->statusbild = $ds[6];
		$this->energiebedarf = $ds[7];
	}
}


class Level {
	public $id;
	public $name;
	public $stufe;
	public $beschreibung;
	public $erfahrung_naechster_level;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->name = $ds[1];
		$this->stufe = $ds[2];
		$this->beschreibung = $ds[3];
		$this->erfahrung_naechster_level = $ds[4];
	}
}
























?>