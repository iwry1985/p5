<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function cookie_connect() {
	$ci =& get_instance();
	$ci->load->model('user_model', 'userManager');

	if(isset($_COOKIE['sd_username'], $_COOKIE['sd_password']) && !empty($_COOKIE['sd_username']) && !empty($_COOKIE['sd_password'])) {

		$cookie_username = htmlspecialchars($_COOKIE['sd_username']);
		$cookie_password = htmlspecialchars($_COOKIE['sd_password']);

		
		$user = $ci->userManager->connect_user($cookie_username, $cookie_password);

		return $user;
	} elseif(isset($_SESSION['auth'])) {
		$user = unserialize($_SESSION['auth']);

		//pour être sûr d'avoir tout updaté (changement banner, infos, etc)
		$user = $ci->userManager->get_by_id($user->id());

		return $user;
	}
}