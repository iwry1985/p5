<?php

class User extends Entity {
	protected $id,
			  $username,
			  $email,
			  $password,
			  $old_password,
			  $admin,
			  $banner,
			  $ban_id_series,
			  $avatar,
			  $presentation,
			  $facebook,
			  $twitter,
			  $instagram,
			  $youtube,
			  $blog,
			  $confirmation_token,
			  $confirmed_at,
			  $validate,
			  $reset_at,
			  $reset_token,
			  $visionnages = array(),
			  $annonces_renew = array(),
			  $demande_amis = array(),
			  $shows_toBegin,
			  $shows_toCatch,
			  $shows_running,
			  $shows_ended,
			  $shows_trashed,
			  $count_seen_shows;

	//Liste des setters
	public function setId($id) {
		$id = (int)$id;
		if($id > 0) {
			$this->id = $id;
		}
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setAdmin($admin) {
		$this->admin = $admin;
	}

	public function setBanner($banner) {
		$this->banner = $banner;
	}

	public function setAvatar($avatar) {
		$this->avatar = $avatar;
	}

	public function setPresentation($presentation) {
		$this->presentation = $presentation;
	}

	public function setFacebook($facebook) {
		$this->facebook = $facebook;
	}

	public function setTwitter($twitter) {
		$this->twitter = $twitter;
	}

	public function setInstagram($instagram) {
		$this->instagram = $instagram;
	}

	public function setYoutube($youtube) {
		$this->youtube = $youtube;
	}

	public function setBlog($blog) {
		$this->blog = $blog;
	}

	public function setVisionnages($episodes = array()) {
		$this->visionnages = $episodes;
	}

	public function setAnnonces_renew($renew = array()) {
		$this->annonces_renew = $renew;
	}

	public function setBan_id_series($ban_id_series) {
		$this->ban_id_series = $ban_id_series;
	}

	public function setShows_toBegin($shows_toBegin) {
		$this->shows_toBegin = $shows_toBegin;
	}

	public function setShows_toCatch($shows_toCatch) {
		$this->shows_toCatch = $shows_toCatch;
	}

	public function setShows_running($shows_running) {
		$this->shows_running = $shows_running;
	}

	public function setShows_ended($shows_ended) {
		$this->shows_ended = $shows_ended;
	}

	public function setShows_trashed($shows_trashed) {
		$this->shows_trashed = $shows_trashed;
	}

	public function setCount_seen_shows($count_seen_shows) {
		$this->count_seen_shows = $count_seen_shows;
	}


//---------------------------------------------------------------
	//Liste des getters
	public function id() {
		return $this->id;
	}

	public function username() {
		return $this->username;
	}

	public function email() {
		return $this->email;
	}

	public function password() {
		return $this->password;
	}

	public function admin() {
		return $this->admin;
	}

	public function banner() {
		return $this->banner;
	}

	public function avatar() {
		return $this->avatar;
	}

	public function presentation() {
		return $this->presentation;
	}

	public function facebook() {
		return $this->facebook;
	}

	public function twitter() {
		return $this->twitter;
	}

	public function instagram() {
		return $this->instagram;
	}

	public function youtube() {
		return $this->youtube;
	}

	public function blog() {
		return $this->blog;
	}

	public function visionnages() {
		return $this->visionnages;
	}

	public function annonces_renew() {
		return $this->annonces_renew;

		unset($_SESSION['flash']);
	}

	public function confirmation_token() {
		return $this->confirmation_token;
	}

	public function reset_token() {
		return $this->reset_token;
	}

	public function ban_id_series() {
		return $this->ban_id_series;
	}

	public function shows_toBegin() {
		return $this->shows_toBegin;
	}

	public function shows_toCatch() {
		return $this->shows_toCatch;
	}

	public function shows_running() {
		return $this->shows_running;
	}

	public function shows_ended() {
		return $this->shows_ended;
	}

	public function shows_trashed() {
		return $this->shows_trashed;
	}

	public function count_seen_shows() {
		return $this->count_seen_shows;
	}

//-----------------------------------------------------------------------------------------
	public function getFlash() {
		foreach($_SESSION['flash'] as $type => $message) {
		echo '<div class="alert alert-'.$type.'">'.$message.'</div>';
		}

	unset($_SESSION['flash']);
	}

	public function hasFlash() {
		return isset($_SESSION['flash']);
	}

	public function setFlash($value, $type) {
		$_SESSION['flash'][$type] = $value;
	}

	public function hasRenew() {
		return isset($this->annonces_renew);
	}

}

