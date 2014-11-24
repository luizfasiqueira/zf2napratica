<?php

error_reporting(E_ERROR | E_PARSE);
//ini_set('error_reporting', E_ALL | E_PARSE);
//ini_set('display_errors', E_ALL | E_PARSE);
//ini_set('html_errors', E_ALL | E_PARSE);
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();
