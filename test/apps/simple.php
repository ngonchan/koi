<?php

class Application extends Koi\Application
{
	public function index()
	{
		return "Hello, index!";
	}
	
	public function param($name, $surname)
	{
		return "hello $name $surname";
	}
}

$app = new Application();

$app->map('/'     , 'index');
$app->map('/param', 'param:args');