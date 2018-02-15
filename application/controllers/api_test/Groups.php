<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Groups extends REST_Controller {

	function __construct() {
		parent :: __construct();
		$this -> load -> model('api/Directory_model','model');
		$this -> load -> helper('string');
	}

	public function index_get() {
		$id = $this -> get('id');
		$includePath = $this -> get('includePath');
		$includeContent = $this -> get('includeContent');

		// valido la entrada
		try {
			v :: key('id', v :: optional(v :: numeric()), TRUE)  // (string $name, v $validator, boolean $mandatory = true)
			  -> key('includePath', v :: boolVal(), FALSE)
			  -> key('includeContent', v :: boolVal(), FALSE)
			  -> assert($this -> get());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// la validacion no afecta a los valores que chequea -> convierto "strings booleanos" a booleanos reales
		to_boolean($includePath);
		to_boolean($includeContent);

		// proceso request
		if (empty($id)) {
			$data = $this -> model -> getRootGroup($includePath, $includeContent);
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$data = $this -> model -> getGroup($id, $includePath, $includeContent);
			if (!empty($data)) {
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Group not found']], REST_Controller :: HTTP_NOT_FOUND);
			}
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
		$newGroupId = $this -> model -> createGroup($parentId, $name, $extraFields);
		if ($newGroupId !== NULL) {
			$data = $this -> model -> getGroup($newGroupId);
			$this -> response($data, REST_Controller :: HTTP_CREATED);
		} else {
			$this -> response(['errors' => ['Could not create group']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
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
		if ($this -> model -> getGroup($id) !== NULL) {
			if ($this -> model -> updateGroup($id, $name, $extraFields)) {
				$data = $this -> model -> getGroup($id);
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Could not update group']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Group not found']], REST_Controller :: HTTP_NOT_FOUND);
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
		if ($this -> model -> getGroup($id) !== NULL) {
			if ($this -> model -> deleteGroup($id)) {
				$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
			} else {
				$this -> response(['errors' => ['Could not delete group']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Group not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}
}
