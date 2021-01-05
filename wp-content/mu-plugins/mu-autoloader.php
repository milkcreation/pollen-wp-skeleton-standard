<?php

/**
 * @name mu-autoloader
 * @description Chargement automatique des mu-plugins Wordpress.
 * @package presstify/mu-autoloader
 * @version 1.0.1
 *
 * @see https://codex.wordpress.org/Must_Use_Plugins
 */
defined('DS') ? : define('DS', DIRECTORY_SEPARATOR);

// Chargement automatique de mu-plugins Wordpress
$dirs = glob(dirname(__FILE__) . '/*' , GLOB_ONLYDIR);
foreach($dirs as $dir) :
    if(file_exists($dir . DS . basename($dir) . ".php")) :
        require($dir . DS . basename($dir) . ".php");
    endif;
endforeach;