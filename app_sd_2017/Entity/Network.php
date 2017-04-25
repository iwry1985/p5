<?php

class Network extends Entity {

	protected $id,
			  $network_img,
			  $network_name,
			  $country;


	//getters
	public function id() {
		return $this->id;
	}

	public function network() {
		return $this->network_img;
	}

	public function network_name() {
		return $this->network_name;
	}

	public function country() {
		return $this->country;
	}
}