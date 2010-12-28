<?php

/**
 * Function called whenever a class is loaded without it being required.
 *
 * @see    Koi\Autoloader()
 * @author Yorick Peterse
 * @param  string $class The name of the class that has to be loaded.
 * @return void
 */
function koi_autoload($class)
{
	$path = Koi\Autoloader::get($class);
	
	if ( $path !== FALSE )
	{
		require_once $path;
	}
}

spl_autoload_register('koi_autoload');