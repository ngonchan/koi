<?php
namespace Koi;

/**
 * The Cookie class provides an object-oriented approach to working with cookies.
 * A cookie can be created or retrieved by creating a new instance of the Cookie
 * class and passing the options to the constructor.
 *
 * The syntax of creating a new Cookie object looks like the following:
 *
 * @$c = new Koi\Cookie(name, value, domain, path, expire, secure, http_only)@
 *
 * An example of this would be the following:
 *
 * @$c = new Koi\Cookie('name', 'Yorick Peterse', 'localhost', '/test', time() + 86400);@
 *
 * The value of this cookie, among other settings (such as the domain), can also
 * be changed using the object's attributes:
 *
 * bc. $c->value = 'Yorick Peterse';
 * $c->domain = '/';
 *
 * When you're done working with your cookie you can either save or destroy it:
 *
 * bc. $c->save(); // Saves the cookie and sends it to the browser.
 * $c->destroy(); // Removes the object and deletes the cookie from the browser.
 *
 * For more information see each individual method of this class on how to use it.
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
class Cookie
{
	/**
	 * String containing the value of the cookie.
	 *
	 * @access public
	 * @var    string
	 */
	public $value = NULL;
	
	/**
	 * The domain of the cookie.
	 *
	 * @access public
	 * @var    string
	 */
	public $domain = NULL; // Public domain? Meh, I prefer MIT.
	
	/**
	 * The cookie path.
	 *
	 * @access public
	 * @var    string
	 */
	public $path = '/';
	
	/**
	 * The exipire date of the cookie.
	 *
	 * @access public
	 * @var    string
	 */
	public $expire = NULL;
	
	/**
	 * Boolean that indicates that the cookie can only be used over a secure
	 * connection.
	 *
	 * @access public
	 * @var    bool
	 */
	public $secure = FALSE;
	
	/**
	 * Boolean that indicates if the cookie should only be accessible through
	 * HTTP. This prevents Javascript applications to use cookie data.
	 *
	 * @access public
	 * @var    bool
	 */
	public $http_only = FALSE;
	
	/**
	 * Creates a new instance of the Cookie class. Whenever creating a new Cookie object
	 * the class will check if the value of the cookie (as specified by the name) can
	 * be found in the $_COOKIE array. If the cookie exists in this array the value will
	 * be extracted from this array and will overwrite the one specified in the constructor.
	 *
	 * @author Yorick Peterse
	 * @param  string $name The name of the cookie.
	 * @param  string $value The value of the cookie.
	 * @param  string $domain The domain of the cookie.
	 * @param  string $path The URI/path of the cookie.
	 * @param  string $expire The expire date of the cookie.
	 * @param  bool $secure Boolean that indicates if the cookie should only be sent
	 * over a secure connection.
	 * @param  bool $http_only Boolean that indicates that the cookie can only be used by
	 * the HTTP protocol.
	 * @return object
	 */
	public function __construct($name, $value = NULL, $domain = NULL, $path = '/', $expire = NULL, $secure = FALSE, $http_only = FALSE)
	{
		// Check if the cookie exists. If so we'll retrieve the value, otherwise we'll prepare a new cookie
		if ( isset($_COOKIE[$name]) )
		{
			$value = $_COOKIE[$name];
		}

		$this->name      = $name;
		$this->value     = $value;
		$this->domain    = $domain;
		$this->path      = $path;
		$this->expire    = $expire;
		$this->secure    = $secure;
		$this->http_only = $http_only;
	}
	
	/**
	 * Saves the cookie by sending it to the browser.
	 *
	 * @author Yorick Peterse
	 * @return bool
	 */
	public function save()
	{
		if ( headers_sent() === FALSE )
		{
			return setcookie($this->name, $this->value, $this->expire, $this->path, $this->domain, $this->secure, $this->http_only);
		}
	}
	
	/**
	 * Destroys a cookie and removes it from the browser. This method also
	 * empties all attributes such as the name and value. Not doing this
	 * could cause confusement about whether or not a cookie has been deleted
	 * successfully.
	 *
	 * @author Yorick Peterse
	 * @return bool
	 */
	public function destroy()
	{
		if ( !isset($_COOKIE[$this->name]) )
		{
			throw new Koi\Exception\CookieException("The specified cookie (\"{$htis->name}\") does not exist or hasn't been sent to the browser yet.");
		}
		
		// Reset all variables
		unset($_COOKIE[$this->name]);
		
		$this->name      = NULL;
		$this->value     = NULL;
		$this->domain    = '';
		$this->path      = '/';
		$this->expire    = NULL;
		$this->secure    = FALSE;
		$this->http_only = FALSE;
		
		// Expire the cookie by setting it's expire date in the past.
		return setcookie($this->name, $this->value, time() - 86400, $this->path, $this->domain, $this->secure, $this->http_only);
	}
}