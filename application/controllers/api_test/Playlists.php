<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;

class Playlists extends REST_Controller {

	function __construct() {
		// Construct the parent class
		parent :: __construct();
		// Load directory model
		$this -> load -> model('Signage_model_new','model');
		$this -> load -> library('form_validation');
		$this -> load -> helper('string');
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		// $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		// $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
	}

	public function index_get() {
		$id = $this -> get('id');
		$includeItems = $this -> get('includeItems');

		// $number = "123";
		// $this -> response(v::numeric()->validate($number), REST_Controller :: HTTP_OK);

		// valido la entrada
		$toValidate = [
			'id' => $id,
			'includeItems' => $includeItems
		];
		$this -> form_validation -> set_data($toValidate);
		if ($this -> form_validation -> run('playlists/index_get') == FALSE){
			$this -> response(['errors' => $this -> validation_errors()], REST_Controller :: HTTP_BAD_REQUEST);
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
		$this -> form_validation -> set_data(['name' => $name]);
		if ($this -> form_validation -> run('playlists/index_put') === FALSE){
			$this -> response(['errors' => $this -> validation_errors()], REST_Controller :: HTTP_BAD_REQUEST);
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
		$this -> form_validation -> set_data(['id' => $id]);
		if ($this -> form_validation -> run('playlists/index_post') === FALSE){
			$this -> response(['errors' => $this -> validation_errors()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		if ($this -> model -> getPlaylist($id) !== NULL) {
			if ($this -> model -> updatePlaylist($id, $name, $items)) {
				$includeItems = TRUE;
				$data = $this -> model -> getPlaylist($id, $includeItems);
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
		$this -> form_validation -> set_data(['id' => $id]);
		if ($this -> form_validation -> run('playlists/index_delete') === FALSE){
			$this -> response(['errors' => $this -> validation_errors()], REST_Controller :: HTTP_BAD_REQUEST);
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
