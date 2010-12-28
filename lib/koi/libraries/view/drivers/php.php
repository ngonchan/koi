<?php
namespace Koi\View;

/**
 * View driver for handling static HTML and dynamic PHP files. Note that if you want
 * to use PHP in your views you'll have to change the extension to .php.
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
class PHP implements ViewInterface
{
	/**
	 * String that will contain the raw view data.
	 *
	 * @access public
	 * @var    string
	 */
	public $raw_view = '';
	
	/**
	 * Creates a new instance of the PHP driver and stores the raw content
	 * of the view in a variable.
	 *
	 * @author Yorick Peterse
	 * @param  string $raw_view The raw view data or a path to the view file to load.
	 * @param  array  $variables Associative array containing variables that will be sent
	 * @return object
	 */
	public function __construct($raw_view, $variables = array())
	{
		// File or raw data?
		if ( file_exists($raw_view) )
		{
			ob_start();
			
			if ( !empty($variables) )
			{
				extract($variables);
			}
			
			require_once($raw_view);
			$raw_view = ob_get_contents();
			
			ob_end_clean();
		}
		
		$this->raw_view = $raw_view;
	}
	
	/**
	 * Renders the view data and returns the results.
	 *
	 * @author Yorick Peterse
	 * @return string
	 */
	public function render()
	{
		return $this->raw_view;
	}
}