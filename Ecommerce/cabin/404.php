<?php 
/**
 * File index.php
 * berfungsi sebagai halaman 404
 * Error Page Not Found
 * Back Store
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

if (!defined('PILUS_SHOP'))
{
	header("HTTP/1.0 404 Not Found");
	header("Location: 403.php");
	exit;
}


?>


<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">404 - Page Not Found!</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">function leave() {  window.location = "<?php echo PL_CABIN; ?>index.php?module=dashboard";} setTimeout("leave()", 5000);</script>
