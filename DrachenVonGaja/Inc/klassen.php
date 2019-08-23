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
		$this->id = $ds[0];
		$this->account_id = $ds[1];
		$this->bilder_id = $ds[2];
		$this->gattung = $ds[3];
		$this->level_id = $ds[4];
		$this->startgebiet = $ds[5];
		$this->name = $ds[6];
		$this->geschlecht = $ds[7];
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

?>