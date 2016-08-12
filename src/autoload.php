<?php

spl_autoload_register(function($class) {
	$src = 'src/' . $class . '.php';

	if (is_file($src)) {
		require_once($src);
		return true;
	}

	return false;
});
