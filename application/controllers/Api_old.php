<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api_old extends REST_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
		// Load directory model
		$this -> load -> model('Signage_model','model');
	}

	// SETUP

	public function setup_put() {
		$udid = $this -> put('udid');
		// agrego udid de pantalla a la DB con su pin correspondiente
		$setup = $this -> model -> addSetup($udid);
		$message = [
			'setup' => $setup,
			'message' => 'Screen ready for setup'
		];
		$this -> response($message, REST_Controller :: HTTP_CREATED);
	}

	public function setup_get() {
		$pin = $this -> get('pin');
		// consulto al modelo
		$setup = $this -> model -> getSetup($pin);
		if (empty($setup)) {
			$message = [
				'message' => 'No screen found with pin ' . $pin
			];
			$this -> response($message, REST_Controller :: HTTP_NOT_FOUND); 
		}
		$this -> response($setup, REST_Controller :: HTTP_OK);
	}
}
