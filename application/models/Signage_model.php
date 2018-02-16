<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signage_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// cargo base de datos
		$this -> load -> database();
	}

	// SETUP

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
}