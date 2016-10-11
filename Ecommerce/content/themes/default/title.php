<?php
/**
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

if (!defined('PILUS_SHOP')) header("Location: 403.php");
	

$dbh = new Pldb(); //creating object $dbh

if (isset($_GET['slug'])) { 

	$slug = isset($_GET['slug']) ? htmlspecialchars(strip_tags($_GET['slug'])) : "";

	$sql = "SELECT product_name FROM pl_product WHERE slug = ? ";

	$cleaned = $sanitasi -> sanitasi($slug, 'xss');
	
	$data = array($cleaned);

	$sth = $dbh -> pstate($sql, $data);
	$title_opt = $sth -> fetchObject();

	if ($title_opt) {
		echo $title_opt->product_name;
	} else {

		echo "$siteName | $keywords";

	}

} elseif (isset($_GET['catslug'])) {
	
	$catslug = isset($_GET['catslug']) ? htmlspecialchars(strip_tags($_GET['catslug'])) : "";

	$sql = "SELECT product_cat FROM pl_product_category WHERE slug = ?";

	$data = array($catslug);

	$sth = $dbh -> pstate($sql, $data);
	$title_opt = $sth -> fetchObject();

	if ($title_opt) {
		echo $title_opt -> product_cat;
	} else {

		echo "$siteName | $keywords";

	}
	
} elseif (isset($_GET['blogid'])) {

	$postID = $sanitasi -> sanitasi($_GET['blogid'], 'xss');

	$sql = "SELECT post_title FROM pl_post WHERE post_slug = ? AND post_type = 'blog' ";

	$data = array($postID);

	$sth = $dbh -> pstate($sql, $data);

	$title_opt = $sth -> fetchObject();

	if ($title_opt) {
		echo  $title_opt -> post_title;
	} else {
		echo "$siteName | $keywords";
	}


} elseif (isset($_GET['articleid'])) {
	
	$postcatID = $sanitasi -> sanitasi($_GET['articleid'], 'xss');
	
	$sql = "SELECT postCat_name FROM pl_post_category WHERE slug = ?";
	
	$data = array($postcatID);
	
	$sth = $dbh -> pstate($sql, $data);
	
	$title_opt = $sth -> fetchObject();
	
	if ($title_opt) {
		echo  $title_opt -> postCat_name;
	} else {
	
		echo "$siteName | $keywords";
	}
	
} elseif (isset($_GET['pageid'])) {
	
	$pageid = isset($_GET['pageid']) ? htmlspecialchars(strip_tags($_GET['pageid'])) : "";
	
	$sql = "SELECT post_title FROM pl_post WHERE post_slug = ? AND post_type = 'page'";
	
	$data = array($pageid);
	
	$sth = $dbh -> pstate($sql, $data);
	
	$title_opt = $sth -> fetchObject();
	
	if ($title_opt) {
		echo  $title_opt -> post_title . ' | ' . $tagline;
	} else {
		
		echo "$siteName | $keywords";
	}
	
} else {

	echo "$siteName | $keywords";

}