<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Screens extends REST_Controller {

	function __construct() {
		parent :: __construct();
		$this -> load -> model('api/Directory_model','model');
		$this -> load -> helper('string');
	}

	public function index_get() {
		$id = $this -> get('id');
		$udid = $this -> get('udid');

		// valido la entrada
		try {
			v :: key('id', v :: numeric(), FALSE)  // (string $name, v $validator, boolean $mandatory = true)
			  -> key('udid', v :: numeric(), FALSE)
			  -> assert($this -> get());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		$data = NULL;
		if ($id !== NULL) {
			$data = $this -> model -> getScreen($id);
		} else {
			$data = $this -> model -> getScreenFromUDID($udid);
		}

		if (!empty($data)) {
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$this -> response(['errors' => ['Screen not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}

	public function index_put() {
		$parentId = $this -> put('parentId');
		$name = $this -> put('name');
		$extraFields = $this -> put('extraFields');

		// valido la entrada
		try {
			v :: key('parentId', v :: numeric(), TRUE) // (string $name, v $validator, boolean $mandatory = true)
			  -> key('name', v :: notEmpty(), TRUE)
			  -> key('extraFields', v :: alwaysValid(), TRUE)
			  -> assert($this -> put());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		$newScreenId = $this -> model -> createScreen($parentId, $name, $extraFields);
		if ($newScreenId !== NULL) {
			$data = $this -> model -> getScreen($newScreenId);
			$this -> response($data, REST_Controller :: HTTP_CREATED);
		} else {
			$this -> response(['errors' => ['Could not create screen']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function index_post() {
		$id = $this -> post('id');
		$name = $this -> post('name');
		$extraFields = $this -> post('extraFields');
		
		// valido la entrada
		try {
			v :: key('id', v :: numeric(), TRUE) // (string $name, v $validator, boolean $mandatory = true)
			  -> key('name', v :: notEmpty(), FALSE)
			  -> key('extraFields', v :: alwaysValid(), FALSE)
			  -> assert($this -> post());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		if ($this -> model -> getScreen($id) !== NULL) {
			if ($this -> model -> updateScreen($id, $name, $extraFields)) {
				$data = $this -> model -> getScreen($id);
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Could not update screen']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Screen not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}

	public function index_delete() {
		$id = $this -> query('id');

		// valido la entrada
		try {
			v :: key('id', v :: numeric(), TRUE) // (string $name, v $validator, boolean $mandatory = true)
			  -> assert($this -> query());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		if ($this -> model -> getScreen($id) !== NULL) {
			if ($this -> model -> deleteScreen($id)) {
				$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
			} else {
				$this -> response(['errors' => ['Could not delete screen']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Screen not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}
}
