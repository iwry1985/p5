<?php

class Character extends Entity {
	protected $id,
			  $id_series,
			  $name,
			  $actor_name,
			  $img,
			  $folder,
			  $id_tv_maze;


	//setters
	public function setId($id) {
		$this->id = (int)$id;
	}

	public function setId_series($id_series) {
		$this->id_series = (int)$id_series;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setActor_name($actor_name) {
		$this->actor_name = $actor_name;
	}

	public function setImg($img) {
		$this->img = (int)$img;
	}

	public function setId_tv_maze($id_tv_maze) {
		$this->id_tv_maze = (int)$id_tv_maze;
	}

	public function setFolder($folder) {
		$this->folder = (int)$folder;
	}

	//getters
	public function id() {
		return $this->id;
	} 

	public function id_series(){
		return $this->id_series;
	}

	public function name() {
		return $this->name;
	}

	public function actor_name() {
		return $this->actor_name;
	}

	public function img() {
		if($this->img < 10) {
			$this->img = sprintf( "%02d", $this->img );
		}
		return $this->img;
	}

	public function folder() {
		return $this->folder;
	}

	public function id_tv_maze() {
		return $this->id_tv_maze;
	}
//-----------------------------------------------------------------------------------------

}