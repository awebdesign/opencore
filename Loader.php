<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

if (!defined('DIR_APPLICATION')) {
	exit;
}

if (!defined('AWEBCORE_VERSION')) {
    define('AWEBCORE_VERSION', '1.0.0');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Helper/General.php';
require_once __DIR__ . '/OcRouter.php';