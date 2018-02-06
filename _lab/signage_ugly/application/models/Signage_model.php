<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signage_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// cargo base de datos
		$this -> load -> database();
		// cargo y configuro libreria para el manejo del directorio (grupos y pantallas)
		$this -> load -> library('nested_set', NULL, 'directory');
		$this -> directory -> setControlParams('directory', 'left_value', 'right_value', 'id', 'parent_id', 'name');
		// creo nuevo directorio (si no hay uno)
		if (count($this -> directory -> getRootNodes()) == 0) {
			$this -> _setupDirectory();
		}
	}

	// GRUPOS Y PANTALLAS

		public function getGroup($id = NULL, $includeContent = FALSE, $includePath = FALSE) {
			$groupId = $id;
			if ($id === NULL) {
				$rootGroup = $this -> directory -> getRootNodes()[0];
				$groupId = $rootGroup['id'];
			}
			$query = $this -> db
			-> select('*')
			-> from('groups')
			-> where('id', $groupId)
			-> get();
			$group = $query -> row_array();

			if (!empty($group)) {
				if ($includePath) {
					$group['path'] = $this -> _getPathData($groupId);
				}
				if ($includeContent) {
					$group['content'] = $this -> _getContentData($groupId);
				}
			}

			return $group;
		}

		public function getScreen($where, $includeContent = FALSE) {
			$query = $this -> db
			-> select('*')
			-> from('screens')
			-> where($where)
			-> get();
			$screen = $query -> row_array();

			if (!empty($screen) && $includeContent) {
				$screen['content'] = $this -> _getPlaylistContent($screen['playlist_id']);
			}
			return $screen;
		}

		public function addItem($type, $name, $parentId, $extraFields) {
			// obtengo grupo destino
			$parenGroup = $this -> directory -> getNodeFromId($parentId);
			// creo nuevo item en directorio
			$newItem = $this -> directory -> appendNewChild($parenGroup, array("name" => $name, "type" => $type));
			// adjunto id generado a la info especifica al tipo [group/screen]
			$extraFields['id'] = $newItem['id'];
			// tabla destino segun el tipo (si fueran mas tipos se podria armar una loockup table)
			$type_table = $type === GROUP ? 'groups_data' : 'screens_data';
			// trato de insertar item
			if ($this -> db -> insert($type_table, $extraFields)) {
				// el item se creo con exito
				return $newItem['id'];
			} else {
				// rollback del insert en el directorio (si hubo un error al insertar en la tabla con info especifica al tipo)
				$this -> directory -> deleteNode($newItem);
				return NULL;
			}
		}

		public function updateItem($type, $id, $name, $extraFields) {
			$this -> db -> trans_start();
			// OJO! si se implementa el "mover" la transaccion no 
			// va a andar (tiene que ver con estar usando una libreria en el medio)

			// modifico tabla con datos comun a todos los tipos
			if (!empty($name)) {
				$this -> db -> update('directory', array('name' => $name), array('id' => $id));
			}
			// modifico tabla destino dependiente del tipo (si fueran mas tipos se podria armar una loockup table)
			if (!empty($extraFields)) {
				$type_table = $type === GROUP ? 'groups_data' : 'screens_data';
				$this -> db -> update($type_table, $extraFields, array('id' => $id));
			}
			return $this -> db -> trans_complete();
		}

		public function deleteItem($id) {
			$item = $this -> directory -> getNodeFromId($id);
			return $this -> directory -> deleteNode($item);
		}

	// CHEQUEOS

		public function isValidItemId($id) {
			$id = (int) $id;
			return $id > 0;
		}

		public function itemExists($type, $id) {
			$item = $this -> directory -> getNodeFromId($id);
			return (!empty($item) && $item['type'] === $type);
		}

		public function isValidNewItemName($type, $name, $parentId) {
			$query = $this -> db 
				-> select('*')
				-> from('directory')
				-> where(['type' => $type, 'name' => $name, 'parent_id' => $parentId])
				-> get();
			$result = $query -> row();
			return empty($result);
		}

		public function isValidRename($type, $name, $id) {
			$parentId = $this -> directory -> getNodeFromId($id)['parent_id'];
			$query = $this -> db 
				-> select('*')
				-> from('directory')
				-> where(['type' => $type, 'name' => $name, 'parent_id' => $parentId, 'id !=' => $id])
				-> get();
			$result = $query -> row();
			return empty($result);
		}

	// PLAYLISTS

		public function getPlaylists($id = NULL, $includeContent = NULL) {
			// obtengo playlist/s
			if ($id === NULL) {
				$query = $this -> db -> get('playlists');
				$playlists = $query -> result_array();
				if ($includeContent) {
					foreach ($playlists as &$playlist) {
						$content = $this -> _getPlaylistContent($playlist['id']);
						$playlist['items'] = $content;
					}
				}
				return $playlists;
			} else {
				$query = $this -> db -> get_where('playlists', ['id' => $id]);
				$playlist = $query -> row_array(); 
				if ($includeContent) {
					$playlist['items'] = $this -> _getPlaylistContent($playlist['id']);
				}
				return $playlist;
			}
		}

		private function _getPlaylistContent($id) {
			$query = $this -> db 
			-> select('playlists_images.position, images.id, images.location')
			-> from('playlists_images')
			-> join('images', 'playlists_images.image_id = images.id')
			-> where('playlists_images.playlist_id', $id)
			-> order_by('position', 'ASC')
			-> get();
			return $query -> result_array();
		}

		public function addSetup($udid) {
			$setup = null;
			// chequeo si ya esta en la lista de pantallas listas para configurarse (i.e. con su pin correspondiente)
			$query = $this -> db -> get_where('screens_setup', ['udid' => $udid]);
			$setup = $query -> row_array();
			// si no esta en la lista, le genero un pin y la agrego
			if (empty($setup)) {
				$pin = $this -> _generatePin();
				// chequeo que por casualidad el pin generado no este ya en uso por otra pantalla
				$oldSetup = $this -> db -> get_where('screens_setup', ['pin' => $pin]) -> row_array();
				while (!empty($oldSetup)) {
					$pin = $this -> _generatePin();
				}
				$setup = ['udid' => $udid, 'pin'=> $pin];
				$this -> db -> insert('screens_setup', $setup);
			}
			return $setup;
		}

		public function getSetup($pin) {
			return $this -> db -> get_where('screens_setup', ['pin' => $pin]) -> row_array();
		}

		private function _generatePin($length = 4) {
			$pin = "";
			// $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
			$characters = array_merge(range('0','9'));
			$max = count($characters) - 1;
			for ($i = 0; $i < $length; $i++) {
				$randomIndex = mt_rand(0, $max);
				$pin .= $characters[$randomIndex];
			}
			return $pin;
		}

	// METODOS INTERNOS / PRIVADOS

		private function _getContentData($id) {
			// obtengo grupos
			$query = $this -> db
				-> select('*')
				-> from('groups')
				-> where('parent_id', $id)
				-> get();
			$groups = $query -> result_array();
			// obtengo pantallas
			$query = $this -> db
				-> select('*')
				-> from('screens')
				-> where('parent_id', $id)
				-> get();
			$screens = $query -> result_array();
			// combino los arreglos
			$contents = array_merge($groups, $screens);
			return $contents;
		}

		private function _getPathData($id) {
			$path = array();
			$group = $this -> directory -> getNodeFromId($id);
			$pathItems = $this -> directory -> getPath($group, TRUE, TRUE);
			foreach ($pathItems as $pathItem) {
				$path[] = array(
					"id" => $pathItem['id'],
					"name" => $pathItem['name']
				);
			};
			return $path;
		}

		private function _setupDirectory() {
			$this -> directory -> insertNewTree(array("name" => "Groups and Screens", "type" => GROUP));
			$rootGroup = $this -> directory -> getRootNodes()[0];
			// OJO! editar al modificar groups_data
			$extraFields = [];
			$extraFields['id'] = $rootGroup['id'];
			$this -> db -> insert('groups_data', $extraFields);
		}
}