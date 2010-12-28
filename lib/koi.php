<?php

/**
 * Koi is a very lightweight framework inspired by both Innate and Sinatra (Ruby frameworks).
 * It has no MVC, no models or authentication libraries. However, what it does offer is a rock solid
 * system for creating your own framework whether it's MVC or not.
 *
 * Out of the box Koi enables you to do the following:
 *
 * * Work with a very easy to use framework ;)
 * * Easily build your own framework (MVC or not)
 * * Render a view using HAML, HTML, Smarty, etc
 * * Easily work with cookies, session, log files, etc
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

// Shortcuts to common data
define('KOI_PATH'   , __DIR__ . '/koi');
define('KOI_VERSION', '0.1');

// Set our timezone to GMT by default. You can easily override this by calling
// this function in your own code.
date_default_timezone_set('GMT');

require_once KOI_PATH . '/exceptions/autoloader.php';
require_once KOI_PATH . '/exceptions/mapping.php';
require_once KOI_PATH . '/exceptions/router.php';

require_once KOI_PATH . '/core/autoloader.php';
require_once KOI_PATH . '/core/functions.php';

// The autoloader will load all our classes based on the path bound to that class.
Koi\Autoloader::add('Koi\Application'   , KOI_PATH . '/core/application.php');
Koi\Autoloader::add('Koi\Request'       , KOI_PATH . '/core/request.php');
Koi\Autoloader::add('Koi\Router'        , KOI_PATH . '/core/router.php');

// Load all library bootstrap files which in turn will lazy load the libraries themselves.
require_once KOI_PATH . '/libraries/cookie/bootstrap.php';
require_once KOI_PATH . '/libraries/log/bootstrap.php';
require_once KOI_PATH . '/libraries/cli/bootstrap.php';
require_once KOI_PATH . '/libraries/view/bootstrap.php';
require_once KOI_PATH . '/libraries/log/bootstrap.php';