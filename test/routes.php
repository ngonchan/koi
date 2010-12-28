<?php
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/apps/routes.php';

use Waffles\Test as Test;

Test::group("Test the routes system", function()
{
	Test::add("Test a regular route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/', $app);
		
		$test->expects($app_response)->to()->equal('index method');
	});
	
	Test::add("Test a sub route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/hello/world', $app);

		$test->expects($app_response)->to()->equal('sub method');
	});
	
	Test::add("Test an alphabetical route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/abc', $app);
		
		$test->expects($app_response)->to()->equal('alphabetic method');
	});
	
	Test::add("Test a numeric route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/123', $app);
		
		$test->expects($app_response)->to()->equal('numeric method');
	});
	
	Test::add("Test an alphanumeric route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/abc123', $app);
		
		$test->expects($app_response)->to()->equal('alphanumeric method');
	});
	
	Test::add("Test a regex route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/koi-123', $app);

		$test->expects($app_response)->to()->equal('regex method');
	});
	
	Test::add("Test a 404 route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/does-not-exist', $app);

		$test->expects($app_response)->to()->equal('404 method');
	});
	
	Test::add("Test a sub 404 route", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/sublevel/does-not-exist', $app);

		$test->expects($app_response)->to()->equal('sub 404 method');
	});
	
	Test::add("Test a route with arguments", function($test)
	{
		global $app;
		
		// Fake a request
		$app_response = run_application('/route_args/123', $app);

		$test->expects($app_response)->to()->equal('route with args method 123');
	});
});

// Run all our tests
Test::run_all();