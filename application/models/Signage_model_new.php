<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signage_model_new extends CI_Model {

	public function __construct() {
		parent::__construct();
		// cargo base de datos
		$this -> load -> database();
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

		public function updatePlaylist($id, $newValues) {
			// proceso update como transaccion (dado que incluye varios cambios en la BD)
			$this -> db -> trans_start();

			if ($newValues['name'] != NULL) {
				$data  = ['name' => $newValues['name']];
				$where = ['id' => $id];
				$this -> db -> update('playlists', $data, $where);
			}
			if ($newValues['items'] != NULL) {
				$this -> _updatePlaylistItems($id, $newValues['items']);
			}

			$this -> db -> trans_complete();
			return $this -> db -> trans_status();
		}

		public function deletePlaylist($id) {
			$where = ['id' => $id];
			return $this -> db -> delete('playlists', $where);
		}

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