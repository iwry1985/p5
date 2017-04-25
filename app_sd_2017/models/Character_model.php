<?php  
require_once('Generic_model.php');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Character_model extends Generic_model {

	public function get_characters($id_series) {
		$req = $this->db->conn_id->prepare('SELECT * FROM characters WHERE id_series = :id_series');
		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Character');

		$characters = $req->fetchAll();

		foreach($characters as $char) {
			$folder = find_folder($id_series);
			$char->setFolder($folder);
		}
		
		return $characters;
	}
//---------------------------------------------------------------------------------------------
	//on vérifie que le personnage soit déjà répertoriée
	public function character_exists($id_tv_maze, $name, $actor_name) {
		$req = $this->db->conn_id->prepare('SELECT id FROM characters WHERE id_tv_maze = :id_tv_maze AND id_tv_maze != 0 OR (name = :name AND actor_name = :actor_name)');
		$req->bindValue(':id_tv_maze', $id_tv_maze, PDO::PARAM_INT);
		$req->bindValue(':name', $name, PDO::PARAM_STR);
		$req->bindValue(':actor_name', $actor_name, PDO::PARAM_STR);
		$req->execute();

		$exists = $req->fetch(PDO::FETCH_ASSOC);
		return $exists;
	}
//----------------------------------------------------------------------------------------
	//on update les infos du personnages
	public function updateCharacter(Character $character) {

		$update = $this->db->conn_id->prepare('UPDATE characters SET id_series = :id_series, name = :name, actor_name = :actor_name, img = :img, id_tv_maze = :id_tv_maze WHERE id = :id');
		$update->bindValue(':id_series', $character->id_series(), PDO::PARAM_INT);
		$update->bindValue(':name', $character->name(), PDO::PARAM_STR);
		$update->bindValue(':actor_name', $character->actor_name(), PDO::PARAM_STR);
		$update->bindValue(':img', $character->img(), PDO::PARAM_INT);
		$update->bindValue(':id_tv_maze', $character->id_tv_maze(), PDO::PARAM_INT);
		$update->bindValue('id', $character->id(), PDO::PARAM_INT);
		$update->execute();

		return $msg = 'update_ok';
	}

//---------------------------------------------------------------------------------------------
	//AJOUTER UN PERSONNAGE
	public function addCharacter(Character $character) {

		$add = $this->db->conn_id->prepare('INSERT INTO characters SET id_series = :id_series, name = :name, actor_name = :actor_name, img = :img, id_tv_maze = :id_tv_maze');

		$add->bindValue(':id_series', $character->id_series(), PDO::PARAM_INT);
		$add->bindValue(':name', $character->name(), PDO::PARAM_STR);
		$add->bindValue(':actor_name', $character->actor_name(), PDO::PARAM_STR);
		$add->bindValue(':img', $character->img(), PDO::PARAM_INT);
		$add->bindValue(':id_tv_maze', $character->id_tv_maze(), PDO::PARAM_INT);
		$add->execute();

		return $msg = 'character_added';
	}
//----------------------------------------------------------------------
	public function get_character($id) {
		$req = $this->db->conn_id->prepare('SELECT * FROM characters WHERE id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Character');

		$char = $req->fetch();

		$folder = find_folder($char->id_series());
		$char->setFolder($folder);
		
		return $char;
	}

}