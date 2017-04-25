<?php  
require_once('Generic_model.php');

class Episode_model extends Generic_model {

	//va chercher tous les épisodes de la saison
	public function get_episodes_bySeason($id_series, $season) {

		$req = $this->db->conn_id->prepare('SELECT *, DATE_FORMAT(airdate, "%d/%m/%Y") AS date_fr FROM episodes WHERE id_series = :id_series AND season = :season ORDER BY number ASC');

		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$all = $req->fetchAll();
		return $all;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//va chercher tous les épisodes de la série qui ont déjà été diffusés
	public function get_aired_episodes($id_series) {

		$now = date('Y-m-d', time());
		$req = $this->db->conn_id->prepare('SELECT * FROM episodes WHERE id_series = :id_series AND airdate < :now AND airdate != \'0000-00-00\'');

		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':now', $now, PDO::PARAM_STR);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$aired = $req->fetchAll();
		return $aired;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//compte le nombre de saisons de la série par rapport aux épisodes diffusés
	public function get_count_seasons($id_series) {
		$req = $this->db->conn_id->prepare('SELECT season FROM episodes WHERE id_series = :id_series GROUP BY season ASC');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->execute();

		$nb_seasons = $req->fetchAll();
		$nb_seasons = count($nb_seasons);

		return $nb_seasons;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//va chercher le prochain épisode de la série qui sera diffusé
	public function get_next_ep($id_series) {
		$now = date('Y-m-d', time());

		$req = $this->db->conn_id->prepare('SELECT *, DATE_FORMAT(airdate, "%d-%m-%Y") AS date_fr FROM episodes WHERE id_series = :id_series AND airdate != \'0000-00-00\' AND (airdate > :now OR airdate = :now) ORDER BY airdate ASC LIMIT 0, 1');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':now', $now);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		return $next = $req->fetch();
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//va chercher un épisode en particulier
	public function get_this_ep($id_series, $season, $number) {
		$req = $this->db->conn_id->prepare('SELECT * FROM episodes WHERE id_series = :id_series AND season = :season AND number =:number');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':number', $number, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');
		$bdd_episode = $req->fetch();

		return $bdd_episode;
	}
//---------------------------------------------------------------------------------------
	//ajoute un épisode à la bdd
	public function add_episode_to_show(Episode $episode) {
		$req = $this->db->conn_id->prepare('INSERT INTO episodes SET id_series = :id_series, season = :season, number = :number, name = :name, airdate = :airdate');

		$req->bindValue(':id_series', $episode->id_series(), PDO::PARAM_INT);
		$req->bindValue(':season', $episode->season(), PDO::PARAM_INT);
		$req->bindValue(':number', $episode->number(), PDO::PARAM_INT);
		$req->bindValue(':name', $episode->name(), PDO::PARAM_STR);
		$req->bindValue(':airdate', $episode->airdate(), PDO::PARAM_STR);
		$req->execute();
	}
//-------------------------------------------------------------------------------------
	//modifier un épisode (param id_ep pour update tv_maze)
	public function update_show_episode(Episode $episode, $id_ep = '') {
		$req = $this->db->conn_id->prepare('UPDATE episodes SET id_series = :id_series, season = :season, number = :number, name = :name, airdate = :airdate WHERE id_ep = :id_ep');
		$req->bindValue(':id_series', $episode->id_series(), PDO::PARAM_INT);
		$req->bindValue(':season', $episode->season(), PDO::PARAM_INT);
		$req->bindValue(':number', $episode->number(), PDO::PARAM_INT);
		$req->bindValue(':name', $episode->name(), PDO::PARAM_STR);
		$req->bindValue(':airdate', $episode->airdate(), PDO::PARAM_STR);
		$req->bindValue(':id_ep', $id_ep, PDO::PARAM_INT);
		$req->execute();
	}


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	//modifier un seul champ de la table
	public function update_one_info($id_ep, $champ, $valeur) {
		$req = $this->db->conn_id->prepare('UPDATE episodes SET '.$champ.' = :champ WHERE id_ep = :id_ep');
		$req->bindValue(':champ', $valeur, PDO::PARAM_STR);
		$req->bindValue(':id_ep', $id_ep, PDO::PARAM_INT);
		$req->execute();

		return 'episode_update';
	}
//-----------------------------------------------------------------------------------------------------------------------------	
	//vérifie que l'épisode n'est pas le dernier de la saison
	public function verify_season_finale($id_series, $season, $number) {

		$next_ep = $number + 1;
		$req = $this->db->conn_id->prepare('SELECT season, number FROM episodes
			WHERE id_series = :id_series AND season = :season AND number = :next_ep');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':next_ep', $next_ep, PDO::PARAM_INT);
		$req->execute();

		$not_season_finale = $req->fetch();
		return $not_season_finale;
	}
	
//--------------------------------------------------------------------------------------------------
	public function count_number_episodes($id_series) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT COUNT(id_ep) as count FROM episodes AS ep LEFT JOIN series AS se ON ep.id_series = se.id WHERE ep.id_series = :id_series AND airdate != \'0000-00-00\' AND airdate <= :today');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();


		$count_total = $req->fetch();
		return $count_total;
	}
//--------------------------------------------------------------------------


}