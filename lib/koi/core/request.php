<?php

namespace Koi;

/**
 * The Request class is used to retrieve data such as the request URI and can be used
 * to send the final output back to the browser. Koi itself uses the class
 * to determine what method to call based on the request URI, however it's perfectly
 * fine to use the request class for your own applications as it can also be used
 * to verify certain actions, such as checking if an action was made using AJAX.
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
class Request
{	
	/**
	 * Retrieves the current request URI. 
	 * 
	 * The request URI data will be retrieved from $_SERVER['PATH_INFO'],
	 * if that doesn't exist we'll use $_SERVER['PHP_SELF'] instead and mimic
	 * the results of the PATH_INFO key.
	 *
	 * @author Yorick Peterse
	 * @return void
	 */
	public static function uri()
	{
		if ( isset($_SERVER['PATH_INFO']) )
		{
			$uri = $_SERVER['PATH_INFO'];
		}
		// When using the REQUEST_URI we'll have to do some extra work to
		// mimic the PATH_INFO data.
		else
		{
			$uri = $_SERVER['PHP_SELF'];
			$uri = explode('.php', $uri);
			$uri = $uri[1];
			
			if ( empty($uri) )
			{
				$uri = '/';
			}
		}
		
		// We always want a trailing slash
		if ( substr($uri, -1, 1) !== '/' OR $uri === '/' )
		{
			$uri .= '/'; 
		}
		
		return $uri;
	}
	
	/**
	 * Checks if the current request was executed using Ajax.
	 * Returns TRUE if this is the case, FALSE otherwise.
	 *
	 * @author Yorick Peterse
	 * @return bool
	 */
	public static function is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}
	
	/**
	 * Retrieves the server protocol and falls back to HTTP/1.1 if no protocol could
	 * be found.
	 *
	 * @author Yorick Peterse
	 * @return string
	 */
	public static function server_protocol()
	{
		if ( isset($_SERVER['SERVER_PROTOCOL']) AND !empty($_SERVER['SERVER_PROTOCOL']) )
		{
			return $_SERVER['SERVER_PROTOCOL'];
		}
		else
		{
			return "HTTP/1.1";
		}
	}
}