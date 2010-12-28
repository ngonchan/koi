<?php

Koi\Autoloader::add('Koi\Exception\ViewException', KOI_PATH . '/libraries/view/exceptions/view_exception.php');
Koi\Autoloader::add('Koi\View\ViewInterface'     , KOI_PATH . '/libraries/view/interface.php');

Koi\Autoloader::add('Koi\View\Mustache'          , KOI_PATH . '/libraries/view/drivers/mustache.php');
Koi\Autoloader::add('Koi\View\Dwoo'              , KOI_PATH . '/libraries/view/drivers/dwoo.php');
Koi\Autoloader::add('Koi\View\PHP'               , KOI_PATH . '/libraries/view/drivers/php.php');
Koi\Autoloader::add('Koi\View\View'              , KOI_PATH . '/libraries/view/view.php');