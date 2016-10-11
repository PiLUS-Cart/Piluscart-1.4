<?php
/**
 * route.php
 * berfungsi untuk mengelola request 
 * konten dari browser
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

if(!isset($_SESSION['allow_access']) || (isset($_SESSION['allow_access']) && $_SESSION['allow_access'] !== true)) die('You are not allowed to access this directory');

if (isset($_GET['content']) && $_GET['content'] != '') {

	$content = htmlentities(strip_tags($_GET['content']));
	$contentLoaded = substr(strtolower(preg_replace('([^a-zA-Z0-9-/])', ' ', $content)), 0, 150);
	
}

if (isset($contentLoaded) && $contentLoaded != function_exists($contentLoaded) && isset($product_slug) && $product_slug == 0) {
	
	// jika fungsi konten halaman front store tidak ditemukan panggil 404 Error page
	include_once($contentError);

} else {

	// sebaliknya panggil fungsi konten halaman front store
	loadContent($contentLoaded);

}