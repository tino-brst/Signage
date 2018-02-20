<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getImage($id) {
		$query = $this -> db -> get_where('images', ['id' => $id]);
		$image = $query -> row_array(); 
		return $image;
	}

	public function getImages() {
		$query = $this -> db -> get('images');
		$images = $query -> result_array();
		return $images;
	}

	public function addImage($location) {
		$created = $this -> db -> insert('images', ['location' => 'public/images/' . $location]);
		return $created ? $this -> db -> insert_id() : NULL;
	}

	public function deleteImage($id) {
		$where = ['id' => $id];
		$image = $this -> db -> get_where('images', $where) -> row_array();
		$deleted = unlink(FCPATH . $image['location']);
		$deleted &= $this -> db -> delete('images', $where);
		return $deleted;
	}
}