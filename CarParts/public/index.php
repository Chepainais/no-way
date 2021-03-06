<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
date_default_timezone_set('Europe/Riga');

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', 
        realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') ||
         define('APPLICATION_ENV', 
                (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(
        implode(PATH_SEPARATOR, 
                array(
                        realpath(APPLICATION_PATH . '/../library'),
                        get_include_path()
                )));

/**
 * Zend_Application
 */
require_once 'Zend/Application.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Config/Ini.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, 
        APPLICATION_PATH . '/configs/application.ini');

if (isset($_COOKIE['session_id']))
    session_id($_COOKIE['session_id']);
Zend_Session::start(); // or session_start();
if (! isset($_COOKIE['session_id']))
    setcookie('session_id', session_id(), 0, '/', '.bilparts.test.chepa.lv');

$application->bootstrap()->run();
