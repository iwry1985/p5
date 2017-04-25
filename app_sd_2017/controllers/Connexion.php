<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connexion extends CI_Controller {

	public function index() {

		$user = cookie_connect();
		//si session, home.php
		if(!empty($user)) {

			redirect();

		} else { //sinon, on affiche la page connexion
			$this->layout->view('no_user/connexion', array(
				'title' => 'Connexion'));

			//Si le formulaire de connexion est envoyé
			if(isset($_POST['cx_submit']) && isset($_POST['cx_username']) && isset($_POST['cx_password'])) {
				
				$username = trim(htmlspecialchars($this->input->post('cx_username')));
				$password = trim(htmlspecialchars($this->input->post('cx_password')))
				;

				//on va chercher le mdp pour hashage
				$this->load->model('user_model', 'userManager');
				$hash = $this->userManager->get_password($username);


				//si hash n'est pas vide, c'est que $user a rentré un mot de password_hash
				if(!empty($hash) && $hash['password'] != '') {
					$hash = $hash['password'];

					if(password_verify($password, $hash)) {
						//on va chercher les infos de $user
						$user = $this->userManager->connect_user($username, $hash);

						if($user) {
							if(isset($_POST['remember_me'])) {
								setcookie('sd_username', $username, time()+365*24*3600, null, null, false, true);
								setcookie('sd_password', $hash, time()+365*24*3600, null, null, false, true);
							}

							$_SESSION['auth'] = serialize($user);
							redirect();
						} else {
							$_SESSION['flash']['danger'] = 'Tu n\'as pas encore validé ton compte';
							redirect('connexion');
							
						}
						

					} else {
						$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect.';
						redirect('connexion');
					}
					
				//si pas de mot de passe password_hash, on vérifie dans la colonne old_password
				} else {
					$password = sha1($password);

					$old = $this->userManager->verify_old($username, $password);
	
					if($old > 0) {
						redirect('connexion/reinitialisation_password');

					//si aucune correspondance, identifiant ou mot de passe incorrect
					} else {
						$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect.';
						redirect('connexion');
					}
				}
				
			}//end if(isset($_post['cx_submit'])) 
		} //end else(!isset($_session))	
		
	}//end index()
//--------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------------------

	public function reinitialisation_password() {
		$user = cookie_connect();

		if($user != NULL) {
			$this->layout->view('user/home');

		} else {
			$this->layout->view('no_user/change_password', array(
								'title' => 'SeriesDOM - Réinitialisation mot de passe',
								));

			//si le formulaire est envoyé
			if(isset($_POST['init_submit'])) {

				//si tous les champs sont remplis
				if(isset($_POST['init_username']) && isset($_POST['init_old_password']) && isset($_POST['init_password']) && isset($_POST['init_password_confirm'])) {

						$username = trim(htmlspecialchars($this->input->post('init_username')));
						$old_password = trim(htmlspecialchars($this->input->post('init_old_password')));
						$password = trim(htmlspecialchars($this->input->post('init_password')));
						$password_confirm = trim(htmlspecialchars($this->input->post('init_password_confirm')));

						//on vérifie d'abord que l'ancien mot de passe et username correspondent
						$old_hash = sha1($old_password);

						$this->load->model('user_model', 'userManager');
						$user = $this->userManager->verify_old($username, $old_hash);

						//si ça correspond, on vérifie que le nouveau mot de passe et la confirmation de mdp coincident
						if($user) {
							if($password == $password_confirm) {
								if(strlen($password) >= 5 && preg_match('/^[a-z0-9A-Z_]+$/', $password) && strlen($password) <= 20) {
									//si tout est ok, on update le password dans la bdd et on supprimer l'ancien mot de passe
									$hash = password_hash($password, PASSWORD_DEFAULT);
									$this->userManager->change_password($user['id'], $hash);
									$this->userManager->delete_old_password($username);

									//on redirige user vers la page de connexion
									$_SESSION['flash']['success'] = 'Le mot de passe a bien été modifié. Tu peux à présent te connecter.';
			        				redirect('connexion');


								} else {
									$_SESSION['flash']['danger'] = 'Votre mot de passe n\'est pas valide.';
									redirect(site_url('connexion/reinitialisation_password'));
								}

							} else { //password != $password_confirm
								$_SESSION['flash']['danger'] = 'La confirmation du mot de passe ne correspond pas.';
								redirect(site_url('connexion/reinitialisation_password'));
							}
						} else {
							$_SESSION['flash']['danger'] = 'Pseudo ou ancien mot de passe incorrect.';
							redirect(site_url('connexion/reinitialisation_password'));
						}
				} else {
					$_SESSION['flash']['danger'] = 'Tous les champs ne sont pas remplis.';
					redirect(site_url('connexion/reinitialisation_password'));
				}
			} 

		}
	}

//------------------------------------------------------------------------------------------------------------------------
}
