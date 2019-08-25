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
	public $balance;
	public $zuletzt_gespielt;

	public function __construct($ds=null) {
		$this->spieler_id = $ds[0];
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
		$this->balance = $ds[19];
		$this->zuletzt_gespielt = $ds[20];
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

?>