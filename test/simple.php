<?php
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/apps/simple.php';

use Waffles\Test as Test;

Test::group("Test a very basic application", function()
{
	Test::add("Run a simple hello world example", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/', $app);
		
		$test->expects($app_response)->to()->equal('Hello, index!');
	});
	
	Test::add("Run a method with a parameter", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/param/yorick/peterse', $app);

		$test->expects($app_response)->to()->equal('hello yorick peterse');
	});
});

// Run all our tests
Test::run_all();