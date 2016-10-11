<?php
/**
 * cabin-route.php
 * berfungsi untuk mengelola request yang 
 * diminta oleh browser
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

if (!defined('PILUS_SHOP')) header("Location: 403.php"); 


$module = '';
$pathToError = "404.php";

if (isset($_GET['module']) && $_GET['module'] != '') {

	$module = htmlentities(strip_tags(strtolower($_GET['module'])));

	$pathToModule = "module/$module.php";

}

// cek direktori modul
if (!is_readable($pathToModule) || empty($module) || checkedModule($module) == false) {

	include_once($pathToError);

} else {
	
	include_once($pathToModule);
	
}