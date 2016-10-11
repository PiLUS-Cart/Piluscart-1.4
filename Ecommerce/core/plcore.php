<?php
/**
 * File plcore.php
 * to load all important
 * classes and libraries
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', true);

// starts new or resumes existing session
if (!isset($_SESSION)) {
	session_start();
}

@ob_start();

// include required files
require dirname(__FILE__) . '/setting.php';
require (PL_SYSPATH. PL_CORE . '/utilities.php');

spl_autoload_register(null, false);

// spesifikasi file php yang akan diload
spl_autoload_extensions(".class.php, .php");

if (!function_exists('ploader')) {
	
	function ploader($class) 
	{
		try {
			
			$className = PL_SYSPATH . PL_CORE .'/system/' . strtolower($class) . '.class.php';
			$libraryName = PL_SYSPATH. PL_CORE . '/library/' .strtolower($class) . '.php';
			
			if (is_file($libraryName) && !class_exists($libraryName)) {
					
				require $libraryName;
					
			} elseif (is_readable($className) && !class_exists($className)) {
					
				require $className;
			}
			
		} catch (Exception $e) {
			
			echo 'Exception caught :', $e -> getMessage(), "\n";
			
		}
		
	}
}

			
if (version_compare(PHP_VERSION, '5.1.2', '>=')) {

    if (version_compare(PHP_VERSION, '5.3', '>=')) {

     spl_autoload_register('ploader');

    } 

} else {
	
	function __autoload($class)
	{
		if (is_readable(PL_SYSPATH . PL_CORE .'/library/' . strtolower($class).'.php')) {
	
			require(PL_SYSPATH . PL_CORE . '/library/' . strtolower($class) . '.php');
	
		} elseif (is_readable(PL_SYSPATH . PL_CORE . '/system/' . strtolower($class) . '.class.php'))  {
	
			require(PL_SYSPATH . PL_CORE . '/system/' . strtolower($class) . '.class.php');
	
		}
	
	}

}


$admins = new Admin();
$themes = new Template();
$option = new Option();
$sanitasi = new Sanitize();
$shippingCost = new District();
$shoppingCart = new ShoppingCart();
$customer =  new Customer();
$product = new Product();
$order = new Order();
$stats = new Statistics();
$captcha = new ResponsiveCaptcha();
$blog =  new Post();
$blogCats = new Postcat();
$comments = new PostComment();


set_exception_handler('LogError::exceptionHandler');
set_error_handler('LogError::errorHandler');
