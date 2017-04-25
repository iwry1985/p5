<?php

class Serie extends Entity {

	protected $id,
			  $name,
			  $VF,
			  $img,
			  $folder,
			  $characters_folder,
			  $synopsis,
			  $begin_date,
			  $end_date,
			  $origine,
			  $network,
			  $runtime,
			  $etat,
			  $genre,
			  $seasons,
			  $producer,
			  $renew,
			  $date_renew,
			  $tv_maze,
			  $network_id,
			  $network_name,
			  $country_name,
			  $etat_id,
			  $random,
			  $note,
			  $stats_statut = [],
			  $stats_note = [],
			  $nb_users,
			  $nb_episodes,
			  $seasons_tbEp;

	//setters
	public function setId($id) {
		$this->id = (int)$id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setVF($vf) {
		$this->VF = $vf;
	}

	public function setImg($img) {
		$this->img = (int)$img;
	}

	public function setSynopsis($synopsis) {
		$this->synopsis = $synopsis;
	}

	public function setBegin_date($begin_date) {
		$this->begin_date = (int)$begin_date;
	}

	public function setEnd_date($end_date) {
		$this->end_date = (int)$end_date;
	}

	public function setOrigine($origine) {
		$this->origine = (int)$origine;
	}

	public function setRuntime($runtime) {
		$this->runtime = (int)$runtime;
	}

	public function setEtat($etat) {
		$this->etat = (int)$etat;
	}

	public function setGenre($genre) {
		$this->genre = (int)$genre;
	}

	public function setNetwork($network) {
		$this->network = (int)$network;
	}

	public function setSeasons($seasons) {
		$this->seasons = (int)$seasons;
	}

	public function setProducer($producer) {
		$this->producer = $producer;
	}

	public function setRenew($renew) {
		if(empty($renew)) {
			$renew = 'NC';
		} 
		$this->renew = $renew;
	}

	public function setTv_maze($tv_maze) {
		$this->tv_maze = (int)$tv_maze;
	}

	public function setRandom($random) {
		$this->random = (int)$random;
	}

	public function setFolder($folder) {
		$this->folder = (int)$folder;
	}

	public function setCharacters_folder($folder) {
		$this->characters_folder = (int)$folder;
	}

	public function setStats($array = array(), $group) {
		$i = 0;
		$length = count($array);
		$data = [];

		for($i; $i <= $length - 1; $i++) {
			if(@preg_match('#img/([a-z]+)/([a-z]+)\.jpg/#', $array[$i]['icon'], $match)) {
				$type = $match[1];
				var_dump($type);
			}

			if($group == 'statut') {
				//pour éviter les 'not interested';
				if($array[$i]['id'] != '6') {
					$this->stats_statut[] = array(
					"count" => $array[$i]['count'],
					$group => $array[$i][$group],
					"id" => $array[$i]['id'],
					"icon" => $array[$i]['icon']
					);
				}
			} else {
				//pour éviter les notes '1' et '2' --> correspondent à pas de notes
				if($array[$i]['id'] != '1' && $array[$i]['id'] != '2') {
					$this->stats_note[] = array(
					"count" => $array[$i]['count'],
					$group => $array[$i][$group],
					"id" => $array[$i]['id'],
					"icon" => $array[$i]['icon']
					);
				}
			}
			
		}
	}

	public function setNb_users($nb_users = array()) {
		$i = 0;
		$length = count($nb_users);
		$data = [];

		for($i; $i <= $length - 1; $i++) {
			//on ne compte pas les 'not interested';
			if($nb_users[$i]['id'] != '6') {
				$this->nb_users += $nb_users[$i]['count'];
			}
		}
	}

	public function setNb_episodes($nb_episodes) {
		if(is_int($nb_episodes)) {
			$this->nb_episodes = $nb_episodes;
		}
	}

	public function setSeasons_tbEp($nb_seasons) {
		if(is_int($nb_seasons)) {
			$this->seasons_tbEp = $nb_seasons;
		}
	}

	//---------------------------------------------------------------------------------
	//Liste des getters
	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function VF() {
		return $this->VF;
	}

	public function img() {
		return $this->img;
	}

	public function folder() {
		return $this->folder;
	}

	public function characters_folder() {
		return $this->characters_folder;
	}

	public function synopsis() {
		return $this->synopsis;
	}

	public function begin_date() {
		return $this->begin_date;
	}

	public function end_date() {
		return $this->end_date;
	}

	public function origine() {
		return $this->origine;
	}

	public function network() {
		return $this->network;
	}

	public function runtime() {
		return $this->runtime;
	}

	public function etat() {
		return $this->etat;
	}

	public function genre() {
		return $this->genre;
	}

	public function seasons() {
		return $this->seasons;
	}

	public function producer() {
		return $this->producer;
	}

	public function renew() {
		return $this->renew;
	}

	public function date_renew() {
		return $this->date_renew;
	}

	public function tv_maze() {
		return $this->tv_maze;
	}

	public function network_id() {
		return $this->network_id;
	}

	public function network_name() {
		return $this->network_name;
	}


	public function country_name() {
		return $this->country_name;
	}

	public function etat_id() {
		return $this->etat_id;
	}

	public function random() {
		return $this->random;
	}

	public function note() {
		return number_format($this->note, 1, ',', '');
	}

	public function stats_statut() {
		return $this->stats_statut;
	}

	public function stats_note() {
		return $this->stats_note;
	}

	public function nb_users() {
		return $this->nb_users;
	}

	public function nb_episodes() {
		return $this->nb_episodes;
	}

	public function seasons_tbEp() {
		return $this->seasons_tbEp;
	}


//----------------------------------------------------------------------
	//couleur de fond selon status
	public function back_color() {
		switch($this->etat_id) {
			case 1:
			return 'bck_green';
			break;

			case 2:
			return 'bck_abandonner';
			break;

			case 3:
			return 'bck_enCours';
			break;

			default:
			return 'bck_notInterested';
		}
	}
}