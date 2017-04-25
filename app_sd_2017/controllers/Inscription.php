<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscription extends CI_Controller {

	public function index() {
		$user = cookie_connect();

		if(!empty($user)) {
			$this->layout->view('user/home');

		} else {
			$this->layout->view('no_user/inscription', array('title' => 'Inscription'));


			if(isset($_POST['sign_in'])) {
				if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

					$username = trim(htmlspecialchars($this->input->post('username')));
					$email = trim(htmlspecialchars($this->input->post('email')));
					$password = trim(htmlspecialchars($this->input->post('password')));
					$password_confirm = trim(htmlspecialchars($this->input->post('password_confirm')));

					//vérification du pseudo
					if(preg_match('/^[a-z0-9A-Z_]+$/', $username) && strlen($username) >= 3 && strlen($username) <= 20) {

						//on vérifie que le pseudo est libre
						$this->load->model('user_model', 'userManager');
						$pseudo_exists = $this->userManager->in_bdd('users', 'username', $username);

						if(empty($pseudo_exists)) {
							//vérification du mail
							if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
								//on  vérifie que l'adresse n'est pas déjà utilisée
								$email_exists = $this->userManager->in_bdd('users', 'email', $email);

								if(empty($email_exists)) {
									//vérification du mot de passe
									if($password == $password_confirm && strlen($password) >= 5 && preg_match('/^[a-z0-9A-Z_]+$/', $password)) {

										//si pas d'erreurs, on ajouter user dans bdd
										if(empty($_SESSION['flash'])) {
											$hash = password_hash($password, PASSWORD_DEFAULT);
											$token = create_confirmation_token();


											$this->userManager->add_user($username, $email, $hash, $token);
											$last_user = $this->userManager->get_last_user();

											//on envoie le mail de confirmation
											$header = header_inscription_mail();
											$message = mail_inscription($last_user, $token);

											mail($email, 'Confirmation de ton inscription sur SeriesDOM.com', $message, $header);

											$_SESSION['flash']['success'] = 'Un email de confirmation a été envoyé afin de valider ton compte !';
											redirect('connexion');
										}

									} else { //mot de passe non valide
										$_SESSION['flash']['danger'] = 'Votre mot de passe n\'est pas valide.';
										redirect('inscription');
									}

								} else { //adresse déjà utilisée
									$_SESSION['flash']['danger'] = 'Cette adresse est déjà utilisée pour un autre compte.';
									redirect('inscription');
								}

							} else { //adresse non valide
								$_SESSION['flash']['danger'] = 'Votre adresse mail n\'est pas valide.';
								redirect('inscription');
							}


						} else { //pseudo utilisé
							$_SESSION['flash']['danger'] = 'Ce pseudo est déjà utilisé.';
							redirect('inscription');
						}


					} else { //pseudo non valide
						$_SESSION['flash']['danger'] = 'Votre pseudo n\'est pas valide.';
						redirect('inscription');
					}
				} else { //champs non remplis
					$_SESSION['flash']['danger'] = 'Tous les champs ne sont pas remplis.';
					redirect('inscription');
				}
			}

		}	
	}

	//---------------------------------------------------------------------------------------------
	public function confirm($id, $token) {
		$user = cookie_connect();

		if(empty($user)) {
			if(isset($id) && !empty($id) && isset($token) && !empty($token)) {

				$id = htmlspecialchars($id);
				$token = htmlspecialchars($token);

				$this->load->model('user_model', 'userManager');
				$user_token = $this->userManager->get_token_for_confirmation($id);
				

				if(!empty($user_token) && $user_token != NULL) {
					if($user_token == $token) {

						$this->userManager->validate_inscription($id);
						$_SESSION['flash']['success'] = 'Félicitations ! Ton compte a bien été validé, tu peux à présent te connecter !';
						redirect('connexion');
					} else {
						$_SESSION['flash']['danger'] = 'Ce token n\'est pas valide';
						redirect('connexion');
					}
				} else {
					show_404();
				}

			} else {
				redirect();
			}
		} else {
			redirect();
		}
	}
}
