<?php
$path = dirname(dirname(__DIR__)) . '/autoload.php';
if (file_exists($path)) {
	require_once($path);
} else {
	$path = __DIR__ . '/vendor/autoload.php';
	if (file_exists($path)) {
		require_once($path);
	} else {
		echo 'No vendor/autoload.php file found for testing.' . PHP_EOL;
		die();
	}
}
