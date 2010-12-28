<?php

namespace Koi\View;

/**
 * The View class is the only class that's related to the MVC pattern and is used to
 * render HTML, Smarty templates and so on. The basic use involves creating
 * a new instance of the View class and calling the render() method.
 *
 * bc. $v = new Koi\View\View("Koi rocks!");
 * $v->render();
 *
 * It's also possible to load a view by path, simply set the second argument
 * to a valid file path and the view system will copy the contents of this file into
 * the driver.
 *
 * bc. $v = new Koi\View\View('/path/to/view/file.php');
 * $v->render();
 *
 * Please note that the View class itself will not actually render the view file.
 * Instead it will load the driver based on the specified engine and use that to
 * render the view.
 *
 * h2. Creating Drivers

 * When creating a driver your class should implement the View interface to ensure all the
 * required method are available.
 *
 * For more information see Koi\View\ViewInterface in lib/koi/libraries/view/interface.php.
 *
 * h2. Adding a Driver
 *
 * Adding a new driver can de done by updating the $drivers array (a static array). The key
 * of each item is the name of the driver (lowercase) and the value a string that contains
 * the full namespace and class name for the driver. Example:
 *
 * @Koi\View\View::$drivers['cheese'] = 'Koi\View\Cheese';@
 *
 * Note that while it's not required it is recommended to put the view drivers under the same
 * namespace as all default drivers (Koi\View\CLASSNAME).
 *
 * h2. Available Drivers
 *
 * By default Koi ships with the following drivers:
 *
 * * Mustache
 * * Dwoo
 * * Plain PHP/HTML
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
class View
{
	/**
	 * Array containing the driver names and their classes.
	 * 
	 * @static
	 * @access public
	 * @var    array
	 */
	public static $drivers = array(
		'php'      => 'Koi\View\PHP',
		'dwoo'     => 'Koi\View\Dwoo',
		'mustache' => 'Koi\View\Mustache'
	);
	
	/**
	 * String containing the name of the default template engine to use.
	 *
	 * @static
	 * @access public
	 * @var    string
	 */
	public static $default_driver = 'php';
	
	/**
	 * Variable that will contain a new instance of the view driver.
	 *
	 * @access public
	 * @var    object
	 */
	public $view_driver = NULL;
	
	/**
	 * Creates a new instance of the view class and creates a new instance
	 * of the view driver. When setting the view data you can either use
	 * a filepath or a string containing the raw view data.
	 *
	 * @author Yorick Peterse
	 * @param  string $raw_view Either the raw view data or a filepath to the view to load.
	 * The driver will handle the process of reading and parsing the file.
	 * @param  array $variables Array containing variables that will be sent to the view.
	 * @param  string $driver The name of the view driver. If no name is specified Koi\View::$default_driver
	 * will be used.
	 * @return object
	 */
	public function __construct($raw_view, $variables = array(), $driver = NULL)
	{
		if ( !isset($driver) OR empty($driver) )
		{
			$driver = self::$default_driver;
		}
		
		if ( !isset(self::$drivers[$driver]) )
		{
			throw new Koi\Exception\ViewException("No class for the specified driver (\"$driver\") could be found.");
		}
		
		$this->view_driver = new self::$drivers[$driver]($raw_view, $variables);
	}
	
	/**
	 * Renders the view using the view driver loaded in the constructor method.
	 *
	 * @author Yorick Peterse
	 * @return string
	 */
	public function render()
	{
		return $this->view_driver->render();
	}
}