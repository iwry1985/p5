<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class my404 extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$user = cookie_connect();

		$this->output->set_status_header('404');
		$this->layout->view('errors/404', array(
								'title' => 'SeriesDOM - 404',
								'user' => $user
							));
	}
}
