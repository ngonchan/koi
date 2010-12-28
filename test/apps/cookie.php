<?php
require_once __DIR__ . '/../../lib/koi.php';

use Koi\Cookie as Cookie;

class Application extends Koi\Application
{
	public function index()
	{
		$c = new Cookie('name', 'Yorick Peterse', NULL, '/', time() + 86400);
		$c->save();
		
		return $c->value;
	}
	
	public function name()
	{
		$c = new Cookie('name');

		return $c->value;
	}
	
	public function delete()
	{
		$c = new Cookie('name');
		
		if ( $c->destroy() )
		{
			return "Cookie destroyed";
		}
		else
		{
			return "Failed to destroy the cookie";
		}
	}
}

$app = new Application();

$app->map('/'      , 'index');
$app->map('/name'  , 'name');
$app->map('/delete', 'delete');
$app->run();