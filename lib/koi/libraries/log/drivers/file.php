<?php
namespace Koi\Log;

/**
 * The File logger is a log class that will create physical log files
 * based on the current date (day, month and year) and store them in a directory
 * specified by the developer. Do remember that while this class will try to
 * create the base directory if it doesn't exist it will not change the
 * permissions so make sure your directory permissions are set up correctly.
 * An octal CHMOD value of 0775 should do in most cases.
 *
 * h2. Usage
 *
 * Using the File logger works as following:
 *
 * @$l = new Koi\Log\Log('file');@
 *
 * If you want to customize the options, such as the date format, pass an
 * associative array as the second argument of the constructor:
 *
 * @$l = new Koi\Log\Log('file', array('date_format' => 'd-m-Y'));@
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
class File implements LogInterface
{
	/**
	 * String containing the path to the base directory for all log files.
	 *
	 * @access private
	 * @var    string
	 */
	private $directory = '';
	
	/**
	 * String containing the date format for the log file.
	 *
	 * @access private
	 * @var    string
	 */
	private $date_format = 'd-m-Y';
	
	/**
	 * String containing the time format used to show the date + time of
	 * each log message.
	 *
	 * @access private
	 * @var    string
	 */
	private $time_format = 'H:i';
	
	/**
	 * The constructor is used to create a new instance of the logger and
	 * is used to set configuration options. The following options are available:
	 *
	 * * directory: The base directory for all log files.
	 * * date_format: The date format, set to "d-m-Y" by default.
	 * * time_format: The time format to use for each log message.
	 *
	 * @author Yorick Peterse
	 * @param  array $options Optional array of options is optional
	 * @return object
	 */
	public function __construct($options = array())
	{
		// Assign all of our options, LIKE A BOSS
		foreach ( $options as $option => $value )
		{
			if ( isset($this->$option) )
			{
				$this->$option = $value;
			}
		}
		
		if ( empty($this->directory) )
		{
			throw new \Koi\Exception\LogException("You need to specify a log directory");
		}
		
		if ( substr($this->directory, -1, 1) === '/' )
		{
			$this->directory = substr_replace($this->directory, '', -1, 1);
		}
		
		// Create the directory
		if ( !file_exists($this->directory) )
		{
			if ( !@mkdir($this->directory) )
			{
				throw new \Koi\Exception\LogException("Failed to create the log directory at {$this->directory}");
			}
		}
	}
	
	/**
	 * The write method is used to write the specified data to the log.
	 * You don't have to worry about log instances being active for more than
	 * a day as the write() method will check for the current date each time it's
	 * called.
	 *
	 * @author Yorick Peterse
	 * @param  string $data The data to log.
	 * @return bool
	 */
	public function write($data)
	{
		$date      = date($this->date_format, time());
		$date_time = date($this->date_format . ' ' . $this->time_format, time());
		$file      = $this->directory . '/' . $date . '.log';
		$data      = "[ $date_time ] " . $data . PHP_EOL;
		
		if ( !$handle = fopen($file, 'a+') )
		{
			throw new \Koi\Exception\LogException("Could not open the log file $file");
		}
		
		if ( fwrite($handle, $data) )
		{
			return TRUE;
		}
		else
		{
			throw new \Koi\Exception\LogException("Failed to write the log data to $file");
		}
	}
}