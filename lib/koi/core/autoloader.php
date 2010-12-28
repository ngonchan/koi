<?php

namespace Koi;

/**
 * The autoloader class is the bread and butter of Koi when it comes to loading classes.
 * By registering a class and a path name you don't have to manually require it, this will be
 * done whenever it's needed.
 *
 * Adding a new path/class combination is fairly straightforward and has the following syntax:
 *
 * @Autoloader::add(class, path);@
 *
 * For example, if we wanted to register the class "World" defined under the "Hello" namespace
 * we'd do the following:
 *
 * @Autoloader::add('Hello\World', 'path\to\hello_world.php');@
 *
 * From this point on if the Hello\World class is called it will be loaded from path/to/hello_world.php
 * (if this hasn't already been done). If a class is already loaded it will simply be ignored.
 *
 * @author  Yorick Peterse
 * @link    http://yorickpeterse.com/
 * @licence MIT License
 * @package Koi
 *
 * Copyright (c) 2010, Yorick Peterse
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class Autoloader
{
	/**
	 * Associative array containing the class/path names as a key/value pair.
	 *
	 * @acces private
	 * @var   array
	 */
	public static $load_paths = array();
	
	/**
	 * Static method used for registering a new class and it's load path.
	 * This method will raise an exception in case the path doesn't exist.
	 *
	 * @author Yorick Peterse
	 * @param  string $class The name of the class along with the namespace (if there is any).
	 * @param  string $path The path to the file that contains the class (including the .php extension).
	 * @return void
	 */
	public static function add($class, $path)
	{
		if ( !file_exists($path) )
		{
			throw new Exception\AutoloaderException("$path does not exist.");
		}
		
		self::$load_paths[$class] = $path;
	}
	
	/**
	 * Retrieve the path for the specified class name. Please note that in order to stay
	 * compatible with other classes that don't belong to Koi and are loaded using the
	 * spl autoloader this method will return FALSE instead of throwing an exception.
	 *
	 * @author Yorick Peterse
	 * @param string $class The name of the class of which the path should be retrieved.
	 * @return string
	 */
	public static function get($class)
	{
		if ( !isset(self::$load_paths[$class]) )
		{
			return FALSE;
		}
		
		return self::$load_paths[$class];
	}
}