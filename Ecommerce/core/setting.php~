<?php
/**
 * File Setting.php
 * Common configuration system
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @link      http://www.getpilus.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

// set memory limit
ini_set('memory_limit', '64M');

// set timezone
date_default_timezone_set('Asia/Jakarta');

// Package detail
define('PACK_TITLE', 'PiLUS');
define('PACK_CODENAME', 'Ubi Ungu');
define('PACK_VERSION', '1.4.0');

// Database credential
define('PL_DBTYPE', 'mysql');
define('PL_DBHOST', 'localhost');
define('PL_DBUSER', 'root');
define('PL_DBPASS', 'root');
define('PL_DBNAME', 'pilus');

// Site configuration
define('PL_DIR', 'http://localhost/pilus/');  // define site path
define('PL_SITEEMAIL', 'admin@localhost'); 
define('PL_SITEKEY', 'd0d48739c3b82db413b3be8fbc5d7ea1c1fd3e2792605d3cbfda1HEM78!!');
define('PL_CONTENT', 'content/themes');
define('PL_CORE', 'core');
define('PL_CABIN', PL_DIR .'cabin/');
define('PL_FILES', 'content/uploads/files/');
define('PRODUCT_IMAGE_PATH', 'content/uploads/products');
define('IMG_TYPE_FULLSIZE', 'fullsize');

define('SEND_ERRORS_TO', 'webdev@kartatopia.com');  // set email notification email address
define('DISPLAY_DEBUG', true); // display db errors?

if (!defined('PL_SYSPATH')) define('PL_SYSPATH', dirname(dirname(__FILE__)) . '/');

if (!defined('PHP_EOL')) define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");

// define include checker
$salt = 'P1Lu5@sh0p!%#$23456789';
$token = sha1(mt_rand(1, 1000000).$salt);
define('PILUS_SHOP', $token);

?>
