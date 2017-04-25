<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Layout {

	private $CI;
	private $content;
	private $var = array();


	public function __construct() {
		$this->CI = get_instance();
		$this->var['content'] = '';
	}

	public function view($name, $data = array()) {
		$this->var['content'] .= $this->CI->load->view($name, $data, true);

		$this->CI->load->view('../layout/default.php', $this->var);
	}

	public function views($name, $data = array()) {
		$this->var['content'] .= $this->CI->load->view($name, $data, true);
		return $this;
	}

	public function set_title($title) {
		if(is_string($title) && !empty($title)) {
			$this->var['title'] = $title;
			return true;
		}
		return false;
	}


}