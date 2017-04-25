<?php

class Icon extends Entity {

	protected $id,
			  $libelle,
			  $icon_img;


	//getters
	public function id() {
		return $this->id;
	}

	public function libelle() {
		return $this->libelle;
	}

	public function icon() {
		return $this->icon_img;
	}
}