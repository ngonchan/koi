<?php

namespace Koi;

/**
 * The Application class is the heart of Koi. It's used to map methods to URLs and
 * run the application. When creating your own application it's class should extend
 * Koi\Application in order to be able to use Koi's methods provided by this class.
 *
 * Creating a very basic application for your web application can be done using the
 * following:
 *
 * bc. <?php
 * class Application extends Koi\Application
 * {
 *   public function some_method()
 *   {
 *     return "output";
 *   }
 * }
 *
 * Now before we can use the application we need to map a URI to the method, this
 * can be done as following:
 *
 * bc. $app = new Application();
 * $app->map('/some_method', 'some_method');
 * $app->run();
 *
 * Now whenever you vrowse to /some_method the browser will show "output".
 *
 * h2. Commandline Applications
 *
 * Creating commandline applications using Koi is extremely easy and doesn't
 * require you to modify any code. It uses the same routing system, same methods, etc.
 * Optionally you can use the CLI class to set/retrieve commandline options.
 * 
 * See Koi\CLI() for more information on how to work with commandline options.
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
class Application
{
	/**
	 * Array containing the URL/method mappings.
	 *
	 * @access private
	 * @var    array
	 */
	private $mappings = array();
	
	/**
	 * String containing the last HTTP status message sent back to the browser.
	 * Set to HTTP/1.1 200 OK by default.
	 *
	 * @access public
	 * @var    string
	 */
	public $last_http_status = 'HTTP/1.1 200 OK';
	
	/**
	 * String containing the last Content-Type value sent back to the browser.
	 * Set to text/html by default.
	 *
	 * @access public
	 * @var    string
	 */
	public $last_content_type = 'text/html';
	
	/**
	 * Maps a method to the specified URL. Note that if no class is specified
	 * Koi will assume the method is available to the current class ($this).
	 * A class can be specified by setting the third argument. Note that when you're
	 * using the third argument this should be an object, not a string.
	 *
	 * @author Yorick Peterse
	 * @param string $uri The URI to map the method to.
	 * @param string $method The method to call when the $url URL is requested.
	 * @param object $class The class to invoke $method on. By default the current class
	 * will be used.
	 * @return void
	 */
	public function map($uri, $method, $class = NULL)
	{
		// Get the default object to call $method on
		if ( empty($class) OR $class === NULL )
		{
			$class =& $this;
		}
		
		if ( isset($this->mappings[$uri]) AND !empty($this->mappings[$uri]) )
		{
			throw new Exception\MappingException("A method has already been mapped to $uri");
		}
		
		$prep   = Router::prepare_uri($uri, $method);
		$uri    = $prep[0];
		$method = $prep[1];
		
		$this->mappings[$uri] = array(
			'method' => $method,
			'object' => $class
		);
	}
	
	/**
	 * Runs the application by retrieving and calling the method for the current action.
	 *
	 * @author Yorick Peterse
	 * @return void
	 */
	public function run()
	{
		if ( empty($this->mappings) )
		{
			throw new Exception\MappingException("No methods have been mapped to any URL");
		}
		
		// Set the PATH_INFO based on the CLI arguments.
		if ( defined('KOI_DEBUG') != TRUE AND PHP_SAPI === 'cli' )
		{
			$_SERVER['PATH_INFO'] = CLI::uri();
		}
	
		// Route the call
		$result = Router::route($this->mappings);

		if ( $result === FALSE )
		{
			throw new Exception\MappingException("No methods have been bound to {$_SERVER['PATH_INFO']}");
		}

		$this->render($result['body'], $result['status'], $result['content_type']);
	}
	
	/**
	 * Sends the specified data (this should be text or HTML) back to the browser
	 * and takes care of all the required headers.
	 *
	 * @author Yorick Peterse
	 * @param  string $output The data to send to the browser.
	 * @param  string $http_status The HTTP status to send to the browser.
	 * @param  string $content_type The content type header. Set to "text/html" by default.
	 * @return void
	 */
	public function render($output, $http_status = 'HTTP/1.1 200 OK', $content_type = "text/html")
	{
		$this->last_http_status  = $http_status;
		$this->last_content_type = $content_type;

		// Ignore the headers when running from the CLI
		if ( PHP_SAPI != 'cli' )
		{
			header("Host: {$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}");
			header($http_status);
			header("Content-Type: $content_type");
		}
		
		echo $output;
	}
}