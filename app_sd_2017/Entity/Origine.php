<?php

class Origine extends Entity {

	protected $id,
			  $origine_img,
			  $country;


	//getters
	public function id() {
		return $this->id;
	}

	public function origine() {
		return $this->origine_img;
	}

	public function country() {
		return $this->country;
	}
}