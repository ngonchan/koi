<?php

class Application extends Koi\Application
{
	public function index()
	{
		return array("index method", 200, "text/html");
	}
	
	public function not_found()
	{
		return array("not_found method", 404, "text/html");
	}
	
	public function no_content()
	{
		return array("no_content method", 200);
	}
}

$app = new Application();

$app->map('/'          , 'index');
$app->map('/not_found' , 'not_found');
$app->map('/no_content', 'no_content');