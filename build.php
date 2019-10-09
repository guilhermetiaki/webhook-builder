<?php

// Change to your repository path
$repository_path = '/path/to/repository';

// Change to match the secret set on GitHub
$webhook_secret = '5ecret';

function build() {
	global $repository_path;

	$out = shell_exec("cd $repository_path 2>&1 && git fetch origin master 2>&1 && git reset --hard FETCH_HEAD 2>&1");
	echo $out;
	$out = shell_exec("cd $repository_path 2>&1 && make 2>&1");
	echo $out;
}

// To test the script without having to push to GitHub: sudo -u www-data php /var/www/html/build.php
if( php_sapi_name() === 'cli') {
	build();
	exit();
}

if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
	// HTTP header 'X-Hub-Signature' is missing
	exit();
}

if (!extension_loaded('hash')) {
	// Missing 'hash' extension to check the secret code validity.
	exit();
}

list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
if (!in_array($algo, hash_algos(), TRUE)) {
	// Hash algorithm not supported.
	exit();
}

$raw_post = file_get_contents('php://input');
$expected_hash = hash_hmac($algo, $raw_post, $webhook_secret);
if (hash_equals($hash, $expected_hash)) {
	build();
	exit();
}

?>