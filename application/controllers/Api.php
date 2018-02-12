<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

	function __construct() {
		// Construct the parent class
		parent::__construct();
		// Load directory model
		$this -> load -> model('Signage_model','model');
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		// $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		// $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
	}

	// GRUPOS

		public function group_get() {
			$group = NULL;
			$id = $this -> get('id');
			$includeContent = filter_var ($this -> get('includeContent'), FILTER_VALIDATE_BOOLEAN);
			$includePath = filter_var ($this -> get('includePath'), FILTER_VALIDATE_BOOLEAN);

			// si se paso un id vacio devuelvo el grupo raiz
			if ($id === '') {
				$group = $this -> model -> getGroup(NULL, $includeContent, $includePath);
				$this -> response($group, REST_Controller :: HTTP_OK);
			}

			// chequeo validez del grupo
			$this -> _isValidItem(GROUP, $id);

			// devuelvo el grupo correspondiente
			$group = $this -> model -> getGroup($id, $includeContent, $includePath);
			$this -> response($group, REST_Controller :: HTTP_OK);
		}

		public function group_put() {
			$name = $this -> put('name');
			$parentId = $this -> put('parentId');
			$extraFields = $this -> put('extraFields');
			$this -> _addItem(GROUP, $name, $parentId, $extraFields);
		}

		public function group_post() {
			$id = $this -> post('id');
			$name = $this -> post('name');
			// usar para implementar "mover"
			// $parentId = $this -> post('parentId');
			$extraFields = $this -> post('extraFields');
			$this -> _updateItem(GROUP, $id, $name, $extraFields);
		}

		public function group_delete() {
			$id = $this -> query('id');
			$this -> _deleteItem(GROUP, $id);
		}

	// PANTALLAS

		public function screen_get() {
			$udid = $this -> get('udid');
			$id = $this -> get('id');
			$includeContent = filter_var($this -> get('includeContent'), FILTER_VALIDATE_BOOLEAN);
			$screen = NULL;
			// busqueda por udid
			if ($udid != NULL) {
				$where = ['udid' => $udid];
				$screen = $this -> model -> getScreen($where, $includeContent);
			}
			// busqueda por id
			if ($id != NULL) {
				$where = ['id' => $id];
				$screen = $this -> model -> getScreen($where, $includeContent);
			}

			if (!empty($screen)) {
				$this -> response($screen, REST_Controller :: HTTP_OK);
			} else {
				$message = [
				'message' => 'Screen not found'
				];
				$this -> response($message, REST_Controller :: HTTP_NOT_FOUND); 
			}	
		}

		public function screen_put() {
			$name = $this -> put('name');
			$parentId = $this -> put('parentId');
			$extraFields = $this -> put('extraFields');
			$this -> _addItem(SCREEN, $name, $parentId, $extraFields);
		}

		public function screen_post() {
			$id = $this -> post('id');
			$name = $this -> post('name');
			// usar para implementar "mover"
			// $parentId = $this -> post('parentId');
			$extraFields = $this -> post('extraFields');
			$this -> _updateItem(SCREEN, $id, $name, $extraFields);
		}

		public function screen_delete() {
			$id = $this -> query('id');
			$this -> _deleteItem(SCREEN, $id);
		}

	// PLAYLISTS

		public function playlist_get() {
			$id = $this -> get('id');
			$includeContent = filter_var($this -> get('includeContent'), FILTER_VALIDATE_BOOLEAN);
			// consulto al modelo
			$playlists = $this -> model -> getPlaylists($id, $includeContent);
			$this -> response($playlists, REST_Controller :: HTTP_OK);
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

	// METODOS INTERNOS

		private function _addItem($type, $name, $parentId, $extraFields) {
			// chequeo validez del grupo destino
			$this -> _isValidItem(GROUP, $parentId);

			// chequeo validez del nombre
			$this -> _isValidNewItemName($type, $name, $parentId);

			// trato de agregar el nuevo item
			$newItemId = $this -> model -> addItem($type, $name, $parentId, $extraFields);
			if ($newItemId != NULL) {
				// item agregado con exito
				$item = $type === GROUP ? $this -> model -> getGroup($newItemId) : $this -> model -> getScreen(['id' => $newItemId]);
				$message = [
					'item' => $item,
					'message' => ucfirst($type) . ' created'
				];
				$this -> response($message, REST_Controller :: HTTP_CREATED);
			} else {
				// no se pudo agregar el item
				$message = [
					'message' => 'Could not create ' . $type
				];
				$this -> response($message, REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		}

		private function _updateItem($type, $id, $name, $extraFields) {
			// chequeo validez del item
			$this -> _isValidItem($type, $id);

			// chequeo si se esta tratando de mover y el grupo destino es valido 
			// ... para despues

			// chequeo si se esta renombrando y su validez
			if ($name != NULL) {
				$this -> _isValidRename($type, $name, $id);
			}

			// trato de modificar el item correspondiente
			if ($this -> model -> updateItem($type, $id, $name, $extraFields)) {
				// item modificado con exito
				$item = $type === GROUP ? $this -> model -> getGroup($id) : $this -> model -> getScreen(['id' => $id]);
				$message = [
					'item' => $item,
					'message' => ucfirst($type) . ' updated'
				];
				$this -> response($message, REST_Controller :: HTTP_OK);
			} else {
				// no se pudo modificar el item
				$message = [
					'id' => $id,
					'message' => 'Could not update ' . $type
				];
				$this -> response($message, REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		}

		private function _deleteItem($type, $id) {
			// chequeo validez del item
			$this -> _isValidItem($type, $id);

			// elimino el item correspondiente        
			if ($this -> model -> deleteItem($id)) {
				// item eliminado existosamente
				$this -> response(NULL, REST_Controller :: HTTP_NO_CONTENT);
			} else {
				// no se pudo eliminar el item
				$message = [
					'id' => $id,
					'message' => 'Could not delete ' . $type
				];
				$this -> response($message, REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);
			}
		}

		// Chequeos (para dar una respuesta apropiada a cada request)

		private function _isValidItem($type, $id) {
			// chequeo validez del id
			if (!$this -> model -> isValidItemId($id)) {
				$this -> response(NULL, REST_Controller :: HTTP_BAD_REQUEST);
			}

			// chequeo que el item exista      
			if (!$this -> model -> itemExists($type, $id)) {
				$message = [
					'id' => $id,
					'message' => ucfirst($type) . ' not found'
				];
				$this -> response($message, REST_Controller :: HTTP_NOT_FOUND);
			}
		}

		private function _isValidNewItemName($type, $name, $parentId) {
			if (!$this -> model -> isValidNewItemName($type, $name, $parentId)) {
				// nombre en uso
				$message = [
					'message' => 'Name already in use'
				];
				$this -> response($message, REST_Controller :: HTTP_CONFLICT);
			}
		}

		private function _isValidRename($type, $name, $id) {
			if (!$this -> model -> isValidRename($type, $name, $id)) {
				// nombre en uso
				$message = [
					'message' => 'Name already in use'
				];
				$this -> response($message, REST_Controller :: HTTP_CONFLICT);
			}
		}

}
