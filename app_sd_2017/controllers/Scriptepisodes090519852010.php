<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scriptepisodes090519852010 extends CI_Controller {

	//script automatique pour update de bdd
	public function load_episodes_from_tvmaze() {
		
		$this->load->model('serie_model', 'seriesManager');
		$running = $this->seriesManager->get_all_running_shows();

		foreach($running as $show) {
			$tv_maze = $show->tv_maze();

			//on va chercher les épisodes de la série sur tv_maze
			$episodes = api_tv_maze($tv_maze, 'episodes');
	
			foreach($episodes as $ep) {
				$episode = new Episode ([
					'id_series' => $show->id(),
					'season' => htmlspecialchars($ep['season']),
					'number' => htmlspecialchars($ep['number']),
					'name' => htmlspecialchars($ep['name']),
					'airdate' => htmlspecialchars($ep['airdate'])
				]);


				//on va chercher épisode par épisode ds la bdd
				$this->load->model('episode_model', 'episodesManager');
				$bdd_episode = $this->episodesManager->get_this_ep($episode->id_series(), $episode->season(), $episode->number());

				//si l'épisode n'est pas répertorié, on l'ajoute
				if(empty($bdd_episode)) {
					$this->episodesManager->add_episode_to_show($episode);
				} else { 

				//sinon on compare pour voir s'il y a besoin de le modifier 
					if($bdd_episode->season() == $episode->season() && $bdd_episode->number() == $episode->number() && ($bdd_episode->name() != $episode->name() || $bdd_episode->airdate() != $episode->airdate())) {
					
						$this->episodesManager->update_show_episode($episode, $bdd_episode->id_ep());
					}
				}
			}
		}

		redirect();
	}
//--------------------------------------------------------------------------------------------------------------------------
	//ajouter les épisodes d'une seule série
	public function add_all_episodes_from_show($id_series) {
		$user = cookie_connect();

		if(!empty($user) && $user['admin'] == 'admin' && isset($id_series) && $id_series > 0) {
			$id_series = htmlspecialchars($id_series);

			//on va chercher l'id tv_maze de la série correspondante
			$this->load->model('serie_model', 'serieManager');
			$tv_maze_id = $this->serieManager->get_tv_maze_id($id_series);

			if($tv_maze_id > 0) {
				//on récupère tous les épisodes correspondants à la série
				$episodes = api_tv_maze($tv_maze_id, 'episodes');

				foreach($episodes as $ep) {
					$episode = new Episode ([
						'id_series' => $id_series,
						'season' => htmlspecialchars($ep['season']),
						'number' => htmlspecialchars($ep['number']),
						'name' => htmlspecialchars($ep['name']),
						'airdate' => htmlspecialchars($ep['airdate'])
					]);

					////on va chercher épisode par épisode ds la bdd
					$this->load->model('episode_model', 'episodesManager');
					$bdd_episode = $this->episodesManager->get_this_ep($episode->id_series(), $episode->season(), $episode->number());

					//si l'épisode n'est pas répertorié, on l'ajoute
					if(empty($bdd_episode)) {
						$this->episodesManager->add_episode_to_show($episode);

					} else { 

						//sinon on compare pour voir s'il y a besoin de le modifier 
						if($bdd_episode->season() == $episode->season() && $bdd_episode->number() == $episode->number() && ($bdd_episode->name() != $episode->name() || $bdd_episode->airdate() != $episode->airdate())) {
						
							$this->episodesManager->update_show_episode($episode, $bdd_episode->id_ep());
						}
					}
				}

				$user->setFlash('Mise à jour des épisodes réussie. <a href="'.base_url('series/show/'.$id_series.'').'" class="lien_success">Voir la fiche</a>', 'success');

				redirect('/admin/show/'.$id_series.'');
				
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}


//----------------------------------------------------------------------------------------

}