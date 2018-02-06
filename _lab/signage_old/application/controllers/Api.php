<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this -> load -> model('Signage_model','model');
	}

	public function get_screens($id = null) {
		$data = null;
		if ($id == null) {
			$data = $this -> model -> get_screens();
		} else {
			$data = $this -> model -> get_screen($id);
		}
		echo json_encode($data);
	}

	public function get_screens_with_playlists() {
		$data = $this -> model -> get_screens_with_playlists();
		echo json_encode($data);
	}

	public function get_playlists($id = null) {
		if ($id == null) {
			$data = $this -> model -> get_playlists();
		} else {
			$data = $this -> model -> get_playlist($id);
		}
		echo json_encode($data);
	}

	public function get_playlist_items($id) {
		$data = $this -> model -> get_playlist_items($id);
		echo json_encode($data);
	}

	public function get_playlist_for_screen($screen_id) {
		$screen = $this -> model -> get_screen($screen_id);
		$playlist = $this -> model -> get_playlist($screen -> playlist_id);
		$playlist -> items = $this -> model -> get_playlist_items($playlist -> id);
		echo json_encode($playlist);
	}

	public function new_screen() {
		$data = array('id' => $_POST['screenID'], 'playlist_id' => $_POST['playlistID'], 'name'=>$_POST['name']);
		echo $this -> model -> new_screen($data);
	}

	public function delete_screen($id) {
		echo $this -> model -> delete_screen($id);
	}

	public function get_setup_pin($screen_id) {
		$pin = null;
		// chequeo si la pantalla ya tiene un pin de setup asignado
		$screenSetup = $this -> model -> get_setup_for_screen($screen_id);
		if ($screenSetup != null) {
			// si ya tiene uno, uso ese
			$pin = $screenSetup -> pin;
		} else {
			// si no, genero uno nuevo
			$pin = $this -> generate_pin();
			// chequeo que por casualidad el pin generado no este en uso por otra pantalla
			while ($this -> model -> get_setup_for_pin($pin) != null) {
				$pin = $this -> generate_pin();
			}
			// agrego id de pantalla y su pin a las pantallas por configurar
			$data = array('screen_id' => $screen_id, 'pin'=> $pin);
			$this -> model -> new_screen_setup($data);
			// devuelvo el pin generado
		}
		echo $pin;
	}

	public function get_screen_for_pin($pin) {
		echo $this -> model -> get_setup_for_pin($pin) -> screen_id;
	}

	// metodos privados

	private function generate_pin($length = 4) {
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

}