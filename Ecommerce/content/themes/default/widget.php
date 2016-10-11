<?php
/**
 * File widget.php
 * berfungsi sebagai widget
 * di sisi footer dan sidebar tema default
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

// fungsi setup widget -- footer
function setup_widget($type, $position) 
{
	
	$dbh = new Pldb ();
	
	$html = '';
	
	if ($position == 'footer') :
		
		$html = '<div class="col col_16">';
	 

	elseif ($position == 'footer-right') :
		
		$html = '<div class="col col_13 no_margin_right">';
	

	endif;
	
	switch ($type) {
		
		case 'postcat' :
			
			$sql = "SELECT pc.ID, pc.postCat_name,
			pc.slug, pc.description, pc.actived,
			COUNT(p.ID) AS jml
			FROM pl_post_category pc
			LEFT JOIN pl_post AS p
			ON p.post_cat = pc.ID
			WHERE pc.actived = 'Y'
			GROUP BY pc.postCat_name DESC LIMIT 4 ";
			
			$sth = $dbh->query( $sql );
			
			$html .= '<h4>Blog</h4>';
			$html .= '<ul class="footer_menu">';
			
			while ( $postcat = $sth->fetch( PDO::FETCH_ASSOC ) ) {
				
				$html .= '<li><a href="article-'.$postcat['slug'].'" title="'.$postcat['postCat_name'].'">' . $postcat ['postCat_name'] . '(' . $postcat ['jml'] . ') </a></li>';
			}
			
			$html .= '</ul>';
			
			break;
		
		case 'staticpage' :
			
			$getStaticpage = "SELECT pg.ID, pg.post_image, pg.post_author,
		                     pg.post_date, pg.post_title, pg.post_slug, pg.post_content,
		                     pg.post_status, pg.post_type, pg.comment_status,
		                     i.filename, i.caption, i.slug, a.admin_login
		                     FROM `pl_post` AS pg
		                     INNER JOIN `pl_post_img` AS i ON pg.post_image = i.ID
		                     INNER JOIN pl_admin AS a ON pg.post_author = a.ID
		                     WHERE pg.post_type = 'page'
		                     ORDER BY pg.ID
		                     LIMIT 4";
			
			$sth = $dbh->query ( $getStaticpage );
			
			$html .= '<h4>Halaman</h4>';
			$html .= '<ul class="footer_menu">';
			
			while ($staticpage = $sth -> fetch(PDO::FETCH_ASSOC)) {
			
				$html .= '<li><a href="'.$staticpage['post_slug'].'.html" title="'.$staticpage['post_title'].'">'.$staticpage['post_title'].'</a></li>';
			}
			
			$html .= '</ul>';
			
			break;
			
		case 'productcategories' :
			
			$getProduct_Category = 'SELECT pc.ID, pc.product_cat, pc.slug,
			                        COUNT(p.ID) AS jml
			                        FROM pl_product_category pc
			                        LEFT JOIN pl_product AS p
			                        ON p.product_catId = pc.ID
			                        GROUP BY pc.product_cat DESC LIMIT 5';
			
			$sth = $dbh -> query($getProduct_Category);
			
			$html .= '<h4>Toko</h4>';
			$html .= '<ul class="footer_menu">';
			
			while ($prodcat = $sth -> fetch(PDO::FETCH_ASSOC)) {
				
				$html .= '<li><a href="kategori-'.$prodcat['slug'].'" title="'.$prodcat['product_cat'].'" >' . $prodcat['product_cat'] . ' (' . $prodcat['jml'] . ') </a></li>';
			}
			
			$html .= '</ul>';
			
			break;
			
		case 'customer' :
			
			$html .= '<h4>Kustomer</h4>';
			$html .= '<ul class="footer_menu">';
			$html .= '<li><a href="daftar-member" title="Registrasi Member">Daftar Member</a></li>';
			$html .= '<li><a href="testimoni" title="Testimoni kustomer" >Testimoni</a></li>';
			$html .= '</ul>';
			
			break;
			
		case 'aboutus':
			
			$html .= '<h4>Tentang Kami</h4>';
			
			$sql = "SELECT pg.ID, pg.post_image, pg.post_author,
			pg.post_date, pg.post_title, pg.post_slug, pg.post_content,
			pg.post_status, pg.post_type, pg.comment_status,
			img.filename, img.caption, img.slug
			FROM pl_post AS pg
			INNER JOIN pl_post_img AS img ON pg.post_image = img.ID
			WHERE pg.post_slug = ? AND pg.post_type = 'page'";
			
			$post_id = "tentang-kami";
			$data = array($post_id);
			$sth = $dbh -> pstate($sql, $data);
			$result = $sth -> fetchObject();
			// tampilkan hanya sebagian isi artikel
			$isi_artikel = strip_tags($result -> post_content);
			$isi = substr($isi_artikel, 0, 220);
			$isi = substr($isi_artikel, 0, strrpos($isi, " "));
			
			$html .= '<p>'.html_entity_decode($isi).'... <a href="'.$result -> post_slug.'.html" title="'.htmlspecialchars($result -> post_title).'" rel="nofollow"><strong>Selengkapnya</strong></a></p>';
			
			break;
			
	}
	
	$html .= '</div>';
	
	return $html;
	
}

// fungsi Set Menu Front End
function set_MenuFront() 
{
	
	global $content;
	
	$active = '';
	
	$dbh = new Pldb;
	
	$html = array();
	
	$main = "SELECT menu_id, menu_label, menu_link, menu_order, menu_role
			FROM pl_menu WHERE menu_role = 'public'";
	
	$sth = $dbh->query( $main );
	
	while ( $r = $sth->fetch() ) {
		
		if ($content == $r['menu_label']) {
			$active = 'class="active"';
		}
		
		$html[] = '<li><a href="' . $r['menu_link'] . '" ' . $active . ' title="'.$r['menu_label'].'">' . $r['menu_label'] . '</a>';
		
		$active = ''; // clear out the selected option flag
		
		$sub = "SELECT mc.menu_child_id, mc.menu_child_label, mc.menu_child_link,
		mc.menu_parent_id, mc.menu_grand_child, mc.menu_child_role,
		mp.menu_label, mp.menu_link, mp.menu_order, mp.menu_role
		FROM pl_menu_child AS mc
		INNER JOIN pl_menu AS mp ON mc.menu_parent_id = mp.menu_id
		AND mc.menu_parent_id='$r[menu_id]'
		AND mc.menu_grand_child=0 AND mc.menu_child_role='public'";
		
		$stmt = $dbh->query ( $sub );
		$jml = $stmt->rowCount();
		
		// apabila sub menu ditemukan
		if ($jml > 0) {
			
			$html[] = '<ul>';
			
			while ( $w = $stmt->fetch () ) {
				
				$html[] = '<li><a href="' . $w['menu_child_link'] . '">' . $w['menu_child_label'] . '</a>';
				
				$sub2 = "SELECT menu_child_id, menu_child_label, menu_child_link, menu_parent_id,
				menu_grand_child, menu_child_role FROM pl_menu_child
				WHERE menu_grand_child='$w[menu_child_id]'
				AND menu_grand_child!=0";
				
				$sth2 = $dbh->query ( $sub2 );
				
				$jml2 = $sth2->rowCount ();
				
				if ($jml2 > 0) {
					
					$html[] = '<ul>';
					
					while ( $s = $sth2->fetch () ) {
						
						$html[] = '<li><a href="' . $s['menu_child_link'] . '" title="'.$s['menu_child_label'].'">' . $s['menu_child_label'] . '</a></li>';
					}
					
					$html[] = '</ul></li>';
				}
			}
			
			$html[] = '</li></ul></li>';
			
		} 
		else {
			
			$html[] = '</li>';
		}
	}
	
	return implode ( "\n", $html );
	
}

// fungsi slider produk best seller
function product_slider() 
{
	$dbh = new Pldb ();
	
	$html = array ();
	
	$sql = 'SELECT ID, product_catId, product_name,
			slug, price, stock,
			weight, date_submited, bought,
			discount, image
			FROM pl_product ORDER BY bought DESC LIMIT 10';
	
	$sth = $dbh->query ( $sql );
	
	while ( $slide = $sth->fetch () ) {
		
		$html[] = '<div class=SlideItMoo_element>
				  <a  href="produk-' . $slide['slug'] . '" title="' . $slide['product_name'] . '" target="_parent">
				  <img src="content/uploads/products/thumbs/thumb' . $slide['image'] . '" alt="' . $slide['product_name'] . '">
				  </a></div>';
	}
	
	return implode ( "\n", $html );
	
}

// fungsi kategori produk sidebar kiri
function product_categories() 
{
	
	$dbh = new Pldb ();
	
	$html = array ();
	
	$sql = 'SELECT pc.ID, pc.product_cat, pc.slug,
			COUNT(p.ID) AS jml
			FROM pl_product_category pc
			LEFT JOIN pl_product AS p
			ON p.product_catId = pc.ID
			GROUP BY pc.product_cat';
	
	$sth = $dbh->query ( $sql );
	
	$html [] = '<h3>Kategori Produk</h3>';
	$html [] = '<ul class="sidebar_menu">';
	
	while ( $prodcat = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
		
		$html [] = '<li><a href="kategori-'.$prodcat['slug'].'"  title="'.$prodcat['product_cat'].'">' . $prodcat['product_cat'] . ' (' . $prodcat['jml'] . ') </a></li>';
	}
	
	$html [] = '</ul>';
	
	return implode ( "\n", $html );
}

// fungsi keranjang belanja
function side_ShoppingCart() 
{
	
	$dbh = new Pldb ();
	
	$html = array ();
	
	$sid = session_id ();
	
	$sql = "SELECT SUM(quantity*(price-(discount/100)*price)) AS total,SUM(quantity) AS totalquantity FROM pl_orders_temp
	INNER JOIN pl_product ON pl_orders_temp.product_id = pl_product.ID WHERE pl_orders_temp.temp_session = '$sid'";
	
	$sth = $dbh->query ( $sql );
	
	$html [] = '<h3> <img src="content/themes/default/images/cart.png" alt="free open source shopping cart system, CMS Toko Online, Aplikasi Toko Online Gratis"> Keranjang Belanja</h3>';
	$html [] = '<ul class="sidebar_menu">';
	
	while ( $result = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
		if ($result['totalquantity'] != '') {
			$html [] = '<li>(' . $result['totalquantity'] . ') item produk</li>';
			
			$html [] = '<li>Total: Rp.' . $total_rupiah = idrFormat( $result['total'] ) . '</li>';
			$html [] = '<li><a href="shopping-basket" title="keranjang belanja">Keranjang Belanja</a></li>';
			$html [] = '<li><a href="checkout-shopping" title="checkout, selesai belanja">Selesai Belanja</a></li>';
		} else {
			$html [] = '<li> 0 item produk </li>';
			$html [] = '<li> Total: Rp. 0 </li>';
		}
	}
	
	$html [] = '</ul>';
	
	return implode ( "\n", $html );
}

// fungsi sidebar customer_service dengan yahooMessengger
function customer_service() 
{
	
	$dbh = new Pldb ();
	
	$html = array ();
	
	$sql = "SELECT ymchat_id, name, openID
			FROM pl_ymchat ORDER BY ymchat_id DESC";
	$sth = $dbh->query ( $sql );
	
	if ( $sth -> rowCount() > 0)
	{
		
	   $html [] = '<h3>Customer Service</h3>';
	   $html [] = '<ul class="sidebar_menu">';

		while ( $result = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
		
			$html [] = '<li><a href="ymsgr:sendIM?' . $result['openID'] . '" title="customer service" rel="nofollow">
				    <img src=http://opi.yahoo.com/online?u=' . $result['openID'] . '&amp;m=g&amp;t=1 border=0 height=16 width=64 alt="PiLUS CMS, free open source e-commerce software">' . $result['name'] . '</a></li>';
		}
		
		$html [] = '</ul>';
	}
	else 
	{
		$html[] = '<h3>Customer Service</h3>';
		$html[] = '<ul class="sidebar_menu">';
		$html[] = '<li><a href="#"><b>#YM Live Chat</b></a></li>';
		$html[] = '</ul>';
	}
	
	return implode ( "\n", $html );
}

// fungsi sidebar bannerAdv
function banner_advertising($table) 
{
	
	$dbh = new Pldb ();
	
	$html = array ();
	
	if ($dbh->tableExists($table) == 1) {
		
		$sql = "SELECT banner_id, title, url, 
				image, uploadedOn FROM  " . $table . " ORDER BY banner_id DESC LIMIT 4";
		
		$sth = $dbh->query( $sql );
		
		
		$html[] = '<h3>Banner</h3>';
		$html[] = '<ul class="sidebar_menu">';
		
		if ($sth -> rowCount() > 0) {
	
			while ($result = $sth->fetch (PDO::FETCH_ASSOC)) {
					
				$html[] = '<li><a href="'.$result['url'].'" target="_blank"  title="'.$result['title'].'" rel="nofollow">
					  <img width=120 src="content/uploads/images/'.$result['image'].'" alt="'.$result['title'].'"></a></li>';
			}
			
		} else {
			
			$html[] = '<li><a href="#"><b>#Banner</b></a></li>';
			
		}
		
		$html [] = '</ul>';
		
	} 
	
	return implode ( "\n", $html);
	
}

// fungsi sidebar kategori tulisan 
function blog_categories() 
{
	
	$dbh = new Pldb;
	
	$html = array ();
	
	$sql = "SELECT pc.ID, pc.postCat_name,
			pc.slug, pc.description, pc.actived,
			COUNT(p.ID) AS jml
			FROM pl_post_category pc
			LEFT JOIN pl_post AS p
			ON p.post_cat = pc.ID
			WHERE pc.actived = 'Y'
			GROUP BY pc.postCat_name DESC LIMIT 4 ";
		
	$sth = $dbh->query ( $sql );
	
	
	$html [] = '<h3>Kategori Artikel</h3>';
	$html [] = '<ul class="sidebar_menu">';
	
	while ( $blogcat = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
	
		$html [] = '<li><a href="article-'.$blogcat['slug'].'" title="'.$blogcat['postCat_name'].'">' . $blogcat['postCat_name'] . ' (' . $blogcat['jml'] . ') </a></li>';
	}
	
	$html [] = '</ul>';
	
	return implode ( "\n", $html );
			
}

// fungsi sidebar download katalog produk
function katalog_product() 
{
	
	$dbh = new Pldb;
	
	$html = array();
	
	$numbers = "SELECT download_id FROM pl_download";
	
	$stmt = $dbh -> query($numbers);
	
	$jumlah = $stmt -> rowCount();
	
	if ( $jumlah > 0)
	{
		$sql = "SELECT download_id, title, filename, date_uploaded, 
				hits, slug FROM pl_download ORDER BY download_id DESC LIMIT 5";
		
		$sth = $dbh -> query($sql);
		
		$html [] = '<h3>Katalog</h3>';
		$html [] = '<ul class="sidebar_menu">';
		
		while ( $row = $sth -> fetchObject() ) {
		   
			 $html[] = '<li><a href="catalog.php?filename='.$row -> filename.'" title="'.$row -> title.'">'.$row -> title.'</a> ('.$row -> hits.')</li>';
		}
		
		$html [] = '</ul>';
	}
	else 
	{
		$html[] = '<h3>Katalog</h3>';
		$html[] = '<ul class="sidebar_menu">';
		$html [] = '<li><a href="#"><b>#Katalog</b></a></li>';
		$html[] = '</ul>';
		
	}
	
	return implode ( "\n", $html );
}

// fungsi sidebar jika member log in
function dataMember() 
{
		
	$html = array();
	
	$dbh = new Pldb;
	
	$sql = "SELECT ID, fullname, email, password, address, phone, district_id, shipping_id, 
			customer_type, customer_resetKey, customer_resetComplete,
			customer_session FROM pl_customers 
			WHERE customer_type='member' AND customer_session = ?";
	
	$data = array($_SESSION['member_session']);
	
	$stmt = $dbh -> pstate($sql, $data);
	
	$member = $stmt -> fetchObject();
	
	$html[] = '<h5><img src="content/themes/default/images/user.png" alt="PiLUS, Free Open Source Shopping Cart Sytem, CMS Toko Online"> '.$member -> fullname.'</h5>';
	$html[] = '<ul class="sidebar_menu">';
	$html[] = '<li><a href="my-profile&memberId='.$member -> ID.'&amp;memberToken='.$member -> customer_session.'" title="Edit Profil Member">Edit Profile</a></li>';
	$html[] = '<li><a href="ganti-password&memberId='.$member -> ID.'&amp;memberToken='.$member -> customer_session.'">Ganti Password</a></li>';
	$html[] = '<li><a href="riwayat-belanja&memberId='.$member -> ID.'&amp;memberToken='.$member -> customer_session.'" title="Riwayat Belanja">Riwayat Belanja</a></li>';
	$html[] = '<li><a href="kirim-testimoni&memberId='.$member -> ID.'&amp;memberToken='.$member -> customer_session .'">Kirim Testimoni</a></li>';
	$html[] = '<li><a href="member-logout" title="log out member">Log out</a></li>';
	$html[] = '</ul>';
	
	return implode ( "\n", $html );
}