<?php
namespace Koi\View;

/**
 * View driver description goes in here.
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
class _Template implements ViewInterface
{
	/**
	 * String that will contain the raw view data.
	 *
	 * @access public
	 * @var    string
	 */
	public $raw_view = '';
	
	/**
	 * Variable containing a new instance of the template system.
	 *
	 * @access public
	 * @var    object
	 */
	public $_template = NULL;
	
	/**
	 * Creates a new instance of the driver and stores the raw content
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