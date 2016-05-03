<?php

return [
	'allowed_origins' => [
		'*.perna.dev',
		'perna.dev',
		'http://perna.dev',
		'https://perna.dev',
		'localhost',
		'chrome-extension://*'
	],

	'allowed_methods' => [
		'GET',
		'POST',
		'PUT',
		'DELETE',
		'OPTIONS'
	],

	'allowed_headers' => [
		'Content-Type'
	]
];