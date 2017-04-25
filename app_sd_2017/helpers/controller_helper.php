<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//vérifie que ce n'est pas le dernier épisode de la saison
//controller Series, Frontend_ajax_ctler, Profil
function get_season_user_is_watching($id_user, $id_series, $show_seasons, $statut) {
	$ci =& get_instance();
	$ci->load->model('watch_model', 'watchManager');
	$season_userIsWatching = $ci->watchManager->get_season_userIsWatching($id_user, $id_series);

	if(isset($season_userIsWatching['season']) && !empty($season_userIsWatching['season']) && $statut != '4') {
		//on vérifie que $user n'est pas au dernier épisode de la saison
		$number = intval($season_userIsWatching['number']);
		$season = intval($season_userIsWatching['season']);

		//on vérifie que ce n'est pas le dernier épisode en allant chercher le prochain épisode de la saison
		$ci->load->model('episode_model', 'episodesManager');
		$not_season_finale = $ci->episodesManager->verify_season_finale($id_series, $season, $number);
						
		//si le résultat est false, c'est le season finale, donc $season = la saison suivante (si elle a commencé)
		if($not_season_finale == NULL) {
			if($season < $show_seasons) {
				$season = $season + 1;
			} else {
				$season = $show_seasons;
			}
		}		
	} elseif($statut == '4') {
		//si terminée, on affiche la dernière saison
		$season = $show->seasons();
	} else {
		$season = 1;
	}

	return $season;
}

//--------------------------------------------------------------------------
//HELPER AJOUTER/SUPPRIMER UN OU DES EPISODE(S)
function add_delete_all_ep_from_season($id_user, $id_series, $season, $model) {
	$ci =& get_instance();
	$ci->load->model('watch_model', 'watchManager');

	//on va chercher les épisodes vus ou non vus de la saison (selon le $model passé en paramètre)
	$episodes = $ci->watchManager->$model($id_user, $id_series, $season);

	foreach($episodes as $ep) {
		$id_ep = $ep['id_ep'];
		$exist = $ci->watchManager->episode_seen($id_user, $id_ep);

		if($exist > 0) {
			helper_function_delete_episode($id_user, $id_series, $id_ep);
		} else {
			helper_function_add_episode($id_user, $id_series, $id_ep);
		}
	}
}

function helper_function_add_episode($id_user, $id_series, $id_ep) {
	$ci =& get_instance();
	$ci->load->model('seriesdom_model', 'seriesdomManager');

	$ci->seriesdomManager->add_ep($id_user, $id_series, $id_ep);
}

function helper_function_delete_episode($id_user, $id_series, $id_ep) {
	$ci =& get_instance();
	$ci->load->model('seriesdom_model', 'seriesdomManager');

	$ci->seriesdomManager->delete_ep($id_user, $id_series, $id_ep);
}

//-------------------------------------------------------------------------
//upload d'avatar user (pour alléger le controller)
function user_avatar_upload_img($user, $files, $folder) {
	if(!empty($files['avatar'])) {
		if($files['avatar']['error'] == 0) {
			$ext_up = strtolower(substr(strrchr($files['avatar']['name'], '.'), 1));
			$allow_ext = array('jpg', 'png', 'gif');

			$allow_type = array('image/jpeg', 'image/png', 'image/gif');
			$type_up = $files['avatar']['type'];

			//test_extension
			if(in_array($ext_up, $allow_ext)) {

				//test type
				if(in_array($type_up, $allow_type)) {

					//test taille
					$maxsize = 307200; //max 300ko

					if($files['avatar']['size'] != NULL && $files['avatar']['size'] < $maxsize) {

						//si tout est ok on upload l'image
						$name = $user->id().'.'.$ext_up;
						$server = url_server();
						$chemin = $server.'/img/users/avatar/'.$folder.'/'.$name;

						//si il y a déjà un avatar, on le supprime
						if(file_exists($chemin)) {
							unlink($chemin);
							delete_cache();
						}

						move_uploaded_file($files['avatar']['tmp_name'], $chemin);
								

						//on redimensionne
						list($width, $height) = getimagesize($chemin);
						$new_width = 250;
						$new_height = 250;

						$new_img = imagecreatetruecolor($new_width, $new_height);
									$src = imagecreatefromjpeg($chemin);

						imagecopyresampled($new_img, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
						imagejpeg($new_img, $chemin);


						//on update la base de données
						$ci =& get_instance();
						$ci->load->model('user_model', 'userManager');
						
						$ci->userManager->update_one_thing('users', 'avatar', $name, $user->id());
						$user->setAvatar($name);

						$user->setFlash('Ton avatar a bien été uploadé !', 'success');


						}	else { 
							$user->setFlash('Le fichier est trop volumineux (max. 300 ko)', 'danger');
						}
					} else {
						$user->setFlash('Ce type de fichier n\'est pas autorisé', 'danger');
								
					}
				} else {
					$user->setFlash('L\'extension n\'est pas valide', 'danger');
				}
			} else {
				$user->setFlash('Aucune image uploadée', 'danger');
			}
		} else {
			$user->setFlash('Un problème est survenu', 'danger');
		}
}
//--------------------------------------------------------------------------------
function helper_random($id_users, $number, $model) {
	if($number > 0 && $number <= 6) {
		$ci =& get_instance();
		$ci->load->model('watch_model', 'watchManager');

		$random = $ci->watchManager->$model($id_users, $number);
		return $random;
	} else {
		redirect('profil');
	}
}

//------------------------------------------------------------------------
function count_nb_ep_total($count_unseen) {
	$count_show = count($count_unseen);

	//on additionne le nombre d'épisodes total
	$i = 0;
	$count_ep = 0;
			
	for($i; $i <= $count_show - 1; $i++) {
		$count_ep += $count_unseen[$i]['count_episodes'];
	}

	return $count_ep;
}

function count_total_show($to_count) {
	$count_total_ep = 0;

	foreach($to_count as $show) {
		$count_total_ep += $show['count_ep'];
	}

	return $count_total_ep;
}

//----------------------------------------------------------------------
function get_activities_date($dates_add, $dates_up) {
	$dates_activites = [];

	//on fait un tableau et on y stocke toutes les dates d'activités (ajout et update)
	foreach($dates_add as $date) {
		if(!in_array($date, $dates_activites)) {
			$dates_activites[$date['year_add']] = $date['year_add'];
		}		
	}

	foreach($dates_up as $date) {
		if(!in_array($date, $dates_activites)) {
			$dates_activites[$date['year_update']] = $date['year_update'];
		}		
	}

	return $dates_activites;
}

function get_month_and_year_for_bilan($month, $year, $dates_activites) {
	$cu_month = date('n');
	$cu_year = date('Y');

	if(!empty($month) && !empty($year)) {
		if($year > $cu_year || !in_array($year, $dates_activites) && is_string($year)) {
				$year = $cu_year;
		}

		if($month > 12 || $month <= 0) {
			$month = 1;
		}
	} elseif(empty($year) && !empty($month)) {
		$year = $cu_year;
		$month = (int)htmlspecialchars($month);

		if($month > 12 || $month <= 0) {
			$month = $cu_month;
		}

	} elseif(!empty($year) && empty($month)) {
		$month = $cu_month;
		$year = htmlspecialchars($year);

		if($year > $cu_year || !in_array($year, $dates_activites) && is_string($year)) {
				$year = $cu_year;
		}
	} else {
		$month = $cu_month;
		$year = $cu_year;
	}

	$result = array('month' => $month, 'year' => $year);
	return $result;
}