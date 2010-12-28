<?php

namespace Waffles;

/**
 * Class used to specify requirements such as if strings should be empty or not.
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
class Requirement
{
	/**
	 * Variable that contains the specified input data to verify
	 *
	 * @access Private
	 * @var    Mixed
	 */
	private $input = NULL;
	
	/**
	 * Variable that indicates if the if statement should or shouldn't match.
	 *
	 * @access Private
	 * @var    Bool
	 */
	private $if_type = NULL;
	
	/**
	 * Array that contains information about all failed requirements along with
	 * their stacktraces.
	 *
	 * @access Public
	 * @var    Array
	 */
	public $errors = array();
	
	/**
	 * Prepare the requirement by storing the input data and allowing the developer
	 * to chain requirements together by returning $this.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  Mixed $input The input data to verify.
	 * @return Object
	 */
	public function expects($input)
	{
		$this->input = $input;
		Test::$statistics['requirements']++;
		
		return $this;
	}
	
	/**
	 * Method that indicates that any following requirements in the chain shouldn't
	 * be matched. For example, you can use this method for specifying that a value shouldn't be empty.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @return Object
	 */
	public function to_not()
	{
		$this->if_type = FALSE;
		
		return $this;
	}
	
	/**
	 * Method that indicates that any following requirements in the chain should
	 * be matched. For example, you can use this method for specifying that a value should equal
	 * something else.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @return Object
	 */
	public function to()
	{
		$this->if_type = TRUE;
		
		return $this;
	}
	
	/**
	 * Checks if $this->input is empty or not.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @return Object
	 */
	public function be_empty()
	{
		if ( empty($this->input) != $this->if_type )
		{
			$this->fail('be_empty()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Checks if the given value equals $this->input.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  String $compare The value to compare $this->input with.
	 * @return Void
	 */
	public function equal($compare)
	{
		if ( ($this->input == $compare) != $this->if_type )
		{
			$this->fail('equal()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Check if the value in $this-input contains the given string.
	 *
	 * @author Yorick Peterse
	 * @access Public
	 * @param  String $find The string to search for in $this->input
	 * @return Void
	 */
	public function contain($find)
	{
		if ( (strstr($this->input, $find)) != $this->if_type )
		{
			$this->fail('contain()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Test if $this->input matches the pattern specified in $regex.
	 *
	 * @author Yorick Peterse
	 * @param  String $regex The regular expression pattern.
	 * @return Void
	 */
	public function match($regex)
	{
		if ( preg_match($regex, $this->input) != $this->if_type )
		{
			$this->fail('match()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Check if the object stored in $this->input responds to the specified
	 * method or attribute.
	 *
	 * @author Yorick Peterse
	 * @param  String $attribute The method or attribute for the object.
	 * @return Void
	 */
	public function respond_to($attribute)
	{
		$has_attr   = isset($this->input->$attribute);
		$has_method = method_exists($this->input, $attribute);
		
		if ( $has_attr != $this->if_type AND $has_method != $this->if_type )
		{
			$this->fail('respond_to()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Check if $this->input is greater than the specified value.
	 *
	 * @author Yorick Peterse
	 * @param  Integer $number The value that should (or shouldn't) be lower than $this->input.
	 * @return Void
	 */
	public function be_greater_than($number)
	{
		if ( ($number < $this->input) != $this->if_type )
		{
			$this->fail('be_greater_than()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Check if $this->input is lower than the specified value.
	 *
	 * @author Yorick Peterse
	 * @param  Integer $number The value that should (or shouldn't) be greater than $this->input.
	 * @return Void
	 */
	public function be_lower_than($number)
	{
		if ( ($number > $this->input) != $this->if_type )
		{
			$this->fail('be_lower_than()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Checks if the type of variable stored in $this->input matches the one specified.
	 *
	 * @author Yorick Peterse
	 * @param  String $type The name of the type of variable (string, stdClass, etc)
	 * @return Void
	 */
	public function be_type_of($type)
	{
		if ( (gettype($this->input) == $type) != $this->if_type )
		{
			$this->fail('be_type_of()', debug_backtrace());
		}
		
		return $this;
	}
	
	/**
	 * Adds a new error to the array.
	 *
	 * @author Yorick Peterse
	 * @param  String $requirement The name of the requirement that failed.
	 * @param  Array  $stacktrace Stacktrace generated by the requirement.
	 * @return Void
	 */
	private function fail($requirement, $stacktrace)
	{
		$this->errors[] = array(
			'requirement' => $requirement,
			'stacktrace'  => $stacktrace
		);
	}
}