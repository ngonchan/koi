<?php

namespace Koi\View;

/**
 * Interface that can be used to create view drivers.
 *
 * @see     Koi\View\View
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
interface ViewInterface
{
	/**
	 * The __construct() method is used to store the raw view data in the object
	 * and return a new instance of the class.
	 * 
	 * @author Yorick Peterse
	 * @param  string $view_content The raw view data.
	 * @param  array  $variables Associative array containing variables that will be sent
	 * to the view.
	 * @return void
	 */
	public function __construct($view_content, $variables = array());
	
	/**
	 * The render() method is used to convert the raw view data into HTML. It should
	 * return the processed view data back to the View class.
	 *
	 * @author Yorick Peterse
	 * @return string The converted view data.
	 */
	public function render();
}