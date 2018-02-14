<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Nodes extends REST_Controller {

	function __construct() {
		parent :: __construct();
		$this -> load -> model('Signage_model_new','model');
		$this -> load -> helper('string');
	}

	public function index_get() {
		$id = $this -> get('id');
		$group = $this -> model -> getGroup($id);
		$this -> response($group, REST_Controller :: HTTP_OK);
	}

	public function index_put() {
		$id = $this -> put('id');
		$this -> model -> createGroup($id);
		$this -> response($id, REST_Controller :: HTTP_OK);
	}

	public function index_post() {
		$id = $this -> post('id');
		$this -> model -> updateGroup($id);
		$this -> response($id, REST_Controller :: HTTP_OK);
	}

	public function index_delete() {
		$id = $this -> query('id');
		$this -> model -> deleteGroup($id);
		$this -> response('chan', REST_Controller :: HTTP_OK);
	}
}
