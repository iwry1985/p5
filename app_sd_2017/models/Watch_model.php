<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Watch_model extends CI_Model {

	//va chercher les infos de la série par rapport à $user
	public function watch_show($id_users, $id_show) {
		$req = $this->db->conn_id->prepare('SELECT * FROM seriesdom AS sd 
			WHERE id_users = :id_users AND id_series = :id_series');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_show, PDO::PARAM_INT);
		$req->execute();

		$watch = $req->fetch(PDO::FETCH_ASSOC);
		return $watch;
	}
//---------------------------------------------------------------------------------------------------------------------------------------------
	
	//va chercher la liste d'ép que $user a vu de la série dans la table watchlist
	public function get_ep_seen($id_users, $id_show) {
		$req = $this->db->conn_id->prepare('SELECT * FROM watchlist WHERE id_users = :id_users AND id_series = :id_series');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_show, PDO::PARAM_INT);
		$req->execute();
		$nb_ep_vus = $req->rowCount();

		$watchlist_user = $req->fetchAll(PDO::FETCH_ASSOC);

		//id des ép vus par $user
		$id_ep_seen = array();

		foreach($watchlist_user as $ep) {
			$id_ep_seen[] = $ep['id_ep'];
			$id_ep_seen['nb_ep_vus'] = $nb_ep_vus;
		}

		return $id_ep_seen;

	}	

//----------------------------------------------------------------------------------------------------------------------------------------------
	//vérifie que user a vu un ep en particulier
	public function episode_seen($id_users, $id_ep) {

		$req = $this->db->conn_id->prepare('SELECT * FROM watchlist WHERE id_users = :id_users AND id_ep = :id_ep');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':id_ep', $id_ep, PDO::PARAM_INT);
		$req->execute();

		$episode_seen = $req->rowCount();

		return $episode_seen;
	}
//----------------------------------------------------------------------------------------------------

	//va chercher la saison en cours de user
	public function get_season_userIsWatching($id_users, $id_series) {

		$seasons = $this->get_number_of_seasons_to_see($id_users, $id_series);

		//si $user est a jour, on va chercher la saison du dernier ep vu
		if(empty($seasons)) {
			$req = $this->db->conn_id->prepare('SELECT ep.season, ep.number FROM episodes AS ep 
			LEFT JOIN watchlist AS watch ON ep.id_ep = watch.id_ep 
			WHERE watch.id_users = :id_users AND ep.id_series = :id_series ORDER BY watch.id DESC');
			$req->bindValue('id_users', $id_users, PDO::PARAM_INT);
			$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
			$req->execute();

			$season = $req->fetch(PDO::FETCH_ASSOC);
		} else {
			//on donne à saison la valeur de la première des saisons qu'il lui reste à voir
			$season = $seasons[0];
		}


		return $season;
	}	
//----------------------------------------------------------------------------------
	public function get_infos_to_show_page_home($result) {
		$visionnages = array();
		$id_series = '';
		$id_users = '';
		$array_count = array();
		$i = 0;


		//on va chercher toutes les infos par rapport aux derniers épisodes vus
		foreach($result as $res) {
			//tant qu'on a pas 30 résultats on va chercher les infos
			if(count($visionnages) < 30) {
				if($id_series == $res['id_series'] && $id_users == $res['id_users']) {
					$count_ep_per_show++;

				} else {
					$req = $this->db->conn_id->prepare('SELECT watch.id, watch.id_ep, watch.id_users,  watch.watch_date, se.img as series_poster, se.name as series_name, ep.name as episode_name, ep.season as episode_season, ep.number as episode_number, DATE_FORMAT(watch_date, "%d/%m/%Y à %Hh%i") as watchdate_fr, us.avatar, ban.img as banner, ban.id_series as ban_id_series, us.username, watch.id_series
					FROM watchlist AS watch
					LEFT JOIN series AS se ON se.id = watch.id_series
					LEFT JOIN episodes AS ep ON ep.id_ep = watch.id_ep
					LEFT JOIN users AS us ON watch.id_users = us.id
					LEFT JOIN banner AS ban ON ban.id_banner = us.banner
					WHERE watch.id = :id AND watch.id_users = :id_users
					ORDER BY watch_date');
					$req->bindValue(':id', $res['watch_id'], PDO::PARAM_INT);
					$req->bindValue(':id_users', $res['id_users'], PDO::PARAM_INT);
					$req->execute();
					$visionnage = $req->fetch(PDO::FETCH_ASSOC);

					$i++;
					$visionnages[$i] = $visionnage;
					$count_ep_per_show = 1;
			
					//on stocke le dernier $id_series récupérer pour éviter trop de répétitions
				 	$id_series = $visionnage['id_series'];
				 	$id_users = $visionnage['id_users'];
				}

				//nombre d'ép vus de la série
				$visionnages[$i]['count_ep'] = $count_ep_per_show;
						
			} else {
				break;
			}
			
		}
		return $visionnages;
	}

//----------------------------------------------------------------------	
	//va chercher la liste de visionnage de $user et ses amis (même s'il n'en a pas)
	public function user_and_friends_watching($id_user, $start) {
		//on va chercher les 500 derniers résultats des visionnages de user et de ses amis
		$req = $this->db->conn_id->prepare('SELECT watch.id as watch_id, watch_date, watch.id_series, watch.id_users
			FROM watchlist AS watch
			LEFT JOIN friends AS fr ON fr.id_users_1 = watch.id_users
			WHERE fr.id_users_2 = :id_user AND fr.friends = :friends 
			UNION SELECT watch.id, watch_date, watch.id_series, watch.id_users
			FROM watchlist AS watch
			WHERE watch.id_users = :id_user
			ORDER BY watch_id DESC LIMIT '.$start.', 500');
		$req->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$req->bindValue(':friends', 'friends', PDO::PARAM_STR);
		$req->execute();
		$result = $req->fetchAll(PDO::FETCH_ASSOC);

		$visionnages = $this->get_infos_to_show_page_home($result);

		return $visionnages;

	}
//-----------------------------------------------------------------------------------
	public function user_watching($id_user, $start) {
		$req = $this->db->conn_id->prepare('SELECT watch.id as watch_id, watch_date, watch.id_series, watch.id_users
			FROM watchlist AS watch
			WHERE watch.id_users = :id_user
			ORDER BY watch_id DESC LIMIT '.$start.', 500');
		$req->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$req->execute();
		$result = $req->fetchAll(PDO::FETCH_ASSOC);

		$visionnages = $this->get_infos_to_show_page_home($result);

		return $visionnages;
	}
//--------------------------------------------------------------------------------------
	public function everybody_watching($start) {
		$req = $this->db->conn_id->query('SELECT watch.id as watch_id, watch_date, watch.id_series, watch.id_users
			FROM watchlist AS watch
			ORDER BY watch_id DESC LIMIT '.$start.', 500');
		$result = $req->fetchAll(PDO::FETCH_ASSOC);

		$visionnages = $this->get_infos_to_show_page_home($result);
		return $visionnages;
	}
//-----------------------------------------------------------------------------------------------------
	//compte le nombre de séries de $user à voir, à commencer,...	
	public function count_shows_user_is_watching($id_users, $statut) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(id) as count FROM seriesdom WHERE id_users = :id_users AND statut = :statut');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':statut', $statut, PDO::PARAM_INT);
		$req->execute();
		$count = $req->fetch(PDO::FETCH_ASSOC);


		return $count['count'];
	}
//-------------------------------------------------------------------------------------------------------------
	//va chercher les séries en cours de $user diffusées la veille (s'affiche jusqu'à ce que $user le coche)
	public function get_yesterday_aired_episodes($id_users) {
		$yesterday = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-1, date('y'))); 
		$today = date('Y-m-d');


		$req = $this->db->conn_id->prepare('SELECT DISTINCT se.id, se.img, ep.season, ep.number, se.name
			FROM series AS se 
			LEFT JOIN episodes AS ep ON ep.id_series = se.id
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			WHERE ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users) AND sd.id_users = :id_users AND (sd.statut = 3 OR (sd.statut = 1 AND se.etat = 3)) AND ((ep.airdate = :yesterday AND se.network != 6) OR (ep.airdate = :today AND se.network = 6))
			ORDER BY se.name, ep.season, ep.number ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':yesterday', $yesterday);
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$aired_yesterday = $req->fetchAll(PDO::FETCH_ASSOC);

		return $aired_yesterday;
	}
//---------------------------------------------------------------------------------------------------------------
	//va chercher les épisodes vus par $user durant la semaine (du lundi au dimanche)
	public function get_weekly_seen_episodes($id_user) {
		//date('N') nous donne le jour de la semaine (ex. : 3 mercredi, 5 vendredi. On soustrait un jour pour ne pas compter aujourd'hui)
		$date = date('N') - 1;
		//on soustrait le résultat pour obtenir le nombre de jours depuis lundi
		//'ex: vendredi $date = 5 - 1 -> On soustrait 4 jours à la date d'aujourd'hui pour obtenir la date de lundi
		$monday = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d') - $date, date('y')));

		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name, count(watch.id) as count_ep FROM watchlist AS watch
			LEFT JOIN series AS se ON se.id = watch.id_series
			WHERE watch.id_users =  :id_user AND watch_date >= :monday
			GROUP BY watch.id_series ORDER BY count_ep DESC');
		$req->bindValue(':id_user', $id_user, PDO::PARAM_INT);
		$req->bindValue(':monday', $monday);
		$req->execute();

		$seen_this_week = $req->fetchAll(PDO::FETCH_ASSOC);
		return $seen_this_week;
	}
//----------------------------------------------------------------------------------------------------------
	//va chercher les séries les plus regardés pendant 1 mois
	public function get_most_watched($id_users) {
		$period = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d') - 
			30, date('y')));

		$req= $this->db->conn_id->prepare('SELECT se.id, se.img, se.name, COUNT(watch.id) as count_ep FROM watchlist AS watch LEFT JOIN series AS se ON watch.id_series = se.id WHERE watch.id_users = :id_users AND watch_date >= :period 
			GROUP BY watch.id_series ORDER BY count_ep DESC LIMIT 0,6');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':period', $period);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$most_watched = $req->fetchAll();
		return $most_watched;
	}

//----------------------------------------------------------------------------------------
	//va chercher les séries de user diffusées dans la semaine (du lundi au dimanche)
	public function get_this_week_episodes ($id_users) {
		//date('N') nous donne le jour de la semaine (ex. : 3 mercredi, 5 vendredi.
		$date = date('N');

		if($date != 7) { //si on est pas dimanche
			//on soustrait le num à 7 pour savoir combien de jours il reste jusque dimanche
			$restants = 7 - $date;
			//on additionne le résultat pour obtenir le nombre de jours jusque dimanche
			//'ex: vendredi $date = 5 -> $restants = 7 - 5 = 2. On additionne 2 jours à la date d'aujourd'hui pour obtenir la date de dimanche
			$sunday = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d') + $restants, date('y')));

		} else { //le dimanche, on affiche les épisodes jusqu'au prochain samedi
			$sunday = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d') + 6, date('y')));
		}
		
		$today = date('Y-m-d');


		$req = $this->db->conn_id->prepare('SELECT DISTINCT se.name as show_name, ep.id_series, se.img, ep.name, ep.season, ep.number, ep.airdate FROM episodes AS ep
			LEFT JOIN series AS se ON ep.id_series = se.id
			LEFT JOIN seriesdom AS sd ON ep.id_series = sd.id_series
			WHERE sd.id_users = :id_users AND (sd.statut = 3 OR se.etat = 3) AND ((airdate <= :sunday AND airdate > :today) OR airdate = :today)
			GROUP BY id_series ORDER BY airdate');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':sunday', $sunday);
		$req->bindValue(':today', $today);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$airing_this_week = $req->fetchAll();
		return $airing_this_week;

	}
//----------------------------------------------------------------------------------------------------------
	//on va vérifie s'il y a des épisodes à voir pour les séries en cours de user
	public function count_unseen_shows_and_episodes($id_users) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT COUNT(ep.id_ep) as count_episodes, se.name, se.id, se.seasons FROM episodes AS ep 
			LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
			LEFT JOIN series AS se ON sd.id_series = se.id
			WHERE sd.id_users = :id_users AND sd.statut = 3 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
			GROUP BY sd.id_series ORDER BY se.name ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$count_unseen = $req->fetchAll(PDO::FETCH_ASSOC);
		return $count_unseen;
	}
//----------------------------------------------------------------------------------------------
	//on va chercher les séries qui arrivent : nouvelles saisons et nouvelles séries que user veut commencer
	public function get_coming_shows($id_users) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT DISTINCT ep.id_series, se.img, se.name AS show_name, ep.name, ep.airdate, ep.season, ep.number FROM episodes AS ep
			LEFT JOIN seriesdom AS sd ON ep.id_series = sd.id_series
			LEFT JOIN series AS se ON se.id = ep.id_series
			WHERE sd.id_users = :id_users AND (se.etat = 1 AND sd.statut = 3 OR se.etat = 3 AND sd.statut = 1) AND ep.number = 1 AND airdate >= :today AND airdate != \'0000-00-00\'
			ORDER BY ep.airdate ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$coming = $req->fetchAll();
		return $coming;
	}

//---------------------------------------------------------------------------------------------
	//MODE RANDOM EN COURS
	public function random_watchlist($id_users, $number) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT ep.id_series
            FROM episodes AS ep
            LEFT JOIN series AS se ON se.id = ep.id_series
            LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
            WHERE sd.id_users = :id_users AND sd.statut = 3 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
            AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
            GROUP BY ep.id_series ORDER BY RAND()');
        $req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
        $req->bindValue(':today', $today);
        $req->execute();

		$result = $req->fetchAll(PDO::FETCH_ASSOC);

		//on va chercher les infos épisodes des séries récupérées et on filtre pour ne pas avoir plusieurs fois la même série
		$id_series = '';
		$random_watchlist = [];

		foreach($result as $res) {
			if(count($random_watchlist) < $number) {
				$req = $this->db->conn_id->prepare('SELECT ep.id_series, ep.id_ep, se.img, ep.name, ep.number, ep.season, se.name AS show_name, ep.name
                    FROM episodes AS ep
                    LEFT JOIN series AS se ON se.id = ep.id_series
                    LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
                    WHERE se.id = :id_series 
                    AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users) ORDER BY ep.season ASC, ep.number ASC');
                    $req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
                    $req->bindValue(':id_series', $res['id_series'], PDO::PARAM_INT);
                    $req->execute();

				$show = $req->fetch(PDO::FETCH_ASSOC);
				$random_watchlist[] = $show;
			} else {
				break;
			}
		}

		$random_json = json_encode($random_watchlist);

		return $random_json;
	}

//------------------------------------------------------------------------------------
	//va chercher les épisodes non vus d'une série
	public function get_unseen_episodes_from_show($id_users, $id_series, $season, $limit_1, $limit_2) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT DISTINCT ep.*, se.img, st.id as stat_id, et.etat, st.statut, st.icon, se.seasons, DATE_FORMAT(airdate, "%d/%m/%Y") AS date_fr
			FROM episodes AS ep
			LEFT JOIN watchlist AS watch ON watch.id_ep = ep.id_ep
			LEFT JOIN series AS se ON se.id = ep.id_series
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			LEFT JOIN statut AS st ON st.id = sd.statut
			LEFT JOIN etat AS et ON se.etat = et.id
			WHERE sd.id_users = :id_users AND sd.id_series = :id_series AND sd.statut = 3 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\' AND ep.season = :season
			AND ep.id_ep NOT IN (SELECT id_ep FROM watchlist WHERE id_users = :id_users AND id_series = :id_series)
			ORDER BY ep.number ASC LIMIT '.$limit_1.', '.$limit_2.'');
		$req->bindValue(':id_users', $id_users, PDO:: PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Episode');

		$watch_show = $req->fetchAll();
		return $watch_show;
	}
//------------------------------------------------------------------------------------------
	//calcule le nombre d'épisodes restants pour terminer la saison
	public function count_unseen_episodes_from_season($id_users, $id_series, $season) {
		$today = date('Y-m-d');
		$req = $this->db->conn_id->prepare('SELECT DISTINCT ep.name, ep.season, ep.number, ep.id_ep
			FROM episodes AS ep
			LEFT JOIN watchlist AS watch ON watch.id_ep = ep.id_ep
			LEFT JOIN series AS se ON se.id = ep.id_series
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			LEFT JOIN statut AS st ON st.id = sd.statut
			WHERE sd.id_users = :id_users AND sd.id_series = :id_series AND sd.statut = 3 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\' AND ep.season = :season
			AND ep.id_ep NOT IN (SELECT id_ep FROM watchlist WHERE id_users = :id_users AND id_series = :id_series)');
		$req->bindValue(':id_users', $id_users, PDO:: PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$count_nbr_ep = $req->fetchAll(PDO::FETCH_ASSOC);
		return $count_nbr_ep;
	}
//------------------------------------------------------------------------
		//calcule le nombre d'épisodes restants pour terminer la saison
	public function get_seen_episodes_from_season($id_users, $id_series, $season) {

		$req = $this->db->conn_id->prepare('SELECT ep.id_ep
			FROM episodes AS ep
			LEFT JOIN watchlist AS watch ON watch.id_ep = ep.id_ep
			WHERE watch.id_users = :id_users AND watch.id_series = :id_series AND ep.season = :season');
		$req->bindValue(':id_users', $id_users, PDO:: PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->execute();

		$seen_ep = $req->fetchAll(PDO::FETCH_ASSOC);
		return $seen_ep;
	}
//-------------------------------------------------------------------------------------------------
	//calcule le nombre d'épisodes de la série vus par $user
	public function count_number_episodes_seen_by_user($id_series, $id_users) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT COUNT(id_ep) as count FROM watchlist WHERE id_series = :id_series AND id_users = :id_users
			GROUP BY id_series');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();


		$ep_vus = $req->fetch();
		return $ep_vus;
	}
//--------------------------------------------------------------------------------------------
	//va chercher les séries à rattraper de user
	public function get_waiting_list_shows_and_episodes($id_users) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT COUNT(ep.id_ep) as count_ep, se.name, se.id, se.img FROM episodes AS ep
			LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
			LEFT JOIN series AS se ON se.id = ep.id_series
			WHERE sd.id_users = :id_users AND sd.statut = 2 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
			GROUP BY sd.id_series ORDER BY count_ep ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$waiting = $req->fetchAll(PDO::FETCH_ASSOC);

		return $waiting;
	}

//--------------------------------------------------------------------------------------
	//MODE RANDOM EN COURS
	public function random_waitingList($id_users, $number) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT DISTINCT se.id, se.img, COUNT(ep.id_ep) as count_ep
			FROM series AS se
			LEFT JOIN episodes AS ep ON ep.id_series = se.id
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			WHERE sd.id_users = :id_users AND sd.statut = 2 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
			GROUP BY se.id ORDER BY RAND() LIMIT 0, '.$number.'');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$random_waitingList = $req->fetchAll(PDO::FETCH_ASSOC);

		$random_json = json_encode($random_waitingList);

		return $random_json;
	}

//--------------------------------------------------------------------------------
	//va chercher les séries à rattraper de user
	public function get_begin_list_shows_and_episodes($id_users) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT COUNT(ep.id_ep) as count_ep, se.name, se.id, se.img FROM episodes AS ep
			LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
			LEFT JOIN series AS se ON se.id = ep.id_series
			WHERE sd.id_users = :id_users AND sd.statut = 1 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
			GROUP BY sd.id_series ORDER BY count_ep ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$begin = $req->fetchAll(PDO::FETCH_ASSOC);

		return $begin;
	}
//----------------------------------------------------------------------------------
	//MODE RANDOM EN COURS
	public function random_beginList($id_users, $number) {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT DISTINCT se.id, se.img, COUNT(ep.id_ep) as count_ep
			FROM series AS se
			LEFT JOIN episodes AS ep ON ep.id_series = se.id
			LEFT JOIN seriesdom AS sd ON sd.id_series = se.id
			WHERE sd.id_users = :id_users AND sd.statut = 1 AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			GROUP BY se.id ORDER BY RAND() LIMIT 0, '.$number.'');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$random_beginList = $req->fetchAll(PDO::FETCH_ASSOC);

		$random_json = json_encode($random_beginList);

		return $random_json;
	}
//--------------------------------------------------------------------------
	//compte le nombre d'ép vus par user
	public function get_all_episodes_seen_by_user($id_users) {

		$req = $this->db->conn_id->prepare('SELECT id_ep FROM watchlist WHERE id_users = :id_users');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();

		$count_ep = $req->rowCount();

		return $count_ep;
	}
//---------------------------------------------------------------------------
	//va chercher le nombre d'ép vu durant le mois sélectionné
	public function get_monthly_ep($id_users, $month, $year) {
		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name, COUNT(watch.id) AS count_ep FROM watchlist as watch
			LEFT JOIN series as se ON se.id = watch.id_series
			WHERE watch.id_users = :id_users AND watch_month = :month AND watch_year = :year GROUP BY watch.id_series ORDER BY count_ep DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':month', $month, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$ep_seen = $req->fetchAll();
		return $ep_seen;
	}
//-----------------------------------------------------------------------------

	public function get_number_of_seasons_to_see($id_users, $id_series) {
		$today = date('Y-m-d');

		//on va chercher toutes les saisons où il reste des ép à voir
		$req = $this->db->conn_id->prepare('SELECT ep.season, ep.number, se.name
			FROM episodes AS ep 
			LEFT JOIN seriesdom AS sd ON sd.id_series = ep.id_series
			LEFT JOIN series AS se ON sd.id_series = se.id
			WHERE sd.id_users = :id_users AND se.id = :id_series AND ((ep.airdate < :today AND se.network != 6) || (ep.airdate <= :today AND se.network = 6)) AND ep.airdate != \'0000-00-00\'
			AND ep.id_ep NOT IN(SELECT id_ep FROM watchlist WHERE id_users = :id_users)
			ORDER BY ep.season ASC, ep.number ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':today', $today);
		$req->execute();

		$result = $req->fetchAll(PDO::FETCH_ASSOC);
		$seasons = [];

		if(!empty($result)) {
			$season = '';

			foreach($result as $count) {
				if($count['season'] != $season) {
					$seasons[] = $count;
					$season = $count['season'];
				}
			}
		};
		return $seasons;
	}
//------------------------------------------------------------------------------------
	public function get_last_added_shows($id_users) {
		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name 
			FROM seriesdom AS sd
			LEFT JOIN series AS se ON sd.id_series = se.id
			WHERE sd.id_users = :id_users AND sd.statut != \'6\' AND sd.date_time_add <= NOW()
			ORDER BY sd.date_time_add DESC LIMIT 0,6');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$last_added = $req->fetchAll();
		return $last_added;
	}
//-------------------------------------------------------------------------------
	//épisodes vus à l'année
	public function get_yearly_ep_seen($id_users, $year) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(watch.id) AS count FROM watchlist as watch
			LEFT JOIN series as se ON se.id = watch.id_series
			WHERE watch.id_users = :id_users AND watch_year = :year');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$ep_seen = $req->fetch(PDO::FETCH_ASSOC);
		return $ep_seen['count'];
	}
} 
