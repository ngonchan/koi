<?php
require_once __DIR__ . '/../../helper.php';

use Waffles\Test as Test;

define('LOG_DIR', __DIR__ . '/logs');

Test::group("Test the Dwoo view system", function()
{
	Test::add("Initialize a new logger using the File driver", function($test)
	{
		$logger = new Koi\Log\Log('file', array('directory' => LOG_DIR));
		
		$test->expects($logger)->to_not()->be_empty();
		$test->expects($logger)->to()->be_type_of('object');
	});
	
	Test::add("Write some data to a log file", function($test)
	{
		$logger = new Koi\Log\Log('file', array('directory' => LOG_DIR));
		$wrote  = $logger->write("Hello, world!");

		$test->expects($logger)->to_not()->be_empty();
		$test->expects($logger)->to()->be_type_of('object');
		
		$test->expects($wrote)->to()->be_type_of('boolean');
		$test->expects($wrote)->to()->equal(TRUE);
	});
});

Test::run_all();