<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	public function __construct($rules = []) {
		parent :: __construct($rules);
		// mensajes para cada regla creada
		$this -> set_message('is_boolean', '{field} must be a boolan.');
	}

	public function is_boolean($str) {
		return filter_var($str, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== NULL;
	}
}