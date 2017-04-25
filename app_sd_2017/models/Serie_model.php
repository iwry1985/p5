<?php  
require_once('Generic_model.php');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Serie_model extends generic_model {

	//va chercher une série demandée
	public function get_show($id) {

		$req = $this->db->conn_id->prepare('SELECT se.*, org.origine AS origine, net.network AS network, gen.genre as genre, etat.etat as etat, se.id, net.id AS network_id, etat.id AS etat_id, org.country AS country_name, AVG(sd.note) AS note, org.origine AS origine, net.network AS network, net.network_name as network_name 
				FROM series AS se 
				LEFT JOIN networks AS net ON se.network = net.id
				LEFT JOIN genre AS gen ON se.genre = gen.id
				LEFT JOIN origine AS org ON se.origine = org.id
				LEFT JOIN etat ON se.etat = etat.id 
				LEFT JOIN seriesdom AS sd ON se.id = sd.id_series
				WHERE se.id = :id AND sd.note != 1 AND sd.note != 2');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$serie = $req->fetch();
		$folder = find_folder($serie->img());
		$serie->setFolder($folder);

		return $serie;
	}

//------------------------------------------------------
	//Va chercher les stats de la série
	public function get_stats($sort_id, $id, $group) {
		$count = $this->db->conn_id->prepare('SELECT COUNT(seriesdom.id) AS count, '.$group.'.'.$group.', '.$group.'.icon, '.$group.'.id FROM seriesdom LEFT JOIN '.$group.' ON seriesdom.'.$group.' = '.$group.'.id WHERE seriesdom.'.$sort_id.' = :id GROUP BY seriesdom.'.$group.' ORDER BY count DESC');

		$count->bindValue(':id', $id, PDO::PARAM_INT);
		$count->execute();
		$count = $count->fetchAll(PDO::FETCH_ASSOC);

		return $count;
	}
//-----------------------------------------------------------------------------

	//va chercher les séries similaires
	public function get_show_suggestions($id_series, $genre, $runtime, $id_users) {
		$short = $runtime - 10;
		$long = $runtime + 10;

		$sugg = $this->db->conn_id->prepare('SELECT DISTINCT se.id, se.img, se.synopsis FROM series AS se LEFT JOIN genre AS gen ON se.genre = gen.id 
			LEFT JOIN seriesdom AS sd ON se.id = sd.id_series
			WHERE se.id NOT IN(SELECT id_series FROM seriesdom WHERE id_users = :id_users) AND se.id != :id_series AND gen.genre = :genre AND runtime BETWEEN :short AND :long ORDER BY RAND() LIMIT 0,5');
		$sugg->bindValue('id_users', $id_users, PDO::PARAM_INT);
		$sugg->bindValue('id_series', $id_series, PDO::PARAM_INT);
		$sugg->bindValue('genre', $genre, PDO::PARAM_STR);
		$sugg->bindValue('short', $short, PDO::PARAM_INT);
		$sugg->bindValue('long', $long, PDO::PARAM_INT);
		$sugg->execute();

		$sugg->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$similaires = $sugg->fetchAll();

		foreach($similaires as $sugg) {
			$folder = find_folder($sugg->img());
			$sugg->setFolder($folder);
		}
		
		return $similaires;
	}
//------------------------------------------------------------------------------------
	//vérifie si la série est déjà répertoriée ou pas
	public function show_exists($champ, $valeur) {

		$req = $this->db->conn_id->prepare('SELECT id FROM series WHERE '.$champ.'= :valeur');
		$req->bindValue('valeur', $valeur, PDO::PARAM_STR);
		$req->execute();
		
		return $show_exists = $req->rowCount();
	}
//---------------------------------------------------------------------------------------
	//change le statut de $user qd une série commandée qu'il souhaite commencer passe à 'en cours'. Le statut de $user passe également à en cours
	public function change_status_new_show($id_series, $etat) {

		$req = $this->db->conn_id->prepare('SELECT sd.id, sd.statut FROM seriesdom AS sd LEFT JOIN series AS se ON sd.id_series = se.id WHERE sd.statut = :statut AND se.etat = :etat AND sd.id_series = :id_series');
		$req->bindValue('statut', 1, PDO::PARAM_INT);
		$req->bindValue('etat', 3, PDO::PARAM_INT);
		$req->bindValue('id_series', $id_series, PDO::PARAM_INT);
		$req->execute();
		$show = $req->fetch(PDO::FETCH_ASSOC);


		if(!empty($show) && $etat == 1 && $show['statut'] == '1') {
			$year_update = date('Y', time());
			$month_update = date('m', time());


			$updateUser = $this->db->conn_id->prepare('UPDATE seriesdom SET statut = :statut, date_time_update = NOW(), month_update = :month_update, year_update = :year_update WHERE id_series = :id_series');
			$updateUser->bindValue('statut', 3, PDO::PARAM_INT);
			$updateUser->bindValue('id_series', $id_series, PDO::PARAM_INT);
			$updateUser->bindValue(':month_update', $month_update, PDO::PARAM_INT);
			$updateUser->bindValue(':year_update', $year_update, PDO::PARAM_INT);
			$updateUser->execute();
		}
	}

//---------------------------------------------------------------------------------
	//UPDATE SERIE
	public function update_show(Serie $serie) {
		//voir fonction au-desssus
		$this->change_status_new_show($serie->id(), $serie->etat());

		//pour série dont le statut passe en 'terminée'... si pas de end_date, end_date == année en cours
		if($serie->etat() == 2 && $serie->end_date() == 0) {
			$year = date('Y', time());
			$serie->setEnd_date($year);
		}

		//on update la série
		$update = $this->db->conn_id->prepare('UPDATE series SET name = :name, VF = :VF, img = :img, synopsis = :synopsis, begin_date = :begin_date, end_date = :end_date, origine = :origine, network = :network, runtime = :runtime, etat = :etat, genre = :genre, seasons = :seasons, producer = :producer, renew = :renew, tv_maze = :tv_maze, random = :random WHERE id = :id');
		$update->bindValue('name', $serie->name(), PDO::PARAM_STR);
		$update->bindValue('VF', $serie->VF(), PDO::PARAM_STR);
		$update->bindValue('img', $serie->img(), PDO::PARAM_INT);
		$update->bindValue('synopsis', $serie->synopsis(), PDO::PARAM_STR);
		$update->bindValue('begin_date', $serie->begin_date(), PDO::PARAM_INT);
		$update->bindValue('end_date', $serie->end_date(), PDO::PARAM_INT);
		$update->bindValue('origine', $serie->origine(), PDO::PARAM_INT);
		$update->bindValue('network', $serie->network(), PDO::PARAM_INT);
		$update->bindValue('runtime', $serie->runtime(), PDO::PARAM_INT);
		$update->bindValue('etat', $serie->etat(), PDO::PARAM_INT);
		$update->bindValue('genre', $serie->genre(), PDO::PARAM_INT);
		$update->bindValue('seasons', $serie->seasons(), PDO::PARAM_INT);
		$update->bindValue('producer', $serie->producer(), PDO::PARAM_STR);
		$update->bindValue('renew', $serie->renew(), PDO::PARAM_STR);
		$update->bindValue('tv_maze', $serie->tv_maze(), PDO::PARAM_INT);
		$update->bindValue('random', $serie->random(), PDO::PARAM_INT);
		$update->bindValue('id', $serie->id(), PDO::PARAM_INT);
		$update->execute();

		return $msg = 'update_ok';
	}
//------------------------------------------------------------------------------------------------------
	//AJOUTER LA SERIE
	public function add_show(Serie $serie) {
		//on vérifie si la série n'est déjà pas présente dans la bdd
		$exist = $this->show_exists('name', $serie->name());

		if($exist == 0) {

			//on ajoute la série
			$add = $this->db->conn_id->prepare('INSERT INTO series SET name = :name, VF = :VF, img = :img, synopsis = :synopsis, begin_date = :begin_date, end_date = :end_date, origine = :origine, network = :network, runtime = :runtime, etat = :etat, genre = :genre, seasons = :seasons, producer = :producer, renew = :renew, tv_maze = :tv_maze, random = :random');

			$add->bindValue('name', $serie->name(), PDO::PARAM_STR);
			$add->bindValue('VF', $serie->VF(), PDO::PARAM_STR);
			$add->bindValue('img', $serie->img(), PDO::PARAM_INT);
			$add->bindValue('synopsis', $serie->synopsis(), PDO::PARAM_STR);
			$add->bindValue('begin_date', $serie->begin_date(), PDO::PARAM_INT);
			$add->bindValue('end_date', $serie->end_date(), PDO::PARAM_INT);
			$add->bindValue('origine', $serie->origine(), PDO::PARAM_INT);
			$add->bindValue('network', $serie->network(), PDO::PARAM_INT);
			$add->bindValue('runtime', $serie->runtime(), PDO::PARAM_INT);
			$add->bindValue('etat', $serie->etat(), PDO::PARAM_INT);
			$add->bindValue('genre', $serie->genre(), PDO::PARAM_INT);
			$add->bindValue('seasons', $serie->seasons(), PDO::PARAM_INT);
			$add->bindValue('producer', $serie->producer(), PDO::PARAM_STR);
			$add->bindValue('renew', $serie->renew(), PDO::PARAM_STR);
			$add->bindValue('tv_maze', $serie->tv_maze(), PDO::PARAM_INT);
			$add->bindValue('random', $serie->random(), PDO::PARAM_INT);
			$add->execute();

			return $msg = 'add_ok';
		}
	}
//----------------------------------------------------------------------------------------------------
	//va chercher le num id_tv_maze correspondant à la série
	public function get_tv_maze_id($id_series) {

		$req = $this->db->conn_id->prepare('SELECT tv_maze FROM series WHERE id = :id');
		$req->bindValue('id', $id_series, PDO::PARAM_INT);
		$req->execute();

		$tv_maze = $req->fetch(PDO::FETCH_ASSOC);
		$tv_maze = $tv_maze['tv_maze'];

		return $tv_maze;

	}
//---------------------------------------------------------------------------------------------------
	//va chercher toutes les séries non-terminées (pour update)
	public function get_all_running_shows() {
		$req = $this->db->conn_id->query('SELECT id, tv_maze, img, name FROM series WHERE etat != 2');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$running = $req->fetchAll();

		return $running;
	}
//------------------------------------------------------------------------------------------------
	//compte le nombre de séries dans la bdd
	public function count_all_shows() {
		$req = $this->db->conn_id->query('SELECT COUNT(id) as count FROM series');
		$all_shows = $req->fetch(PDO::FETCH_ASSOC);

		return $all_shows;
	}
//--------------------------------------------------------------------------------------------------
	//va chercher toutes les séries (limit 36)
	public function get_all_shows($start) {
		$req = $this->db->conn_id->query('SELECT id, img, name FROM series ORDER BY name ASC LIMIT '.$start.', 36');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$all_shows = $req->fetchAll();

		return $all_shows;
	}
//--------------------------------------------------------------------------------------------------------
	//recherche une série par nom
	public function search_show($search) {

		$req = $this->db->conn_id->prepare('SELECT id, img, name FROM series WHERE name LIKE :search OR VF LIKE :search ORDER BY name ASC');
		$req->bindValue(':search', '%'.$search.'%');
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$result = $req->fetchAll();
		return $result;
	}
//-----------------------------------------------------------------------------------
	//va chercher les dernières séries ajoutées sur le site
	public function get_last_shows_added() {

		$req = $this->db->conn_id->query('SELECT * FROM series ORDER BY id DESC LIMIT 0,48');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$last = $req->fetchAll();

		return $last;
	}
//------------------------------------------------------------
	//va chercher les bannières de la série
	public function get_all_users_banners($id_series) {
		$req = $this->db->conn_id->prepare('SELECT * FROM banner WHERE id_series = :id_series ORDER BY id_banner DESC');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->execute();

		$banners = $req->fetchAll(PDO::FETCH_ASSOC);
		return $banners;
	}

//-------------------------------------------------------------------
	//va chercher une partie de la saison
	public function get_the_rest_of_episodes_from_season($id_series, $season, $limit_1, $limit_2) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT DISTINCT ep.*, se.img, st.id as stat_id, et.etat, st.statut, st.icon, se.seasons, DATE_FORMAT(airdate, "%d/%m/%Y") AS date_fr, se.tv_maze
			FROM episodes AS ep
			LEFT JOIN series AS se ON se.id = ep.id_series
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			LEFT JOIN statut AS st ON st.id = sd.statut
			LEFT JOIN etat AS et ON se.etat = et.id
			WHERE sd.id_series = :id_series AND sd.statut = 3 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\' AND ep.season = :season
			ORDER BY ep.number ASC LIMIT '.$limit_1.', '.$limit_2.'');
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$watch_show = $req->fetchAll();
		return $watch_show;
	}
}