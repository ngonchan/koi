<?php
/**
 * Main file that loads all the sub classes required by Waffles. You should
 * include this file in your tests instead of individual files.
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

/**
 * Simple function that loads files from the "waffles" directory relative to
 * the current file ("waffles.php"). This means you no longer have to do
 * "require_once(dirname(__FILE__) . '/filename.php');".
 *
 * @author Yorick Peterse
 * @param  String $path Relative path to the file that has to be loaded.
 * @return Void
 */
function require_waffle($path)
{
	require_once(__DIR__ . '/waffles/' . $path . '.php');
}

/**
 * Function that outputs the specified variable followed by a newline at the end.
 *
 * @author Yorick Peterse
 * @param  String $value The value to echo to the console.
 * @param  Bool $double_line Boolean that specificies if a double linebreak should be used.
 * @return Void
 */
function puts($value = NULL, $double_line = FALSE)
{
	$message = $value . PHP_EOL;
	
	if ( $double_line === TRUE )
	{
		$message .= PHP_EOL;
	}
	
	echo $message;
}

// Configure various aspects of Waffles using, you guessed it right, CONSTANTS!
define('WAFFLES_VERSION'  ,  0.1);
define('WAFFLES_DATE'     , '08-12-2010');
define('WAFFLES_WEBSITE'  , 'https://github.com/yorickpeterse/waffles');
define('WAFFLES_SEPARATOR', "=====================================");

// Load all of our required classes
require_waffle('helpers/colors');
require_waffle('requirement');
require_waffle('base');
