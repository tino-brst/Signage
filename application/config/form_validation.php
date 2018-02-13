<?php

$config = [
	// PLAYLISTS
		'playlists/index_get' => [
			[
				'field' => 'id',
				'rules' => 'is_natural'
			],
			[
				'field' => 'includeItems',
				'rules' => 'is_boolean'
			]
		],
		'playlists/index_put' => [
			[
				'field' => 'name',
				'rules' => 'required'
			]
		],
		'playlists/index_post' => [
			[
				'field' => 'id',
				'rules' => 'required|is_natural'
			]
		],
		'playlists/index_delete' => [
			[
				'field' => 'id',
				'rules' => 'required|is_natural'
			]
		],
];