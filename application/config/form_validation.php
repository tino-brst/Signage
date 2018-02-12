<?php

$config = [
	'playlists/index_get' => 
		[
			'field' => 'id',
			'rules' => [
				'required',
				'is_natural'
			]
		],
		[
			'field' => 'includeItems',
			'rules' => [
				'is_boolean'
			]
		]
];