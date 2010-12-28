<?php
require_once __DIR__ . '/../lib/koi.php';

// Create our application
class Application extends Koi\Application
{
	public function index()
	{
		return "Hello, world!";
	}
	
	public function not_found()
	{
		return array("The requested page could not be found", 404);
	}
}

$app = new Application();

$app->map('/'    , 'index');
$app->map('/:404', 'not_found');
$app->run();