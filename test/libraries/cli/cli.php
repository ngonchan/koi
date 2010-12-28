<?php
require_once __DIR__ . '/../../helper.php';

use Waffles\Test as Test;
use Koi\CLI as CLI;

Test::group("Test if CLI applications can be created using Koi", function()
{
	Test::add("Generate the correct CLI URI", function($test)
	{
		$GLOBALS['argv'] = array('cli.php', 'hello world');
		$cli_uri         = CLI::uri();

		$test->expects($cli_uri)->to()->equal("/hello/world/");
	});
	
	Test::add("Get the values of a set of options", function($test)
	{
		CLI::set_opt(NULL, 'age', 'Get a person\'s age');
		CLI::set_opt(NULL, 'n'  , 'Get the name of a person');
		CLI::set_opt(NULL, 'd'  , 'Description');
		
		$GLOBALS['argv'] = array('cli.php', 'hello', '--age=10', '-n', 'yorick', '-x', '-d', 'hello world');
		
		$age  = CLI::get_opt('age');
		$name = CLI::get_opt('n');
		$desc = CLI::get_opt('d');
		$x    = CLI::get_opt('x'); // -x Reverts to NULL as there's no value assigned to it.

		$test->expects($age)->to()->equal('10');
		$test->expects($name)->to()->equal('yorick');
		$test->expects($desc)->to()->equal('hello world');
		
		$test->expects($x)->to()->be_type_of('NULL');
		$test->expects($x)->to()->equal(NULL);
	});
	
	Test::add("Get all options along with their values", function($test)
	{
		CLI::set_opt(NULL, 'name', 'Enter your name');
	
		$GLOBALS['argv'] = array('cli.php', 'hello', '--name', 'yorick');
		$opts            = CLI::opts();

		$test->expects($opts)->to()->be_type_of('array');
		$test->expects($opts['name']['value'])->to()->equal('yorick');
	});
	
	Test::add("Get all options along with their values for a specific method", function($test)
	{
		CLI::set_opt('hello', 'name', 'Enter your name');
	
		$GLOBALS['argv'] = array('cli.php', 'hello', '--name', 'yorick');
		$opts            = CLI::opts('hello');

		$test->expects($opts)->to()->be_type_of('array');
		$test->expects($opts['name']['value'])->to()->equal('yorick');
	});
	
	Test::add("Check if all required options are set", function($test)
	{
		CLI::set_opt(NULL, 'name', 'Enter your name', TRUE);
	
		$GLOBALS['argv'] = array('cli.php', 'hello', '--name', 'yorick');
		$has_opts        = CLI::has_opts();

		$test->expects($has_opts)->to()->be_type_of('boolean');
		$test->expects($has_opts)->to()->equal(TRUE);
	});
});

Test::run_all();