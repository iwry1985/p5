<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_model extends CI_Model {

	//Va chercher tous les avis possibles (excepté '1 == série à commencer')
	public function get_all_notes() {
		$req = $this->db->conn_id->query('SELECT id, icon AS icon_img, note AS libelle FROM note WHERE id != 2 GROUP BY id');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Icon');
		$notes = $req->fetchAll();

		return $notes;
	}
//-----------------------------------------------------------------------------------------------------
	
	//Va chercher tous les statuts possibles (excepté 6 == not interested)
	public function get_all_status() {
		$req = $this->db->conn_id->query('SELECT id, icon As icon_img, statut AS libelle FROM statut WHERE id != 6 GROUP BY id');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Icon');
		$statuts = $req->fetchAll();

		return $statuts;
	}
//-------------------------------------------------------------------------------------------------------
	
	//Va chercher tous les pays
	public function get_all_org() {
		$req = $this->db->conn_id->query('SELECT id, origine AS origine_img, country FROM origine ORDER BY country ASC');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Origine');
		$org = $req->fetchAll();

		return $org;
	}
//-------------------------------------------------------------------------------------------------------------------

	//va chercher tous les networks
	public function get_all_networks() {
		$req = $this->db->conn_id->query('SELECT id, network AS network_img, network_name, country FROM networks ORDER BY network_name ASC');
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Network');
		$networks = $req->fetchAll();

		return $networks;
	}
//-------------------------------------------------------------------------------------------------

	//va chercher tous les etats possibles (en cours, terminée, commandée)
	public function get_all_etats() {
		$req = $this->db->conn_id->query('SELECT * FROM etat');
		$etats = $req->fetchAll(PDO::FETCH_ASSOC);

		return $etats;
	}
//-------------------------------------------------------------------------------------------------

	//va chercher tous les genres
	public function get_all_genre() {
		$req = $this->db->conn_id->query('SELECT * FROM genre ORDER BY genre ASC');
		$genres = $req->fetchAll(PDO::FETCH_ASSOC);

		return $genres;
	}
//------------------------------------------------------------------------------------------------
	

}