<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {


	public function index() {

		$user = cookie_connect();
			
		if(!empty($user)) {
			//on va vérifier si $user a des amis
			$this->load->model('user_model', 'userManager');
			$friends = $this->userManager->get_user_friends($user->id());

			//on vérifie si user a coché des épisodes (pour afficher ou pas l'onglet (moi uniquement'))
			$this->load->model('watch_model', 'watchManager');
			$count_ep_users = $this->watchManager->get_all_episodes_seen_by_user($user->id());

			if(!empty($friends)) {
				//si $user a des amis, on va chercher la liste des épisodes que lui et ses amis ont vus
				$choice = 'friends';
			} else {
			//sinon on affiche juste la liste de ses épisodes vus
				$choice = 'me';
			}

			//si pas d'amis et pas d'épisodes vus, on affiche le flux de tous
			if(empty($count_ep_users)) {
				$choice = 'all';
			}
			
			//on passe tout à la classe qui va chercher la vue adéquate
			$this->set_this_home_view($user, $choice);
		//---------------------------------------------------------------------------------
		//SI PAS $USER
		} else {
			$this->layout->view('no_user/accueil', array(
									'title' => 'Accueil'));
		}	
	}

//--------------------------------------------------------------------------------------------
	//va chercher la vue adquéate à la sélection (utilisée pour index() et feed())
	public function set_this_home_view($user, $choice) {
		$this->load->model('watch_model', 'watchManager');

		if($choice == 'friends') {
			//si $user a des amis, on va chercher la liste des épisodes que lui et ses amis ont vus
			$visionnages = $this->watchManager->user_and_friends_watching($user->id(), 0);

				//deuxième passage pour être sûre qu'il y ait assez de séries à afficher
				if(count($visionnages) < 30) {
					$second = $this->watchManager->user_and_friends_watching($user->id(), 500);
					$visionnages = array_merge($visionnages, $second);
				}
				$infos = 'friends_and_user';

		} elseif($choice == 'me') {
			//sinon on affiche juste la liste de ses épisodes vus
			$visionnages = $this->watchManager->user_watching($user->id(), 0);

				if(count($visionnages) < 30) {
					$second = $this->watchManager->user_watching($user->id(), 500);
					$visionnages = array_merge($visionnages, $second);
				}
				$infos = 'just_user';

		} else {
			$visionnages = $this->watchManager->everybody_watching(0);

				if(count($visionnages) < 30) {
					$second = $this->watchManager->everybody_watching(500);
					$visionnages = array_merge($visionnages, $second);
				}
				$infos = 'all';
		}

		//on passe la variable à l'objet $user
		$user->setVisionnages($visionnages);


		//on fait passer tous les counts séries à l'objet $user en passant par la class Profil_count qui s'occuper de tout compter
		$this->load->library('Profil_count');
		$this->profil_count->setCounts($user);

		//--------------------------------------------------------
		//On va chercher les épisodes des séries 'en cours' de $user diffusés la veille
		$aired_yesterday = $this->watchManager->get_yesterday_aired_episodes($user->id());

		//--------------------------------------------------------
		//on va chercher tous les épisodes regardés pendant la semaine (du lundi au dimanche)
		$seen_this_week = $this->watchManager->get_weekly_seen_episodes($user->id());
		$weekly_count = 0;
		//on fait un compte de tous les épisodes vus
		foreach($seen_this_week as $weekly) {
			$weekly_count += $weekly['count_ep'];
		}

		//-----------------------------------------------------------------------
		//on vérifie si user a des amis (pour afficher l'onglet 'mes amis')
		$this->load->model('user_model', 'userManager');
		$friends = $this->userManager->get_user_friends($user->id());
 			
 		//on vérifie si user a coché des épisodes (pour afficher ou pas l'onglet (moi uniquement'))
		$this->load->model('watch_model', 'watchManager');
		$count_ep_users = $this->watchManager->get_all_episodes_seen_by_user($user->id());

		//on crée un objet $profil identique à $user pour éviter les erreurs de la function count_shows_box utilisée sur la page Home et toutes les pages de profil
		$profil = $user;


		//on passe les infos à la vue
		$this->layout->view('user/home', array(
								'title' => 'SeriesDOM - Home',
								'user' => $user,
								'friends' => $friends,
								'aired_yesterday' => $aired_yesterday,
								'seen_this_week' => $seen_this_week,
								'weekly_count' => $weekly_count,
								'info' => $infos,
								'count_ep_users' => $count_ep_users,
								'profil' => $profil
							));
	}
//----------------------------------------------------------------------------------------------------------------
	public function feed($choice) {

		$user = cookie_connect();
		$choice = htmlspecialchars($choice);
			
		if(!empty($user) && !empty($choice)) {
			//on passe tout à la classe qui va chercher la vue adéquate
			$this->set_this_home_view($user, $choice);
		//----------------------------------------------------------
		//SI PAS $USER
		} else {
			redirect();
		}	
	}

}
