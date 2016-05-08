<?php

$viewDir = __DIR__ . '/../view';

return [
	'template_path_stack' => [
		'perna' => $viewDir
	],
	'template_map' => [
		'api/default' => $viewDir . '/default/default.phtml',
		'api/google-auth-callback' => $viewDir . '/default/google-auth-callback.php',
		'api/layout/empty' => $viewDir . '/layout/empty.php'
	]
];