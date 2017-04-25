<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seriesdom_model extends CI_Model {

	//TABLE GENERIQUE (DELETE w/ id_users AND id_series)
	public function generic_delete($table, $id_users, $id_series) {

		$delete = $this->db->conn_id->prepare('DELETE FROM '.$table.' WHERE id_users = :id_users AND id_series = :id_series');
		$delete->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$delete->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$delete->execute();
	}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		//AJOUTER LA SERIE A LA LISTE DE USER
	public function add_show($id_users, $id_series) {
		$statut = 1;
		$note = 1;
		$year_add = date('Y', time());
		$month_add = date('m', time());

		$add = $this->db->conn_id->prepare('INSERT INTO seriesdom SET id_users = :id_users, id_series = :id_series, statut = :statut, note = :note, date_time_add = NOW(), month_add = :month_add, year_add = :year_add');

		$add->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$add->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$add->bindValue(':statut', $statut, PDO::PARAM_INT);
		$add->bindValue(':note', $note, PDO::PARAM_INT);
		$add->bindValue(':month_add', $month_add, PDO::PARAM_INT);
		$add->bindValue(':year_add', $year_add, PDO::PARAM_INT);


		$add->execute();
	}

//--------------------------------------------------------------------------------------------------------------------------------------------

	//MODIFIE LE STATUT DE LA SERIES DE USER (en cours,...)
	public function change_status_show($id_users, $id_series, $statut) {
		$year_update = date('Y', time());
		$month_update = date('m', time());

		//si le statut est à commencer, on supprime les éventuelles données de watchlist et fav_characters
		if($statut == 1) {
			$note = 1;

			$update = $this->db->conn_id->prepare('UPDATE seriesdom SET statut = :statut, note = :note, date_time_update = NOW(), month_update = :month_update, year_update = :year_update WHERE id_users = :id_users AND id_series = :id_series');
			$update->bindValue(':statut', $statut, PDO::PARAM_INT);
			$update->bindValue(':note', $note, PDO::PARAM_INT);
			$update->bindValue(':month_update', $month_update, PDO::PARAM_INT);
			$update->bindValue(':year_update', $year_update, PDO::PARAM_INT);
			$update->bindValue(':id_users', $id_users, PDO::PARAM_INT);
			$update->bindValue(':id_series', $id_series, PDO::PARAM_INT);
			$update->execute();

		} else {

			$update = $this->db->conn_id->prepare('UPDATE seriesdom SET statut = :statut, date_time_update = NOW(), month_update = :month_update, year_update = :year_update WHERE id_users = :id_users AND id_series = :id_series');
			$update->bindValue(':statut', $statut, PDO::PARAM_INT);
			$update->bindValue(':month_update', $month_update, PDO::PARAM_INT);
			$update->bindValue(':year_update', $year_update, PDO::PARAM_INT);
			$update->bindValue(':id_users', $id_users, PDO::PARAM_INT);
			$update->bindValue(':id_series', $id_series, PDO::PARAM_INT);
			$update->execute();
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------------------

	//CHANGE LA NOTE DE USER SUR LA SERIE
	public function change_note($id_users, $id_series, $note) {

		$year_note = date('Y', time());
		$month_note = date('m', time());

		$emo = $this->db->conn_id->prepare('UPDATE seriesdom SET note = :note, month_note = :month_note, year_note = :year_note WHERE id_users = :id_users AND id_series = :id_series');
		$emo->bindValue(':note', $note, PDO::PARAM_INT);
		$emo->bindValue(':month_note', $month_note, PDO::PARAM_INT);
		$emo->bindValue(':year_note', $year_note, PDO::PARAM_INT);
		$emo->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$emo->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$emo->execute();
	}
//----------------------------------------------------------

	//AJOUTE EPISODE DANS LA LISTE DE USER
	public function add_ep($id_users, $id_series, $id_ep) {
		$watch_year = date('Y', time());
		$watch_month = date('m', time());

		$add = $this->db->conn_id->prepare('INSERT INTO watchlist SET id_series = :id_series, id_users = :id_users, id_ep = :id_ep, watch_date = NOW(), watch_month = :watch_month, watch_year = :watch_year');
		$add->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$add->bindValue('id_users', $id_users, PDO::PARAM_INT);
		$add->bindValue(':id_ep', $id_ep, PDO::PARAM_INT);
		$add->bindValue(':watch_month', $watch_month, PDO::PARAM_INT);
		$add->bindValue(':watch_year', $watch_year, PDO::PARAM_INT);
		$add->execute();
	}

	//SUPPRIMER EPISODE DANS LA LISTE DE USER
	public function delete_ep($id_users, $id_series, $id_ep) {

		$del = $this->db->conn_id->prepare('DELETE FROM watchlist WHERE id_users = :id_users AND id_series = :id_series AND id_ep = :id_ep');
		$del->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$del->bindValue('id_users', $id_users, PDO::PARAM_INT);
		$del->bindValue(':id_ep', $id_ep, PDO::PARAM_INT);
		$del->execute();
	}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//dates ajouts pour bilan
	public function get_dates_add($id_users) {
		$req = $this->db->conn_id->prepare('SELECT DISTINCT year_add FROM seriesdom WHERE id_users = :id_users AND year_add != 0 ORDER BY year_add ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();

		$dates = $req->fetchAll(PDO::FETCH_ASSOC);
		return $dates;
	}
//----------------------------------------------------------------------------
	//dates update pour bilan
	public function get_dates_update($id_users) {
		$req = $this->db->conn_id->prepare('SELECT DISTINCT year_update FROM seriesdom WHERE id_users = :id_users AND year_update != 0 ORDER BY year_update ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->execute();

		$dates = $req->fetchAll(PDO::FETCH_ASSOC);
		return $dates;
	}
//--------------------------------------------------------------------------
	//va chercher les séries ajoutées durant la période sélectionnée
	public function get_monthly_added_show($id_users, $month, $year) {
		$req = $this->db->conn_id->prepare('SELECT se.img, se.id, se.name FROM seriesdom AS sd LEFT JOIN series AS se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.month_add = :month AND sd.year_add = :year ORDER BY sd.date_time_add DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':month', $month, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$shows_added = $req->fetchAll();
		return $shows_added;
	}
//----------------------------------------------------------------------------
	//va chercher les séries terminées pendant la période sélect
	public function get_monthly_ended_show($id_users, $month, $year) {
		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name FROM seriesdom as sd LEFT JOIN series as se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.month_update = :month AND sd.year_update = :year AND sd.statut = :statut ORDER BY sd.date_time_add DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':month', $month, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->bindValue(':statut', 4, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$shows_ended = $req->fetchAll();
		return $shows_ended;
	}
//----------------------------------------------------------------------
	//va chercher les coups de coeur de la période sélect
	public function get_monthly_fav($id_users, $month, $year) {
		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name FROM seriesdom as sd LEFT JOIN series as se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.note = :note AND sd.month_note = :month AND sd.year_note = :year ORDER BY se.name ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':note', 10, PDO::PARAM_INT);
		$req->bindValue(':month', $month, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$coup_de_coeur = $req->fetchAll();
		return $coup_de_coeur;
	}
//---------------------------------------------------------------------------
	//va chercher les nouvelles séries commencées pendant la période
	public function get_monthly_began_show($id_users, $month, $year) {
		$req = $this->db->conn_id->prepare('SELECT se.id, se.img, se.name FROM series as se 
			LEFT JOIN watchlist as watch ON se.id = watch.id_series 
			LEFT JOIN episodes as ep ON ep.id_ep = watch.id_ep
			WHERE watch.id_users = :id_users AND ep.season = :season AND ep.number = :number AND watch.watch_month = :month AND watch.watch_year = :year ORDER BY watch.watch_date DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':season', 1, PDO::PARAM_INT);
		$req->bindValue(':number', 1, PDO::PARAM_INT);
		$req->bindValue(':month', $month, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Serie');
		$began_shows = $req->fetchAll();
		return $began_shows;
	}
//--------------------------------------------------------------------------
	//séries ajoutées par user sur un an
	public function get_yearly_added_show($id_users, $year) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(se.id) AS count FROM seriesdom AS sd LEFT JOIN series AS se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.year_add = :year ORDER BY sd.date_time_add DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$shows_added = $req->fetch(PDO::FETCH_ASSOC);
		return $shows_added['count'];
	}
//------------------------------------------------------------------------------
	//séries terminées par user en un an
	public function get_yearly_ended_show($id_users, $year) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(se.id) AS count FROM seriesdom as sd LEFT JOIN series as se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.year_update = :year AND sd.statut = :statut ORDER BY sd.date_time_add DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->bindValue(':statut', 4, PDO::PARAM_INT);
		$req->execute();

		$shows_ended = $req->fetch(PDO::FETCH_ASSOC);
		return $shows_ended['count'];
	}
//----------------------------------------------------------------------
	//coups de coeur de user en un an
	public function get_yearly_fav($id_users, $year) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(se.id) AS count FROM seriesdom as sd LEFT JOIN series as se ON sd.id_series = se.id WHERE sd.id_users = :id_users AND sd.note = :note AND sd.year_note = :year ORDER BY se.name ASC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':note', 10, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$coup_de_coeur = $req->fetch(PDO::FETCH_ASSOC);
		return $coup_de_coeur['count'];
	}
//---------------------------------------------------------------------------
	//séries commencées par user en un an
	public function get_yearly_began_show($id_users, $year) {
		$req = $this->db->conn_id->prepare('SELECT COUNT(se.id) AS count FROM series as se 
			LEFT JOIN watchlist as watch ON se.id = watch.id_series 
			LEFT JOIN episodes as ep ON ep.id_ep = watch.id_ep
			WHERE watch.id_users = :id_users AND ep.season = :season AND ep.number = :number AND watch.watch_year = :year ORDER BY watch.watch_date DESC');
		$req->bindValue(':id_users', $id_users, PDO::PARAM_INT);
		$req->bindValue(':season', 1, PDO::PARAM_INT);
		$req->bindValue(':number', 1, PDO::PARAM_INT);
		$req->bindValue(':year', $year, PDO::PARAM_INT);
		$req->execute();

		$began_shows = $req->fetch(PDO::FETCH_ASSOC);
		return $began_shows['count'];
	}
}