<?php
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/apps/returns.php';

use Waffles\Test as Test;

Test::group("Test if methods can return customized values, HTTP status codes and content types", function()
{	
	Test::add('return: array("index method", 200, "text/html")', function($test)
	{
		global $app;
		$app_response = run_application('/', $app);
		
		$test->expects($app_response)->to()->equal("index method");
		$test->expects($app->last_http_status)->to()->equal('HTTP/1.1 200 OK');
		$test->expects($app->last_content_type)->to()->equal('text/html');
	});
	
	Test::add('return: array("not_found method", 404, "text/html")', function($test)
	{
		global $app;
		$app_response = run_application('/not_found', $app);
		
		$test->expects($app_response)->to()->equal("not_found method");
		$test->expects($app->last_http_status)->to()->equal('HTTP/1.1 404 Not Found');
		$test->expects($app->last_content_type)->to()->equal('text/html');
	});
	
	Test::add('return: array("no_content method", 200)', function($test)
	{
		global $app;
		$app_response = run_application('/no_content', $app);
		
		$test->expects($app_response)->to()->equal("no_content method");
		$test->expects($app->last_http_status)->to()->equal('HTTP/1.1 200 OK');
		$test->expects($app->last_content_type)->to()->equal('text/html');
	});
	
});

Test::run_all();