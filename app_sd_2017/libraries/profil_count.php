<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Profil_count {

	private $CI;


	public function __construct() {
		$this->CI = get_instance();

		$this->CI->load->model('watch_model', 'watch_Manager');
	}


	public function setCounts($user) {
		//séries à commencer
		$shows_toBegin = $this->CI->watch_Manager->count_shows_user_is_watching($user->id(), 1);
		
		//séries à rattraper
		$shows_toCatch = $this->CI->watch_Manager->count_shows_user_is_watching($user->id(), 2);
		
		//séries en cours
		$shows_running = $this->CI->watchManager->count_shows_user_is_watching($user->id(), 3);
		
		//séries terminées
		$shows_ended = $this->CI->watchManager->count_shows_user_is_watching($user->id(), 4);
		
		//séries abandonnées
		$shows_trashed =$this->CI->watchManager->count_shows_user_is_watching($user->id(), 5);

		//on compte le nombre total de séries regardées ($count_shows - les séries à commencer et 'not interested')
		$count_seen_shows = $shows_toCatch + $shows_running + $shows_ended + $shows_trashed;

		$user->setShows_toBegin($shows_toBegin);
		$user->setShows_toCatch($shows_toCatch);
		$user->setShows_running($shows_running);
		$user->setShows_ended($shows_ended);
		$user->setShows_trashed($shows_trashed);
		$user->setCount_seen_shows($count_seen_shows);
	}

}