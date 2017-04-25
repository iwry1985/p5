<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function get_shows_with_wrong_status() {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT se.img, se.id, se.name FROM series AS se LEFT JOIN episodes AS ep ON ep.id_series = se.id
			WHERE se.etat = 3 AND (ep.number = 1 AND ep.season = 1 AND ep.airdate < :today AND ep.airdate != \'0000-00-00\')');
		$req->bindValue(':today', $today); 
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$shows = $req->fetchAll();

		return $shows;
	}
//--------------------------------------------------------------

	public function get_new_seasons_shows() {
		$today = date('Y-m-d');

		$req = $this->db->conn_id->prepare('SELECT se.img, se.id, se.name, ep.season, ep.airdate, se.seasons FROM series AS se LEFT JOIN episodes AS ep ON ep.id_series = se.id
			WHERE se.etat = 1 AND (ep.season > se.seasons AND ep.airdate != \'0000-00-00\' AND ep.airdate <= :today) GROUP BY se.id ORDER BY airdate DESC');
		$req->bindValue(':today', $today); 
		$req->execute();
		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');

		$shows = $req->fetchAll();

		return $shows;
	}

//-------------------------------------------------------
	public function count_members() {
		$req = $this->db->conn_id->query('SELECT COUNT(id) as count FROM users');
		$count = $req->fetch();
		$count = $count['count'];

		return $count;
	}
}