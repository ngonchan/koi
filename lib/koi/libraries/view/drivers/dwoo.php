<?php
namespace Koi\View;

require_once __DIR__ . '/../vendor/dwoo/dwooAutoload.php';

/**
 * View driver for handling Dwoo templates.
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
class Dwoo implements ViewInterface
{
	/**
	 * String that will contain the raw view data.
	 *
	 * @access public
	 * @var    string
	 */
	public $raw_view = '';
	
	/**
	 * Object containing the Dwoo instance.
	 *
	 * @access public
	 * @var    object
	 */
	public $dwoo = NULL;
	
	/**
	 * Array containing the variables passed to the view.
	 *
	 * @access public
	 * @var    array
	 */
	public $variables = array();
	
	/**
	 * Creates a new instance of the Dwoo driver and stores the raw content
	 * of the view in a variable.
	 *
	 * @author Yorick Peterse
	 * @param  string $raw_view The raw view data or a path to the view file to load.
	 * @param  array  $variables Associative array containing variables that will be sent
	 * @return object
	 */
	public function __construct($raw_view, $variables = array())
	{
		$this->dwoo = new \Dwoo();
		
		// File or raw data?
		if ( file_exists($raw_view) )
		{
			$raw_view = new \Dwoo_Template_File($raw_view);
		}
		else
		{
			$raw_view = new \Dwoo_Template_String($raw_view);
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
		$data = new \Dwoo_Data();
		$data->assign($this->variables);
		
		return $this->dwoo->get($this->raw_view, $data);
	}
}