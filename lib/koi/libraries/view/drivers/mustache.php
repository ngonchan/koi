<?php
namespace Koi\View;

require_once __DIR__ . '/../vendor/mustache/Mustache.php';

/**
 * View driver for the Mustache template system.
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
class Mustache implements ViewInterface
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
	public $mustache = NULL;
	
	/**
	 * Array containing the variables passed to the view.
	 *
	 * @access public
	 * @var    array
	 */
	public $variables = array();
	
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
		$this->mustache = new \Mustache();
		
		// File or raw data?
		if ( file_exists($raw_view) )
		{
			$raw_view = $this->load_file($raw_view);
			
			if ( $raw_view === FALSE )
			{
				throw new \Koi\Exception\ViewException("Failed to open the Mustache template located at $path");
			}
		}
		
		$this->variables = $variables;
		$this->raw_view  = $raw_view;
	}
	
	/**
	 * Renders the view data and returns the results.
	 *
	 * @author Yorick Peterse
	 * @return string
	 */
	public function render()
	{
		return $this->mustache->render($this->raw_view, $this->variables);
	}
	
	/**
	 * Retrieves the content from a Mustache template file and returns it.
	 *
	 * @author Yorick Peterse
	 * @param  string $path The path to the Mustache template to load.
	 * @return string
	 */
	private function load_file($path)
	{
		if ( is_file($path) AND file_exists($path) )
		{
			if ( $handle = fopen($path, 'r') )
			{
				if ( $content = fread($handle, filesize($path)) )
				{
					fclose($handle);
					return $content;
				}
			}
		}
		
		return FALSE;
	}
}