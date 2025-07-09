<?php

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set timezone
date_default_timezone_set('UTC');

// Load autoloader
require_once __DIR__ . '/../app/autoload.php';

// Load and dispatch routes
$router = require_once __DIR__ . '/../routes/web.php';
$router->dispatch();
