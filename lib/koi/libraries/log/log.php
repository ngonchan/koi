<?php
namespace Koi\Log;

/**
 * The Logger class can be used to write, what else, log files. What you're
 * going to log is all up to you, Koi just provides you with the required tools.
 *
 * h2. Logging Data
 *
 * Before we can start logging data we need to create a new instance of the logger.
 * Creating a new logger instance is as simple as the following:
 *
 * @$l = new Koi\Log\Logger();@
 *
 * As you can see this works very much like the view system provided by Koi.
 * The only difference is that defining options has to be done using an associative
 * array as the second argument of the construct. Example:
 *
 * @$l = new Koi\Log\Logger('file', array('date_format' => 'd-m-Y'));@
 *
 * Writing data can be done by calling the write() method on the object
 * created earlier.
 *
 * @$l->write("HARRO!");@
 *
 * For more information see each individual logger or the log interface.
 *
 * h2. Creating Loggers
 *
 * Creating a new logger is fairly easy, create a class that implements the LogInterface
 * and add the class to the available drivers. A basic class looks like the following:
 *
 * bc. <?php
 * class MyLogger implements LogInterface
 * {
 *   public function __construct($options = array())
 *   {
 *
 *   }
 * 
 *   public function write($data)
 *   {
 *	
 *   }
 * }
 *
 * Once your class is done you can add it to the list of available drivers as following:
 *
 * @Koi\Log\Log::$drivers['mylogger'] = 'Koi\Log\MyLog';@
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
class Log
{
	/**
	 * Static array containing all log drivers.
	 *
	 * @static
	 * @access public
	 * @var    array
	 */
	public static $drivers = array(
		'file' => 'Koi\Log\File'
	);
	
	/**
	 * Static string that contains the name of the default
	 * driver to use in case no driver has been specified
	 * when creating a new instance of this class.
	 *
	 * @static
	 * @access public
	 * @var    string
	 */
	public static $default_driver = 'file';
	
	/**
	 * Variable containing a new instance of the view driver.
	 *
	 * @access public
	 * @var    object
	 */
	public $log_driver = NULL;
	
	/**
	 * Creates a new instance of the Logger class and loads the log driver.
	 *
	 * @author Yorick Peterse
	 * @param  string $driver The name of the log driver to use.
	 * @param  array $options Additional options such as the date format in case of the
	 * File logger.
	 * @return object
	 */ 
	public function __construct($driver = 'file', $options = array())
	{
		if ( !isset($driver) OR empty($driver) )
		{
			$driver = self::$default_driver;
		}
		
		if ( !isset(self::$drivers[$driver]) )
		{
			throw new Koi\Exception\LogException("No class for the specified log driver (\"$driver\") could be found.");
		}
		
		$this->log_driver = new self::$drivers[$driver]($options);
	}
	
	/**
	 * Writes the specified data to the log using the current log driver.
	 *
	 * @author Yorick Peterse
	 * @param  string $data The data to write to the log.
	 * @return bool
	 */
	public function write($data)
	{
		return $this->log_driver->write($data);
	}
}