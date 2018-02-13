<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Playlists extends REST_Controller {

	function __construct() {
		// Construct the parent class
		parent :: __construct();
		// Load directory model
		$this -> load -> model('Signage_model_new','model');
		$this -> load -> helper('string');
	}

	public function index_get() {
		$id = $this -> get('id');
		$includeItems = $this -> get('includeItems');

		// valido la entrada
		try {
			v :: key('id', v :: numeric(), FALSE) // (string $name, v $validator, boolean $mandatory = true)
			  -> key('includeItems', v :: boolVal(), FALSE)
			  -> assert($this -> get());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// la validacion no afecta a aquellos valores que chequea -> convierto "strings booleanos" a booleanos reales
		to_boolean($includeItems);

		// proceso request
		if ($id === NULL) {
			$data = $this -> model -> getPlaylists($includeItems);
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$data = $this -> model -> getPlaylist($id, $includeItems);
			if (!empty($data)) {
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Playlist not found']], REST_Controller :: HTTP_NOT_FOUND);
			}
		}
	}

	public function index_put() {
		$name = $this -> put('name');

		// valido la entrada
		try {
			v :: key('name', v :: notEmpty(), TRUE) // (string $name, v $validator, boolean $mandatory = true)
			  -> assert($this -> put());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		$newPlaylistId = $this -> model -> createPlaylist($name);
		if ($newPlaylistId != NULL) {
			$data = $this -> model -> getPlaylist($newPlaylistId);
			$this -> response($data, REST_Controller :: HTTP_CREATED);
		} else {
			$this -> response(['errors' => ['Could not create playlist']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function index_post() {
		$id = $this -> post('id');
		$name = $this -> post('name');
		$items = $this -> post('items');
		
		// valido la entrada
		try {
			v :: key('id', v :: numeric(), TRUE) // (string $name, v $validator, boolean $mandatory = true)
			  -> key('name', v :: notEmpty(), FALSE)
			  -> assert($this -> post());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		if ($this -> model -> getPlaylist($id) !== NULL) {
			if ($this -> model -> updatePlaylist($id, $name, $items)) {
				$data = $this -> model -> getPlaylist($id, $includeItems = TRUE);
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Could not update playlist']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Playlist not found']], REST_Controller :: HTTP_NOT_FOUND);
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
		if ($this -> model -> getPlaylist($id) !== NULL) {
			if ($this -> model -> deletePlaylist($id)) {
				$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
			} else {
				$this -> response(['errors' => ['Could not delete playlist']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Playlist not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}
}
