<?php

$data = [
	'success' => true
];

if ( $this->data !== null )
	$data['data'] = $this->data;

return $data;