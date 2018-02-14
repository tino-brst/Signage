<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signage_model_new extends CI_Model {

	public function __construct() {
		parent::__construct();
		// cargo base de datos
		$this -> load -> database();
		$this -> _setupDirectory();
	}
	
	// DIRECTORIO

		// GRUPOS

			public function getGroup($id, $includePath = NULL, $includeContent = NULL) {
				$group = $this -> _getNode($id, 'group');
				if ($group !== NULL) {
					if ($includePath) {
						$group['path'] = $this -> _getPath($id);
					}
					if ($includeContent) {
						$group['content'] = $this -> _getDirectChildren($id);
					}						
				}
				return $group;
			}

			public function createGroup($parentId, $name, $extraFields) {
				return $this -> _appendNewChild($parentId, 'group', $name, $extraFields);
			}

			public function updateGroup($id, $name, $extraFields) {
				return $this -> _updateNode($id, $name, $extraFields);
			}

			public function deleteGroup($id) {
				return $this -> _deleteNode($id);
			}

		// PANTALLAS

			public function createScreen($parentId) {
				$extraFields = [
					'playlist_id' => 1,
					'udid' => rand()
				];
				$this -> _appendNewChild($parentId, 'screen', 'dummy screen :P - ' . rand(), $extraFields);
			}

			public function updateScreen($id) {
				$extraFields = [
					'playlist_id' => 2,
					'udid' => rand()
				];
				$this -> _updateNode($id, "kylo" . rand(), $extraFields);
			}

		// Helpers privados

			private function _setupDirectory() {
				if ($this -> _getRootNode() === NULL) {
					// nuevo nodo raiz
					$parentId = 0;
					$leftValue = 1;
					$rightValue = 2;
					$this -> _addNode($parentId, $leftValue, $rightValue, 'group', 'Groups and Screens');
				}
			}

			private function _getRootNode() {
				$query = $this -> db -> get_where('directory_copy', ['parent_id' => 0]);
				return $query -> row_array(); 
			}

			private function _getNode($id, $type) {
				$where = ['id' => $id];
				$typeView = $type === 'group' ? 'groups_copy' : 'screens_copy';
				$query = $this -> db -> get_where($typeView, $where);
				return  $query -> row_array();
			}

			private function _getNodeCore($id) {
				$query = $this -> db -> get_where('directory_copy', ['id' => $id]);
				return $query -> row_array();
			}

			private function _getDirectChildren($id) {
				$where = ['parent_id' => $id];
				$query = $this -> db -> get_where('groups_copy', $where);
				return $query -> result_array();
			}

			private function _getPath($id) {;
				$node = $this -> _getNodeCore($id);

				$query = $this -> db
					-> select('id, name')
					-> where('left_value' . ' <= ' . $node['left_value'] . ' AND ' . 'right_value' . ' >= ' . $node['right_value'])
					-> order_by('left_value')
					-> get('directory_copy');

				$path = $query -> result_array();

				return $path;
			}

			private function _addNode($parentId, $leftValue, $rightValue, $type, $name, $extraFields = []) {
				$newNode['parent_id'] = (int) $parentId;
				$newNode['left_value'] = (int) $leftValue;
				$newNode['right_value'] = (int) $rightValue;
				$newNode['type'] = $type;
				$newNode['name'] = $name;

				// proceso operacion como transaccion (afecta a varias tablas en la BD)
				$this -> db -> trans_start();

				$this -> db -> insert('directory_copy', $newNode);
				$newNodeId = $this -> db -> insert_id();
				$extraFields['id'] = $newNodeId;
				$typeTable = $type === 'group' ? 'groups_data_copy' : 'screens_data_copy';
				$this -> db -> insert($typeTable, $extraFields);

				$this -> db -> trans_complete();
				$created = $this -> db -> trans_status();
				return $created ? $newNodeId : NULL;
			}

			private function _appendNewChild($parentId, $type, $name, $extraFields = []) {
				$parentNode = $this -> _getNodeCore($parentId);

				$parentId = $parentNode['id'];
				$leftValue = $parentNode['right_value'];
				$rightValue = $leftValue + 1;
				$nodeWidth = 2;  // (rightValue - leftValue) + 1 = 2 para todo nuevo nodo
				
				$newNodeId = NULL;
				if ($this -> _shiftNodes($leftValue, $nodeWidth)) {
					$newNodeId = $this -> _addNode($parentId, $leftValue, $rightValue, $type, $name, $extraFields);
				}

				return $newNodeId;
			}

			private function _updateNode($id, $name = NULL, $extraFields = []) {
				$node = $this -> _getNodeCore($id);

				// proceso operacion como transaccion (afecta a varias tablas en la BD)
				$this -> db -> trans_start();

				$where = ['id' => $id];
				if ($name !== NULL) {
					$newValue = ['name' => $name];
					$this -> db -> update('directory_copy', $newValue, $where);
				}
				if (!empty($extraFields)) {
					$typeTable = $node['type'] === 'group' ? 'groups_data_copy' : 'screens_data_copy';
					$this -> db -> update($typeTable, $extraFields, $where);
				}

				$this -> db -> trans_complete();
				return $this -> db -> trans_status();
			}

			private function _deleteNode($id) {
				$node = $this -> _getNodeCore($id);

				// proceso operacion como transaccion (afecta a varias tablas en la BD)
				$this -> db -> trans_start();

				$where = [
					'left_value' . ' >=' => $node['left_value'],
					'left_value' . ' <=' => $node['right_value'],
				];
				$this -> db -> delete('directory_copy', $where);

				$nodeWidth = $node['left_value'] - $node['right_value'] - 1;
				$this -> _shiftNodes($node['right_value'] + 1, $nodeWidth);

				$this -> db -> trans_complete();
				return $this -> db -> trans_status();
			}

			private function _shiftNodes($startingPoint, $changeVal) {
				// proceso operacion como transaccion (afecta a varias filas en la BD)
				$this -> db -> trans_start();

				$this -> db -> set('left_value', 'left_value' . '+' . (int) $changeVal, FALSE);
				$this -> db -> where('left_value' . ' >=', (int) $startingPoint);
				$this -> db -> update('directory_copy');

				$this -> db -> set('right_value', 'right_value' . '+' . (int) $changeVal, FALSE);
				$this -> db -> where('right_value' . ' >=', (int) $startingPoint);
				$this -> db -> update('directory_copy');

				$this -> db -> trans_complete();
				return $this -> db -> trans_status();
			}

	// PLAYLISTS

		public function getPlaylist($id = NULL, $includeItems = NULL) {
			$query = $this -> db -> get_where('playlists', ['id' => $id]);
			$playlist = $query -> row_array(); 
			if (!empty($playlist) && $includeItems) {
				$playlist['items'] = $this -> _getPlaylistItems($playlist['id']);
			}
			return $playlist;
		}

		public function getPlaylists($includeItems = NULL) {
			$query = $this -> db -> get('playlists');
			$playlists = $query -> result_array();
			if (!empty($playlists) && $includeItems) {
				foreach ($playlists as &$playlist) {
					$playlist['items'] = $this -> _getPlaylistItems($playlist['id']);
				}
			}
			return $playlists;
		}

		public function createPlaylist($name) {
			$created = $this -> db -> insert('playlists', ['name' => $name]);
			return $created ? $this -> db -> insert_id() : NULL;
		}

		public function updatePlaylist($id, $name = NULL, $items = NULL) {
			// proceso update como transaccion (dado que incluye varios cambios en la BD)
			$this -> db -> trans_start();

			if ($name !== NULL) {
				$data  = ['name' => $name];
				$where = ['id' => $id];
				$this -> db -> update('playlists', $data, $where);
			}
			if ($items !== NULL) {
				$this -> _updatePlaylistItems($id, $items);
			}

			$this -> db -> trans_complete();
			return $this -> db -> trans_status();
		}

		public function deletePlaylist($id) {
			$where = ['id' => $id];
			return $this -> db -> delete('playlists', $where);
		}

		// Helpers privados

			private function _getPlaylistItems($id) {
				$query = $this -> db 
				-> select('images.id, images.location')
				-> from('playlists_images')
				-> join('images', 'playlists_images.image_id = images.id')
				-> where('playlists_images.playlist_id', $id)
				-> order_by('position', 'ASC')
				-> get();
				return $query -> result_array();
			}

			private function _updatePlaylistItems($id, $items) {
				// elimino items actuales
				$where = ['playlist_id' => $id];
				$this -> db -> delete('playlists_images', $where);
				// inserto nuevos items
				foreach ($items as $key => $item) {
					$data = [
						'playlist_id' => $id, 
						'image_id' => $item['id'],
						'position' => $key
					];
					$this -> db -> insert('playlists_images', $data);
				}
			}
}