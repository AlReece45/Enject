<?php

	error_reporting(E_ALL);
	$base = realpath(dirname(__FILE__) . '/../../');

	set_include_path("$base/src"
		. PATH_SEPARATOR . "$base/dev/PHPUnit"
		. PATH_SEPARATOR . get_include_path());
