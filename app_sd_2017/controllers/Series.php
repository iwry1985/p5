<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Series extends CI_Controller {

//------------------------------------------------------------------------------------------------------------------------------------------------------------

	//FICHE SERIE ($id en param)
	public function show($id = '', $season = '') {

		//------------------------------------------------
		//si $user
		$user = cookie_connect();

			//si $id, on affiche la page série
			if(isset($id) && !empty($id) && $id > 0) {

				//on va chercher la série dans bdd
				$id_show = htmlspecialchars($id);
				$this->load->model('serie_model', 'serieManager');
				$show = $this->serieManager->get_show($id_show);

				//si l'id correspond bien à une série -----------------
				if($show->id() != null) {

					//on va voir si $user a la série dans sa liste ---
					if(!empty($user)) {
						$id_user = $user->id();
						$this->load->model('watch_model', 'watchManager');
						$watch = $this->watchManager->watch_show($id_user, $id_show);
					} else {
						$watch = 'no_user';
					}

					$title = $show->name();
					

					//STATS_STATUTS------------------------------------
					$count_statut = $this->serieManager->get_stats('id_series', $show->id(), 'statut');
					$show->setStats($count_statut, 'statut');
					$show->setNb_users($count_statut);

					//STATS_NOTE------------------------------------------
					$count_note = $this->serieManager->get_stats('id_series', $show->id(), 'note');
					$show->setStats($count_note, 'note');

					//CHARACTERS-------------------------------------
					$this->load->model('character_model', 'charactersManager');
					$characters = $this->charactersManager->get_characters($show->id());
					$folder_char = find_folder($show->id());
					$show->setCharacters_folder($folder_char);

					//EPISODES--------------------------------
					//tous les épisodes + le prochain ép
					$this->load->model('episode_model', 'episodesManager');
					$nb_seasons = $this->episodesManager->get_count_seasons($show->id());
					$show->setSeasons_tbEp($nb_seasons);
					$next_ep = $this->episodesManager->get_next_ep($show->id());


					//WATCHLIST_USER---------------------------------------
					//les épisodes que $user a vu de la série
					if(!empty($user)) {
						$watchlist_user = $this->watchManager->get_ep_seen($id_user, $show->id());
						$season = get_season_user_is_watching($user->id(), $show->id(), $show->seasons(), $watch['statut']);
					} else {
						$watchlist_user = '';
						$season_userIsWatching = '';
						$season = 1;
					}
						

					$episodes = $this->episodesManager->get_episodes_bySeason($show->id(), $season);

					if(!empty($user)) {
						$unseen_ep_from_season = $this->watchManager->count_unseen_episodes_from_season($id_user, $show->id(), $season);
					} else {
						$unseen_ep_from_season = 'no_user';
					}

			
					//épisodes diffusés
					$aired_episodes = $this->episodesManager->get_aired_episodes($show->id());
					$nb_episodes = count($aired_episodes);
					$show->setNb_episodes($nb_episodes);


					//ON VA CHERCHER LES DONNEES FORM (avis, statuts)------------------------------------
					$this->load->model('data_model', 'dataManager');
					$notes = $this->dataManager->get_all_notes();
					$statuts = $this->dataManager->get_all_status();


					//ON VA CHERCHER LES AMIS de $user-----------------------------------
					if(!empty($user)) {
						$this->load->model('User_model', 'userManager');
						$all_friends = $this->userManager->get_user_friends($id_user);
						$show_friends = $this->userManager->get_friends_who_watch($show->id(), $id_user);
					} else {
						$all_friends = '';
						$show_friends = '';
					}


					//SUGGESTIONS séries similaires---------------------------------------------
					if(!empty($user)) {
						$similaires = $this->serieManager->get_show_suggestions($show->id(), $show->genre(), $show->runtime(), $id_user);
					} else {
						$similaires = $this->serieManager->get_show_suggestions($show->id(), $show->genre(), $show->runtime(), 0);
					}

					//on va chercher les banner de la série
					$banners = $this->serieManager->get_all_users_banners($show->id());


					//ON AFFICHE LA PAGE---------------------------
					$this->layout->view('series/serie', array(
						'show' => $show, 
						'watch' => $watch, 
						'title' => 'SeriesDOM - '.$title, 
						'count_statut' => $count_statut, 
						'count_note' => $count_note,
						'characters' => $characters,
						'episodes' => $episodes,
						'watchlist_user' => $watchlist_user,
						'notes' => $notes,
						'statuts' => $statuts,
						'next_ep' => $next_ep,
						'similaires' => $similaires,
						'show_friends' => $show_friends,
						'season_userIsWatching' => $season,
						'user' => $user,
						'banners' => $banners,
						'unseen_ep_from_season' => $unseen_ep_from_season
						));
				//------------------------------------------------------	
				} else { //sinon, page d'erreur
					show_404();
				}

			//-------------------------------------------------------	
			} else { //sinon on affiche l'index
				show_404();
			}
		
		//---------------------------------------------------------

	}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function listing($page = '') {

		$user = cookie_connect();

		//on va chercher toute les séries présentes dans la bdd
		$this->load->model('serie_model', 'serieManager');
		$count = $this->serieManager->count_all_shows();
		$count = $count['count'];

		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url('series/listing');
		$config['total_rows'] = $count;
		$config['per_page'] = 36;
		$this->pagination->initialize($config);

		if(isset($page) && $page > 0 && $page <= $count) {
			$cPage = $page;
		} else {
			$cPage = 0;
		}

		//on va chercher toutes les séries de la bdd
		$all_shows = $this->serieManager->get_all_shows($cPage);

		//ON AFFICHE LA PAGE---------------------------
					$this->layout->view('series/listing', array(
						'all_shows' => $all_shows, 
						'title' => 'SeriesDOM - Listing',
						'user' => $user
						));
	}
//-------------------------------------------------------------------------------------------------------
	public function search() {
		$user = cookie_connect();

		$search = htmlspecialchars(trim($this->input->post('search')));
		$count_caracteres = strlen($search);

		if(empty($search) || $count_caracteres < 2) {
			$result = '';
		} else {

			$this->load->model('serie_model', 'serieManager');
			$result = $this->serieManager->search_show($search);

			if(empty($result)) {
				//si aucun résultat, on explode la recherche pour chercher mot par mot
				$search_ = explode(' ', $search);

				foreach($search_ as $word) {
					//on cherche chaque mot, jusqu'à ce qu'on trouve une correspondance
					if(empty($result)) {
						$result = $this->serieManager->search_show($word);
					}
				}
			}
		}

		$this->layout->view('series/search', array(
							'title' => 'SeriesDOM - Search',
							'result' => $result,
							'user' => $user));
	}

//-------------------------------------------------------------------------------------
	public function derniers_ajouts() {
		$user = cookie_connect();

		$this->load->model('serie_model', 'serieManager');
		$last_added = $this->serieManager->get_last_shows_added();

		$this->layout->view('series/derniers_ajouts', array(
										'title' => 'SeriesDOM - Derniers ajouts',
										'last_added' => $last_added,
										'user' => $user
			));
	}
//---------------------------------------------------------------------------------------------
	public function banner($id = '') {
		$user = cookie_connect();

		if(!empty($user)) {
			if(isset($id) && !empty($id) && $id > 0) {
				$id_series = htmlspecialchars($id);

				$this->load->model('serie_model', 'serieManager');
				$show = $this->serieManager->get_show($id_series);

				//si l'id correspond bien à une série -----------------
				if($show->id() != null) {
					$banners = $this->serieManager->get_all_users_banners($id_series);

					$this->layout->view('series/banners', array(
											'title' => 'SeriesDOM - '.$show->name().' banner',
											'banners'=> $banners,
											'user' => $user
											));

				} else { //sinon, page d'erreur
					show_404();
				}

			} else {
				show_404();
			}
		}
	}
}