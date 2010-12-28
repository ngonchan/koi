<?php

require_once __DIR__ . '/../lib/koi.php';
require_once __DIR__ . '/../lib/waffles.php';

/**
 * Helper function that can be used to run a Koi application
 * and returns the response instead of echo'ing it.
 *
 * @author Yorick Peterse
 * @param  string $uri The request URI.
 * @param  object $app The application object.
 * @return string
 */
function run_application($uri, $app)
{
	define('DEBUG_KOI', TRUE);
	$_SERVER['PATH_INFO'] = $uri;
	
	ob_start();
	$app->run();
	$response = ob_get_contents();
	ob_end_clean();
	
	return $response;
}