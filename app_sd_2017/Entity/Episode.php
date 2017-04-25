<?php

class Episode extends Entity {

	protected $id_ep,
			  $id_series,
			  $season,
			  $number,
			  $name,
			  $airdate,
			  $season_finale,
			  $spec_runtime,
			  $specials,
			  $date_fr,
			  $decompte,
			  $jour_diffusion;

	
	//setters
	public function setId_ep($id_ep) {
		$this->id_ep = (int)$id_ep;
	}

	public function setId_series($id_series) {
		$this->id_series = (int)$id_series;
	}

	public function setSeason($season) {
		$this->season = (int)$season;
	}

	public function setNumber($number) {
		$this->number = (int)$number;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setAirdate($airdate) {
		$this->airdate = $airdate;
	}

	public function setDecompte() {
		$now = date('Y-m-d', time());
		$this->decompte = (strtotime($this->airdate) - strtotime($now));
		//résultat en secondes, pour avoir en jours (60*60*24);
		$this->decompte = (int)number_format($this->decompte/86400, 0);
	}

	public function setJour_diffusion() {
		$airdate = $this->airdate;
		$airdate = explode("-", $airdate);
		$airdate = date('l', mktime(0, 0, 0, $airdate['1'], $airdate['2']));

		switch($airdate) {
			case 'Monday':
				$diffusion = 'Lundi';
				break;
			case 'Tuesday':
				$diffusion = 'Mardi';
				break;
			case 'Wednesday':
				$diffusion = 'Mercredi';
				break;
			case 'Thursday':
				$diffusion = 'Jeudi';
				break;
			case 'Friday':
				$diffusion = 'Vendredi';
				break;
			case 'Saturday':
				$diffusion = 'Samedi';
				break;
			case 'Sunday':
				$diffusion = 'Dimanche';
				break;
		}

		$this->jour_diffusion = $diffusion;

	}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//getters
	public function id_ep() {
		return $this->id_ep;
	}

	public function id_series() {
		return $this->id_series;
	}

	public function season() {
		return $this->double_unit($this->season);
	}

	public function number() {
		return $this->double_unit($this->number);
	}

	public function name() {
		return $this->name;
	}

	public function airdate() {
		return $this->airdate;
	}

	public function season_finale() {
		return $this->season_finale;
	}

	public function spec_runtime() {
		return $this->spec_runtime;
	}

	public function specials() {
		return $this->specials;
	}

	public function date_fr() {
		return $this->date_fr;
	}

	public function decompte() {
		$this->setDecompte();
		return $this->decompte;
	}

	public function season_single_number() {
		return $this->season;
	}

	public function jour_diffusion() {
		$this->setJour_diffusion();
		return $this->jour_diffusion;
	}

	//-----------------------------------------------------
	public function double_unit($method) {
		if($method < 10) {
			$method = sprintf( "%02d", $method);
		}
		return $method;
	}

	public function transform_array() {
		return get_object_vars($this);
	}

}