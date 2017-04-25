<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {


	public function index() {
		$user = cookie_connect();

		if($user != NULL) {

			//on compte le nombre de séries que $user regardent
			$this->load->model('watch_model', 'watchManager');

			//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
			$this->load->library('Profil_count');
			$this->profil_count->setCounts($user);

			//on fait les requêtes nécessaires à l'affichage des infos et on distille la variable $data qui en résulte
			$data = $this->get_profil_infos($user);
			$airing_this_week = $data['airing_this_week'];
			$most_watched = $data['most_watched'];
			$last_added = $data['last_added'];

			//On définit un objet $profil semblable à $user (pour les visites de profil)
			$profil = $user;

			//on vérifie si $user a des demandes d'amis
			$this->load->model('user_model', 'userManager');
			$requests = $this->userManager->get_friends_requests($user->id());


			//-----------------------------------------------------------------------------------------
			//on passe toutes les variables à la vue
			$this->layout->view('user/profil/index', array(
											'title' => 'SeriesDOM - Profil',
											'user' => $user,
											'profil' => $profil,
											'most_watched' => $most_watched,
											'airing_this_week' => $airing_this_week,
											'requests' => $requests,
											'last_added' => $last_added
														));
		} else {
			redirect();
		}
	}
//----------------------------------------------------------------------------------------------
	//REQ SIMILAIRES INDEX ET FEED
	public function get_profil_infos($user) {

		//on va chercher le planning séries de la semaine de user
		$airing_this_week = $this->watchManager->get_this_week_episodes($user->id());

		//va chercher les séries les plus regardées en 15 jours
		$most_watched = $this->watchManager->get_most_watched($user->id());

		//on va chercher les dernières séries ajoutées
		$last_added = $this->watchManager->get_last_added_shows($user->id());

		$data = array('airing_this_week' => $airing_this_week,
					  'most_watched' => $most_watched,
					  'last_added' => $last_added);
		return $data;

	}

//-------------------------------------------------------------------------------------
	//pour aller visiter un profil d'un autre membre
	public function feed($username = '') {
		$user = cookie_connect();

		if($user != NULL) {
			if(!empty($username)) {
				$username = htmlspecialchars($username);

				$this->load->model('user_model', 'userManager');
				$profil = $this->userManager->get_user_by_username($username);

				if(!empty($profil)) {
					$title = 'SeriesDOM - Profil de '.$username;

					//on compte le nombre de séries que $user regardent
					$this->load->model('watch_model', 'watchManager');

					//on fait les requêtes nécessaires à l'affichage des infos et on distille la variable $data qui en résulte
					$data = $this->get_profil_infos($profil);
					$airing_this_week = $data['airing_this_week'];
					$most_watched = $data['most_watched'];
					$last_added = $data['last_added'];
					

					//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
					$this->load->library('Profil_count');
					$this->profil_count->setCounts($profil);

					//-----------------------------------------------------------------------------------------
					//on regarde si $user et $profil sont amis
					$friend = $this->userManager->is_friend($user->id(), $profil->id());


					//on passe toutes les variables à la vue
					$this->layout->view('user/profil/index', array(
													'title' => $title,
													'user' => $user,
													'profil' => $profil,
													'most_watched' => $most_watched,
													'airing_this_week' => $airing_this_week,
													'friend' => $friend,
													'last_added' => $last_added
																));
				} else {
					show_404();
				}
			} else {
				redirect('profil');
			}

		} else {
			redirect('connexion');
		}
	}

//----------------------------------------------------------------------------------------------------------------------------
	public function watchlist() {
		$user = cookie_connect();

		if($user != NULL) {

			//NEW SHOWS AND NEW SEASONS--------------------------------------------------------------------
			$this->load->model('watch_model', 'watchManager');
			$coming_shows = $this->watchManager->get_coming_shows($user->id());


			//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
			$this->load->library('Profil_count');
			$this->profil_count->setCounts($user);
				

			//EPISODES A VOIR-------------------------------------------------------------------------------
			//on va voir s'il y a des épisodes à voir des séries en cours
			$count_unseen = $this->watchManager->count_unseen_shows_and_episodes($user->id());
			$count_show = count($count_unseen);
			$count_ep = count_nb_ep_total($count_unseen);


			//SERIE AFFICHEE----------------------------------------------------------------
			//on prend la première série (par ordre alphabétique)
			if(!empty($count_unseen)) {
				$id_series = $count_unseen['0']['id'];
				$show_seasons = intval($count_unseen['0']['seasons']);

				//on va chercher la saison en cours de $user (par 10 ép)
				$season = get_season_user_is_watching($user->id(), $id_series, $show_seasons, 3);

				$unseen_ep_from_season = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season); 

				//On va chercher les numéros de saisons que user doit encore voir
				$nbr_season_to_see = $this->watchManager->get_number_of_seasons_to_see($user->id(), $id_series);

				//on va chercher les épisodes non vus de la saison
				$watch_show = $this->watchManager->get_unseen_episodes_from_show($user->id(), $id_series, $season, 0, 10);

				//on compte le nombre d'épisodes à voir dans la saison
				$count_nbr_ep = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season);

				//ON VA CHERCHER LES DONNEES FORM(statuts)------------------------------------
				$this->load->model('data_model', 'dataManager');
				$statuts = $this->dataManager->get_all_status();

				//ON COMPTE LE nbr d'épisodes de la série déjà diffusé
				$count_total = $this->episodesManager->count_number_episodes($id_series);
				$total_ep = $count_total['count'];

				//on compte le nombre d'épisodes que $user a vu de la série
				$ep_vus = $this->watchManager->count_number_episodes_seen_by_user($id_series, $user->id());
				$ep_vus = $ep_vus['count'];



				//on passe toutes les infos à la vue
				$this->layout->view('user/profil/watchlist', array(
									'title' => 'SeriesDOM - Watchlist',
									'user' => $user,
									'profil' => $user,
									'count_unseen' => $count_unseen,
									'coming_shows' => $coming_shows,
									'count_show' => $count_show,
									'count_ep' => $count_ep,
									'watch_show' => $watch_show,
									'nbr_season_to_see' => $nbr_season_to_see,
									'count_nbr_ep' => $count_nbr_ep,
									'statuts' => $statuts,
									'total_ep' => $total_ep,
									'ep_vus' => $ep_vus,
									'all_ep_from_season' => $season,
									'unseen_ep_from_season' => $unseen_ep_from_season
									));

				} else { //watchlist vide
					$no_show = '';

					$this->layout->view('user/profil/watchlist', array(
										'title' => 'SeriesDOM - Watchlist',
										'user' => $user,
										'no_show' => $no_show,
										'profil' => $user
										));
				}
		} else {
			redirect();
		}
	}

//-----------------------------------------------------------------------------------------------------
	public function update() {
		$user = cookie_connect();

		if($user != NULL) {
			if($user->id() > 0) {
				$this->layout->view('user/profil/update', array(
										'title' => 'SeriesDOM - Update Profil',
										'user' => $user
										));
			} else {
				show_404();
			}
		} else {
			redirect();
		}
	}

//---------------------------------------------------------------------------------------------------
	public function waitinglist() {
		$user = cookie_connect();

		if($user != NULL) {

			if($user->id() > 0) {
				$this->load->model('watch_model', 'watchManager');
				$waiting = $this->watchManager->get_waiting_list_shows_and_episodes($user->id());

				//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
				$this->load->library('Profil_count');
				$this->profil_count->setCounts($user);

				$count_total_ep = count_total_show($waiting);

				$this->layout->view('user/profil/waitinglist', array(
										'title' => 'SeriesDOM - WaitingList',
										'user' => $user,
										'profil' => $user,
										'waiting' => $waiting,
										'count_total_ep' => $count_total_ep
										));
			} else {
				show_404();
			}
		} else {
			redirect();
		}

	}

//-------------------------------------------------------------------------------------
	public function BeginList() {
		$user = cookie_connect();

		if($user != NULL) {

			if($user->id() > 0) {
				$this->load->model('watch_model', 'watchManager');
				$begin = $this->watchManager->get_begin_list_shows_and_episodes($user->id());

				//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
				$this->load->library('Profil_count');
				$this->profil_count->setCounts($user);

				$count_total_ep = count_total_show($begin);

				$this->layout->view('user/profil/beginlist', array(
										'title' => 'SeriesDOM - BeginList',
										'user' => $user,
										'profil' => $user,
										'begin' => $begin,
										'count_total_ep' => $count_total_ep
										));
			} else {
				show_404();
			}

		} else {
			redirect();
		}
	}
//-------------------------------------------------------------------------------------------------------------------
	public function bilan($month = '', $year = '') {
		$user = cookie_connect();
		$cu_month = date('n');
		$cu_year = date('Y');
		$month = (int)htmlspecialchars($month);
		$year = htmlspecialchars($year);

		if($user != NULL) {
			//dates activités
			$this->load->model('seriesdom_model', 'seriesdomManager');
			$dates_add = $this->seriesdomManager->get_dates_add($user->id());
			$dates_up = $this->seriesdomManager->get_dates_update($user->id());

			$dates_activites = get_activities_date($dates_add, $dates_up);
				

			$date= get_month_and_year_for_bilan($month, $year, $dates_activites);
			$month = $date['month'];
			$year = $date['year'];


			//pour afficher les mois en français (et éviter qu'ils n'affichent les mois qui ne sont pas encore passés si année en cours)
			if($year == $cu_year) {
				$mois = mois_in_french($cu_month);
			} else {
				$mois = mois_in_french(12);
			}


			$this->load->model('watch_model', 'watchManager');

			//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
			$this->load->library('Profil_count');
			$this->profil_count->setCounts($user);


			//on récupère la liste d'ép vus depuis le début du mois
			$ep_seen = $this->watchManager->get_monthly_ep($user->id(), $month, $year);

			//on calcule le total d'ép vus
			$total_ep_seen = '';
			foreach($ep_seen as $show) {
				$total_ep_seen += $show->count_ep;
			}

			//on récupère les séries ajoutées
			$shows_added = $this->seriesdomManager->get_monthly_added_show($user->id(), $month, $year);

			//on récupère les séries terminées
			$monthly_show_ended = $this->seriesdomManager->get_monthly_ended_show($user->id(), $month, $year);

			//on récupère les coups de coeur
			$coup_de_coeur = $this->seriesdomManager->get_monthly_fav($user->id(), $month, $year);

			//on récupère les nouvelles séries commencées
			$monthly_show_begin = $this->seriesdomManager->get_monthly_began_show($user->id(), $month, $year);


			//si l'année est finie on fait le bilan de l'année
			if($year != $cu_year && $month == 12) {
				//épisodes vus en un an
				$yearly_ep_seen = $this->watchManager->get_yearly_ep_seen($user->id(), $year);

				//séries ajoutées sur un an
				$yearly_shows_added = $this->seriesdomManager->get_yearly_added_show($user->id(), $year);

				//séries terminées sur un an
				$yearly_shows_ended = $this->seriesdomManager->get_yearly_ended_show($user->id(), $year);

				//séries commencées en un an
				$yearly_began_shows = $this->seriesdomManager->get_yearly_began_show($user->id(), $year);

				//coups de coeur en un an
				$yearly_fav = $this->seriesdomManager->get_yearly_fav($user->id(), $year);

				$bilan_year = array('yearly_ep_seen' => $yearly_ep_seen, 'yearly_shows_added' => $yearly_shows_added, 'yearly_shows_ended' => $yearly_shows_ended, 'yearly_began_shows' => $yearly_began_shows, 'yearly_fav' => $yearly_fav);

			} else {
				$bilan_year = '';
			}

				$this->layout->view('user/profil/bilan', array(
										'title' => 'SeriesDOM - Bilan',
										'user' => $user,
										'profil' => $user,
										'dates' => $dates_activites,
										'year' => $year,
										'month' => $month,
										'mois' => $mois,
										'ep_seen' => $ep_seen,
										'shows_added' => $shows_added,
										'monthly_show_ended' => $monthly_show_ended,
										'coup_de_coeur' => $coup_de_coeur,
										'monthly_show_begin' => $monthly_show_begin,
										'total_ep_seen' => $total_ep_seen,
										'bilan_year' => $bilan_year
										));
		} else {
			redirect();
		}
	}
}