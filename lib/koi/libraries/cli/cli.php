<?php
namespace Koi;

/**
 * The CLI class can be used to set, retrieve and validate commandline arguments.
 * Options can be added, verified, help messages can be shown, etc.
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
class CLI
{
	/**
	 * Array containing all global options.
	 *
	 * @static
	 * @access private
	 * @var    array
	 */
	private static $global_opts = array();
	
	/**
	 * Array containing all method specific options
	 *
	 * @static
	 * @access private
	 * @var    array
	 */
	private static $method_opts = array();
	
	/**
	 * String containing the application banner that's shown whenever the help message is shown.
	 *
	 * @static
	 * @access public
	 * @var    string
	 */
	public static $banner = '';
	
	/**
	 * Generates a PATH_INFO URI based on the commandline arguments.
	 *
	 * @author Yorick Peterse
	 * @return string
	 */
	public static function uri()
	{
		$args = self::parse_args();
		$uri  = '/';

		foreach ( $args as $index => $value )
		{
			if ( is_int($index) )
			{
				$uri .= str_replace(' ', '/', $value) . '/';
			}
		}

		return $uri;
	}
	
	/**
	 * Parse the commandline options and return an associative array of option/value
	 * pairs. Extra data added to the commandline options without any switches will be saved
	 * as indexes. For example, the command @php cli.php hello world --time=30@ would result
	 * in the following array:
	 *
	 * bc. array(
	 *   [0]    => "hello",
	 *   [1]    => "world",
	 *   [time] => "30",
	 * )
	 *
	 * As you can see the first argument, which is the script's name, is removed.
	 *
	 * @author Yorick Peterse
	 * @return array
	 */
	public static function parse_args()
	{
		$argv        = $GLOBALS['argv']; unset($argv[0]);
		$argv_parsed = array();

		foreach ( $argv as $index => $arg )
		{
			$first  = substr($arg,  0, 1);
			$second = substr($arg,  1, 1);

			if ( $first === '-' OR $first === '"' OR $first === '\'' )
			{
				$arg = substr_replace($arg, '', 0, 1);
				
				if ( $second === '-' )
				{
					$arg = substr_replace($arg, '', 0, 1);
				}
				
				// Value specified using a = ?
				if ( strpos($arg, '=') != FALSE )
				{
					$arg                  = explode('=', $arg);
					$argv_parsed[$arg[0]] = $arg[1];
				}
				// Extract the next value
				else
				{
					$next = $argv[$index + 1];

					if ( substr($next, 0, 1) !== '-' )
					{
						$argv_parsed[$arg] = $next;
					}
					else
					{
						$argv_parsed[$arg] = NULL;
					}
				}
			}
			else
			{
				if ( !in_array($arg, array_values($argv_parsed)) )
				{
					$argv_parsed[] = $arg;
				}
			}
		}

		return $argv_parsed;
	}
	
	/**
	 * Adds either a new global or method specific option.
	 * In order to add a global option you should set the first
	 * argument of this method to NULL, otherwise set it to the name of the method.
	 *
	 * @author Yorick Peterse
	 * @param  string $method The name of the method to which the option belongs. Set
	 * to NULL for global options.
	 * @param  string $opt The name of the option. Any option longer than 1 character
	 * will be turned into a long option (--option) otherwise this will be a short option
	 * (-o).
	 * @param  string $desc The option's description.
	 * @param  bool $req Specifies if the option is required or not.
	 * @return void
	 */
	public static function set_opt($method, $opt, $desc, $req = FALSE)
	{
		$prefix = (strlen($opt) > 1 ? '--' : '-');
		
		$new_opts = array(
			'desc'   => $desc,
			'req'    => $req,
			'prefix' => $prefix
		);
		
		if ( !empty($method) )
		{
			self::$method_opts[$method][$opt] = $new_opts;
		}
		else
		{
			self::$global_opts[$opt] = $new_opts;
		}
	}
	
	/**
	 * Retrieves the value of the specified option.
	 *
	 * @author Yorick Peterse
	 * @param  string $opt The name of the option of which the value should
	 * be retrieved.
	 * @return array
	 */
	public static function get_opt($opt)
	{
		$args = self::parse_args();

		if ( array_key_exists($opt, $args) )
		{
			return $args[$opt];
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Retrieves all options for the specified method, otherwise this will retrieve
	 * all global options. The values for each option will also be retrieved.
	 *
	 * @author Yorick Peterse
	 * @param  string $method The name of the method for which all options should
	 * be retrieved. Set to NULL for global options.
	 * @return  array
	 */
	public static function opts($method = NULL)
	{
		if ( !empty($method) )
		{
			$opts = self::$method_opts[$method];
		}
		else
		{
			$opts = self::$global_opts;
		}
		
		$args = self::parse_args();
		
		// Add the input value to the $opts array
		foreach ( $opts as $opt => $details )
		{
			if ( array_key_exists($opt, $args) )
			{
				$details['value'] = $args[$opt];
			}
			else
			{
				$details['value'] = NULL;
			}
			
			$opts[$opt] = $details;
		}
		
		return $opts;
	}
	
	/**
	 * Checks if all required options for the specified method have been set.
	 * Returns FALSE if any of the required options are missing.
	 *
	 * @author Yorick Peterse
	 * @param  string $method The name of the method for which to check all options.
	 * @return bool
	 */
	public static function has_opts($method = NULL)
	{
		if ( !empty($method) )
		{
			$opts = self::$method_opts[$method];
		}
		else
		{
			$opts = self::$global_opts;
		}
		
		$args = self::parse_args();
		
		foreach ( $opts as $opt => $details )
		{
			if ( !array_key_exists($opt, $args) AND $details['req'] === TRUE )
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Terminates the session by showing the custom message along with
	 * all available options (either globally or for the specified method).
	 *
	 * @author Yorick Peterse
	 * @param  string $method The name of the method for which all options should be shown.
	 * @param  string $message The custom message to display.
	 * @return void
	 */
	public static function terminate($method = NULL, $message = NULL)
	{
		echo $message . PHP_EOL;
		
		self::show_help($method);
		
		exit;
	}
	
	/**
	 * Show the help message for either all commands or the specified one.
	 *
	 * @author Yorick Peterse
	 * @param  string $method The name of the method for which the help message should be shown.
	 * @return void
	 */
	public static function show_help($method = NULL)
	{
		if ( !empty($method) )
		{
			$method_opts = self::$method_opts[$method];
		}
		
		$global_opts = self::$global_opts;
		
		echo self::$banner . PHP_EOL;
		
		// Show all command specific options
		if ( !empty($method_opts) )
		{
			echo PHP_EOL . "Method options: " . PHP_EOL;
			
			foreach ( $method_opts as $opt => $details )
			{
				$req = NULL;
				
				if ( $details['req'] === TRUE )
				{
					$req = " [REQUIRED]";
				}
				
				echo "  " . $details['prefix'] . $opt . ": " . $details['desc'] . $req . PHP_EOL;
			}
		}
		
		if ( !empty($global_opts) )
		{
			echo PHP_EOL . "Global options: " . PHP_EOL;
			
			foreach ( $global_opts as $opt => $details )
			{
				$req = NULL;
				
				if ( $details['req'] === TRUE )
				{
					$req = " [REQUIRED]";
				}
				
				echo "  " . $details['prefix'] . $opt . ": " . $details['desc'] . $req . PHP_EOL;
			}
		}
	}
}