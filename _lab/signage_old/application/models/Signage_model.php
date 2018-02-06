<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signage_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this -> load -> database();
	}

	public function get_playlists() {
		$query = $this -> db
		-> get('playlists');
		return $query -> result();
	}

	public function get_playlist($id) {
		$query = $this -> db
		-> select('*')
		-> from('playlists')
		-> where('playlists.id', $id)
		-> get();
		return $query -> row();
	}

	public function get_playlist_items($playlist_id) {
		$query = $this -> db 
		-> select('images.id, images.location, playlists_images.position')
		-> from('playlists_images')
		-> join('images', 'playlists_images.image_id = images.id')
		-> where('playlists_images.playlist_id', $playlist_id)
		-> order_by('position', 'ASC')
		-> get();
		return $query -> result();
	}

	public function get_screens() {
		$query = $this -> db
		-> get('screens');
		return $query -> result();
	}

	public function get_screen($id) {
		$query = $this -> db
		-> select('*')
		-> from('screens')
		-> where('screens.id', $id)
		-> get();
		return $query -> row();
	}

	public function get_screens_with_playlists() {
		$query = $this -> db 
		-> select('screens.id AS screen_id, screens.name AS screen_name, playlists.id AS playlist_id, playlists.name AS playlist_name')
		-> from('screens')
		-> join('playlists', 'screens.playlist_id = playlists.id')
		-> get();
		return $query -> result();
	}

	public function new_screen($data) {
		return $this -> db -> insert('screens',$data);
	}

	public function delete_screen($id) {
		return $this -> db -> delete('screens', ['id' => $id]);
	}

	public function get_setup_for_pin($pin) {
		$query = $this -> db
		-> select('*')
		-> from('screens_setup')
		-> where('screens_setup.pin', $pin)
		-> get();
		return $query -> row();
	}

	public function get_setup_for_screen($screen_id) {
		$query = $this -> db
		-> select('*')
		-> from('screens_setup')
		-> where('screens_setup.screen_id', $screen_id)
		-> get();
		return $query -> row();
	}

	public function new_screen_setup($data) {
		return $this -> db -> insert('screens_setup',$data);
	}
}