<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signage extends CI_Controller {

	public function __construct() {
		parent::__construct();	
		$this -> load -> helper(['url_helper']);	
	}

	public function admin() {
		$this -> load -> view('admin');
	}

	public function screen($udid) {
		$data['screen_udid'] = $udid;
		$this -> load -> view('screen', $data);
	}
} 