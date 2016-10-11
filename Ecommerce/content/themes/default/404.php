<?php 
/**
 * 404.php front store
 */

if(!isset($_SESSION['allow_access']) || (isset($_SESSION['allow_access']) && $_SESSION['allow_access'] !== true)) die('You are not allowed to access this directory');

?>


<h2>404 Error!</h2>

<div class="cleaner h20"></div>

<div class="cleaner"></div>
<blockquote>
	<h3>Maaf, Halaman tidak ditemukan!</h3>
	404 - Page Not Found
</blockquote>
<script type="text/javascript">function leave() {  window.location = "<?php echo PL_DIR; ?>";} setTimeout("leave()", 5000);</script>

