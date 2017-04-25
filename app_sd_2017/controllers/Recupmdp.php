<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recupmdp extends CI_Controller {

	public function index() {
		$user = cookie_connect();

		if(empty($user)) {
			$this->layout->view('no_user/recup_mdp', array(
				'title' => 'SeriesDOM - Mot de passe perdu'));

			if(isset($_POST['recup_submit'])) {
				if(isset($_POST['recup_mail']) && !empty($_POST['recup_mail'])) {
					$email = htmlspecialchars(trim($this->input->post('recup_mail')));

					//vérifie que l'adresse email introduite correspond bien à un utilisateur
					$this->load->model('user_model', 'userManager');
					$user = $this->userManager->get_user_by_email($email);

					//si l'adresse mail correspond à un utilisateur
					if($user) {
						$token = create_confirmation_token();
						$this->userManager->set_reset_token($token, $user->id());

						//on envoie un mail avec l'adresse de récup + token
						$header = header_inscription_mail();
						$message = mail_recup_mdp($user, $token);
						mail($email, 'Réinitialisation de ton mot de passe sur SeriesDOM.com', $message, $header);

						//on prévient $user de l'envoie du mail
						$_SESSION['flash']['success'] = 'Les instructions de réinitialisation du mot de passe ont été envoyées à l\'adresse '.$email;
						redirect('recupmdp');

					} else {
						$_SESSION['flash']['danger'] = 'Cette adresse n\'est pas valide';
					}
				} else {
					$_SESSION['flash']['danger'] = 'Indique ton adresse email';
				}
			}

		} else {
			redirect();
		}
	}

//--------------------------------------------------------------------------------
	public function reinitialisation_mdp($id, $token) {
		$user = cookie_connect();

		if(empty($user)) {
			if(isset($id) && !empty($id) && isset($token) && !empty($token) && !isset($_SESSION['auth'])) {
				$id = htmlspecialchars($id);
				$token = htmlspecialchars($token);

				$this->load->model('user_model', 'userManager');
				$user = $this->userManager->get_token_for_reset_password($id, $token);

				if($user) { //si le token correspond, on affiche le form pour changer password
					$this->layout->view('no_user/reinitialisation_mdp', array(
					'title' => 'SeriesDOM - Réinitialisation du mot de passe'));


					//FORM ENVOYE
					if(isset($_POST['reset_submit'])) {
						if(isset($_POST['reset_password']) && !empty($_POST['reset_password']) && isset($_POST['reset_password_confirm']) && !empty($_POST['reset_password_confirm'])) {
							$password = htmlspecialchars(trim($this->input->post('reset_password')));
							$password_confirm = htmlspecialchars(trim($this->input->post('reset_password_confirm')));

							if($password == $password_confirm && strlen($password) >= 5 && preg_match('/^[a-z0-9A-Z_]+$/', $password)) {

								$hash = password_hash($password, PASSWORD_DEFAULT);
								$this->userManager->change_password($user->id(), $hash);
								$this->userManager->delete_reset_token($user->id());

								$_SESSION['flash']['success'] = 'Le mot de passe a bien été changé';
								redirect('connexion');
								exit();

							} else {
								$_SESSION['flash']['danger'] = 'Le mot de passe n\'est pas valide';
							}

						} else {
							$_SESSION['flash']['danger'] = 'Tous les champs ne sont pas remplis';
						}
					}

				} else {
					$_SESSION['flash']['danger'] = 'Ce token n\'est pas valide ou a expiré';
					redirect('recupmdp');
					exit();
				}

			} else {
				redirect();
			}

		} else {
			redirect();
		}
	}
}