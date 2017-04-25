<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend_ajax_ctler extends CI_Controller {

	public function index() {

	}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
	//ajoute une série à la liste de user
	public function user_add_show() {

		$id_series = htmlspecialchars($this->input->post('id_series'));

		if(!empty($id_series) && $id_series > 0) {
			$user = cookie_connect();

			if($user != NULL) {
				$id_user = $user->id();

				//on vérifie que la série ne se trouve déjà pas dans la liste
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);

				if(empty($watch)) {
					$msg = $this->load->model('seriesdom_model', 'seriesdomManager');
					$msg = $this->seriesdomManager->add_show($id_user, $id_series);
					echo 'added';
				}
			} else {
				redirect();
			}
		} else {
			show_404();
		}	
	}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
	//change le statut de la série
	public function user_select_status() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$statut = htmlspecialchars($this->input->post('statut'));

		if(!empty($id_series) && $id_series > 0 && !empty($statut) && $statut > 0) {
			$user = cookie_connect();

			if($user != NULL) {
				$id_user = $user->id();

				//on vérifie que la série est bien dans la liste de user
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);

				if(!empty($watch)) {
					$this->load->model('seriesdom_model', 'seriesdomManager');

					if($statut == 1) {
						//si le statut == 'à commencer', on supprimer les épisodes vus et les personnages préférés
						$this->seriesdomManager->generic_delete('watchlist', $id_user, $id_series);
						$this->seriesdomManager->generic_delete('fav', $id_user, $id_series);
					}
					
					$this->seriesdomManager->change_status_show($id_user, $id_series, $statut);

					echo 'status_changed';
				}
			}
		} else {
			show_404();
		}
	}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	//change la note de user
	public function user_change_note() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$note = htmlspecialchars($this->input->post('note'));

		if(!empty($id_series) && $id_series > 0 && !empty($note) && $note > 0) {
			$user = cookie_connect();

			if($user != NULL) {
				$id_user = $user->id();

				//on vérifie que la série est bien dans la liste de user
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);

				if(!empty($watch)) {
					$this->load->model('seriesdom_model', 'seriesdomManager');
					$this->seriesdomManager->change_note($id_user, $id_series, $note);

					echo 'note_changed';
				}
			}

		} else {
			show_404();
		}
	}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	//DELETE SHOW (USER)
	public function user_delete_show() {
		$id_series = htmlspecialchars($this->input->post('id_series'));

		if(!empty($id_series) && $id_series > 0) {
			$user = cookie_connect();

			if($user != NULL) {
				$id_user = $user->id();

				//on vérifie que la série est bien dans la liste de user
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);

				if(!empty($watch)) {
					$this->load->model('seriesdom_model', 'seriesdomManager');
					$this->seriesdomManager->generic_delete('seriesdom', $id_user, $id_series);
					$this->seriesdomManager->generic_delete('watchlist', $id_user, $id_series);
					$this->seriesdomManager->generic_delete('fav', $id_user, $id_series);
						
					echo 'deleted';
				}
			}
		} else {
			show_404();
		}	
	}
//-------------------------------------------------------------------------------------------------------------------------------------------------------
	//COCHER/DECOCHER UN EPISODE
	public function user_episode() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$id_ep = htmlspecialchars($this->input->post('id_ep'));

		if(!empty($id_series) && $id_series > 0 && !empty($id_ep) && $id_ep > 0) {
			$user = cookie_connect();

			if($user != NULL) {
				$id_user = $user->id();

				//on vérifie que la série est bien dans la liste de user
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);

				if(!empty($watch)) {
					$episode_seen = $this->watchManager->episode_seen($id_user, $id_ep);

					if($episode_seen > 0) {

						helper_function_delete_episode($id_user, $id_series, $id_ep);

						echo 'ep_deleted';
					} else {
						helper_function_add_episode($id_user, $id_series, $id_ep);

						echo 'ep_added';
					}
						
				}
			}
		} else {
			show_404();
		}	
	}
//--------------------------------------------------------------------------------------------
	//COCHE/DECOCHE TOUS LES EP DE LA SAISON
	public function coche_all_season_ep() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$season = htmlspecialchars($this->input->post('season'));
		$type = htmlspecialchars($this->input->post('type'));

		if(!empty($id_series) && $id_series > 0 && !empty($season) && $season > 0 && !empty($type)) {
			$user = cookie_connect();

			if(!empty($user)) {
				//si ajout d'épisodes
				if($type == 'add') {
					add_delete_all_ep_from_season($user->id(), $id_series, $season, 'count_unseen_episodes_from_season');
					echo 'eps_added';

				//si suppression d'épisodes
				} elseif($type = 'delete') {
					add_delete_all_ep_from_season($user->id(), $id_series, $season, 'get_seen_episodes_from_season');
					echo 'eps_deleted';

				} else {
					redirect('series/show/'.$id_series);
				}
			} else {
				redirect('connexion');
			}
		} else {
			show_404();
		}
	}

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//changer la saison sans recharger la page (fiche série)
	public function change_season() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$season = htmlspecialchars($this->input->post('season'));

		if(!empty($id_series) && $id_series > 0 && !empty($season) && $season > 0) {
			$user = cookie_connect();

			//page série -> accessible par tout le monde
			if(!empty($user)) {
				$id_user = $user->id();
			}
			
			//va chercher les infos de la série
			$this->load->model('serie_model', 'serieManager');
			$show = $this->serieManager->get_show($id_series);

			//va chercher les épisodes de la saison sélectionnée
			$this->load->model('episode_model', 'episodesManager');
			$episodes = $this->episodesManager->get_episodes_bySeason($id_series, $season);

			if(!empty($user)) {
				//va chercher les infos de $user ($watch ($user regarde la série) et $watchlist_user($user a déjà vu des épisodes))
				$this->load->model('watch_model', 'watchManager');
				$watch = $this->watchManager->watch_show($id_user, $id_series);
				$watchlist_user = $this->watchManager->get_ep_seen($id_user, $show->id());

				$unseen_ep_from_season = $this->watchManager->count_unseen_episodes_from_season($id_user, $show->id(), $season);
			} else {
				$watch = 'no_user';
				$watchlist_user = '';
				$unseen_ep_from_season = 'no_user';
			}

				$this->load->view('series/_episodes', array('show' => $show, 
								'episodes' => $episodes,
								'watch' => $watch,
								'watchlist_user' => $watchlist_user,
								'user' => $user,
								'unseen_ep_from_season' => $unseen_ep_from_season
								));
		} else {
			show_404();
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------
	//Mode random_watchlist
	public function executeRandom_watchlist($number = '') {
		if(!empty($number)) {
			$number = htmlspecialchars($number);

			$user = cookie_connect();

			if($user != NULL) {
				$id_users = $user->id();

				$random = helper_random($id_users, $number, 'random_watchlist');
				echo $random;
			}
		} else {
			redirect('connexion');
		}	 
	}
//----------------------------------------------------------------------------------------------
	//change la série à afficher sur la page watchlist
	public function change_show_watchlist() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$show_seasons = htmlspecialchars($this->input->post('seasons'));


		if(!empty($id_series) && $id_series > 0 && !empty($show_seasons) && $show_seasons > 0) {

			$user = cookie_connect();

			if(!empty($user)) {
				$season = get_season_user_is_watching($user->id(), $id_series, $show_seasons, 3);
				$unseen_ep_from_season = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season);

				//on va chercher les épisodes non vus de la saison
				$watch_show = $this->watchManager->get_unseen_episodes_from_show($user->id(), $id_series, $season, 0, 10);

				//On va chercher les numéros de saisons que user doit encore voir
				$nbr_season_to_see = $this->watchManager->get_number_of_seasons_to_see($user->id(), $id_series);


				//on compte le nombre d'épisodes à voir dans la saison
				$count_nbr_ep = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season);
				$count_ep = count($count_nbr_ep);


				//ON VA CHERCHER LES DONNEES FORM(statuts)------------------------------------
				$this->load->model('data_model', 'dataManager');
				$statuts = $this->dataManager->get_all_status();

				//ON COMPTE LE nbr d'épisodes de la série déjà diffusé
				$this->load->model('episode_model', 'episodesManager');
				$count_total = $this->episodesManager->count_number_episodes($id_series);
				$total_ep = $count_total['count'];

				//on compte le nombre d'épisodes que $user a vu de la série
				$ep_vus = $this->watchManager->count_number_episodes_seen_by_user($id_series, $user->id());
				$ep_vus = $ep_vus['count'];


				//on recharge une partie de la page avec les nouvelles infos
				$this->load->view('user/profil/_watch_listing', array(
							'user' => $user, 
							'watch_show' => $watch_show,
							'nbr_season_to_see' => $nbr_season_to_see,
							'count_nbr_ep' => $count_nbr_ep,
							'statuts' => $statuts,
							'total_ep' => $total_ep,
							'ep_vus' => $ep_vus,
							'all_ep_from_season' => $season,
							'unseen_ep_from_season' => $unseen_ep_from_season
							));
			} else {
				redirect();
			}
		} else {
			show_404();
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function show_all_season() {
		$id_series = htmlspecialchars($this->input->post('id_series'));
		$limit = htmlspecialchars($this->input->post('limit'));
		$season = htmlspecialchars($this->input->post('season'));
		$type = htmlspecialchars($this->input->post('type'));
		$etat = htmlspecialchars($this->input->post('etat'));

		if(!empty($limit)) {
			$start = 10;
		} else {
			$start = 0;
			$limit = 10;
		}

		if($id_series > 0 && $season > 0) {
			$user = cookie_connect();

			if(!empty($user)) {
				$this->load->model('watch_model', 'watchManager');
 
				//etat -> pour afficher le reste des épisodes lorsqu'on coche la saison en une fois sur la watchlist
				if($etat == 'seen') {
					//à afficher lorsqu'on clique sur 'cocher la saison', on va chercher tout le reste des épisodes de la saison
					$this->load->model('serie_model', 'serieManager');
					$watch_show = $this->serieManager->get_the_rest_of_episodes_from_season($id_series, $season, $start, $limit);
				} else {
					//on va chercher les épisodes non vus de la saison
					$watch_show = $this->watchManager->get_unseen_episodes_from_show($user->id(), $id_series, $season, $start, $limit);
				}
				

				$unseen_ep_from_season = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season);

				//on compte le nombre d'épisodes à voir dans la saison
				$count_nbr_ep = $this->watchManager->count_unseen_episodes_from_season($user->id(), $id_series, $season);
				$count_ep = count($count_nbr_ep);

				//on recharge une partie de la page avec les nouvelles infos
				if($type == 'change_season') {
					$this->load->view('user/profil/_table', array(
							'user' => $user, 
							'watch_show' => $watch_show,
							'count_nbr_ep' => $count_nbr_ep,
							'all_ep_from_season' => $season,
							'unseen_ep_from_season' => $unseen_ep_from_season
							));

				//on recharge une partie de la page avec tous les épisodes d'affichés
				} elseif($type == 'all_ep') {
					$this->load->view('user/profil/_tbody', array(
						'user' => $user, 
						'watch_show' => $watch_show,
						'count_nbr_ep' => $count_nbr_ep,
						'all_ep_from_season' => $season,
						'unseen_ep_from_season' => $unseen_ep_from_season
						));
				}
				
			} else {
				redirect('connexion');
			}
		} else {
			show_404();
		}
	}
//-----------------------------------------------------------------------------------------------------------
	//update_avatar
	public function update_avatar_user() {
		$user = cookie_connect();
		if($user != NULL) {
			$folder = find_folder($user->id());
				
			user_avatar_upload_img($user, $_FILES, $folder);
				
			redirect('profil/update');
		} else {
			redirect();
		}
	}
//---------------------------------------------------------------------------------------------------------------------
	public function delete_avatar_user() {

		if(isset($_POST['delete_avatar'])) {
			$user = cookie_connect();

			if($user != NULL) {

				$avatar = $user->avatar();
				$folder = find_folder($user->id());

				$server = url_server();
				$chemin = $server.'/img/users/avatar/'.$folder.'/'.$avatar;

				if(file_exists($chemin)) {
					unlink($chemin);
						//on update la base de données
					$user->setAvatar('');

					$user->setFlash('L\'avatar a bien été supprimé', 'success');
				} else {
					$user->setFlash('Un problème est survenu', 'danger');
				}

				redirect('profil/update');
			} else {
				redirect();
			}
		} else {
			redirect();
		}
	}
//---------------------------------------------------------------------------------------------------
	public function update_profil_user() {
		$user = cookie_connect();

		if($user != NULL) {
			if(isset($_POST['submit_update'])) {
				$data = $this->input->post(NULL, TRUE);
				$data = $this->security->xss_clean($data);
				$user_modif = $this->processForm_user($data, $user);

				$this->load->model('user_model', 'userManager');
				$up = $this->userManager->update_user($user_modif, $user->id());

				if($up == 'user_up') {
					$user = $this->userManager->get_by_id($user->id());

					$_SESSION['auth'] = serialize($user);

					$user->setFlash('Ton profil a bien été updaté !', 'success');
				} else {
					$user->setFlash('Un problème est survenu...', 'danger');
				}

				redirect('profil/update');

			} else {
				redirect('profil/update');
			}
		
		} else {
			redirect();
		}
	}
//-----------------------------------------------------------------------------------------------------
	public function processForm_user($data, $user) {

		$user_up = new User ([
			'presentation' => trim(htmlspecialchars($this->input->post('presentation')))
			]);

		return $user_up;
	}

//----------------------------------------------------------------------------------------
	//Mode random_waitingList
	public function executeRandom_waitingList($number = '') {
		if(isset($number) && !empty($number)) {
			$number = htmlspecialchars($number);

			$user = cookie_connect();
			if($user != NULL) {
				$id_users = $user->id();

				$random = helper_random($id_users, $number, 'random_waitingList');
				echo $random;

			} else {
				redirect('connexion');
			}
		} else {
			show_404();
		}	 
	}
//------------------------------------------------------------------------------
	//Mode random_waitingList
	public function executeRandom_beginList($number = '') {
		if(isset($number) && !empty($number)) {
			$number = htmlspecialchars($number);

			$user = cookie_connect();

			if($user != NULL) {
				$id_users = $user->id();

				$random = helper_random($id_users, $number, 'random_beginList');
				echo $random;
			} else {
				redirect('connexion');
			}
		} else {
			show_404();
		}	 
	}
//---------------------------------------------------------------------------------------------------
	//changer banner user
	public function change_banner_users() {
		$user = cookie_connect();

		if(!empty($user)) {
			$banner = htmlspecialchars($this->input->post('banner'));
			$id_series = htmlspecialchars($this->input->post('id_series'));
			
			if(!empty($banner) && $banner > 0) {
				$this->load->model('user_model', 'userManager');
				$msg = $this->userManager->change_banner_users($user->id(), $banner);

				if($msg == 'banner_up') {

					echo $msg;
				}
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//--------------------------------------------------
	//supprimer des amis
	public function delete_friend() {
		$user = cookie_connect();

		if(!empty($user)) {
			$id_friend = htmlspecialchars($this->input->post('profil_id'));

			$this->load->model('user_model', 'userManager');
			$msg = $this->userManager->delete_friend($user->id(), $id_friend);

			if($msg == 'delete') {
				echo 'delete';
			}
		} else {
			redirect('connexion');
		}
	}
//----------------------------------------------------------------------------
	//ajouter des amis
	public function add_friend() {
		$user = cookie_connect();

		if(!empty($user)) {
			$id_friend = htmlspecialchars($this->input->post('profil_id'));

			$this->load->model('user_model', 'userManager');

			//on vérifie que les 2 utilisateurs ne sont déjà pas amis ou qu'une demande n'est déjà pas en cours
			$exists = $this->userManager->verify_if_users_are_friends($user->id(), $id_friend);

			if(empty($exists)) {
				$msg = $this->userManager->add_friend_request($user->id(), $id_friend);

				if($msg == 'add') {
				echo 'add';
				}
			}

		} else {
			redirect('connexion');
		}
	}
//----------------------------------------------------------------
	public function accept_friend_request() {
		$user = cookie_connect();

		if(!empty($user)) {
			$id_friend = htmlspecialchars($this->input->post('profil_id'));

			$this->load->model('user_model', 'userManager');
			$msg = $this->userManager->accept_friend_request($user->id(), $id_friend);

			if(!empty($msg)) {
				echo 'accept';
			}
		} else {
			redirect('connexion');
		}
	}
//---------------------------------------------------------------------
	public function denied_friend_request() {
		$user = cookie_connect();

		if(!empty($user)) {
			$id_not_friend = htmlspecialchars($this->input->post('profil_id'));

			$this->load->model('user_model', 'userManager');
			$msg = $this->userManager->denied_friend_request($user->id(), $id_not_friend);

			if(!empty($msg)) {
				echo 'denied';
			}
		} else {
			redirect('connexion');
		}
	}

//----------------------------------------------------------------
	public function delete_account_user() {
		$user = cookie_connect();

		if(!empty($user)) {
			$confirm = htmlspecialchars($this->input->post('msg'));

			if(!empty($confirm) && $confirm == 'goodbye') {
				$this->load->model('generic_model', 'genericManager');
				$this->genericManager->delete_('seriesdom', 'id_users', $user->id());
				$this->genericManager->delete_('fav', 'id_users', $user->id());
				$this->genericManager->delete_('friends', 'id_users_1', $user->id());
				$this->genericManager->delete_('friends', 'id_users_2', $user->id());
				$this->genericManager->delete_('watchlist', 'id_users', $user->id());
				$msg = $this->genericManager->delete_('users', 'id', $user->id());


				if(!empty($msg) && $msg == 'deleted') {
					echo 'deleted';
				}

			} else {
				show_404();
			}
		} else {
			redirect();
		}
	}
//----------------------------------------------------------------
}