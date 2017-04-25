<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generic_model extends CI_Model {

	//va récupérer le dernier id de la table
	public function get_last_img($table) {
		$req = $this->db->conn_id->query('SELECT img AS last_img FROM '.$table.' ORDER BY id DESC');
		$last_img = $req->fetch();

		return intval($last_img['last_img']);
	}
//--------------------------------------------------------------------
	//DELETE
	public function delete_($table, $champ, $valeur) {

		$delete = $this->db->conn_id->prepare('DELETE FROM '.$table.' WHERE '.$champ.' = :champ');
		$delete->bindValue(':champ', $valeur, PDO::PARAM_INT);
		$delete->execute();
		$msg = $delete->fetch(PDO::FETCH_ASSOC);

		return $msg = 'deleted';
	}
//------------------------------------------------------------------------
	//vérifie si l'id du champ est présent dans la table
	public function in_bdd($table, $champ, $valeur) {
		$req = $this->db->conn_id->prepare('SELECT '.$champ.' FROM '.$table.' WHERE '.$champ.' = :champ');
		$req->bindValue(':champ', $valeur, PDO::PARAM_INT);
		$req->execute();
		$in_bdd = $req->rowCount();

		return $in_bdd;
	}
//--------------------------------------------------------------------------------------
	//update un seul champ selon l'id de la table
	public function update_one_thing($table, $champ, $valeur, $id) {

		$req = $this->db->conn_id->prepare('UPDATE '.$table.' SET '.$champ.' = :champ WHERE id = :id');
		$req->bindValue('champ', $valeur);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
	}
//-----------------------------------------------------------------------
	public function get_one_thing($table, $champ, $id) {
		$req = $this->db->conn_id->prepare('SELECT '.$champ.' FROM '.$table.' WHERE id = :id');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$the_thing = $req->fetch(PDO::FETCH_ASSOC);
		return $the_thing;
	}

//-------------------------------------------------------------------
	public function get_last_id($table) {
		$req = $this->db->conn_id->query('SELECT id FROM '.$table.' ORDER BY id DESC LIMIT 0,1');
		$id = $req->fetch();
		$id = $id['id'];

		return $id;
	}
}