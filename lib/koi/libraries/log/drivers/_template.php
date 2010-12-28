<?php
namespace Koi\Log;

/**
 * Logger description goes in here.
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
class _Template implements LogInterface
{
	/**
	 * The constructor is used to create a new instance of the logger and
	 * is used to set configuration options.
	 *
	 * @author Yorick Peterse
	 * @param  array $options Optional array of options is optional
	 * @return object
	 */
	public function __construct($options = array())
	{
		
	}
	
	/**
	 * The write method is used to write the specified data to the log.
	 * The first argument is required and should be a string.
	 *
	 * @author Yorick Peterse
	 * @param  string $data The data to log.
	 * @return bool
	 */
	public function write($data)
	{
		
	}
	
	/**
	 * The destroy method can be used to remove the log data.
	 *
	 *
	 * @author Yorick Peterse
	 * @return bool
	 */
	public function destroy()
	{
		
	}
}