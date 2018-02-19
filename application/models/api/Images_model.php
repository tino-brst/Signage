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
		$created = $this -> db -> insert('images', ['location' => $location]);
		return $created ? $this -> db -> insert_id() : NULL;
	}

	public function deleteImage($id) {
		$where = ['id' => $id];
		return $this -> db -> delete('images', $where);
	}
}