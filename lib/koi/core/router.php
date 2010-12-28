<?php

namespace Koi;

/**
 * The Router class is used to execute the correct method for the current URL.
 * Routing a request (also known as "mapping") can be done in a few different ways:
 *
 * * directly by specifying the exact URI and method to use
 * * placeholder variables such as :numeric, :alphanumeric and :404
 * * regular expressions
 *
 * Please note that methods will be called in the order of which they've been added.
 * This means that if you use a regular expression based route that's added before
 * an alphanumeric route the regex one will be run first.
 *
 * h2. Placeholders
 *
 * In a lot of cases you might want to add slightly more powerful routes than regular
 * ones but without having to deal with regular expressions. Placeholder variables
 * are excellent for this. They offer the ability to use powerful routes without
 * having to write complex regular expressions. Placeholders can be easily recognized
 * as they're prefixed with a ":".
 *
 * The following placeholders are available:
 *
 * * 404: matches a call that had no method bound to it.
 * * any: matches all calls.
 * * alpha: matches any call that contains only letters (a-z and A-Z)
 * * numeric: matches any call that contains only numbers (0-9)
 * * alphanumeric: matches any call that contains letters and numbers (a-z, A-Z and 0-9)
 * * args: a special placeholder that SHOULD NOT be used on the URI but instead should be used
 * on method. This placeholder tells Koi to pass any additional URI segments as arguments
 * to the method.
 *
 * h2. Mapping/Routing Calls
 *
 * Mapping a method to an URI is quite simple and can be done by calling the map() method
 * on the application object. The first parameter of this method is the URI, the second
 * the method and the third the class. If the third argument isn't set the current
 * class will be used. Say we wanted to map the method "hello" to /hello we'd do the following:
 *
 * @$app->map('/hello', 'hello');@
 *
 * If you want to pass any additional URI segments as arguments to the method simply do the
 * following:
 *
 * @$app->map('/hello/world', 'hello:args');@
 *
 * The :args placeholder tells Koi to remove the /hello/ part and send all other segments
 * of the URI as arguments to the hello() method.
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
class Router
{
	/**
	 * Array containing all regular placeholders and their regular expression equilivants.
	 *
	 * @static
	 * @access public
	 * @var    array
	 */
	private static $placeholders = array(
		':any'          => '.+',
		':alphanumeric' => '([a-zA-Z0-9])+',
		':alpha'        => '([a-zA-Z])+',
		':numeric'      => '([0-9])+',
	);

	/**
	 * Array containing the HTTP status codes and their full header messages.
	 *
	 * @static
	 * @access private
	 * @var array
	 */
	private static $http_messages = array(
		200 => "200 OK",
		201 => "201 Created",
		202 => "202 Accepted",
		203 => "203 Non-Authoritative Information",
		204 => "204 No Content",
		205 => "205 Reset Content",
		206 => "206 Partial Content",
		300 => "300 Multiple Choices",
		301 => "301 Moved Permanently",
		302 => "302 Found",
		303 => "303 See Other",
		304 => "304 Not Modified",
		305 => "305 Use Proxy",
		306 => "306 Switch Proxy",
		307 => "307 Temporary Redirect",
		400 => "400 Bad Request",
		401 => "401 Unauthorized",
		402 => "402 Payment Required",
		403 => "403 Forbidden",
		404 => "404 Not Found",
		405 => "405 Method Not Allowed",
		406 => "406 Not Acceptable",
		407 => "407 Proxy Authentication Required",
		408 => "408 Request Timeout",
		409 => "409 Conflict",
		410 => "410 Gone",
		411 => "411 Length Required",
		412 => "412 Precondition Failed",
		413 => "413 Request Entity Too Large",
		414 => "414 Request-URI Too Long",
		415 => "415 Unsupported Media Type",
		416 => "416 Requested Range Not Satisfiable",
		417 => "417 Expectation Failed",
		418 => "418 I\"m a teapot",
		500 => "500 Internal Server Error",
		501 => "501 Not Implemented",
		502 => "502 Bad Gateway",
		503 => "503 Service Unavailable",
		504 => "504 Gateway Timeout",
		505 => "505 HTTP Version Not Supported",
		506 => "506 Variant Also Negotiates",
		509 => "509 Bandwidth Limit Exceeded",
		510 => "510 Not Extended",
	);
	
	/**
	 * Routes the current action to the correct class/method based on the
	 * current URL and the array of current mapped actions, set as the first
	 * argument.
	 *
	 * @static
	 * @author Yorick Peterse
	 * @param  array $mappings Array containing all mapped classes/methods and their URLs
	 * @return void
	 */
	public static function route($mappings)
	{
		$uri = Request::uri();
		
		foreach ( $mappings as $mapped_uri => $map )
		{
			if ( preg_match($mapped_uri, $uri)  )
			{
				return self::run_method($map['object'], $map['method']);
			}
			
			// Check for a 404 page
			else if ( strpos($mapped_uri, ':404') != FALSE )
			{
				$mapped_uri = str_replace(':404', self::$placeholders[':any'], $mapped_uri);
				
				// Check if the 404 page should be used for the current URI
				if ( preg_match($mapped_uri, $uri)  )
				{
					return self::run_method($map['object'], $map['method']);
				}
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Replaces all placeholder variables in the URI with regular expressions
	 * and prepares the URI so that it can be used in regular expression methods.
	 *
	 * @author Yorick Peterse
	 * @param  string $uri The URI to process
	 * @param  string $method The method that should be executed for the URI
	 * @return string
	 */
	public static function prepare_uri($uri, $method)
	{
		// Replace all placeholders in the current mapped URI with their regular expressions
		foreach ( self::$placeholders as $placeholder => $regex )
		{
			$uri = str_replace($placeholder, $regex, $uri);
		}
		
		// Always add a trailing slash to the URI
		if ( substr($uri, -1, 1) !== '/' OR $uri === '/' )
		{
			$uri .= '/'; 
		}
		
		// Escapes all forward slashes
		$uri = str_replace('/', '\\/', $uri);
		
		// If the method we're calling doesn't take any arguments we'll treat each URI
		// as a unique URI. If :args is used /hello/world would result in hello(world)
		if ( strpos($method, ':args') != FALSE )
		{
			$uri    = '/' . $uri . '/';
			$method = str_replace(':args', '', $method);
		}
		else
		{
			$uri    = '/^' . $uri . '$/';
		}

		return array($uri, $method);
	}
	
	/**
	 * Run a specific method from a class and process the response data.
	 * If the method contains ":args" the URI segments (except the first one) will be passed as an
	 * argument to the method. For example, /hello/world would in this case result in a parameter
	 * with a value of "world" being sent to the method.
	 *
	 * @author Yorick Peterse
	 * @param  object $object The object to call the method on.
	 * @param  string $method The method to call.
	 * @return array
	 */
	private static function run_method($object, $method)
	{
		$args   = explode('/', Request::uri());
		unset($args[0], $args[1]);
		
		$body    = call_user_func_array(array($object, $method), $args);
		$status  = 200;
		$content = 'text/html';
		
		// A custom content type/status code can be set by returning
		// an array in your method. The first item is the content, the second the
		// status code and the third the content type
		if ( is_array($body) )
		{
			$status  = isset($body[1]) ? $body[1] : 200;
			$content = isset($body[2]) ? $body[2] : 'text/html';
			$body    = isset($body[0]) ? $body[0] : NULL;
		}

		$status = Request::server_protocol() . ' ' . self::$http_messages[$status];

		return array('body' => $body, 'status' => $status, 'content_type' => $content);
	}
}