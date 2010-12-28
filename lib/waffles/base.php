<?php
namespace Waffles;

/**
 * Base class that contains all the (static) methods used when creating and running
 * tests.
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
class Test
{
	/**
	 * Array containing all the test groups along with their tests.
	 *
	 * @access Private
	 * @static
	 * @var    Array
	 */
	private static $test_groups = array();
	
	/**
	 * Array that keeps track of how many tests were run, how many passed, etc
	 *
	 * @access Public
	 * @static
	 * @var    Array
	 */ 
	public static $statistics = array(
		'requirements' => 0,
		'tests'        => 0,
		'tests_failed' => 0,
	);
	
	/**
	 * Boolean that when set to TRUE will prevent the run_all() method from being called.
	 * This boolean can be useful when including multiple test files into a single
	 * file without having to remove the Test::run_all() call from each of those files.
	 *
	 * @static
	 * @access Public
	 * @var    Boolean
	 */
	public static $block = FALSE;
	
	/**
	 * Creates a new test group.
	 *
	 * @author Yorick Peterse
	 * @static
	 * @param  String $description Description about the test group.
	 * @param  Function $callback Closure that contains all the tests for this group.
	 * @return Void
	 */
	public static function group($description, $callback)
	{
		Test::$test_groups[] = array(
			'description' => $description,
			'callback'    => $callback
		);
	}
	
	/**
	 * Executes the specific task.
	 *
	 * @author Yorick Peterse
	 * @static
	 * @param  String $description Description about the test to run.
	 * @param  Function $callback Closure that contains all specifications for the test.
	 * @return Void
	 */
	public static function add($description, $callback)
	{
		self::$statistics['tests']++;
		
		echo "  - $description ";
		
		// Create the requirement and run it
		$req = new Requirement();
		$callback($req);
		
		// Validate the results
		if ( empty($req->errors) )
		{
			puts(Colors::green("[SUCCESS]"));
		}
		else
		{
			puts(Colors::red("[FAILED]"));
			
			self::$statistics['tests_failed']++;
			
			// Show all the stacktraces
			foreach ( $req->errors as $error )
			{
				self::format_error($error);
			}
		}
	}
	
	/**
	 * Runs all added tests and shows a nice banner along with the results.
	 *
	 * @author Yorick Peterse
	 * @static
	 * @return Void
	 */
	public static function run_all()
	{
		if ( Test::$block === TRUE )
		{
			return;
		}
		
		// Display our fancy banner
		puts(WAFFLES_SEPARATOR);
		puts('Waffles v' . WAFFLES_VERSION . ' released at ' . WAFFLES_DATE);
		puts('Website: ' . WAFFLES_WEBSITE);
		puts(WAFFLES_SEPARATOR);
		
		// Do we have any groups?
		if ( empty(self::$test_groups) )
		{
			exit(puts(Colors::red("You need to specify at least one test group.")));
		}
		
		// Time to run all our tests
		foreach ( self::$test_groups as $group )
		{
			puts(PHP_EOL . $group['description']);
			
			// Time to run all the tests
			$group['callback']();
		}
		
		// Show our statistics
		self::show_statistics();
	}
	
	/**
	 * Generates a nice error message by formatting the stacktrace with some fancy colors
	 * and showing which requirement failed.
	 *
	 * @author Yorick Peterse
	 * @param  Array $error Array containing the error data such as the requirement and the stacktrace
	 * @return Void
	 */
	private static function format_error($error)
	{
		// First we need to tell the user what requirement failed
		puts(Colors::yellow("      Requirement \"{$error['requirement']}\" failed"));
		
		// Format the stacktrace
		foreach ( $error['stacktrace'] as $index => $stack )
		{
			if ( $stack['function'] == '{closure}' )
			{
				$stack['function'] = 'Closure';
			}
			
			// Get the function/class combination
			if ( isset($stack['class']) AND !empty($stack['class']) )
			{
				$call = "{$stack['class']}->{$stack['function']}" . '()';
			}
			else
			{
				$call = $stack['function'] . '()';
			}

			$call    = Colors::blue($call);
			$message = <<<MESSAGE
      $index. Call: $call
         File: {$stack['file']}
         Line: {$stack['line']}
MESSAGE;

			puts($message);
		}
		
		puts();
	}
	
	/**
	 * Show a nice list of statistics such as the amount of requirements, failed tests, etc.
	 *
	 * @author Yorick Peterse
	 * @access Private
	 * @static
	 * @return Void
	 */
	private static function show_statistics()
	{
		$tests   = Colors::blue("Tests: "     . self::$statistics['tests']);
		$failed  = Colors::red("Failed: "     . self::$statistics['tests_failed']);
		$success = Colors::green("Success: "  . (self::$statistics['tests'] - self::$statistics['tests_failed']));
		$reqs    = "Requirements: "           . self::$statistics['requirements'];
		
		puts(PHP_EOL . "$reqs | $tests | $success | $failed");
	}
}