<?php

Koi\Autoloader::add('Koi\Log\Log'               , KOI_PATH . '/libraries/log/log.php');
Koi\Autoloader::add('Koi\Log\File'              , KOI_PATH . '/libraries/log/drivers/file.php');
Koi\Autoloader::add('Koi\Log\LogInterface'      , KOI_PATH . '/libraries/log/interface.php');
Koi\Autoloader::add('Koi\Exception\LogException', KOI_PATH . '/libraries/log/exceptions/log_exception.php');