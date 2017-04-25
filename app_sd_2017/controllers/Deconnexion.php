<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deconnexion extends CI_Controller {

	public function index() {
		$user = cookie_connect();

		if($user != NULL) {
			$this->load->helper('cookie');
			delete_cookie('sd_session');

			if(isset($_COOKIE['sd_password']) && isset($_COOKIE['sd_username'])) {
				setcookie('sd_username', '', time()-3600);
				setcookie('sd_password', '', time()-3600);
			}

			unset($_SESSION['auth']);

			$_SESSION['flash']['success'] = "Tu es maintenant déconnecté.";
			redirect('connexion');
		}
	}

}