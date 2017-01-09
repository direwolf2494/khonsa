<?php

// load other configuration files from here
require_once 'database.php';

/**
 * The following constants used throughout the framework to find
 * files that are essential to produce the correctly functionality.
 * 
 * ROOT - path to the base folder of the framework. All files within
 *      the framework reside within this folder.
 * 
 * CORE_DIR - path to the directory that contains the files and
 *      directories that make up the base functionality of Khonsa.
 * 
 * CONFIG_FILE - path to the file that contains all configuration 
 *      information that are used throughout the framework.
 * 
 * APP_DIR - path to the directory that contains user defined files
 *      that are used in Khonsa.
 * 
 * ROUTES_FILE - path to file that contains routes that the framework
 *      is coded to facilitate.
 *  
 * CONTROLLER_DIR - path to directory that contains user defined controllers.
 * 
 * MODEL_DIR - path to directory that contains user defined models.
 * 
 * VIEW_DIR - path to directory that contains user defined views.
 * 
**/

define(ROOT, dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

define(CORE_DIR, ROOT . 'core' . DIRECTORY_SEPARATOR);

define(CONFIG_FILE, __FILE__);

define(APP_DIR, ROOT . 'app' . DIRECTORY_SEPARATOR);

define(ROUTES_FILE, APP_DIR . 'routes.php');

define(CONTROLLER_DIR, APP_DIR . 'Controllers' . DIRECTORY_SEPARATOR);

define(MODEL_DIR, APP_DIR . 'Models' . DIRECTORY_SEPARATOR);

define(VIEW_DIR, APP_DIR . 'Views' . DIRECTORY_SEPARATOR);



/**
 * Settings for types of environments.
 * 
 * Environment options: 
 *  development / dev
 *  production / prod
 *  testing (TODO)
 * 
 * When in the development environment, stack traces and errors
 * will be returned by to the client. However, when in any other
 * environment, a Internal Server Error (500) Response code is 
 * returned.
 * 
 * A function is registered to be called when the script is terminated due
 * to fatal or parse errors. An Internal Server Error Page is then returned if 
 * the application is not in the development environment otherwise a the stack 
 * trace is outputted.
 * 
**/

define(ENVIRONMENT, "development");

ini_set("display_errors", (ENVIRONMENT === 'dev' || ENVIRONMENT === 'development'));

register_shutdown_function(function () {
    $last_error = error_get_last();
    if ($last_error && ($last_error['type'] == E_ERROR || $last_error['type'] == E_PARSE))
    {
        header("HTTP/1.1 500 Internal Server Error");
        $path = CORE_DIR . 'default' . DIRECTORY_SEPARATOR . '500.html';
        echo file_get_contents($path);
    }
});