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
	public $basiswerte;
	public $bonus_pkt;
	public $bonus_proz;

	public function __construct($spieler_id=null) {
		if($spielerdaten = get_spieler($spieler_id)){
			$grunddaten = array_slice($spielerdaten, 0, 14);
			$basis = array_slice($spielerdaten, 24, 13);
			$b_pkt = array_slice($spielerdaten, 37, 13);
			$b_proz = array_slice($spielerdaten, 50, 13);
			$gesamtwerte = array_slice($spielerdaten, 14, 10);
			# Grunddaten
			$this->id = $grunddaten[0];
			$this->account_id = $grunddaten[1];
			$this->bilder_id = $grunddaten[2];
			$this->gattung_id = $grunddaten[3];
			$this->level_id = $grunddaten[4];
			$this->gebiet_id = $grunddaten[5];
			$this->name = $grunddaten[6];
			$this->geschlecht = $grunddaten[7];
			$this->gesundheit = $grunddaten[8];
			$this->energie = $grunddaten[9];
			$this->zauberpunkte = $grunddaten[10];
			$this->balance = $grunddaten[11];
			$this->zuletzt_gespielt = $grunddaten[12];
			$this->erfahrung = $grunddaten[13];
			# Gesamtwerte
			$this->staerke = $gesamtwerte[0];
			$this->intelligenz = $gesamtwerte[1];
			$this->magie = $gesamtwerte[2];
			$this->element_feuer = $gesamtwerte[3];
			$this->element_wasser = $gesamtwerte[4];
			$this->element_erde = $gesamtwerte[5];
			$this->element_luft = $gesamtwerte[6];
			$this->initiative = $gesamtwerte[7];
			$this->abwehr = $gesamtwerte[8];
			$this->ausweichen = $gesamtwerte[9];
			# Basiswerte
			$this->basiswerte = new Werte($basis);
			# Bonus in Punkten
			$this->bonus_pkt = new Werte($b_pkt);
			# Bonus in Prozent (von Basiswerten)
			$this->bonus_proz = new Werte($b_proz);
			# Gesamtwert Max Gesundheit, Energie, Zauberpunkte neu berechnen
			if($spieler_id != null) $this->neuberechnung();
		} else {
			return false;
		}
	}
	
	public function gewinn_verrechnen($gewinn, $mit_balance) {
		global $balance_aktiv;
		if ($balance_aktiv AND $mit_balance){
			$this->basiswerte->staerke = $this->basiswerte->staerke + floor_x($gewinn->staerke * ($this->balance / 100), 10);
			$this->basiswerte->intelligenz = $this->basiswerte->intelligenz + floor_x($gewinn->intelligenz * ($this->balance / 100), 10);
			$this->basiswerte->magie = $this->basiswerte->magie + floor_x($gewinn->magie * ($this->balance / 100), 10);
			$this->basiswerte->element_feuer = $this->basiswerte->element_feuer + floor_x($gewinn->element_feuer * ($this->balance / 100), 10);
			$this->basiswerte->element_wasser = $this->basiswerte->element_wasser + floor_x($gewinn->element_wasser * ($this->balance / 100), 10);
			$this->basiswerte->element_erde = $this->basiswerte->element_erde + floor_x($gewinn->element_erde * ($this->balance / 100), 10);
			$this->basiswerte->element_luft = $this->basiswerte->element_luft + floor_x($gewinn->element_luft * ($this->balance / 100), 10);
			$this->gesundheit = $this->gesundheit + $gewinn->gesundheit;
			$this->energie = $this->energie + $gewinn->energie;
			$this->zauberpunkte = $this->zauberpunkte + $gewinn->zauberpunkte;
			$this->basiswerte->initiative = $this->basiswerte->initiative + floor_x($gewinn->initiative * ($this->balance / 100), 10);
			$this->basiswerte->abwehr = $this->basiswerte->abwehr + floor_x($gewinn->abwehr * ($this->balance / 100), 10);
			$this->basiswerte->ausweichen = $this->basiswerte->ausweichen + floor_x($gewinn->ausweichen * ($this->balance / 100), 10);
			$this->erfahrung = $this->erfahrung + floor_x($gewinn->erfahrung * ($this->balance / 100), 10);
		} else {
			$this->basiswerte->staerke = $this->basiswerte->staerke + $gewinn->staerke;
			$this->basiswerte->intelligenz = $this->basiswerte->intelligenz + $gewinn->intelligenz;
			$this->basiswerte->magie = $this->basiswerte->magie + $gewinn->magie;
			$this->basiswerte->element_feuer = $this->basiswerte->element_feuer + $gewinn->element_feuer;
			$this->basiswerte->element_wasser = $this->basiswerte->element_wasser + $gewinn->element_wasser;
			$this->basiswerte->element_erde = $this->basiswerte->element_erde + $gewinn->element_erde;
			$this->basiswerte->element_luft = $this->basiswerte->element_luft + $gewinn->element_luft;
			$this->gesundheit = $this->gesundheit + $gewinn->gesundheit;
			$this->energie = $this->energie + $gewinn->energie;
			$this->zauberpunkte = $this->zauberpunkte + $gewinn->zauberpunkte;
			$this->basiswerte->initiative = $this->basiswerte->initiative + $gewinn->initiative;
			$this->basiswerte->abwehr = $this->basiswerte->abwehr + $gewinn->abwehr;
			$this->basiswerte->ausweichen = $this->basiswerte->ausweichen + $gewinn->ausweichen;
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
	
	# Regeneration der Spielerwerte (Gesundheit, Energie, Zauberpunkte) um volle Punkte
	public function erholung_punkte($gesundheit_pkt = 0, $energie_pkt = 0, $zauberpunkte_pkt = 0){
		$this->attribut_aendern("gesundheit", $gesundheit_pkt, 0, $this->max_gesundheit);
		$this->attribut_aendern("energie", $energie_pkt, 0, $this->max_energie);
		$this->attribut_aendern("zauberpunkte", $zauberpunkte_pkt, 0, $this->max_zauberpunkte);
		$this->db_update();
	}
	
	# Ändert Attribut um Wert (beachtet übergebene Grenzwerte)
	public function attribut_aendern($attribut, $wert, $min=-9999999, $max=9999999){
		if ($attribut == "gesundheit" OR $attribut == "energie" OR $attribut == "zauberpunkte" OR $attribut == "balance" OR $attribut == "erfahrung"){
			$this->$attribut = $this->$attribut + $wert;
			if ($this->$attribut < $min) $this->$attribut = $min;
			if ($this->$attribut > $max) $this->$attribut = $max;
		} else {
			$this->basiswerte->$attribut = $this->basiswerte->$attribut + $wert;
			if ($this->basiswerte->$attribut < $min) $this->basiswerte->$attribut = $min;
			if ($this->basiswerte->$attribut > $max) $this->basiswerte->$attribut = $max;
		}
	}
	
	# Übernimmt aktuelle Werte des Kampfteilnehmers
	public function uebernehme_kt_werte($kt){
		$this->gesundheit = $kt->gesundheit;
		if ($this->gesundheit > $this->max_gesundheit) $this->gesundheit = $this->max_gesundheit;
		$this->zauberpunkte = $kt->zauberpunkte;
		if ($this->zauberpunkte > $this->max_zauberpunkte) $this->zauberpunkte = $this->max_zauberpunkte;
		if ($kt->gesundheit <= 0) $this->energie = 0;
	}
	
	# Spieler rekonfigurieren (Neuberechnung von Gesundheit, Energie, Zauberpunkte, Balance)
	public function neuberechnung(){
		$level_up = false;
		if ($this->erfahrung >= get_erfahrung_naechster_level($this->level_id)){
			$level_up = true;
			$this->level_up();
		}
		$this->max_gesundheit = berechne_max_gesundheit($this);
		$this->attribut_aendern("gesundheit", 0, 0, $this->max_gesundheit);
		$this->max_energie = berechne_max_energie($this);
		$this->attribut_aendern("energie", 0, 0, $this->max_energie);
		$this->max_zauberpunkte = berechne_max_zauberpunkte($this);
		$this->attribut_aendern("zauberpunkte", 0, 0, $this->max_zauberpunkte);
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
					$this->basiswerte->staerke,
					$this->basiswerte->intelligenz,
					$this->basiswerte->magie,
					$this->basiswerte->element_feuer,
					$this->basiswerte->element_wasser,
					$this->basiswerte->element_erde,
					$this->basiswerte->element_luft,
					$this->gesundheit,
					$this->basiswerte->max_gesundheit,
					$this->energie,
					$this->basiswerte->max_energie,
					$this->zauberpunkte,
					$this->basiswerte->max_zauberpunkte,
					$this->basiswerte->initiative,
					$this->basiswerte->abwehr,
					$this->basiswerte->ausweichen,
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
	# Ist der Spieler bewusstlos? (Gesundheit = 0)
	public function bewusstlos(){
		if($this->gesundheit == 0) return true;
		else return false;
	}
}


class Werte {
	public $staerke;
	public $intelligenz;
	public $magie;
	public $element_feuer;
	public $element_wasser;
	public $element_erde;
	public $element_luft;
	public $max_gesundheit;
	public $max_energie;
	public $max_zauberpunkte;
	public $initiative;
	public $abwehr;
	public $ausweichen;
	
	public function __construct($werte=null) {
		if($werte != null){
			$this->staerke = $werte[0];
			$this->intelligenz = $werte[1];
			$this->magie = $werte[2];
			$this->element_feuer = $werte[3];
			$this->element_wasser = $werte[4];
			$this->element_erde = $werte[5];
			$this->element_luft = $werte[6];
			$this->max_gesundheit = $werte[7];
			$this->max_energie = $werte[8];
			$this->max_zauberpunkte = $werte[9];
			$this->initiative = $werte[10];
			$this->abwehr = $werte[11];
			$this->ausweichen = $werte[12];
		} else {
			$this->staerke = 0;
			$this->intelligenz = 0;
			$this->magie = 0;
			$this->element_feuer = 0;
			$this->element_wasser = 0;
			$this->element_erde = 0;
			$this->element_luft = 0;
			$this->max_gesundheit = 0;
			$this->max_energie = 0;
			$this->max_zauberpunkte = 0;
			$this->initiative = 0;
			$this->abwehr = 0;
			$this->ausweichen = 0;
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


class NPCFund {
	public $id;
	public $name;
	public $beschreibung;
	public $wahrscheinlichkeit;
	public $bilder_id;
	public $typ;
		
	public function __construct($ds=null) {
		if ($ds == null) return false;
			else $this->set($ds);
	}
	
	public function set($ds) {
		$this->id = $ds[0];
		$this->name = $ds[1];
		$this->beschreibung = $ds[2];
		$this->wahrscheinlichkeit = $ds[3];
		$this->bilder_id = $ds[4];
		$this->typ = $ds[5];
	}
}


class Item {
	public $id;
	public $bilder_id;
	public $name;
	public $beschreibung;
	public $essbar;
	public $ausruestbar;
	public $verarbeitbar;
	public $gesundheit;
	public $energie;
	public $zauberpunkte;
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
	public $prozent;
	public $fund;
	public $slot;
	public $anzahl;

	public function __construct($verwendung, $ds=null, $ds_zusatz=null) {
		if ($ds == null) $this->set_null();
			else $this->set($verwendung, $ds, $ds_zusatz);
	}
	
	public function set($verwendung, $ds, $ds_zusatz) {
		$this->id = $ds[0];
		$this->bilder_id = $ds[1];
		$this->name = $ds[2];
		$this->beschreibung = $ds[3];
		$this->essbar = $ds[4];
		$this->ausruestbar = $ds[5];
		$this->verarbeitbar = $ds[6];
		$this->gesundheit = $ds[7];
		$this->energie = $ds[8];
		$this->zauberpunkte = $ds[9];
		$this->staerke = $ds[10];
		$this->intelligenz = $ds[11];
		$this->magie = $ds[12];
		$this->element_feuer = $ds[13];
		$this->element_wasser = $ds[14];
		$this->element_erde = $ds[15];
		$this->element_luft = $ds[16];
		$this->initiative = $ds[17];
		$this->abwehr = $ds[18];
		$this->ausweichen = $ds[19];
		$this->prozent = $ds[20];
		if ($verwendung == "Fund") {$this->fund = new ItemFund($ds_zusatz);
			} else {$this->fund = null;}
		if ($verwendung == "Ausrüstung") {
			$this->slot = new Slot($ds_zusatz);
			$this->anzahl = $ds[21];
			} else {
			$this->slot = null;
			$this->anzahl = 0;}
	}
	
	public function set_null() {
		$this->id = null;
		$this->bilder_id = null;
		$this->name = null;
		$this->beschreibung = null;
		$this->essbar = null;
		$this->ausruestbar = null;
		$this->verarbeitbar = null;
		$this->gesundheit = null;
		$this->energie = null;
		$this->zauberpunkte = null;
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
		$this->prozent = null;
		$this->fund = null;
		$this->slot = null;
		$this->anzahl = 0;
	}
	
	public function ausgabe_single_attribut($attribut, $ausgabe_0=false, $in_farbe=false){
		$farbe = false;
		if ($this->prozent == 1) $proz = " %";
			else $proz = "";
		switch ($attribut){
			case "gesundheit": $attribut_txt = "Gesundheit"; break;
			case "energie": $attribut_txt = "Energie"; break;
			case "zauberpunkte": $attribut_txt = "Zauberpunkte"; break;
			case "staerke": $attribut_txt = "Stärke"; break;
			case "intelligenz": $attribut_txt = "Intelligenz"; break;
			case "magie": $attribut_txt = "Magie"; break;
			case "element_feuer": $attribut_txt = "Element Feuer"; break;
			case "element_wasser": $attribut_txt = "Element Wasser"; break;
			case "element_erde": $attribut_txt = "Element Erde"; break;
			case "element_luft": $attribut_txt = "Element Luft"; break;
			case "initiative": $attribut_txt = "Initiative"; break;
			case "abwehr": $attribut_txt = "Abwehr"; break;
			case "ausweichen": $attribut_txt = "Ausweichen"; break;
			default: $attribut_txt = "";
		}
		if ($this->$attribut < 0){
			$vorzeichen = "-";
			if ($in_farbe) $farbe = "darkred";
		}
		if ($this->$attribut > 0){
			$vorzeichen = "+";
			if ($in_farbe) $farbe = "darkgreen";
		}
		if ($this->$attribut != 0){
			if ($farbe){
				echo "<font color='".$farbe."'>".$vorzeichen.$this->$attribut.$proz." ".$attribut_txt."</font><br />";
			} else {
				echo $vorzeichen.$this->$attribut.$proz." ".$attribut_txt."<br />";
			}
		}
		if ($ausgabe_0 and $this->$attribut = 0){
			echo $this->$attribut." ".$attribut_txt."<br />";
		}
	}
	
	public function ausgabe_attribute($topic){
		switch ($topic){
			case "inventar":
				$this->ausgabe_single_attribut("gesundheit", false, true);
				$this->ausgabe_single_attribut("energie", false, true);
				$this->ausgabe_single_attribut("zauberpunkte", false, true);
				$this->ausgabe_single_attribut("staerke", false, true);
				$this->ausgabe_single_attribut("intelligenz", false, true);
				$this->ausgabe_single_attribut("magie", false, true);
				$this->ausgabe_single_attribut("element_feuer", false, true);
				$this->ausgabe_single_attribut("element_wasser", false, true);
				$this->ausgabe_single_attribut("element_erde", false, true);
				$this->ausgabe_single_attribut("element_luft", false, true);
				$this->ausgabe_single_attribut("initiative", false, true);
				$this->ausgabe_single_attribut("abwehr", false, true);
				$this->ausgabe_single_attribut("ausweichen", false, true);
				break;
			default: # Nothing to do
		}
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


class Slot {
	public $id;
	public $name;
	public $angelegt;
	public $max;
	
	public function __construct($ds) {
		$this->id = $ds[0];
		$this->name = $ds[1];
		$this->angelegt = $ds[2];
		$this->max = $ds[3];
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
		echo "name : " . $this->name . "<br />";
		echo "bilder_id : " . $this->bilder_id . "<br />";
		echo "id : " . $this->id . "<br />";
		echo "typ : " . $this->typ . "<br />";
		echo "seite : " . $this->seite . "<br />";
		echo "gesundheit : " . $this->gesundheit . "<br />";
		echo "gesundheit_max : " . $this->gesundheit_max . "<br />";
		echo "zauberpunkte : " . $this->zauberpunkte . "<br />";
		echo "zauberpunkte_max : " . $this->zauberpunkte_max . "<br />";
		echo "staerke : " . $this->staerke . "<br />";
		echo "intelligenz : " . $this->intelligenz . "<br />";
		echo "magie : " . $this->magie . "<br />";
		echo "element_feuer : " . $this->element_feuer . "<br />";
		echo "element_wasser : " . $this->element_wasser . "<br />";
		echo "element_erde : " . $this->element_erde . "<br />";
		echo "element_luft : " . $this->element_luft . "<br />";
		echo "initiative : " . $this->initiative . "<br />";
		echo "abwehr : " . $this->abwehr . "<br />";
		echo "ausweichen : " . $this->ausweichen . "<br />";
		echo "timer : " . $this->timer . "<br />";
		echo "kt_id : " . $this->kt_id . "<br />";
		echo "ki_id : " . $this->ki_id . "<br />";
		echo "gewinn_id : " . $this->gewinn_id . "<br />";
	}
	
	public function ausgabe_kampf($log_detail = 1){
		if ($log_detail >= 0){
			echo "Gesundheit : " . $this->gesundheit . "/" . $this->gesundheit_max . "<br />";
			echo "Zauberpunkte : " . $this->zauberpunkte . "/" . $this->zauberpunkte_max . "<br />";
		}
		if ($log_detail >= 1){
			echo "Initiative : " . $this->initiative . "<br />";
			echo "Abwehr : " . $this->abwehr . "<br />";
			echo "Ausweichen : " . $this->ausweichen . "<br />";
			echo "Timer : " . $this->timer . "<br />";
		}
		if ($log_detail >= 2){
			echo "Staerke : " . $this->staerke . "<br />";
			echo "Intelligenz : " . $this->intelligenz . "<br />";
			echo "Magie : " . $this->magie . "<br />";
			echo "Feuer : " . $this->element_feuer . "<br />";
			echo "Wasser : " . $this->element_wasser . "<br />";
			echo "Erde : " . $this->element_erde . "<br />";
			echo "Luft : " . $this->element_luft . "<br />";
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
		$this->gebiet_id = $ds[1];
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
					$this->log = $kt->name.": ".-$kampf_effekt->wert." ".$attribut." durch Beendigung von ".$kampf_effekt->zauber_name."<br />" . $this->log;
				} else {
					$this->log = $kt->name.": ".$kampf_effekt->wert." ".$attribut." durch ".$kampf_effekt->zauber_name."<br />" . $this->log;
				}
			}
		}
	}
	
	public function log_tot($kt){
		$this->log = "<font color='red'>" . $kt->name . " stirbt im Kampf.</font><br />" . $this->log;
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
	public $faktor_1;
	public $faktor_2;

	public function __construct($ds) {
		$this->id = $ds[0];
		$this->titel = $ds[1];
		$this->text = $ds[2];
		$this->beschreibung = $ds[3];
		$this->art = $ds[4];
		$this->dauer = $ds[5];
		$this->statusbild = $ds[6];
		$this->energiebedarf = $ds[7];
		$this->faktor_1 = $ds[8];
		$this->faktor_2 = $ds[9];
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


class Konfig {
	private $account_id;
	private $konfig_details;
	
	public function __construct($account_id=0){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				einstellungen.id,
				einstellungen.name,
				einstellungen.topic,
				einstellungen.beschreibung,
				einstellungen.rolle_id,
				einstellungen.global,
				einstellungen.default_wert,
				einstellungen_account.wert,
				datentyp.titel,
				einstellungen.min,
				einstellungen.max
			FROM einstellungen
				LEFT JOIN einstellungen_account ON einstellungen_account.einstellungen_id = einstellungen.id AND einstellungen_account.account_id = ?
				LEFT JOIN datentyp ON datentyp.id = einstellungen.datentyp_id
			ORDER BY einstellungen.topic, einstellungen.id")){
			$stmt->bind_param('d', $account_id);
			$stmt->execute();
			if ($konfig_all = $stmt->get_result()){
				while($konfig_detail = $konfig_all->fetch_array(MYSQLI_NUM)){
					$konfig[$konfig_detail[1]] = new KonfigDetail($konfig_detail);
				}
			}
			if ($debug) echo "<br />\nKonfigdaten für Account mit id=" . $account_id . " geladen.<br />\n";
			if (!isset($konfig)) return false;
		} else {
			echo "<br />\nQuerryfehler in Konifg->__constuct()<br />\n";
			return false;
		}
		$this->account_id = $account_id;
		$this->konfig_details = $konfig;
		if(isset($_POST["button_konfiguration_speichern"])){
			foreach($this->konfig_details as $konfig_temp){
				if(isset($_POST["konfig_global_".$konfig_temp->id])){
					$konfig_neuer_wert = $_POST["konfig_global_".$konfig_temp->id];
					if($konfig_neuer_wert != $konfig_temp->default_wert){
						# update konfig default
						$this->update_konfig("default", $konfig_temp->id, $konfig_neuer_wert);
						$konfig_temp->default_wert = $konfig_neuer_wert;
					}
				}
				if(isset($_POST["konfig_account_".$konfig_temp->id])){
					$konfig_neuer_wert = $_POST["konfig_account_".$konfig_temp->id];
					if($konfig_neuer_wert != $konfig_temp->account_wert){
						if($konfig_neuer_wert != $konfig_temp->default_wert){
							if($konfig_temp->account_wert == null){
								$this->update_konfig("account_insert", $konfig_temp->id, $konfig_neuer_wert);
							} else {
								$this->update_konfig("account_update", $konfig_temp->id, $konfig_neuer_wert);
							}
							$konfig_temp->account_wert = $konfig_neuer_wert;
						} else {
							if($konfig_temp->account_wert != null){
								$this->update_konfig("account_delete", $konfig_temp->id, $konfig_neuer_wert);
								$konfig_temp->account_wert = null;
							}
						}
					}
				}
			}
		}
	}
	
	public function get($name){
		$wert = $this->konfig_details[$name]->account_wert;
		if ($wert == null){
			$wert = $this->konfig_details[$name]->default_wert;
		}
		switch($this->konfig_details[$name]->datentyp){
			case "boolean":
				switch ($wert){
					case "true":
					case "ja": $echter_wert = true; break;
					case "false":
					case "nein": $echter_wert = false; break;
					default: $echter_wert = null; break;
				}
				break;
			case "integer":
				$echter_wert = intval($wert);
				break;
			case "float":
				$echter_wert = floatval(str_replace(",", ".", $wert));
				break;
			default:
				$echter_wert = $wert;
				break;
		}
		return $echter_wert;
	}
	
	public function get_all(){
		$konfig_all = false;
		foreach ($this->konfig_details as $konfig_detail){
			$konfig_all[$konfig_detail->name] = $this->get($konfig_detail->name);
		}
		return $konfig_all;
	}
	
	private function check_rolle($konfig_detail){
		global $session;
		if ($konfig_detail->rolle_id >= $session->rolle_id) return true;
			else return false;
	}
	
	public function get_all_details(){
		return array_filter($this->konfig_details, array($this, "check_rolle"));
	}
	
	private function update_konfig($topic, $konfig_id, $neuer_wert){
		global $debug, $connect_db_dvg;
		switch($topic){
			case "default":
				if ($stmt = $connect_db_dvg->prepare("
					UPDATE einstellungen
					SET default_wert = ?
					WHERE id = ?;")){
					$stmt->bind_param('sd', $neuer_wert, $konfig_id);
					$stmt->execute();
				} else {
					echo "<br />\nQuerryfehler in Konfig->update_konfig() - default<br />\n";
					return false;
				}
				break;
			case "account_insert":
				if ($stmt = $connect_db_dvg->prepare("
					INSERT INTO einstellungen_account (account_id, einstellungen_id, wert)
					VALUES (?, ?, ?);")){
					$stmt->bind_param('dds', $this->account_id, $konfig_id, $neuer_wert);
					$stmt->execute();
				} else {
					echo "<br />\nQuerryfehler in Konfig->update_konfig() - account_insert<br />\n";
					return false;
				}
				break;
			case "account_update":
				if ($stmt = $connect_db_dvg->prepare("
					UPDATE einstellungen_account
					SET wert = ?
					WHERE account_id = ?
						AND einstellungen_id = ?;")){
					$stmt->bind_param('sdd', $neuer_wert, $this->account_id, $konfig_id);
					$stmt->execute();
				} else {
					echo "<br />\nQuerryfehler in Konfig->update_konfig() - account_update<br />\n";
					return false;
				}
				break;
			case "account_delete":
				if ($stmt = $connect_db_dvg->prepare("
					DELETE FROM einstellungen_account
					WHERE account_id = ?
						AND einstellungen_id = ?;")){
					$stmt->bind_param('dd', $this->account_id, $konfig_id);
					$stmt->execute();
				} else {
					echo "<br />\nQuerryfehler in Konfig->update_konfig() - account_delete<br />\n";
					return false;
				}
				break;
			default:
				echo "Konfig->update_konfig() aufgerufen aber Operation nicht bekannt.";
		}
	}
}


class KonfigDetail {
	public $id;
	public $name;
	public $topic;
	public $beschreibung;
	public $rolle_id;
	public $global;
	public $default_wert;
	public $account_wert;
	public $datentyp;
	public $min;
	public $max;
	
	public function __construct($ds){
		$this->id = $ds[0];
		$this->name = $ds[1];
		$this->topic = $ds[2];
		$this->beschreibung = $ds[3];
		$this->rolle_id = $ds[4];
		$this->global = $ds[5];
		$this->default_wert = $ds[6];
		$this->account_wert = $ds[7];
		$this->datentyp = $ds[8];
		$this->min = $ds[9];
		$this->max = $ds[10];
	}
}


class Session {
	public $id;
	public $account_id;
	public $rolle_id;
	public $gueltig_von;
	public $gueltig_bis;
	public $aktiv;
	public $ip;
	
	public function __construct($ds, $laden=false){
		global $debug, $connect_db_dvg;
		if ($laden and !is_array($ds)){
			$account_id = $ds;
			if ($stmt = $connect_db_dvg->prepare("
				SELECT *
				FROM session
				WHERE account_id = ?
					and aktiv = 1;")){
				$stmt->bind_param('d', $account_id);
				$stmt->execute();
				$session_data = $stmt->get_result()->fetch_array(MYSQLI_NUM);
				if (isset($session_data)){
					$this->id = $session_data[0];
					$this->account_id = $session_data[1];
					$this->rolle_id = $session_data[2];
					$this->gueltig_von = $session_data[3];
					$this->gueltig_bis = $session_data[4];
					$this->aktiv = $session_data[5];
					$this->ip = $session_data[6];
				} else {
					return false;
				}
			} else {
				echo "<br />\nQuerryfehler in Session->__construct(LADEN)<br />\n";
				return false;
			}
		}
		if (!$laden and is_array($ds)){
			if ($ds[0] == 0){
				$this->id = $this->speichern($ds);
			} else {
				$this->id = $ds[0];
			}
			$this->account_id = $ds[1];
			$this->rolle_id = $ds[2];
			$this->gueltig_von = $ds[3];
			$this->gueltig_bis = $ds[4];
			$this->aktiv = $ds[5];
			$this->ip = $ds[6];
		}
	}
	
	public function speichern($ds){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO session (account_id, rolle_id, gueltig_von, gueltig_bis, aktiv, ip)
			VALUES (?, ?, ?, ?, ?, ?);")){
			$stmt->bind_param('ddssds', $ds[1], $ds[2], $ds[3], $ds[4], $ds[5], $ds[6]);
			$stmt->execute();
			$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM session");
			$stmt->execute();
			$session_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			if ($session_id > 0)
				$this->id = $session_id;
		} else {
			echo "<br />\nQuerryfehler in Session->speichern()<br />\n";
			return false;
		}
	}
	public function aktualisieren(){
		global $debug, $connect_db_dvg, $konfig;
		$jetzt = new DateTime();
		$max_gueltigkeit = new DateInterval("PT".$konfig->get("gueltigkeit_session")."M");
		$gueltig_bis_neu = (new DateTime)->add($max_gueltigkeit);
		if ($stmt = $connect_db_dvg->prepare("
			UPDATE session
			SET gueltig_bis = ?
			WHERE id = ?;")){
			$stmt->bind_param('sd', $gueltig_bis_neu->format('Y-m-d H:i:s'), $this->id);
			$stmt->execute();
			$this->gueltig_bis = $gueltig_bis_neu->format('Y-m-d H:i:s');
		} else {
			echo "<br />\nQuerryfehler in Session->aktualisieren()<br />\n";
			return false;
		}
	}
	public function beenden_logout(){
		global $debug, $connect_db_dvg;
		$jetzt = new DateTime();
		if ($stmt = $connect_db_dvg->prepare("
			UPDATE session
			SET gueltig_bis = ?,
				aktiv = 0
			WHERE id = ?;")){
			$stmt->bind_param('sd', $jetzt->format('Y-m-d H:i:s'), $this->id);
			$stmt->execute();
			$this->aktiv = 0;
			$this->gueltig_bis = $jetzt->format('Y-m-d H:i:s');
		} else {
			echo "<br />\nQuerryfehler in Session->beenden_logout()<br />\n";
			return false;
		}
	}
	public function beenden_abgelaufen(){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			UPDATE session
			SET aktiv = 0
			WHERE account_id = ?;")){
			$stmt->bind_param('d', $this->account_id);
			$stmt->execute();
			$this->aktiv = 0;
		} else {
			echo "<br />\nQuerryfehler in Session->beenden_abgelaufen()<br />\n";
			return false;
		}
	}
	public function ist_gueltig(){
		$abgelaufen = false;
		# abgelaufen
		$jetzt = new DateTime();
		$gueltig_bis = new DateTime($this->gueltig_bis);
		if ($gueltig_bis < $jetzt){
			$abgelaufen = true;
		}
		# andere_ip
		$neue_ip = $_SERVER["REMOTE_ADDR"];
		if ($neue_ip != $this->ip){
			$abgelaufen = true;
		}
		if ($abgelaufen){
			$this->beenden_abgelaufen();
			return false;
		} else {
			return true;
		}
	}
}


class Dialog {
	public $id;
	public $spieler_id;
	public $npc_id;
	public $npc_text_aktuell_id;
	public $npc_text_aktuell;
	public $spieler_texte;
	public $aktion_offen;
	
	public function __construct(){
		$this->set_null();
		$this->aktion_offen = null;
	}
	
	#Neuen Dialog starten
	public function neu($spieler_id, $npc_id){
		# NPC-Text laden
		$npc_starttext = $this->startoptionen_npc($npc_id);
		if (!$npc_starttext){
			echo "Es konnte kein passender Startdialog gefunden werden.<br />";
			return false;
		}
		# Vorhandenen Log-Eintrag laden
		$dialog_log_id = $this->log_id_laden($spieler_id);
		if ($dialog_log_id){
			$test = $this->update($dialog_log_id, $npc_starttext->id);
			if (!$test) return false;
			else $this->id = $dialog_log_id;
		} else {
			$test = $this->insert($spieler_id, $npc_starttext->id);
			if (!$test) return false;
		}
		$this->spieler_id = $spieler_id;
		$this->npc_id = $npc_id;
		$this->npc_text_aktuell_id = $npc_starttext->id;
		$this->npc_text_aktuell = $npc_starttext->text;
		# Spielertexte laden
		$test = $this->optionen_spieler_laden();
		if (!$test) return false;
		return true;
	}
	
	# Dialog fortsetzen
	public function fortsetzen($spieler_id, $dialog_link_spieler_id){
		# Bestehenden Dialog laden
		$test = $this->log_laden($spieler_id);
		if (!$test){
			echo "Keinen bestehenden Dialog gefunden.<br />";
			return false;
		}
		# Weitere Informationen zur gewählten Spieleroption laden 
		$test = $this->optionen_spieler_laden($dialog_link_spieler_id);
		if (!$test){
			echo "Keine Spielerdialoge gefunden.<br />";
			return false;
		}
		# Nächsten NPC-Text laden
		$this->npc_text_aktuell_id = $this->spieler_texte[0]->next_npc_text_id;
		# Wenn Aktion, dann ausführen (Welche Aktion und mit welchem NPC?)
		if ($this->spieler_texte[0]->aktion_id != null){
			$this->aktion_offen = array($this->spieler_texte[0]->aktion_text_intern, $this->spieler_texte[0]->any_id);
		}
		# Wenn kein Folgetext, dann Dialog beenden
		if ($this->npc_text_aktuell_id == null){
			$test = $this->delete();
			if (!$test) return false;
			$this->set_null();
		} else {
		# Wenn Folgetext, dann Dialog Update
			$test = $this->update($this->id, $this->npc_text_aktuell_id);
			if (!$test) return false;
			$test = $this->neue_option_npc();
			if (!$test) return false;
			# Spielertexte laden
			$test = $this->optionen_spieler_laden();
			if (!$test) return false;
		}
		return true;
	}
	
	# NPC-Optionen laden (Start)
	private function startoptionen_npc($npc_id){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				dialog_link_npc.id,
				dialog_link_npc.dialog_text_id,
				dialog_text.inhalt
			FROM dialog_link_npc
				LEFT JOIN dialog_text ON dialog_text.id = dialog_link_npc.dialog_text_id
			WHERE dialog_link_npc.aktiv = 1
				AND dialog_link_npc.startdialog = 1
				AND dialog_link_npc.npc_id = ?")){
			$stmt->bind_param('d', $npc_id);
			$stmt->execute();
			$dialog_data = false;
			$count = 0;
			if ($dialog_data_all = $stmt->get_result()){
				while($dialog_data_einzel = $dialog_data_all->fetch_array(MYSQLI_NUM)){
					$dialog = new DialogNPC($dialog_data_einzel);
					if ($this->bed_prf($dialog)){
						$dialog_data = $dialog;
						$count = $count + 1;
					}
				}
			}
			if (!$dialog_data or $count != 1){
				echo "<br />Keinen oder meherere Startdialoge gefunden<br />";
				return false;
			}
		} else {
			echo "<br />\nQuerryfehler in Dialog->startoptionen_npc()<br />\n";
			return false;
		}
		return $dialog_data;
	}

	# Neuen Datensatz in dialog_log einfügen
	private function insert($spieler_id, $dialog_link_npc_id){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			INSERT INTO dialog_log (spieler_id, dialog_link_npc_id)
			VALUES (?, ?);")){
			$stmt->bind_param('dd', $spieler_id, $dialog_link_npc_id);
			$stmt->execute();
			$stmt = $connect_db_dvg->prepare("SELECT MAX(id) FROM dialog_log");
			$stmt->execute();
			$dialog_log_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			$this->id = $dialog_log_id;
		} else {
			echo "<br />\nQuerryfehler in Dialog->insert()<br />\n";
			return false;
		}
		return true;
	}
	
	# Bestehenden Datensatz in dialog_log ändern
	private function update($dialog_log_id, $dialog_link_npc_id){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			UPDATE dialog_log
			SET dialog_link_npc_id = ?
			WHERE id = ?;")){
			$stmt->bind_param('dd', $dialog_link_npc_id, $dialog_log_id);
			$stmt->execute();
		} else {
			echo "<br />\nQuerryfehler in Dialog->update()<br />\n";
			return false;
		}
		return true;
	}
	
	# Prüfen ob bereits ein Eintrag in dialog_log existiert
	private function log_id_laden($spieler_id){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT id
			FROM dialog_log
			WHERE spieler_id = ?;")){
			$stmt->bind_param('d', $spieler_id);
			$stmt->execute();
			$dialog_log_id = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
			if (isset($dialog_log_id)){
				return $dialog_log_id;
			} else {
				return false;
			}
		} else {
			echo "<br />\nQuerryfehler in Dialog->log_id_laden()<br />\n";
			return false;
		}
		return true;
	}
	
	# Bedingungsprüfung für Dialogtexte (noch inaktiv)
	private function bed_prf($dialog){
		# Funktion zur Bedingungsüberprüfung
		return true;
	}
	
	# Spieleroptionen laden
	private function optionen_spieler_laden($dialog_link_spieler_id=null){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT 
				dialog_link_spieler.id,
				dialog_link_spieler.dialog_text_spieler_id,
				dialog_text.inhalt,
				dialog_link_spieler.next_dialog_link_npc_id,
				dialog_link_spieler.dialog_aktion_id,
				dialog_aktion.titel,
				dialog_aktion.titel_intern,
				dialog_link_spieler.any_id
			FROM dialog_link_spieler
				LEFT JOIN dialog_text ON dialog_text.id = dialog_link_spieler.dialog_text_spieler_id
				LEFT JOIN dialog_aktion ON dialog_aktion.id = dialog_link_spieler.dialog_aktion_id
			WHERE dialog_link_spieler.aktiv = 1
				AND dialog_link_spieler.dialog_link_npc_id = ?;")){
			$stmt->bind_param('d', $this->npc_text_aktuell_id);
			$stmt->execute();
			$dialog_data = false;
			$count = 0;
			if ($dialog_data_all = $stmt->get_result()){
				while($dialog_data_einzel = $dialog_data_all->fetch_array(MYSQLI_NUM)){
					$dialog = new DialogSpieler($dialog_data_einzel);
					if ($this->bed_prf($dialog) and ($dialog_link_spieler_id == null or $dialog_link_spieler_id == $dialog->id)){
						$dialog_data[$count] = $dialog;
						$count = $count + 1;
					}
				}
			}
			if (!$dialog_data) return false;
			else $this->spieler_texte = $dialog_data;
		} else {
			echo "<br />\nQuerryfehler in Dialog->optionen_spieler_laden()<br />\n";
			return false;
		}
		return true;
	}
	
	# Aktuellen Dialogstand laden
	private function log_laden($spieler_id){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT dialog_log.*,
				dialog_link_npc.npc_id
			FROM dialog_log
				JOIN dialog_link_npc ON dialog_link_npc.id = dialog_log.dialog_link_npc_id
			WHERE spieler_id = ?;")){
			$stmt->bind_param('d', $spieler_id);
			$stmt->execute();
			$dialog_data = $stmt->get_result()->fetch_array(MYSQLI_NUM);
			if (isset($dialog_data)){
				$this->id = $dialog_data[0];
				$this->spieler_id = $dialog_data[1];
				$this->npc_text_aktuell_id = $dialog_data[2];
				$this->npc_id = $dialog_data[3];
			} else {
				return false;
			}
		} else {
			echo "<br />\nQuerryfehler in Dialog->log_laden()<br />\n";
			return false;
		}
		return true;
	}
	
	# NPC-Option laden (zwischendrin)
	private function neue_option_npc(){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			SELECT dialog_text.inhalt
			FROM dialog_link_npc 
				JOIN dialog_text ON dialog_text.id = dialog_link_npc.dialog_text_id
			WHERE dialog_link_npc.id = ?;")){
			$stmt->bind_param('d', $this->npc_text_aktuell_id);
			$stmt->execute();
			if ($dialog_data = $stmt->get_result()->fetch_array(MYSQLI_NUM)){
				$this->npc_text_aktuell = $dialog_data[0];
			} else {
				echo "<br />\nKeinen NPC-Dialog gefunden<br />\n";
				return false;
			}
		} else {
			echo "<br />\nQuerryfehler in Dialog->neue_option_npc()<br />\n";
			return false;
		}
		return true;
	}
	
	# Dialog Ende
	private function delete(){
		global $debug, $connect_db_dvg;
		if ($stmt = $connect_db_dvg->prepare("
			DELETE FROM dialog_log
			WHERE spieler_id = ?;")){
			$stmt->bind_param('d', $this->spieler_id);
			$stmt->execute();
		} else {
			echo "<br />\nQuerryfehler in Dialog->delete()<br />\n";
			return false;
		}
		return true;
	}
	
	# Dialog initialisieren bzw. leeren
	private function set_null(){
		$this->id = null;
		$this->spieler_id = null;
		$this->npc_id = null;
		$this->npc_text_aktuell_id = null;
		$this->npc_text_aktuell = null;
		$this->spieler_texte = null;
	}
}


class DialogNPC {
	public $id;
	public $text_id;
	public $text;
	
	public function __construct($ds){
		if (is_array($ds)){
			$this->id = $ds[0];
			$this->text_id = $ds[1];
			$this->text = $ds[2];
			return true;
		} else {
			return false;
		}
	}
}


class DialogSpieler {
	public $id;
	public $text_id;
	public $text;
	public $next_npc_text_id;
	public $aktion_id;
	public $aktion_text;
	public $aktion_text_intern;
	public $any_id;
	
	public function __construct($ds){
		if (is_array($ds)){
			$this->id = $ds[0];
			$this->text_id = $ds[1];
			$this->text = $ds[2];
			$this->next_npc_text_id = $ds[3];
			$this->aktion_id = $ds[4];
			$this->aktion_text = $ds[5];
			$this->aktion_text_intern = $ds[6];
			$this->any_id = $ds[7];
			return true;
		} else {
			return false;
		}
	}
}
	


















?>