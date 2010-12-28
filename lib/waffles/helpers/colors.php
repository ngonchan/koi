<?php

namespace Waffles;

/**
 * Class that can be used to colorize the output sent to the command line.
 * All the methods in this class are static so you don't have to create an object
 * in order to work with colors.
 *
 * @author    Yorick Peterse <info [at] yorickpeterse [dot] com>
 * @link      http://yorickpeterse.com/
 * @package   Waffles
 * @license   MIT License
 * @copyright Copyright (c) 2010, Yorick Peterse
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
class Colors
{
	/**
	 * Array containing all our fancy colors.
	 *
	 * @access Private
	 * @static
	 * @var    Array
	 */
	private static $colors = array(
		'red'		  => "\033[0;31m",
		'green'   => "\033[0;32m",
		'yellow'  => "\033[1;33m",
		'blue'    => "\033[0;34m",
	);
	
	/**
	 * Sets the foreground color of the input string to the called method's name
	 * if it is a valid color.  If it is not, it will simply return the string.
	 *
	 * @author Dan Horrigan
	 * @param  String $method The method name (a valid color)
	 * @param  Array  $args   The method arguments
	 * @return String
	 */
	public static function __callStatic($method, $args)
	{
		if ( !array_key_exists($method, self::$colors) )
		{
			return $args[0];
		}
		
		return self::$colors[$method] . $args[0] . "\033[0m";
	}
}