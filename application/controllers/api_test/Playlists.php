<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Playlists extends REST_Controller {

	function __construct() {
		// Construct the parent class
		parent :: __construct();
		// Load directory model
		$this -> load -> model('Signage_model_new','model');
		$this -> load -> library('form_validation');
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		// $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		// $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
	}

	public function index_get() {
		$id = $this -> get('id');
		$includeItems = $this -> get('includeItems');

		// ----

		$data = ['id' => $id, 'includeItems' => $includeItems];

		$this->form_validation->set_data($data);
		// $this->form_validation->set_rules('playlist');

		if($this->form_validation->run()==FALSE){
			$data = [
				'errors' => $this -> validation_errors()
			];
			// print_r($this->form_validation->error_array());
			$this -> response($data, REST_Controller :: HTTP_OK);
		}
		else{
			$this -> response("all looking good", REST_Controller :: HTTP_OK);
		}

		// -----

		// validacion -> bad request
		$includeItems = filter_var($includeItems, FILTER_VALIDATE_BOOLEAN);
		// ...

		if ($id === NULL) {
			$data = $this -> model -> getPlaylists($includeItems);
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$data = $this -> model -> getPlaylist($id, $includeItems);
			if (!empty($data)) {
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['message' => 'Playlist not found'], REST_Controller :: HTTP_NOT_FOUND);
			}
		}
	}

	public function index_put() {
		$name = $this -> put('name');

		// validacion -> bad request

		$newPlaylistId = $this -> model -> createPlaylist($name);
		if ($newPlaylistId != NULL) {
			$data = $this -> model -> getPlaylist($newPlaylistId);
			$this -> response($data, REST_Controller :: HTTP_CREATED);
		} else {
			$this -> response(['message' => 'Could not create playlist'], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function index_post() {
		$id = $this -> post('id');
		$newValues = [
			'name' => $this -> post('name'),
			'items' => $this -> post('items')
		];
		
		// validacion -> bad request

		if ($this -> model -> updatePlaylist($id, $newValues)) {
			$includeItems = TRUE;
			$data = $this -> model -> getPlaylist($id, $includeItems);
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$this -> response(['message' => 'Could not update playlist'], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function index_delete() {
		$id = $this -> query('id');

		// validacion

		if ($this -> model -> deletePlaylist($id)) {
			$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
		} else {
			$this -> response(['message' => 'Could not delete playlist'], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
		}
	}

}
