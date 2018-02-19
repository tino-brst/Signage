<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Images extends REST_Controller {

	function __construct() {
		parent :: __construct();
		$this -> load -> model('api/Images_model', 'model');
		$this -> load -> helper(['string', 'form', 'url']);
		$this -> load -> library('upload');
	}

	public function index_get() {
		$id = $this -> get('id');

		// valido la entrada
		try {
			v :: key('id', v :: numeric(), FALSE) // (string $name, v $validator, boolean $mandatory = true)
			  -> assert($this -> get());
		} catch (NestedValidationException $exception) {
			$this -> response(['errors' => $exception -> getMessagesIndexedByName()], REST_Controller :: HTTP_BAD_REQUEST);
		}

		// proceso request
		if ($id === NULL) {
			$data = $this -> model -> getImages();
			$this -> response($data, REST_Controller :: HTTP_OK);
		} else {
			$data = $this -> model -> getImage($id);
			if (!empty($data)) {
				$this -> response($data, REST_Controller :: HTTP_OK);
			} else {
				$this -> response(['errors' => ['Image not found']], REST_Controller :: HTTP_NOT_FOUND);
			}
		}
	}

	public function index_post() {
		// formateo imagen

		$uploadedImages = [];
		$files = $_FILES;
		$filesCount = count($_FILES['images']['name']);
		
		for($i =0 ; $i < $filesCount; $i++) {           
			$_FILES['images']['name'] = $files['images']['name'][$i];
			$_FILES['images']['type'] = $files['images']['type'][$i];
			$_FILES['images']['tmp_name'] = $files['images']['tmp_name'][$i];
			$_FILES['images']['error'] = $files['images']['error'][$i];
			$_FILES['images']['size'] = $files['images']['size'][$i];    

			$saved = $this -> upload -> do_upload('images');
			if ($saved) {
				$imageInfo = $this -> upload -> data();
				$imageId = $this -> model -> addImage($imageInfo['file_name']);
				if ($imageId !== NULL) {
					$uploadedImages[] = $this -> model -> getImage($imageId);
				}
			}
		}

		$this -> response($uploadedImages, REST_Controller :: HTTP_CREATED);



		// guardo en carpeta para imagenes y cargo en la BD
		$saved = $this -> upload -> do_upload('image');
		if ($saved) {
			$imageInfo = $this -> upload -> data();
			$imageId = $this -> model -> addImage($imageInfo['file_name']);
			if ($imageId !== NULL) {
				$data = $this -> model -> getImage($imageId);
				$this -> response($data, REST_Controller :: HTTP_CREATED);
			}
		}

		// la imagen no se guardo con exito
		$errors = $this -> upload -> display_errors($openingTag = '', $closingTag = '');
		$this -> response(['errors' => [$errors]], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
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
		if ($this -> model -> getImage($id) !== NULL) {
			if ($this -> model -> deleteImage($id)) {
				$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
			} else {
				$this -> response(['errors' => ['Could not delete image']], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this -> response(['errors' => ['Image not found']], REST_Controller :: HTTP_NOT_FOUND);
		}
	}
}
