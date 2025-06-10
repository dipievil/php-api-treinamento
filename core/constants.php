<?php
/**
 * Global constants for the application
 */

// Define root path
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..'));
}

// Define app path
if (!defined('APP_PATH')) {
    define('APP_PATH', realpath(ROOT_PATH . '/app'));
}
