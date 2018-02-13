<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function to_boolean(&$str) {
	$str = filter_var($str, FILTER_VALIDATE_BOOLEAN);
}