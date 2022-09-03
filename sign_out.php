<?php
	require_once 'basic_functions_file.php'; // file containing a few basic/commonly used functions

	sessionDestroy(); // function destroys active session/cookies
	header("Location: Main_Index.php");
	exit();
?>