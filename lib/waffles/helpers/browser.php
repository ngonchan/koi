<?php

namespace Waffles;

/**
 * Class that can be used to fake basic webbrowser behavior. Common actions
 * such as GET and POST requests are supported along with useragent spoofing,
 * parsing the DOM and various other actions.
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
class Browser
{
	/**
	 * Array containing the correct useragents for each browser.
	 *
	 * @access Private
	 * @var    Array
	 */
	private $user_agents = array(
		'ie6'     => 'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
		'ie7'     => 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
		'ie8'     => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0; SLCC1; .NET CLR 3.0.04320)',
		'safari'  => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-gb) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5',
		'firefox' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-GB; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
		'chrome'  => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.215 Safari/534.10'
	);
	
	/**
	 * Variable that contains the useragent string
	 *
	 * @access Private
	 * @var    String
	 */
	private $user_agent = '';
	
	/**
	 * String containing the raw body sent back by the server.
	 *
	 * @access Public
	 * @var    String
	 */
	public $body = '';
	
	/**
	 * Integer containing the HTTP response code sent by the server.
	 *
	 * @access Public
	 * @var    Integer
	 */
	public $status = 0;
	
	/**
	 * Array containing the results from curl_getinfo() such as the HTTP response
	 * code, URL, etc.
	 *
	 * @access Public
	 * @var    Array
	 */
	public $http_info = array();
	
	/**
	 * Array containing all cookies.
	 *
	 * @access Private
	 * @var    Array
	 */
	private $cookies = array();
	
	/**
	 * Create a new instance of the Browser class and sets the type of
	 * browser based on the first argument. By default Google Chrome's useragent will be used.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  String $browser The browser shorthand to use, e.g. safari.
	 * @return Object
	 */
	public function __construct($browser = 'chrome')
	{
		$this->user_agent = $this->user_agents[$browser];
	}
	
	/**
	 * Adds a new cookie for the next request.
	 *
	 * @author Yorick Peterse
	 * @param  String $name The name of the cookie.
	 * @param  String $value The value of the cookie
	 * @return Void
	 */
	public function set_cookie($name, $value)
	{
		$this->cookies[] = array('name' => $name, 'value' => $value);
	}
	
	/**
	 * Send a GET request and return the response as an object.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  String $url The URL to send the GET request to.
	 * @return Object
	 */
	public function get($url)
	{		
		// Configure cURL
		$curl = $this->configure_curl($url);
		
		curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
		
		// Get and parse the response
		$response  = curl_exec($curl);
		$http_info = curl_getinfo($curl);
		             curl_close($curl);
		
		// Time to store the response data
		$this->http_info = $http_info;
		$this->status    =& $this->http_info['http_code'];
		$this->body      = $this->get_body($response);
		
		$this->cookies = array();
		
		return $this;
	}
	
	/**
	 * Send a POST request to the specified URL and returns the response
	 * as an object. POST data can be specified in the second argument as
	 * an associative array.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  String $url The URL to send the POST request to.
	 * @param  Array $params Array containing the POST keys and their values.
	 * @return Object
	 */
	public function post($url, $params)
	{
		// Create the POST string
		foreach ( $params as $key => $val )
		{
			$post_string .= "$key=$val&";
		}
		$post_string = trim($post_string, '&');

		// Configure cURL
		$curl = $this->configure_curl($url);
		
		curl_setopt($curl, CURLOPT_POST      , TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
		
		// Get and parse the response
		$response  = curl_exec($curl);
		$http_info = curl_getinfo($curl);
		             curl_close($curl);
		
		$this->http_info = $http_info;
		$this->status    =& $this->http_info['http_code'];
		$this->body      = $this->get_body($response);
		
		$this->cookies = array();
		
		return $this;
	}
	
	/**
	 * Sets general configuration options for cURL.
	 *
	 * @author Yorick Peterse
	 * @param  String $url The URL to send the cURL request to.
	 * @return Object
	 */
	private function configure_curl($url)
	{
		$curl          = curl_init();
		$cookie_header = "";
		$cookie_jar    = tmpfile();
		
		// Set our cookies
		foreach ( $this->cookies as $cookie )
		{
			$cookie_header .= $cookie['name'] . '=' . $cookie['value'] . ';';
		}
		
		curl_setopt($curl, CURLOPT_URL           , $url);
		curl_setopt($curl, CURLOPT_COOKIEJAR     , $cookie_jar);
		curl_setopt($curl, CURLOPT_COOKIEFILE    , $cookie_jar);
		curl_setopt($curl, CURLOPT_COOKIE        , $cookie_header);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_USERAGENT     , $this->user_agent);
		
		return $curl;
	}
	
	/**
	 * Retrieves the body data from the specified HTML string.
	 *
	 * @author Yorick Peterse
	 * @param  String $html The HTML response returned by the server.
	 * @return Void
	 */
	private function get_body($html)
	{
		if ( empty($html) )
		{
			return;
		}
		
		$dom     = new \DOMDocument();
		$tmp_dom = new \DOMDocument();

		@$dom->loadHTML(mb_convert_encoding($html, 'UTF-8'));
		
		// Get the body from the DOM and store it in $this->body
		$body = $dom->getElementsByTagName('body');
		$body = $body->item(0)->getElementsByTagName('*')->item(0);
		
		// Fucking PHP, I want my innerHTML method!
		$tmp_dom->appendChild($tmp_dom->importNode($body, TRUE));
		$body = $tmp_dom->saveHTML();
		
		return $body;
	}
}