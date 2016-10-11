<?php
/**
 * Really Simple Syndication - RSS
 * Memuat semua tulisan yang diterbitkan
 * dalam standar RSS Feed
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

include_once('../../core/plcore.php');

// Memuat semua tulisan
$posts = "";
$articles = Post::findPosts(0,10);
$posts = $articles['results']; 

// Mengambil data pemilik toko
$metaowner = '';

$dataOwner = $option -> getOptions();

$metaowner = $dataOwner['results'];

foreach ( $metaowner as $owner ) {
	
   $namaToko = $owner -> getSite_Name();
   $keywords = $owner -> getMeta_Keywords();
}

// add content type header to ensure proper execution
header('Content-Type: text/xml');

// Output the XML declaration
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

?>

<rss version="2.0">
<channel>

	<title><?php echo $namaToko . " | " . $keywords; ?></title>
	<link><?php echo PL_DIR . "blog"; ?></link>
	<description>Feed Description</description>
	<language>id</language>
	<?php 
	   
	foreach ($posts as $post ):
	
	 // build the full URL to the post
	 $url = PL_DIR . 'read-'. $post -> getPost_Slug();
	
	// Format the date correctly for RSS pubDate
	$tanggal = date(DATE_RSS, strtotime($post -> getPost_Date()));
	
	// membuat paragraf post
	 $isi_post = htmlentities(strip_tags(nl2br($post -> getPost_Content())));
	 $isi = substr($isi_post, 0, 220);
	 $isi = substr($isi_post, 0, strrpos($isi," ")); 
	 
	?>
	
	<item>
	    <title><?php echo $post -> getPost_Title(); ?></title>
	    <description><?php echo html_entity_decode($isi); ?></description>
	    <link><?php echo $url; ?></link>
	    <guid><?php echo  $url; ?></guid>
	    <pubDate><?php echo $tanggal; ?></pubDate>
	</item>
	<?php endforeach; ?>
</channel>
</rss>