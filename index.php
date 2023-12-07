<?php

use App\Autoloader;
use App\Controllers\ClothesFunctions;

require_once __DIR__ . '/autoloader.php';
Autoloader::register();
// Lance la session
session_start();
require_once __DIR__ . '/Views/Home.php';
