<?php
require_once __DIR__ . '/../../helper.php';

use Waffles\Test as Test;

// Specify our default view engine
Koi\View\View::$default_driver = 'dwoo';

Test::group("Test the Dwoo view system", function()
{
	Test::add("Render a basic Dwoo template", function($test)
	{
		$v = new Koi\View\View("Hello, world!", NULL);
		$v = $v->render();
		
		$test->expects($v)->to()->equal("Hello, world!");
	});
	
	Test::add("Render a Dwoo variable", function($test)
	{
		$v = new Koi\View\View('Hello, {$name}', array('name' => 'Yorick'));
		$v = $v->render();
		
		$test->expects($v)->to()->equal("Hello, Yorick");
	});
	
	Test::add("Render a basic Dwoo template from a file", function($test)
	{
		$v = new Koi\View\View(__DIR__ . '/dwoo/simple.tpl');
		$v = $v->render();
		
		$test->expects($v)->to()->equal('Hello, world!');
	});
	
	Test::add("Render an advanced Dwoo template from a file", function($test)
	{
		$v = new Koi\View\View(__DIR__ . '/dwoo/advanced.tpl', array('name' => 'Yorick'));
		$v = $v->render();
		
		$test->expects($v)->to()->equal('Hello, Yorick!');
	});
});

Test::run_all();