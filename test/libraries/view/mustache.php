<?php
require_once __DIR__ . '/../../helper.php';

use Waffles\Test as Test;

// Specify our default view engine
Koi\View\View::$default_driver = 'mustache';

Test::group("Test the Mustache view system", function()
{
	Test::add("Render a basic Mustache template", function($test)
	{
		$v = new Koi\View\View("Hello, world!");
		$v = $v->render();
		
		$test->expects($v)->to()->equal("Hello, world!");
	});
	
	Test::add("Render a Mustache variable", function($test)
	{
		$v = new Koi\View\View('Hello, {{name}}', array('name' => 'Yorick'));
		$v = $v->render();
		
		$test->expects($v)->to()->equal("Hello, Yorick");
	});
	
	Test::add("Render a basic Mustache template from a file", function($test)
	{
		$v = new Koi\View\View(__DIR__ . '/mustache/basic.mustache');
		$v = $v->render();
		
		$test->expects($v)->to()->equal('Hello, world!');
	});
	
	Test::add("Render an advanced Mustache template from a file", function($test)
	{
		$v = new Koi\View\View(__DIR__ . '/mustache/advanced.mustache', array('name' => 'Yorick'));
		$v = $v->render();
		
		$test->expects($v)->to()->equal('Hello, Yorick!');
	});

});

Test::run_all();