<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Generic_model.php');


class User_model extends Generic_model {
	

	public function get_password($username) {
		$req = $this->db->conn_id->prepare('SELECT password FROM users WHERE username = :username');
		$req->bindValue(':username', $username);
		$req->execute();


		$password = $req->fetch(PDO::FETCH_ASSOC);
		return $password;
	}
//---------------------------------------------------------------------------------------
	//vérifie si un mot de passe est toujours en sha1
	public function verify_old($username, $password) {
		$req = $this->db->conn_id->prepare('SELECT id FROM users WHERE username = :username AND old_password = :password');
		$req->bindValue(':username', $username);
		$req->bindValue(':password', $password);
		$req->execute();


		$exist = $req->fetch();
		return $exist;
	}
//--------------------------------------------------------------------------------------------
	//connexion
	public function connect_user($username, $password) {
		$req = $this->db->conn_id->prepare('SELECT us.*, ban.img AS banner, ban.id_series AS ban_id_series FROM users AS us LEFT JOIN banner AS ban ON us.banner = ban.id_banner WHERE username = :username and password = :password and validate = 1');
		$req->bindValue(':username', $username);
		$req->bindValue(':password', $password);
		$req->execute();


		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		$user = $req->fetch();
		return $user;
	}
//-----------------------------------------------------------------------------------------------
	//change le mot de passe
	public function change_password($id, $password) {
		$req = $this->db->conn_id->prepare('UPDATE users SET password = :password WHERE id = :id');

		$req->bindValue(':password', $password);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}
//----------------------------------------------------------------------------------
	//supprimer l'ancien mot de passe sha1
	public function delete_old_password($username) {
		$req = $this->db->conn_id->prepare('UPDATE users SET old_password = 0 AND confirmed_at = NOW() WHERE username = :username');
		$req->bindValue(':username', $username);
		$req->execute();
	}
//------------------------------------------------------------------------------------------
	//ajout user
	public function add_user($username, $email, $password, $confirmation_token) {
		$req = $this->db->conn_id->prepare('INSERT INTO users SET username = :username, email = :email, password = :password, confirmation_token = :confirmation_token');
		$req->bindValue(':username', $username);
		$req->bindValue(':email', $email);
		$req->bindValue(':password', $password);
		$req->bindValue(':confirmation_token', $confirmation_token);
		$req->execute();
	}
//----------------------------------------------------------------------------------------------------------
	//confirmation inscription user
	public function get_token_for_confirmation($id) {
		$req = $this->db->conn_id->prepare('SELECT confirmation_token FROM users WHERE id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

		$token = $req->fetch();

		if(!empty($token)) {
			return $token->confirmation_token();
		}
		
	}
//----------------------------------------------------------------------------------------------
	//SUPPRIMER token
	public function validate_inscription($id) {
		$req = $this->db->conn_id->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW(), validate = 1 WHERE id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}
//-----------------------------------------------------------------------------------------------------------------
	//va chercher l'utilisateur par rapport à son adresse mail
	public function get_user_by_email($mail) {
		$req = $this->db->conn_id->prepare('SELECT * FROM users WHERE email = :email');
		$req->bindValue(':email', $mail);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

		$user = $req->fetch();
		return $user;
	}
//-----------------------------------------------------------------------------------
	//ajoute un token à reset_token en cas de demande de mot de passe
	public function set_reset_token($token, $id) {
		$req = $this->db->conn_id->prepare('UPDATE users SET reset_token = :token, reset_at = NOW() WHERE id = :id');
		$req->bindValue(':token', $token);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}
//-----------------------------------------------------------------------------
	//token pour reset password
	public function get_token_for_reset_password($id, $token) {
		$req = $this->db->conn_id->prepare('SELECT * FROM users WHERE id = :id AND reset_token = :token AND reset_at > DATE_SUB(NOW(), INTERVAL 45 MINUTE)');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->bindValue(':token', $token);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

		$user = $req->fetch();
		return $user;
	}
//---------------------------------------------------------
	//supprimer reset_token
	public function delete_reset_token($id) {
		$req = $this->db->conn_id->prepare('UPDATE users SET reset_token = NULL AND reset_at = NULL WHERE id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}
//----------------------------------------------------------------------------------------------
	//update user
	public function update_user(User $user, $id) {
		$up = $this->db->conn_id->prepare('UPDATE users SET presentation = :presentation WHERE id = :id');
		$up->bindValue(':id', $id, PDO::PARAM_INT);
		$up->bindValue(':presentation', $user->presentation(), PDO::PARAM_STR);
		$up->execute();

		return $msg = 'user_up';
	}
//--------------------------------------------------------------------------------------------
	public function get_by_id($id) {
		$req = $this->db->conn_id->prepare('SELECT us.*, ban.id_series as ban_id_series FROM users AS us LEFT JOIN banner AS ban ON us.banner = ban.id_banner WHERE us.id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		$user = $req->fetch();
		return $user;
	}


//------------------------------------------------------------------------------
	//on vérfie que $user_1 et $user_2 sont bien amis
	public function is_friend($id_users_1, $id_users_2) {
		$req = $this->db->conn_id->prepare('SELECT * FROM friends WHERE (id_users_1 = :id_users_1 OR id_users_2 = :id_users_1) AND (id_users_2 = :id_users_2 OR id_users_1 = :id_users_2)');
		$req->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->bindValue('friends', 'friends', PDO::PARAM_STR);
		$req->execute();

		$is_friend = $req->fetch();
		return $is_friend;
	}

//--------------------------------------------------------------------------------------------------
	//va chercher les amis qui regardent la série
	public function get_friends_who_watch($id_series, $id_users) {
		$req = $this->db->conn_id->prepare('SELECT *, no.icon AS note_icon, stat.icon AS statut_icon, fr.id_users_2 AS id FROM friends AS fr LEFT JOIN seriesdom AS sd ON sd.id_users = fr.id_users_2 
			LEFT JOIN users As us ON us.id = fr.id_users_2
			LEFT JOIN note AS no ON sd.note = no.id
			LEFT JOIN statut AS stat ON sd.statut = stat.id
			WHERE fr.id_users_1 = :id_users_1 AND sd.id_series = :id_series AND sd.statut != 5 AND sd.statut != 6 AND friends = :friends ORDER BY sd.statut');
		$req->bindValue(':id_users_1', $id_users, PDO::PARAM_INT);
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue('friends', 'friends', PDO::PARAM_STR);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		$show_friends = $req->fetchAll();

		return $show_friends;
	}
//----------------------------------------------------------------------------------------

	//va chercher tous les amis de $user
	public function get_user_friends($id_users) {
		$req = $this->db->conn_id->prepare('SELECT * FROM users AS us LEFT JOIN friends AS fr ON fr.id_users_2 = us.id WHERE id_users_1 = :id_users AND friends = :friends');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':friends', 'friends', PDO::PARAM_STR);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		$all_friends = $req->fetchAll();
		return $all_friends;
	}
//-------------------------------------------------------------------------------------------------
	//va chercher le num d'id_series correspondant à la banner de $user (needed for find_folder)
	public function get_banner_id_series($banner) {
		$req = $this->db->conn_id->prepare('SELECT id_series FROM banner WHERE id_banner = :banner');
		$req->bindValue(':banner', $banner, PDO::PARAM_INT);
		$req->execute();

		$ban_id = $req->fetch(PDO::FETCH_ASSOC);
		return $ban_id;
	}

//--------------------------------------------------------------------------------------------
	public function get_last_user() {
		$req = $this->db->conn_id->query('SELECT id, username FROM users ORDER BY id DESC');
		$last_user = $req->fetch();

		return $last_user;
	}

//----------------------------------------------------------------------------
	public function get_user_by_username($username) {
		$req = $this->db->conn_id->prepare('SELECT us.id, us.username, us.avatar, ban.img AS banner, us.presentation, ban.id_series AS ban_id_series FROM users AS us LEFT JOIN banner AS ban ON us.banner = ban.id_banner WHERE username = :username');
		$req->bindValue(':username', $username);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		$profil = $req->fetch();
		return $profil;
	}
//---------------------------------------------------------------------
	public function change_banner_users($id_users, $banner) {
		$req = $this->db->conn_id->prepare('UPDATE users SET banner = :banner WHERE id = :id_users');
		$req->bindValue(':banner', $banner, PDO::PARAM_INT);
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();

		return 'banner_up';
	}
//---------------------------------------------------------------------
	public function get_friends_requests($id_users_2) {
		$req = $this->db->conn_id->prepare('SELECT us.id, us.username, fr.friends, us.avatar FROM friends as fr LEFT JOIN users as us ON fr.id_users_1 = us.id WHERE fr.id_users_2 = :id_users_2 AND friends = :friends');
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->bindValue(':friends', 'demande');
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

		$requests = $req->fetchAll();
		return $requests;
	}
//---------------------------------------------------------------------
	public function delete_friend($id_users_1, $id_users_2) {
		$req = $this->db->conn_id->prepare('DELETE FROM friends WHERE (id_users_1 = :id_users_1 OR id_users_1 = :id_users_2) AND (id_users_1 = :id_users_2 OR id_users_2 = :id_users_2) AND friends = \'friends\'');
		$req->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->execute();

		echo 'delete';
	}
//---------------------------------------------------------------------
	public function add_friend_request($id_users_1, $id_users_2) {
		$req = $this->db->conn_id->prepare('INSERT INTO friends SET id_users_1 = :id_users_1, id_users_2 = :id_users_2, friends = :friends');
		$req->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->bindValue(':friends', 'demande');
		$req->execute();

		echo 'add';
	}
//---------------------------------------------------------------------
	public function accept_friend_request($id_users_1, $id_users_2) {
		$update = $this->db->conn_id->prepare('UPDATE friends SET friends = \'friends\' WHERE id_users_1 = :id_users_2 AND id_users_2 = :id_users_1');
		$update->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$update->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$update->execute();

		$add = $this->db->conn_id->prepare('INSERT INTO friends SET id_users_1 = :id_users_1, id_users_2 = :id_users_2, friends = \'friends\'');
		$add->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$add->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$add->execute();

		echo 'accept';
	}
//-------------------------------------------------------------------
	public function denied_friend_request($id_users_1, $id_users_2) {
		$req = $this->db->conn_id->prepare('DELETE FROM friends WHERE id_users_1 = :id_users_2 AND id_users_2 = :id_users_1');
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$req->execute();

		echo 'denied';
	}
//----------------------------------------------------------------
	public function verify_if_users_are_friends($id_users_1, $id_users_2) {
		$req = $this->db->conn_id->prepare('SELECT id FROM friends WHERE id_users_1 = :id_users_2 AND id_users_2 = :id_users_1');
		$req->bindValue(':id_users_2', $id_users_2, PDO::PARAM_INT);
		$req->bindValue(':id_users_1', $id_users_1, PDO::PARAM_INT);
		$req->execute();

		$exists = $req->fetch();
		return $exists;
	}
}