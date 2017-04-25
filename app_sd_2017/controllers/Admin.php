<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index() {
		$user = cookie_connect();

		if($user != NULL) {
			if($user['admin'] == 'admin') {

				//erreur: série commencée et toujours en statut commandée
				$this->load->model('admin_model', 'adminManager');
				$new_show_started = $this->adminManager->get_shows_with_wrong_status();

				//erreur: nouvelle saison commencée
				$wrong_seasons = $this->adminManager->get_new_seasons_shows();


				//nombre de membres
				$count_membres = $this->adminManager->count_members();

				
				$this->layout->view('admin/admin_index', array(
						'title' => 'SeriesDOM - Admin',
						'user' => $user,
						'new_show_started' => $new_show_started,
						'wrong_seasons' => $wrong_seasons,
						'count_membres' => $count_membres
					));
			} else {
				redirect('profil');
			}
		} else {
			redirect();
		}
	}
//-------------------------------------------------------------------------------------------------
	//ajouter une série/modifier si $_GET['id']
	public function show($id = '') {
		$user = cookie_connect();

		if($user != NULL) {

			if($user['admin'] == 'admin') {
				$id_show = htmlspecialchars($id);

				$this->load->model('data_model', 'dataManager');
				//on va chercher tous les pays d'origine
				$org = $this->dataManager->get_all_org();
				//on va chercher tous les networks
				$networks = $this->dataManager->get_all_networks();
				//on va chercher tous les etats possibles
				$etats = $this->dataManager->get_all_etats();
				//on va chercher tous les genres
				$genres = $this->dataManager->get_all_genre();

				$this->load->model('serie_model', 'serieManager');
					//on vérifie si le num correspond bien à un id valide
					$show_exists = $this->serieManager->show_exists('id', $id_show);

				if(!empty($id_show) && $id_show > 0 && $show_exists != 0) {
					//on va chercher les infos sur la série
					$show = $this->serieManager->get_show($id_show);

					$this->layout->view('admin/show_form', array(
						'title' => 'Modifier une série',
						'show' => $show,
						'org' => $org,
						'networks' => $networks,
						'etats' => $etats,
						'genres' => $genres,
						'user' => $user
					));
				
				} else {

					$last_img = $this->serieManager->get_last_img('series');
					$num_img = $last_img + 1;

							
					$this->layout->view('admin/show_form', array(
							'title' => 'Ajouter une série',
							'org' => $org,
							'networks' => $networks,
							'etats' => $etats,
							'genres' => $genres,
							'num_img' => $num_img,
							'user' => $user
							));
				}

			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//------------------------------------------------------------------------------------
	//upload img
	public function executeUpload_files($name) {
		$user = cookie_connect();

		if($user != NULL) {
			//on récupères les données
			$id_series = htmlspecialchars($this->input->post('id_series')); 
			$num_folder = htmlspecialchars($this->input->post('num_folder'));
			$num_img = htmlspecialchars($this->input->post('num_img'));
			$server = url_server();


			//on fournit les infos par rapport à l'image uploadée
			if($user['admin'] == 'admin') {
				//BANNER
				if($name == 'IMG_banner') {
					$chemin = $server.'/img/show/banner/'.$num_folder.'/'.$num_img.'.jpg';
					$percent = 0.24;
					$miniature = $server.'/img/show/banner_min/'.$num_folder.'/'.$num_img.'.jpg';
					$folder = $server.'/img/show/banner/'.$num_folder;

				//POSTER	
				} elseif($name == 'IMG_poster') {
					$chemin = $server.'/img/show/poster/'.$num_folder.'/'.$num_img.'.jpg';
					$percent = 0.6;
					$miniature = $server.'/img/show/poster_min/'.$num_folder.'/'.$num_img.'.jpg';
					$folder = $server.'/img/show/poster/'.$num_folder;

				//PERSONNAGE
				} elseif($name == 'IMG_personnage') {
					$chemin = $server.'/img/show/characters/'.$num_folder.'/'.$id_series.'/'.$num_img.'.jpg';
					$folder = $server.'/img/show/characters/'.$num_folder.'/'.$id_series;
				//NETWORK
				} elseif($name == 'IMG_network') {
					$chemin = $server.'/'.$num_img;
				}

				if($name == 'IMG_poster' || $name == 'IMG_banner' || $name == 'IMG_personnage') {
					$msg = load_img($_FILES[$name], $chemin, $user, 'jpg',  $folder);
				} else {
					$msg = load_img($_FILES[$name], $chemin, $user, 'png');
				}

				//on fait des miniatures
				if(isset($msg) && $name == 'IMG_poster' || $name == 'IMG_banner') {
					$img = resize_copy_img($chemin, $percent, $miniature, $user, $id_series);
				}

				if($name == 'IMG_poster' || $name == 'IMG_banner') {
					redirect('/admin/show/'.$id_series.'');
				} elseif($name == 'IMG_personnage') {
					redirect(base_url('/admin/characters/'.$id_series.'/#char_'.$num_img));
					return $num_img;
				} elseif($name == 'IMG_network') {
					redirect(base_url('admin/networks#net_'.$id_series));
				}
				
			} else {
				redirect();
			}
		} else {
			redirect('connexion');
		}
	}
//-----------------------------------------------------------------------------------------
	//process form
	public function processForm_show($data) {

		$serie = new Serie([
				'id' => htmlspecialchars($this->input->post('id_series')),
				'name' => htmlspecialchars(trim($this->input->post('name'))),
				'VF' => htmlspecialchars(trim($this->input->post('vf'))),
				'tv_maze' => htmlspecialchars($this->input->post('tv_maze')),
				'img' => htmlspecialchars($this->input->post('img')),
				'synopsis' => htmlspecialchars(trim(nl2br($this->input->post('synopsis')))),
				'begin_date' => htmlspecialchars($this->input->post('begin_date')),
				'end_date' => htmlspecialchars($this->input->post('end_date')),
				'runtime' => htmlspecialchars($this->input->post('runtime')),
				'seasons' => htmlspecialchars($this->input->post('seasons')),
				'producer' => htmlspecialchars($this->input->post('producer')),
				'origine' => htmlspecialchars($this->input->post('origine')),
				'network' => htmlspecialchars($this->input->post('network')),
				'etat' => htmlspecialchars($this->input->post('etat')),
				'genre' => htmlspecialchars($this->input->post('genre')),
				'random' => htmlspecialchars($this->input->post('random')),
				'renew' => htmlspecialchars($this->input->post('renew'))
			]);
		
		return $serie;
	}
//---------------------------------------------------------------------------
	//update show
	public function executeUpdate() {
		$user = cookie_connect();

		if($user != NULL) {
			$id_series = htmlspecialchars($this->input->post('id_series'));

			if(isset($_POST['update_show']) && $user['admin'] == 'admin' && $id_series > 0) {
				//on récupère les données et on envoie à $this->processForm
				$data = $this->input->post(NULL, TRUE);
				$data = $this->security->xss_clean($data);
				$serie = $this->processForm_show($data);

				//on update la série
				$this->load->model('serie_model', 'serieManager');
				$msg = $this->serieManager->update_show($serie);

				//si tout s'est bien passé, on envoie un message flash
				if(!empty($msg) && $msg = 'update_ok') {
					$user->setFlash('La série a bien été modifiée. <a href="'.base_url('series/show/'.$id_series.'').'" class="lien_success">Voir la fiche</a>', 'success');
				} else {
					$user->setFlash('Les modifications n\'ont pas été prises en compte.', 'danger');
				}

				redirect('/admin/show/'.$id_series.'');
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//-------------------------------------------------------------------------------------
	//AJOUTER UNE SERIE
	public function executeAdd_show() {
		$user = cookie_connect();

		if($user != NULL) {
		
			if(isset($_POST['add_show']) && $user['admin'] == 'admin') {
				$data = $this->input->post(NULL, TRUE);
				$data = $this->security->xss_clean($data);
				$serie = $this->processForm_show($data);

				//on ajoute la série
				$this->load->model('serie_model', 'serieManager');
				$msg = $this->serieManager->add_show($serie);

				//on va chercher son id
				$id_series = $this->serieManager->get_last_id('series');
				$this->serieManager->update_one_thing('series', 'img', $id_series, $id_series);

				//si la série a été correctement ajoutée, on envoie un flash
				if(!empty($msg) && $msg == 'add_ok') {
					$user->setFlash('La série a bien été ajoutée. <a href="'.base_url('series/show/'.$id_series.'').'" class="lien_success">Voir la fiche</a>', 'success');
				} else {
					$user->setFlash('La série n\'a pas été ajoutée', 'danger');
				}

				redirect('/admin/show/'.$id_series.'');
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//-----------------------------------------------------------------------------------------------
	//AJOUTER UN PERSONNAGE A PARTIR DE l'API TV_MAZE
	public function characters_tvmaze($id_series = '') {
		$user = cookie_connect();

		if($user != NULL) {
			$id_series = htmlspecialchars($id_series);

			if($user['admin'] == 'admin') {
				if(!empty($id_series) && $id_series > 0) {
				
				//on va chercher le num id_tv_maze correspondant à la série
				$this->load->model('serie_model', 'serieManager');
				$tv_maze = $this->serieManager->get_tv_maze_id($id_series);

					if(!empty($tv_maze))  {
						//connexion à tvmaze via la fonction api_tv_maze
						$data = api_tv_maze($tv_maze, 'cast');
						$count_char_toAdd = count($data);

						//va chercher les personnages de la série (s'il y en a)----------------
						$this->load->model('character_model', 'charactersManager');
						$characters = $this->charactersManager->get_characters($id_series);

						$id_tv_maze = array();
						//on fait un tableau avec tous les id_tvmaze des personnages ajoutés (pour comparer si le personnage est déjà présent ou pas)
						foreach($characters as $char) {
							$id_tv_maze[] = $char->id_tv_maze();
						}

						//on récupère le dernier num img de la table characters---------
						$num_img = $this->charactersManager->get_last_img('characters');
						$num_img = $num_img + 1;

						$this->layout->view('admin/characters_tvmaze', array(
									'title' => 'Ajouter les personnages',
									'tv_maze' => $data,
									'id_tv_maze' => $id_tv_maze,
									'num_img' => $num_img,
									'id_series' => $id_series,
									'user' => $user
									));
					} else {
						redirect();
					}

				} else {
					redirect();
				}

			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//---------------------------------------------------------------------------------
	//GERER LES PERSONNAGES AJOUTER
	public function characters($id_series = '') {
		$user = cookie_connect();

		if($user != NULL) {
			$id_series = htmlspecialchars($id_series);

			if($user['admin'] == 'admin') {
				if(!empty($id_series) && $id_series > 0) {

					//on va chercher les personnages de la série (s'il y en a)----------------
					$this->load->model('character_model', 'charactersManager');
					$characters = $this->charactersManager->get_characters($id_series);

					if(!empty($characters)) {
						//on récupère le dernier num img de la table characters (au cas où il faudrait ajouter un personnage) ---------
						$num_img = $this->charactersManager->get_last_img('characters');
						$num_img = $num_img + 1;

						$this->layout->view('admin/characters', array(
									'title' => 'Modifier les personnages',
									'characters' => $characters,
									'num_img' => $num_img,
									'user' => $user,
									'id_series' => $id_series
									));

					} else {
						redirect('admin/show/'.$id_series);
					}
				} else  {
					redirect();
				}

			} else {
				redirect('profil');
			}
		} else {
			redirect();
		}
	}
//------------------------------------------------------------------------------------
	//process form pour les personnages
	public function processForm_character($data) {

		$character = new Character([
				'id' => htmlspecialchars($this->input->post('id')),
				'name' => htmlspecialchars(trim($this->input->post('name'))),
				'actor_name' => htmlspecialchars(trim($this->input->post('actor_name'))),
				'img' => htmlspecialchars($this->input->post('img')),
				'id_series' => htmlspecialchars($this->input->post('id_series')),
				'id_tv_maze' => htmlspecialchars($this->input->post('id_tv_maze'))
			]);
		
		return $character;
	}
//-----------------------------------------------------------------------------
	public function executeCharacters() {
		$user = cookie_connect();

		if($user != NULL) {
			//on récupère les données et on envoie à processForm_characters
			$id_series = htmlspecialchars($this->input->post('id_series'));
			$name = htmlspecialchars($this->input->post('name'));
			$actor_name = htmlspecialchars($this->input->post('actor_name'));
			$id_tv_maze = htmlspecialchars($this->input->post('id_tv_maze'));
			$data = $this->input->post(NULL, TRUE);
			$data = $this->security->xss_clean($data);
			$character = $this->processForm_character($data);

			if($user['admin'] == 'admin') {
				if(!empty($id_series) && $id_series > 0) {
					//on vérifie que le personnage est déjà répertoriée
					$this->load->model('character_model', 'charactersManager');
					$exists = $this->charactersManager->character_exists($id_tv_maze, $name, $actor_name);

					//si le personnage est répertorié, on l'update
					if(!empty($exists)) {
						//on update les infos sur le personnage
						$msg = $this->charactersManager->updateCharacter($character);

						echo $msg;

					//si le personnage n'est pas répertorié, on l'ajoute
					} else {
						$msg = $this->charactersManager->addCharacter($character);

						echo $msg;
					}
				} else {
					redirect();
				}
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//-----------------------------------------------------------------------------------------
	//DELETE CHARACTERS
	public function executeDeleteCharacters() {
		$user = cookie_connect();

		if($user != NULL) {
			$id = htmlspecialchars($this->input->post('id'));

			if($user['admin'] == 'admin') {
				if(!empty($id) && $id > 0) {

				//on vérifie que le personnage est bien répertorié
				$this->load->model('character_model', 'charactersManager');
				$in_bdd = $this->charactersManager->in_bdd('characters', 'id', $id);

					//si oui, on le supprime
					if($in_bdd > 0) {
						$msg = $this->charactersManager->delete_('characters', 'id', $id);

						echo $msg;
					}
				} else {
					redirect();
				}
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//------------------------------------------------------------------------
	//Modifier/Ajouter/Supprimer un network *WORK IN PROGRESS*
	public function networks() {
		$user = cookie_connect();

		if($user != NULL) {

			if($user['admin'] == 'admin') {
				$this->load->model('data_model', 'dataManager');
				$networks = $this->dataManager->get_all_networks();


				$this->layout->view('admin/networks', array(
									'title' => 'SeriesDOM - Modifier les networks',
									'networks' => $networks,
									'user' => $user,
									));
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//-----------------------------------------------------------------------------------------------------
	//Modifier le nom ou la date d'un épisode
	public function executeUpdateEpisode() {
		$user = cookie_connect();

		if($user != NULL) {
			//données
			$id_ep = htmlspecialchars($this->input->post('id_ep'));
			$name = htmlspecialchars(trim($this->input->post('new_name')));
			$id_ep = $this->security->xss_clean($id_ep);
			$name = $this->security->xss_clean($name);
			$date = htmlspecialchars($this->input->post('new_date'));
			$date = $this->security->xss_clean($date);
			$date_fr = date('d/m/Y', strtotime($date));

			if($user['admin'] == 'admin') {
				//on distribue les infos par rapport aux données reçues
				if(!empty($id_ep) && $id_ep > 0 && !empty($name) && !empty($name)) {
					$champ = 'name';
					$valeur = $name;
				} elseif(!empty($id_ep) && $id_ep > 0 && !empty($date) && $date != NULL) {
					$champ = 'airdate';
					$valeur = $date;
				} else {
					die();
				}

				//on update la table selon le champ
				$this->load->model('episode_model', 'episodesManager');
				$this->episodesManager->update_one_info($id_ep, $champ, $valeur);
				
				//si la données à updater était une date, on affiche la nouvelle date en format fr
				if($champ == 'airdate') {
					echo $date_fr;
				} else {
					echo $valeur;
				}
				
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//------------------------------------------------------------------------------------------------
	//supprimer un épisode de la bdd
	public function  executeDeleteEpisode() {
		$user = cookie_connect();

		if($user != NULL) {
			$id_ep = htmlspecialchars($this->input->post('id_ep'));
			$id_ep = $this->security->xss_clean($id_ep);

			if($user['admin'] == 'admin') {
				if(!empty($id_ep) && $id_ep > 0) {
					$this->load->model('episode_model', 'episodesManager');
					$this->episodesManager->delete_('episodes', 'id_ep', $id_ep);

					echo 'deleted';
				} else {
					redirect();
				}
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//-----------------------------------------------------------------------------------------
	//supprimer une image (paramètres lorsqu'on supprime les img suite à la suppression de la série)
	public function delete_img($data = '', $name = '') {
		$user = cookie_connect();

		if($user != NULL) {

			if($user->id() > 0 && $user->admin() == 'admin') {
				if(isset($_POST['delete_poster']) || isset($_POST['delete_banner']) || !empty($data)) {

					//si pas de paramètres, c'est que c'est une image unique à supprimer, on récupère donc son chemin
					if(empty($data) && empty($name)) {
						$chemin = trim(htmlspecialchars($this->input->post('chemin')));
						$id_series = htmlspecialchars($this->input->post('id_series'));
					} else {
						//si pas de paramètres, on indique le chemin à suivre
						$chemin = $data['folder'].'/'.$data['img'].'.jpg';
						$id_series = $data['id_series'];
					}

					//si pas de paramètres, on fournit une variable $name selon poster ou banner à supprimer
					if(isset($_POST['delete_poster'])) {
						$name = 'poster';
						$min = 'poster_min';
					} elseif(isset($_POST['delete_banner'])) {
						$name = 'banner';
					}

					//on appelle la fonction delete_img_admin
					delete_img_admin($name, $chemin, $user, $id_series);

					//on vide l'img de l'objet série
					$this->load->model('serie_model', 'serieManager');
					$show = $this->serieManager->get_show($id_series);
					$show->setImg('');

					//si pas de paramètres, on redirige user
					if(empty($data)) {
						redirect('admin/show/'.$id_series);
					}
				}

				//pour suppression d'img personnages (=>chemins différents)
				if(isset($_POST['delete_character_img'])) {
					$chemin = trim(htmlspecialchars($this->input->post('chemin')));
					$id_series = htmlspecialchars($this->input->post('id_series'));
					$id_char = htmlspecialchars($this->input->post('id_char'));


					if(file_exists($chemin)) {
						unlink($chemin);

						$this->load->model('character_model', 'characterManager');
						$char = $this->characterManager->get_character($id_char);
						$char->setImg('');
					}

					
					redirect('admin/characters/'.$id_series.'#'.$id_char);
				}
			} else {
				redirect();
			}
		} else {
			redirect();
		}
	}
//------------------------------------------------------------------------------------------
	//on vérifie que la série n'est pas terminée par rapport au statut tvmaze
	public function get_shows_that_ended() {
		$user = cookie_connect();

		if($user != NULL) {
			if($user['admin'] == 'admin') {

				//erreur: changer le statut d'une série
				//on va d'abord chercher toutes les séries en cours
				$this->load->model('serie_model', 'seriesManager');
				$running = $this->seriesManager->get_all_running_shows();
				$show_ended = [];

				foreach($running as $show) {
					//pour chaque série on vérifie le statut tv_maze
					$tv_maze = $show->tv_maze();

					$status = api_tv_maze($tv_maze);
					$status = $status['status'];

					if($status == 'Ended') {
						$show_ended[] = $show;
					}
				}


				$this->layout->view('admin/admin_index', array(
						'title' => 'SeriesDOM - Admin',
						'show_ended' => $show_ended,
					));
				
			} else {
				redirect('profil');
			}
		} else {
			redirect('connexion');
		}
	}
//--------------------------------------------------------------------------------
	//suppression série
	public function delete_show_from_site() {
		$user = cookie_connect();

		if($user != NULL) {
			if($user->admin() == 'admin') {
				$id_series = htmlspecialchars($this->input->post('id_series'));
				$confirm = htmlspecialchars($this->input->post('msg'));

				if(!empty($id_series) && $id_series > 0 && !empty($confirm) && $confirm == "delete_show") {
					$this->load->model('generic_model', 'genericManager');

					//on supprime les personnages
					$this->genericManager->delete_('characters', 'id_series', $id_series);
					//on supprime les épisodes
					$this->genericManager->delete_('episodes', 'id_series', $id_series);
					//on supprime de la table seriesdom
					$this->genericManager->delete_('seriesdom', 'id_series', $id_series);
					//on supprime de la table watchlist
					$this->genericManager->delete_('watchlist', 'id_series', $id_series);
					//on supprime toutes les bannières user
					$this->genericManager->delete_('banner', 'id_series', $id_series);

					//on va chercher le num d'img de la série
					$img = $this->genericManager->get_one_thing('series', 'img', $id_series);
					$img = $img['img'];
					$folder = find_folder($img);

					//on supprime toutes les images de la séries (personnages, poster, banners,...)
					$data = (array('id_series' => $id_series,
									'folder' => $folder,
									'img' => $img));

					$this->delete_img($data, 'banner');
					$this->delete_img($data, 'poster');
					$this->delete_all_folder($folder, $id_series, $user);

					//et enfin, on supprime la série
					$this->genericManager->delete_('series', 'id', $id_series);

					echo 'deleted';

				} else {
					redirect();
				}
			} else {
				redirect('profil');
			}

		} else {
			redirect('connexion');
		}
	}
//----------------------------------------------------------------
	public function delete_all_folder($folder, $id_series, $user) {
		$server = url_server();
		
		$characters = $server.'/img/show/characters/'.$folder.'/'.$id_series;
		$user_banner = $server.'/img/users/banner/'.$folder.'/'.$id_series;
		$user_banner_min = $server.'/img/users/banner_min/'.$folder.'/'.$id_series;

		delete_non_empty_folder($characters);
		delete_non_empty_folder($user_banner);
		delete_non_empty_folder($user_banner_min);
	}
}