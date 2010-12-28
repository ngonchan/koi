<?php
require_once __DIR__ . '/../../helper.php';

use Waffles\Test as Test;

Test::group("Test the PHP view system", function()
{
	Test::add("Render a basic view without HTML", function($test)
	{
		$v = new Koi\View\View('Hello, world!');
		$v = $v->render();
		
		$test->expects($v)->to()->equal('Hello, world!');
	});
	
	Test::add("Render a view with some HTML", function($test)
	{
		$v = new Koi\View\View("<strong>Hello, world!</strong>");
		$v = $v->render();
		
		$test->expects($v)->to()->equal("<strong>Hello, world!</strong>");
	});
	
	Test::add("Render a simple view from a file", function($test)
	{
		$path = __DIR__ . '/php/basic.php';
		$v    = new Koi\View\View($path);
		$v    = $v->render();
		
		$test->expects($v)->to()->equal("<strong>Hello, world!</strong>");
	});
	
	Test::add("Render a view with embedded PHP", function($test)
	{
		$path = __DIR__ . '/php/advanced.php';
		$v    = new Koi\View\View($path, array('name' => 'Yorick Peterse'));
		$v    = $v->render();

		$test->expects($v)->to()->equal("<strong>Hello, Yorick Peterse!</strong>");
	});
});

Test::run_all();