<?php
require_once __DIR__ . '/../lib/koi.php';

use Koi\CLI as CLI;

// Create our application
class Application extends Koi\Application
{
	public function __construct()
	{
		CLI::$banner = 'Usage: php cli.php [command] [switches]';
		CLI::set_opt(NULL       , 'help', 'Show this help message');
		CLI::set_opt('show_name', 'name', 'Specify your name', TRUE);
	}
	
	public function index()
	{
		CLI::show_help();
	}
	
	public function name()
	{
		if ( CLI::get_opt('help') === NULL )
		{
			CLI::show_help(); exit;
		}
		
		$name = CLI::get_opt('name');
		
		if ( $name === FALSE )
		{
			CLI::terminate('show_name', 'You need to specify a name');
		}
		
		echo "Your name is $name" . PHP_EOL;
	}
}

$app = new Application();

$app->map('/'    , 'index');
$app->map('/name', 'name');
$app->run();