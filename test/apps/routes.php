<?php

class Application extends Koi\Application
{
	public function index()
	{
		return "index method";
	}
	
	public function sub_route()
	{
		return "sub method";
	}
	
	public function alphabetic()
	{
		return "alphabetic method";
	}
	
	public function numeric()
	{
		return "numeric method";
	}
	
	public function alphanumeric()
	{
		return "alphanumeric method";
	}
	
	public function regex()
	{
		return "regex method";
	}
	
	public function not_found()
	{
		return "404 method";
	}
	
	public function sub_not_found()
	{
		return "sub 404 method";
	}
	
	public function route_args($number)
	{
		return "route with args method $number";
	}
}

$app = new Application();

$app->map('/'                       , 'index');
$app->map('/:alpha'                 , 'alphabetic');
$app->map('/hello/world'            , 'sub_route');
$app->map('/:numeric'               , 'numeric');
$app->map('/:alphanumeric'          , 'alphanumeric');
$app->map('/koi-([a-zA-Z0-9])+' , 'regex');
$app->map('/route_args/:numeric'    , 'route_args:args');

// Note that in order to prevent collisions 404 routes should be added at the very end.
$app->map('/:alphanumeric/:404'     , 'sub_not_found');
$app->map('/:404'                   , 'not_found');