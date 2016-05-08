<?php
$data = [
	'success' => $this->success,
	'state' => $this->state,
	'event' => 'pernaGoogleAuth'
];

if ( !$this->success && $this->errorMessage )
	$data['error'] = $this->errorMessage;

$json = json_encode( $data );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Please Wait...</title>
</head>
<body>
	<h1>Please Wait...</h1>
	<script type="text/javascript">
		(function () {
			var opener = window.opener;
			if ( !opener ) {
				alert("An error occurred. Please close this window yourself.");
				console.error("window.opener is not defined. This module only runs in Popup windows.");
				return;
			}

			var data = JSON.parse('<?= $json; ?>');
			opener.postMessage( data, '*' );
		})();
	</script>
</body>
</html>