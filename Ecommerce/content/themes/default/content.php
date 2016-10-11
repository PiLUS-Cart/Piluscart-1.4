<?php
/**
 * File content.php
 * memiliki peran sebagai 
 * tempat atau wadah fungsi-fungsi
 * konten ditampilkan
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

$content = null;
$contentLoaded = null;
$contentError = "404.php";
$keyword = isset($_POST['keyword']) ? htmlentities(htmlspecialchars($_POST['keyword']), ENT_QUOTES) : "";
$prodcat_slug = isset($_GET['catslug']) ? htmlentities(strip_tags($_GET['catslug'])) : "";
$product_slug = isset($_GET['slug']) ? htmlentities(strip_tags($_GET['slug'])) : "";
$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pageId = isset($_GET['pageid']) ? htmlentities(strip_tags($_GET['pageid'])) : "";
$blogId = isset($_GET['blogid']) ? htmlentities(strip_tags($_GET['blogid'])) : "";
$blogcat_slug = isset($_GET['articleid']) ? htmlentities(strip_tags($_GET['articleid'])) : "";
$productId = isset($_GET['productid']) ? abs((int)$_GET['productid']) : 0; // get product's ID global
$tempToken = isset($_GET['tempToken']) ? htmlentities(strip_tags($_GET['tempToken'])) : "";
$memberId = isset($_GET['memberId']) ? abs((int)$_GET['memberId']) : 0;
$memberToken = isset($_GET['memberToken']) ? htmlentities(strip_tags($_GET['memberToken'])) : "";

include_once('route.php');

// *****************HOMEPAGE*************** //

function homePage() 
{

	$html = array();

	$dbh = new Pldb;

	$sql = 'SELECT 	p.ID, p.product_catId, p.product_name, p.slug,
			p.description, p.price,
			p.stock, p.weight, p.date_submited, p.bought,
			p.discount, p.image, pc.ID AS catId,
			pc.product_cat, pc.slug AS catslug
			FROM pl_product p
			INNER JOIN pl_product_category AS pc
			ON p.product_catId = pc.ID
			ORDER BY p.ID DESC LIMIT 6';

	$sth = $dbh -> query($sql);

	$totalProduct = $sth -> rowCount();

	if ( $totalProduct > 0 ) {
		
		$html[] = '<h2>Produk Terbaru</h2>';
		
		$results = $sth -> fetchAll();
		
		foreach ( $results as $r => $result) :
		
		//Discount
		$harga = idrFormat($result['price']);
		$disc = ($result['discount']/100)*$result['price'];
		$hargadisc = number_format(($result['price']-$disc),0,",",".");
		
		$d = $result['discount'];
		$hargatetap = '<span class="product_price">Rp. '.$hargadisc.'</span>';
		$hargadiskon = '<span style="text-decoration:line-through;" class="product_price">Rp '.$harga.'</span>
			<br>diskon '.$d.'%
					<span class="discount_price">Rp. '.$hargadisc.'</span>';
		
		if ($d!=0) {
			
			$divharga = $hargadiskon;
		
		} else {
			
			$divharga = $hargatetap;
			
		}
		
		//tombol stok habis jika stok = 0
		$stok = $result['stock'];
		$tombolbeli = '<a href="index.php?content=basket&action=additem&amp;productid='.$result['ID'].'"  class="add_to_cart">Beli</a>';
		$tombolhabis = '<span class="prod_habis"></span>';
		
		if ( $stok != 0 ) {
			
			$tombol = $tombolbeli;
			
		} else {
			
			$tombol = $tombolhabis;
			
		}
		
		if ( $r % 3 == 0 ) :
		// tampil produk terbaru
		$html[] = '<div class="col col_14 product_gallery no_margin_right">';
		
		else :
		$html[] = '<div class="col col_14 product_gallery ">';
		
		endif;
		
		$html[] = '<a href="produk-'.$result['slug'].'" title="'.$result['product_name'].'">
			      <img src="content/uploads/products/thumbs/thumb'.$result['image'].'" height="200" width="140" alt="'.$result['product_name'].'" /></a>';
		$html[] = '<h3>'.$result['product_name'].'</h3>';
		$html[] = $divharga;
		$html[] = $tombol;
		$html[] = '</div>';
		
		
		endforeach;
		
	} else {
		
		$html[] = '<h2>Sorry ... </h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Produk Kosong!</h3>
					Data Produk tidak ditemukan
					</blockquote>';
		
	}
	
	return implode("\n", $html);

}

// **************Konten detail produk***************** //
function productDetail() 
{

	global $product_slug, $contentError;

	$html = array();

	$dbh = new Pldb;

	$sql = 'SELECT p.ID, p.product_catId, p.product_name, p.slug,
			p.description, p.price, p.stock, p.weight, p.date_submited, p.bought, 
			p.discount, p.image, pc.product_cat, pc.slug AS catslug
			FROM pl_product p
			INNER JOIN pl_product_category AS pc ON p.product_catId = pc.ID
			WHERE p.slug = ?';

	$data = array($product_slug);

	$sth = $dbh -> pstate($sql, $data);

	$result = $sth -> fetch();

	if ( $result['ID'] == 0 ) {
		
		require_once($contentError);
		
	} else {
		
		//Discount
		$harga = idrFormat($result['price']);
		$disc = ($result['discount']/100)*$result['price'];
		$hargadisc = number_format(($result['price']-$disc),0,",",".");

		$d = $result['discount'];
		$hargatetap = '<span class="product_price">Rp. '.$hargadisc.'</span>';
		$hargadiskon = '<span style="text-decoration:line-through;" class="product_price">Rp '.$harga.'</span>
				         <br>diskon '.$d.'%
						<br><span class="discount_price">Rp. '.$hargadisc.'</span>';

		if ($d!=0) {
			
			$divharga = $hargadiskon;
			
		} else {
			
			$divharga = $hargatetap;
			
		}

		//tombol stok habis jika stok = 0
		$stok = $result['stock'];
		$tombolbeli = '<a href="index.php?content=basket&amp;action=additem&amp;productid='.$result['ID'].'"  class="add_to_cart">Beli</a>';
		$tombolhabis = '<span class="prod_habis"></span>';

		if ( $stok != 0 ) {
			
			$tombol = $tombolbeli;
			
		} else {
			
			$tombol = $tombolhabis;
			
		}

		$html[] = '<h1>'.$result['product_name'].'</h1>';
		$html[] = "<div class='col col_13' >";
		$html[] = "<a href='#pilus' rel='facebox' title=$result[product_name] >
		          <img src=content/uploads/products/$result[image] alt=$result[product_name]  width=292 height=398 /></a>";
		$html[] = '<div id="pilus" style="display:none" ><center>
				<img src="content/uploads/products/'.$result['image'].'" alt="'.$result['product_name'].'" /><br />'.$result['product_name'].
				'</center></div>';
		$html[] = "</div>";
		$html[] = '<div class="col col_13 no_margin_right">';
		$html[] = '<table>';
		$html[] = '<tr>
				<td height="30" width="160">Kategori:</td>
				<td>'.$result['product_cat'].'</td>
						</tr>';
		$html[] = '<tr>
				<td height="30" width="160">Harga:</td>
				<td>'.$divharga.'</td>
						</tr>';
		$html[] = '<tr>
				<td height="30" width="160">Stok:</td>
				<td>'.$result['stock'].'</td>
						</tr>';
		$html[] = '</table>';
		$html[] = '<div class="cleaner h20"></div>';
		$html[] = $tombol;
		$html[] = '</div>';
		$html[] = '<div class="cleaner h30"></div>';
		$html[] = '<h4><strong>Deskripsi Produk</strong></h4>';
		$html[] = '<p>'.html_entity_decode($result['description']).'</p>';
		$html[] = '<div class="cleaner h50"></div>';


		//****************Produk yang lain(random)******************//
		$randomProduct = 'SELECT ID, product_catId, product_name, slug, description, price,
				stock, weight, date_submited, bought, discount, image
				FROM pl_product ORDER BY rand() LIMIT 3';

		$stmt = $dbh -> query($randomProduct);
		$results = $stmt -> fetchAll();

		$html[] = '<h5><strong>Produk Lainnya</strong></h5>';

		foreach ( $results as $r => $result) :

		//Discount
		$harga = idrFormat($result['price']);
		$disc = ($result['discount']/100)*$result['price'];
		$hargadisc = number_format(($result['price']-$disc),0,",",".");

		$d = $result['discount'];
		$hargatetap = '<span class="product_price">Rp. '.$hargadisc.'</span>';
		$hargadiskon = '<span style="text-decoration:line-through;" class="product_price">Rp '.$harga.'</span>
				        <br>diskon '.$d.'%
						<span class="discount_price">Rp. '.$hargadisc.'</span>';

		if ($d!=0){
			$divharga = $hargadiskon;
		} else {
			$divharga = $hargatetap;
		}

		//tombol stok habis jika stok = 0
		$stok = $result['stock'];
		$tombolbeli = '<a href="index.php?content=basket&amp;action=additem&amp;productid='.$result['ID'].'"  class="add_to_cart" title="beli sekarang" >Beli</a>';
		$tombolhabis = '<span class="prod_habis"></span>';

		if ( $stok != 0 ) {
			$tombol = $tombolbeli;
		} else {
			$tombol = $tombolhabis;
		}

		if ($r % 3 == 0) {
			$html[] = '<div class="col col_14 product_gallery no_margin_right">';
		} else {
			$html[] = '<div class="col col_14 product_gallery">';
		}

		$html[] = '<a href="produk-'.$result['slug'].'" title="'.$result['product_name'].'">
				<img src="content/uploads/products/thumbs/thumb'.$result['image'].'" alt="'.$result['product_name'].'" height="200" width="140" /></a>';
		$html[] = '<h3>'.$result['product_name'].'</h3>';
		$html[] = $divharga;
		$html[] = $tombol;
		$html[] = '</div>';

		endforeach;

		return implode("\n", $html);

	}

}

// ******************Konten Kategori Produk******************** //
function prodcatDetail() 
{

	global $prodcat_slug, $sanitasi;

	$html = array();

	$dbh = new Pldb;

	//menentukan nama kategori
	$sql = 'SELECT ID, product_cat, slug FROM pl_product_category WHERE slug = ?';

	$cleaned = $sanitasi -> sanitasi($prodcat_slug, 'xss');
	
	$data = array($cleaned);

	$sth = $dbh -> pstate($sql, $data);

	$row = $sth -> fetchObject();

	$p = new ProdCatPaging();
	$limit = 6;
	$position = $p -> getPosition($limit);

	$getProduct = "SELECT ID, product_catId, product_name, slug, description,
			price, stock, weight, date_submited, bought, discount, image
			FROM pl_product WHERE product_catId = :product_catId
			ORDER BY ID DESC LIMIT :position, :limit";

	$stmt = $dbh -> prepare($getProduct);
	$stmt -> bindValue(":product_catId", $row->ID, PDO::PARAM_INT);
	$stmt -> bindValue(":position", $position, PDO::PARAM_INT);
	$stmt -> bindValue(":limit", $limit, PDO::PARAM_INT);

	//apabila ditemukan produk dalam kategori
	$stmt -> execute();
	$jumlah = $stmt -> rowCount();

	if ( $jumlah > 0)
	{
		$results = $stmt -> fetchAll();

		$html[] = '<h2>'.$row->product_cat.'</h2>';
		
		foreach ($results as $r => $result ) :

		//Discount
		$harga = idrFormat($result['price']);
		$disc = ($result['discount']/100)*$result['price'];
		$hargadisc = number_format(($result['price']-$disc),0,",",".");

		$d = $result['discount'];
		$hargatetap = '<span class="product_price">Rp '.$hargadisc.'</span>';
		$hargadiskon = '<span style="text-decoration:line-through;" class="product_price">Rp '.$harga.'</span>
				        <br>diskon '.$d.'%
						<span class="discount_price">Rp '.$hargadisc.'</span>';

		if ($d!=0)
		{
			$divharga = $hargadiskon;
		}
		else
		{
			$divharga = $hargatetap;
		}

		//tombol stok habis jika stok = 0
		$stok = $result['stock'];
		$tombolbeli = '<a href="index.php?content=basket&amp;action=additem&amp;productid='.$result['ID'].'"  class="add_to_cart">Beli</a>';
		$tombolhabis = '<span class="prod_habis"></span>';

		if ( $stok != 0 )
		{
			$tombol = $tombolbeli;
		}
		else
		{
			$tombol = $tombolhabis;
		}

		if ($r % 3 == 0)
		{
			$html[] = '<div class="col col_14 product_gallery no_margin_right">';
		}
		else
		{
			$html[] = '<div class="col col_14 product_gallery ">';
		}

		
		$html[] = '<a href="produk-'.$result['slug'].'" title="'.$result['product_name'].'">
				<img src="content/uploads/products/thumbs/thumb'.$result['image'].'" alt="'.$result['product_name'].'" height="200" width="140" /></a>';
		$html[] = '<h3>'.$result['product_name'].'</h3>';
		$html[] = $divharga;
		$html[] = $tombol;
		$html[] = '</div>';

		endforeach;

		$id_cleaned = $sanitasi -> sanitasi($row->ID, 'sql');
		$jumlahData = "SELECT ID, product_catId, product_name, slug, description,
		price, stock, weight, date_submited, bought, discount, image
		FROM pl_product WHERE product_catId = '$id_cleaned'";

		$sth = $dbh -> query($jumlahData);
		$totalRows = $sth -> rowCount();

		// Halaman kategori produk
		$totalPage = $p -> totalPage($totalRows, $limit);
		$pageLink = $p -> navPage($_GET['halaman-kategori'], $totalPage); 


		$html[] = '<div class="pages">'.$pageLink.'</div>';

	}
	else
	{
		$html[] = '<h2>Sorry ... </h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Produk Kosong!</h3>
					Data Produk tidak ditemukan
					</blockquote>';
			
		$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
	}
	
	return implode("\n", $html);
	
}

// ***********************Konten contact form********************** //
function contactForm() 
{

	global $option, $sanitasi;

	$html = array();

	$data_option = $option -> getOptions();

	$meta_options = $data_option['results'];

	foreach ($meta_options as $meta_option) {
		
		$owneremail = $meta_option -> getOwnerEmail();
		$sitename = $meta_option -> getSite_Name();
		$shopaddress = $meta_option -> getShopAddress();
		$phone = $meta_option -> getNoTelpon();
		$fax = $meta_option -> getNoFaximile();
		$instagram = $meta_option -> getInstagramAccount();
		$twitter = $meta_option -> getTwitterAccount();
		$facebook = $meta_option -> getFacebookAccount();
		$pinBB = $meta_option -> getPinBB();
	}

	$nama = $email = $subjek = $pesan = "";
	
	$form_fields = array("nama"=>90, "email"=>180, "subjek"=>100, "pesan"=>250);
	
	if (isset($_POST['send']) && $_POST['send'] == 'Kirim') {
		
		$nama = preventInject($_POST['nama']);
		$email = trim(preventInject($_POST['email']));
		$subjek = preventInject($_POST['subjek']);
		$pesan = preventInject($_POST['pesan']);
		
		$badCSRF = true; // check CSRF
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
				|| $_POST['csrf'] !== $_SESSION['CSRF']) {
		
					$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					   <b>Sorry, there was a security issue.</b>
					   </blockquote>';
		
					$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
					$badCSRF = true;
		
		} elseif (empty($nama) || empty($email) 
				|| empty($subjek) || empty($pesan)) {
			
			$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
				    <b>semua kolom harus diisi! </b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
			
		} elseif (valid_email($email) == false) {
						
			$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Alamat Email tidak valid !</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
						
		} elseif ( !preg_match('/^[a-zA-Z ]*$/', $nama)) {
						
			$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Masukkan nama hanya menggunakan huruf!</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
					
		} elseif (!preg_match('/[^A-Za-z0-9.#\\-$]/', $subjek)) {
					
					$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Penulisan Subjek atau judul tidak valid !</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
					
		}  else {
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			if (contentSizeValidation($form_fields)) {}
			
			$data = array(
		
					'sender' => preventInject($nama),
					'email'  => isset($email) ? safeEmail($email) : '',
					'subject' => isset($subjek) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $subjek) : '',
					'messages' => preventInject($pesan),
					'date_sent' => date("Ymd"),
					'time_sent' => date("H:i:s")
			);
			
	$contactUs =  new Inbox($data);
		
	if ($contactUs -> sentMessage()) {
		
	$data_notifikasi = array(
		
		'notify_title' => "newMessage",
		'date_submited' => date("Y-m-d"),
		'time_submited' => date("H:i:s"),
		'content' => preventInject($pesan)
		
			
	);
		
			pushNotification($data_notifikasi);
		
		$html[] = '<h2>Pesan terkirim</h2>
				   <div class="cleaner h20"></div>
				   <div class="cleaner"></div>
				   <blockquote>
				  <h3>Terimakasih telah menghubungi kami</h3>
				Kami akan segera membalasnya ke email Anda
						  </blockquote>';
		
						$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
					}
		
				}
		
	} else {

		$html[] = '<h2>Hubungi Kami</h2>';
		$html[] = '<div class="col col_13">';
		$html[] = '<p>Hubungi kami secara online dengan mengisi form dibawah ini:</p>';
		$html[] = '<div id="contact_form">';
		$html[] = '<form method="post" name="contact" action="hubungi-kami" onSubmit="return validateContactForm(this)" >';
		$html[] = '<label for="author">Nama:</label> 
				   <input type="text" id="author" name="nama" class="required input_field" maxlength="90" />
				   <div class="cleaner h10"></div>';
		$html[] = '<label for="email">Email:</label> 
				   <input type="text" id="email" name="email" class="validate-email required input_field" maxlength="180" />
				   <div class="cleaner h10"></div>';
		$html[] = '<label for="subject">Subjek:</label> <input type="text" name="subjek" id="subject" class="input_field" maxlength="100" />
				  <div class="cleaner h10"></div>';
		$html[] = '<label for="pesan">Pesan:</label> 
				  <textarea id="text" name="pesan" rows="0" cols="0" class="required"></textarea>
				  <div class="cleaner h10"></div>';
		
		// create token for prevent CSRF
		$key= 'ABCDE1FGHI06JKLMNOPQRST88UVWXYZ1234567890!@#$%^&*()~`+-_|{}';
		$CSRF = sha1(mt_rand(1,1000000) . $key);
		$_SESSION['CSRF'] = $CSRF;
		
		$html[] = '<input type="hidden" name="csrf" value="'.$CSRF.'"/>';
		$html[] = '<input type="submit" value="Kirim" id="submit" name="send" class="submit_btn float_l" />';
		$html[] = '<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />';
		$html[] = '</form>';
		$html[] = '</div></div>';
		$html[] = '<div class="col col_13">';
		$html[] = '<h5>Lokasi:</h5>';
		$html[] = $sitename. '<br />';
		$html[] = $shopaddress . '<br />';
		$html[] = '<br /><br />';
		$html[] = '<strong>Phone:</strong> '.$phone.' <br />';
		if ($fax != '') {
			
			$html[] = '<strong>Fax:</strong> '.$fax.'<br />';
		}
		$html[] = '<strong>BBM:</strong> '.$pinBB . '<br />';
		$html[] = '<strong>E-mail:</strong> '. $owneremail . '<br />';
		$html[] = '<div class="cleaner divider"></div>';
		$html[] = '<div class="cleaner h30"></div>';
		$html[] = '</div>';
		$html[] = '<div class="cleaner h30"></div>';

	}

	return implode("\n", $html);

}

// ************************Konten keranjang belanja************************** //
function basket() 
{

	global $shoppingCart, $action, $productId, $sanitasi;

	$html = array();

	$dbh = new Pldb;

	$cleaned = $sanitasi -> sanitasi($productId, 'sql');

	if (isset($action) && $action == 'additem') {
		
		$sid = session_id();

		$getStok = "SELECT stock FROM pl_product WHERE ID = :ID";

		$stmt = $dbh -> prepare($getStok);
		$stmt -> execute(array(":ID" => $cleaned));
		$r = $stmt -> fetch();
		$stok = $r['stock'];

		if ( $stok == 0) {
			
			echo "<script>window.alert('Stok Habis!');
					window.location=('".PL_DIR."')</script>";
			
		} else {
			
			/**
			 * check if the product is already 
			 * in shopping cart table a.k.a pl_orders_temp
			 * for this session
			 */

			$getProduct = "SELECT product_id FROM pl_orders_temp WHERE product_id = :product_id AND temp_session = :temp_session";
				
			$sth = $dbh -> prepare($getProduct);
			$sth -> bindValue(":product_id", $cleaned);
			$sth -> bindValue(":temp_session", $sid);

			$sth -> execute(array(":product_id" => $cleaned, "temp_session" => $sid));
				
			$ketemu = $sth -> rowCount();
				
			if ( $ketemu == 0) {
				
				// put the product in cart table a.k.a pl_orders_temp

				$tgl_sekarang = date("Ymd");
				$jam_sekarang = date("H:i:s");

				$data = array(

						'product_id' => $productId,
						'temp_session' => $sid,
						'quantity' => 1,
						'date_orders_temp' => $tgl_sekarang,
						'time_orders_temp' => $jam_sekarang,
						'temp_stock' => $stok);

				$add_item = new ShoppingCart($data);
				$add_item -> addItem();
				
			} else {
				
				// update product quantity in cart table
				$data = array(
						'temp_session' => $sid,
						'product_id' => $productId);

				$update_qty = new ShoppingCart($data);
				$update_qty -> updateQuantity();
				
			}

			$deleteAbandonedCart = $shoppingCart -> cleanCart();

			header('Location:shopping-basket');
			
		}

	} elseif (isset($action) && $action == 'deleteitem') {

		if (isset($productId)) {
			
			$data = array('orders_temp_id' => $productId);
			$deleteItem = new ShoppingCart($data);
			$deleteItem -> deleteItem();
				
			header('Location:shopping-basket');
			
		}

	} elseif ( isset($action) && $action == 'updateitem') {

		$id = $_POST['id'];
		$jml_data = count($id);
		$jumlah = $_POST['jml'];

		for ($i=1; $i <= $jml_data; $i++) {
			
			$sql = "SELECT temp_stock FROM pl_orders_temp WHERE orders_temp_id = ? ";
			$data = array($id[$i]);
			$sth = $dbh -> pstate($sql, $data);

			while ($r = $sth -> fetch()) {
					
				if ($jumlah[$i] > $r['temp_stock']) {
					
					echo "<script>window.alert('Jumlah yang dibeli melebihi stok yang ada');
							window.location=('shopping-basket')</script>";
					
				} elseif ($jumlah[$i] == 0) {
					
					echo "<script>window.alert('Anda tidak boleh menginputkan angka 0 atau mengkosongkannya!');
							window.location=('shopping-basket')</script>";
					
				} else {
					
					$data = array('quantity' => $jumlah[$i], 'orders_temp_id' => $id[$i]);
					$update_item = new ShoppingCart($data);
					$update_item -> updateItem();

					header('Location: shopping-basket');
					
				}
				
			}
			
		}

	} else {

		$sid = session_id();

		// cek sesi temporer produk dan hitung jumlahnya
		$ketemu = $shoppingCart -> checkItems($sid);

		// mendapatkan isi dalam keranjang belanja
		$basket = '';
		$basket = $shoppingCart -> getBasket($sid);
		$items = $basket['results'];
		$total_item = $basket['totalRows'];

		if ( $ketemu < 1)
		{
			$html[] = '<h2>Terjadi Kesalahan!</h2>
					<div class="cleaner"></div>
					<blockquote>
					<h3>Maaf, Keranjang belanja masih kosong!</h3>
					Silahkan Anda belanja terlebih dahulu
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
		}
		else
		{
			$html[] =  '<h2><img src="content/themes/default/images/cart.png" > Keranjang Belanja</h2>';
			$html[] =  '<form method="post" action="index.php?content=basket&action=updateitem">';
			$html[] =  '<table width="700px" cellspacing="0" cellpadding="5">
					<tr bgcolor="#CCCCCC">

					<th width="220" align="left">Produk </th>
					<th width="180" align="left">Nama Produk </th>
					<th width="60" align="center">Qty </th>
					<th width="60" align="left">Berat(Kg) </th>
					<th width="60" align="right">Harga </th>
					<th width="60" align="right">Total </th>
					<th width="90" align="center">Hapus</th></tr>';


			$no = 1;
			foreach ($items as $item ) :

			$stok = $item -> getProduct_Stock();
			$discount = $item -> getProduct_Discount();
			$jumlah = $item -> getQuantity();
			$product_name = $item -> getProduct_Name();
			$product_image = $item -> getProduct_Image();
			$id_orders_temp = $item -> getOrderTempId();
			$price = $item -> getProduct_Price();

			$disc = ($discount/100)*$price;
			$hargadisc = number_format(($price-$disc),0,",",".");

			$subtotal = ($price-$disc) * $jumlah;
			$total = $total + $subtotal;
			$subtotal_rp = idrFormat($subtotal);
			$total_rp = idrFormat($total);
			$harga = idrFormat($price);

			$html[] =  "<tr>
			<input type=hidden name=id[$no] value=$id_orders_temp />
			<td><img src=content/uploads/products/thumbs/thumb$product_image  alt=$product_name /></td>
			<td>$product_name</td>";

			$html[] =  "<td align='center'>
			<select name=jml[$no] value=$jumlah onChange='this.form.submit()'>";

			for ( $j=1;$j <= $stok;$j++ )
			{
				if ( $j == $jumlah)
				{
					$html[] =  "<option selected>$j</option>";
				}
				else
				{
					$html[] =  "<option>$j</option>";
				}
			}

			$html[] =  '</select></td>
					<td align="center">'.$item -> getProduct_Weight().' </td>
					<td align="right">'.$hargadisc.'</td>
					<td align="right">'.$subtotal_rp.'</td>
					<td align="center">
					<a href="index.php?content=basket&action=deleteitem&productid='.$item -> getOrderTempId().'">
				    <img src="content/themes/default/images/remove.png" alt=Batal /><br />Hapus</a>
					</td>
				</tr>';

			$no++;

			endforeach;

			$html[] = '<tr>
					<td colspan="3" align="right"  height="40px"><a href="javascript:history.go(-1)" class="button">Lanjutkan Belanja</a></td>
					<td align="right" style="background:#ccc; font-weight:bold"> Total Rp. </td>
					<td align="right" style="background:#ccc; font-weight:bold">'.$total_rp.'**)</td>
					<td style="background:#ccc; font-weight:bold"> </td>
					</tr>
					</table></form>';

			$html[] = '<div style="float:right; width: 215px; margin-top: 20px;">
					  <div class="checkout"><a href="checkout-shopping" class="button">Selesai Belanja</a></div>
					  <div class="cleaner h20"></div>
					  </div>';
		    
			$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
			
			$html[] = '<p>**)Total Harga diatas belum termasuk ongkos kirim yang akan dihitung saat <b>Check Out</b></p>';
		}

		return implode("\n", $html);

	}

}


// *********************Konten CheckOut - Selesai Belanja******************* //
function checkOut() 
{

	global $shoppingCart;

	$html = array();

	$dbh = new Pldb;

	$sid =  session_id();

	// cek sesi temporer produk dan hitung jumlahnya
	$ketemu = $shoppingCart -> checkItems($sid);

	// mendapatkan isi dalam keranjang belanja
	$checkout = $shoppingCart -> getBasket($sid);
	$items = $checkout['results'];
	$total_item = $checkout['totalRows'];

	if ($ketemu < 1) {
		
		header('Location: ./');
		exit(0);
		
	}
	elseif (isset($_SESSION['memberLoggedIn'])) // check out bagi member yang login
	{
		$email = $_SESSION['member_email'];
		$password = $_SESSION['member_pass'];
		
		$html[] = '<h2><img src="content/themes/default/images/checkout.png" > Checkout</h2>';
		$html[] = '<h5><strong>Konfirmasi keranjang belanja</strong></h5>';
		$html[] = '<p>Berikut ini adalah data pembelian anda,
				   Silahkan tekan tombol check out untuk menyelesaikan belanja !</p>';
		$html[] = '<form method="post" action="member-transaction" />';
		$html[] = '<div class="cleaner h50"></div>';
		
		$html[] =  '<table width="700px" cellspacing="0" cellpadding="5">
					<tr bgcolor="#CCCCCC">
		
					<th width="220" align="left">Produk </th>
					<th width="180" align="left">Nama Produk </th>
					<th width="60" align="center">Qty </th>
					<th width="60" align="left">Berat(Kg) </th>
					<th width="60" align="right">Harga </th>
					<th width="60" align="right">Total </th>
					</tr>';
		
		
		$no = 1;
		foreach ($items as $item ) :
		
		$discount = $item -> getProduct_Discount();
		$jumlah = $item -> getQuantity();
		$product_name = $item -> getProduct_Name();
		$product_image = $item -> getProduct_Image();
		$id_orders_temp = $item -> getOrderTempId();
		$price = $item -> getProduct_Price();
		$weight = $item -> getProduct_weight();
			
		$disc = ($discount/100)*$price;
		$hargadisc = number_format(($price-$disc),0,",",".");
		
		$subtotal = ($price-$disc) * $jumlah;
		$total = $total + $subtotal;
		$subtotal_rp = idrFormat($subtotal);
		$total_rp = idrFormat($total);
		$harga = idrFormat($price);
		$subtotalberat = $weight * $jumlah; // total berat per item produk
		$totalberat = $totalberat + $subtotalberat; // total berat semua produk yang dibeli
		
		
		$html[] =  "<tr>
		<input type=hidden name=id[$no] value=$id_orders_temp />
		<td><img src=content/uploads/products/thumbs/thumb$product_image  alt=$product_name /></td>
		<td>$product_name</td>";
		
		$html[] =  '<td align="center">'.$jumlah.'</td>';
		
		$html[] =  '<td align="center">'.$item -> getProduct_Weight().' </td>
					<td align="right">'.$hargadisc.'</td>
					<td align="right">'.$subtotal_rp.'</td>
			
				</tr>';
		
		$no++;
		
		endforeach;
		
		$html[] = '<tr>
					<td colspan="3" align="right"  height="40px"><a href="javascript:history.go(-1)" class="button">Kembali</a></td>
					<td align="right" style="background:#ccc; font-weight:bold"> Total Rp. </td>
					<td align="right" style="background:#ccc; font-weight:bold">'.$total_rp.'**</td>
					<td style="background:#ccc; font-weight:bold"> </td>
					</tr>
					</table>';
		
		$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
		
	 $html[] = '<a href="member-transaction" title="checkout shopping" class="button">Check Out</a>';
		
		
		$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
		
		$html[] = '<p>**)Total Harga diatas belum termasuk ongkos kirim yang akan dihitung saat <b>Check Out</b></p>';
		
	}
	else
	{
		
		$html[] = '<h2><img src="content/themes/default/images/checkout.png" > Checkout</h2>';
		
		$html[] = '<h5><strong>Konfirmasi Keranjang Belanja</strong></h5>';
		$html[] =  '<table width="700px" cellspacing="0" cellpadding="5">
					<tr bgcolor="#CCCCCC">
		
					<th width="220" align="left">Produk </th>
					<th width="180" align="left">Nama Produk </th>
					<th width="60" align="center">Qty </th>
					<th width="60" align="left">Berat(Kg) </th>
					<th width="60" align="right">Harga </th>
					<th width="60" align="right">Total </th>
					</tr>';
		
		
		$no = 1;
		foreach ($items as $item ) :
		
		$discount = $item -> getProduct_Discount();
		$jumlah = $item -> getQuantity();
		$product_name = $item -> getProduct_Name();
		$product_image = $item -> getProduct_Image();
		$id_orders_temp = $item -> getOrderTempId();
		$price = $item -> getProduct_Price();
		$weight = $item -> getProduct_weight();
			
		$disc = ($discount/100)*$price;
		$hargadisc = number_format(($price-$disc),0,",",".");
		
		$subtotal = ($price-$disc) * $jumlah;
		$total = $total + $subtotal;
		$subtotal_rp = idrFormat($subtotal);
		$total_rp = idrFormat($total);
		$harga = idrFormat($price);
		$subtotalberat = $weight * $jumlah; // total berat per item produk
		$totalberat = $totalberat + $subtotalberat; // total berat semua produk yang dibeli
		
		
		$html[] =  "<tr>
		<input type=hidden name=id[$no] value=$id_orders_temp />
		<td><img src=content/uploads/products/thumbs/thumb$product_image  alt=$product_name /></td>
		<td>$product_name</td>";
		
		$html[] =  '<td align="center">'.$jumlah.'</td>';
		
		$html[] =  '<td align="center">'.$item -> getProduct_Weight().' </td>
					<td align="right">'.$hargadisc.'</td>
					<td align="right">'.$subtotal_rp.'</td>
			
				</tr>';
		
		$no++;
		
		endforeach;
		
		$html[] = '<tr>
					<td colspan="3" align="right"  height="40px"><a href="javascript:history.go(-1)" class="button">Kembali</a></td>
					<td align="right" style="background:#ccc; font-weight:bold"> Total Rp. </td>
					<td align="right" style="background:#ccc; font-weight:bold">'.$total_rp.'**</td>
					<td style="background:#ccc; font-weight:bold"> </td>
					</tr>
					</table>';
		
		$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
		
		$html[] = '<p>**)Total Harga diatas belum termasuk ongkos kirim yang akan dihitung saat <b>Check Out</b></p>';
		
		// cek out untuk member yang tidak login
		$html[] = '<div class="cleaner h50"></div>';
		$html[] = '<h5><strong>Kustomer Lama</strong></h5>';
		$html[] = '<p>Jika ini bukan pertama kalinya anda melakukan pembelian di toko kami,
				  <br />Silahkan Masuk dengan mengisi email dan password anda.</p>';
		$html[] = '<form name="member" method="post" action="member-transaction" onSubmit="return validasiMember(this)" >';
		// Email member
		$html[] = '<div class="col col_13 checkout">';
		$html[] = 'Email
				  <input type="text" name="email"  style="width:300px;"  maxlength="150" />';
		$html[] = '</div>';
		// Password member
		$html[] = '<div class="col col_13 checkout">
				  Password
				 <input type="password" name="password"  style="width:300px;" maxlength="32"  />
				 <span style="font-size:10px"><a href="lupa-katasandi">Lupa Password ?</a></span>
				</div>';
		$html[] = '<input type="submit" name="login" class="button" value="Masuk"><br />';
		$html[] = '</form>';


		// cek out untuk kustomer baru
		$html[] = '<div class="cleaner h50"></div>';
		$html[] = '<h5><strong>Kustomer Baru</strong></h5>';
		$html[] = '<p>Jika ini pertama kalinya anda melakukan pembelian di toko kami,
				<br />Silahkan isi form berikut untuk menyelesaikan proses pembelian anda.</p>';
		$html[] = '<form method="post" name="form" action="transaction" onSubmit="return validasi(this)" >';
		// nama lengkap
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Nama Lengkap:
				  <input type="text" name="nama_lengkap" style="width:300px;" maxlength="150"  />
				  </div>';
		// alamat pengiriman
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Alamat Pengiriman(lengkap):
				   <input type="text" name="alamat" style="width:300px;" maxlength="255" />
				   </div>';
		// telepon
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Telepon/Hp:
				<input type="text" name="telpon"  style="width:300px;"  />
				</div>';
		
		// e-mail
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Email:
				<input type="text" name="email"  style="width:300px;" maxlength="150"  />
				</div>';
		
		// password
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Password:
				  <input type="password" name="password" style="width:300px;" maxlength="32" />
				  </div>';
		
		// konfirmasi password
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Ketik ulang password:
				   <input type="password" name="confirmed" style="width:300px;" maxlength="32" />
				  </div>';
		
		// Kota tujuan
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '*Kota Tujuan:';
		
		$getCity = "SELECT DISTINCT district_id, district_name
				    FROM pl_district ORDER BY district_id";
		
		$html[] = '<select name="city" ><option value="0" selected>--Pilih Kota Tujuan--</option>';
		
		foreach ( $dbh -> query($getCity) as $city )
		{
			$html[] = "<option value=$city[district_id]>$city[district_name]</option>";
		}
		
		$html[] = '</select></div>';
		
		// Jasa Pengiriman
		$html[] = '<div class="col col_13 checkout" >
				*Pengiriman:<br>';
		
		$getShipping = "SELECT shipping_id, shipping_name, shipping_logo
				       FROM pl_shipping ORDER BY shipping_name";
		
		$html[] = '<select name="shipping" ><option value="0" selected>--Pilih Jasa Pengiriman--</option>';
			
		foreach ( $dbh -> query($getShipping) as $shipping)
		{
			$html[] = "<option value=$shipping[shipping_id]>$shipping[shipping_name]</option>";
		}
	    
		$html[] = '</select>';
		$html[] = '</div>';
				
		$html[] = '<div class="cleaner h50"></div>';
		$html[] = '<div class="col col_13 checkout">';
		$html[] = '<h4>Checkout Options*:</h4>';
		$html[] = '<p><input type="radio" name="tipe" value="member" />Belanja sekaligus daftar member</p>';
		$html[] = '<p><input type="radio" name="tipe" value="guest" />Belanja saja</p>';
		$html[] = '</div>';
		
		$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
		
		$html[] = '<input type="submit" name="checkout" class="button" value="Proses">';
		$html[] = '</form>';
		
	}
	
	return implode("\n", $html);
	
}


// ****************Konten simpan transaksi kustomer lama**************** //
function memberTransaction() 
{

global $shoppingCart, $customer, $order, $action;

$html = array();

$dbh = new Pldb;

if (!$customer -> isMemberLoggedIn())  {
	
	if (isset($_POST['login']) && $_POST['login'] == 'Masuk') {
		
		$email = isset($_POST['email']) ? preventInject($_POST['email']) : "";
		$password = isset($_POST['password']) ? preventInject($_POST['password']) : "";
		
		$member = new Customer(array('email'=>$email, 'password' => $password));
		
		if (empty($email) || empty($password)) {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Kolom e-mail dan password harus diisi!
					</blockquote>';
	
			$html[] = '<script type="text/javascript">function leave() { window.location = "checkout-shopping";} setTimeout("leave()", 2640);</script>';
	
		}
		elseif ($customer -> emailExists($email) == false)  {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Alamat email tidak terdaftar di dalam sistem basis data kami.
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	
		}
		elseif (is_valid_email_address(trim($email)) == 0) {
				
			$html[] = '<h2>Error</h2>
			  <div class="cleaner"></div>
			  <blockquote>
			  <h3>Terjadi Kesalahan!</h3>
			   Silahkan isi alamat E-mail anda dengan benar
			   </blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
				
		}
		elseif (!ctype_alnum($password)) {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password anda tidak valid!
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	
		}
		elseif (strlen($password) < 6) // cek jumlah karakter password
		{
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password kurang dari 6 karakter!
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	
		}
		elseif (!$loggedInMember = $member -> validateCustomer())
		{
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
				   Email atau password anda tidak benar!
				</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	
		}
		else
		{
	
			// mendapatkan data kustomer
			$data_kustomer = $customer -> getCustomer($email, $password);
	
			// member tidak login terlebih dahulu untuk belanja dan data member tidak lengkap
			if ( $data_kustomer -> phone == '')
			{
				$html[] = '<h2>Proses Checkout Belum Lengkap !</h2>';
				$html[] = '<h5><strong>Lengkapi data yang diminta berikut:</strong></h5>';
				$html[] = '<p>Silahkan lengkapi form di bawah ini, untuk menyelesaikan proses belanja.</p>';
				$html[] = '<form method="post" name="form" action="index.php?content=membertransaction&action=updateMember&customerId='.$data_kustomer -> ID.'" onSubmit="return validasi(this)" >';
				$html[] = '<input type="hidden" name="customer_id" value="'.$data_kustomer -> ID.'">';
				$html[] = '<input type="hidden" name="customer_type" value="'.$data_kustomer -> customer_type.'">';
				
				// alamat pengiriman
				$html[] = '<div class="col col_13 checkout">';
				$html[] = '*Alamat Pengiriman(lengkap):
				   <input type="text" name="address" style="width:300px;" maxlength="255" />
				   </div>';
				
				// telepon
				$html[] = '<div class="col col_13 checkout">';
				$html[] = '*Telepon/Hp:
				<input type="text" name="phone"  style="width:300px;"  />
				</div>';
				
				
				// Kota tujuan
				$html[] = '<div class="col col_13 checkout">';
				$html[] = '*Kota Tujuan:';
				
				$getCity = "SELECT DISTINCT district_id, district_name
				    FROM pl_district ORDER BY district_id";
				
				$html[] = '<select name="city" ><option value="0" selected>--Pilih Kota Tujuan--</option>';
				
				foreach ( $dbh -> query($getCity) as $city )
				{
					$html[] = "<option value=$city[district_id]>$city[district_name]</option>";
				}
				
				$html[] = '</select></div>';
				
				// Jasa Pengiriman
				$html[] = '<div class="col col_13 checkout" >
				*Pengiriman:<br>';
				
				$getShipping = "SELECT shipping_id, shipping_name, shipping_logo
				       FROM pl_shipping ORDER BY shipping_name";
				
				$html[] = "<select name='shipping' ><option value='0' selected>--Pilih Jasa Pengiriman--</option>";
					
				foreach ( $dbh -> query($getShipping) as $shipping)
				{
					$html[] = "<option value=$shipping[shipping_id]>$shipping[shipping_name]</option>";
				}
				 
				$html[] = '</select>';
				$html[] = '</div>';
				
				$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
				// create token for prevent CSRF
				$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
				$CSRF = sha1(mt_rand(0,999) . $key);
				$_SESSION['CSRF'] = $CSRF;
				
				$html[] = '<input type="hidden" name="csrf" value="'.$CSRF.'"/>';
				$html[] = '<input type="hidden" name="fullname" value="'.$data_kustomer -> fullname.'"/>';
				$html[] = '<input type="hidden" name="email" value="'.$data_kustomer -> email.'"/>';
				$html[] = '<input type="submit" name="send" class="button" value="Proses">';
				$html[] = '</form>';
				
			}
			else
			{
				
				// member tidak login dahulu untuk belanja dan data member lengkap
				$transaksi_member = $order -> simpanTransaksiMember($email, $password);
					
				$html[] = '<h2>Proses Transaksi Selesai</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Segera cek Email anda!</h3>
					<p>Data pembelian serta nomor rekening transfer sudah terkirim ke email Anda. <br />
					Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p>
					</blockquote>';
					
				$html[] = '<script type="text/javascript">function leave() {  window.location = "./";} setTimeout("leave()", 15000);</script>';
				
			}
	
		}
		
	}
	elseif ( isset($action) && $action == 'updateMember')
	{
		if (isset($_POST['send']) && $_POST['send'] == 'Proses')
		{
			
			$id_member = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : "";
			$telpon_member = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
			$alamat_member = isset($_POST['address']) ? preventInject($_POST['address']) : "";
			$kota = isset($_POST['city']) ? (int)$_POST['city'] : 0;
			$pengiriman = isset($_POST['shipping']) ? (int)$_POST['shipping'] : 0;
			
			$badCSRF = true; // check CSRF
			
			if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
					|| $_POST['csrf'] !== $_SESSION['CSRF'])
			{
				$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
			
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
			
				$badCSRF = true;
			}
			elseif (empty( $telpon_member) || empty($alamat_member))
			{
				$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Alamat dan Nomor telepon harus diisi!</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
			}
			elseif ($kota == 0 || $pengiriman == 0)
			{
				$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Kota tujuan dan pengiriman harus diisi!</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
			}
			// cek alamat
			elseif (!preg_match('/^[A-Z0-9 \',.#-]{2,255}$/i', $alamat_member))
			{
				$html[] = '<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Silahkan isi alamat denga benar, maksimal 255 kata</b><br />
					<a href=javascript:history.go(-1)>Ulangi</a>
					</blockquote>';
			}
			// cek no_telpon
			elseif (!preg_match('/^[0-9]{10,13}$/', $telpon_member))
			{
				$html[] = '<h2>Error</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi nomor telepon dengan benar
					</blockquote>';
				$html[] = '<script type="text/javascript">function leave() { window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
			}
			else 
			{
				$badCSRF = false;
				unset($_SESSION['CSRF']);
				
				$data = array(
						
						'ID' => $id_member,
						'fullname' => isset($_POST['fullname']) ? preventInject($_POST['fullname']) : '',
						'email' => isset($_POST['email']) ? preventInject($_POST['email']) : '',
						'address' => $alamat_member,
						'phone' => $telpon_member,
						'district_id' => $kota,
						'shipping_id' => $pengiriman,
				);
				
				$update_dataMember = new Customer($data);
				$lastUpdated = $update_dataMember -> updateMemberById();
				
				if ( $lastUpdated == 1) {
					$transaksi_member = $order -> simpanTransaksiMember($email, $password);
				}
			}
			
		}
	}
	
} else {
	
	// mendapatkan data kustomer
	$data_kustomer = $customer -> getCustomerBySession($_SESSION['member_session']);
	
	// member login dahulu untuk belanja dan data member tidak lengkap
	if ( $data_kustomer -> getCustomerPhone() == '') {
		
		$html[] = '<h2>Proses Checkout Belum Lengkap!</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3> Lengkapi data profile!</h3>
				   Silahkan lengkapi data profile anda, untuk menyelesaikan proses belanja. Terima Kasih !
				</blockquote>';
		$html[] = '<script type="text/javascript">function leave() {  window.location = "my-profile&memberId='.$data_kustomer -> getId().'&memberToken='.$data_kustomer -> getCustomer_Session().'";} setTimeout("leave()", 6640);</script>';
	
	} else {
		
		// member login dahulu untuk belanja dan data member lengkap
		$transaksi_member = $order -> simpanTransaksiMember($_SESSION['member_email'], $_SESSION['member_pass']);
		
		$html[] = '<h2>Proses Transaksi Selesai</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Segera cek Email anda!</h3>
					<p>Data pembelian serta nomor rekening transfer sudah terkirim ke email Anda. <br />
					Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p>
					</blockquote>';
		
		$html[] = '<script type="text/javascript">function leave() {  window.location = "./";} setTimeout("leave()", 15000);</script>';
		
	}
	
}

return implode("\n", $html);

}

// ***************Konten simpan transaksi******************** //
function saveTransaction() 
{

	global $shoppingCart, $customer, $order;

	$html = array();

	if (isset($_POST['checkout']) && $_POST['checkout'] == 'Proses') {
		
	   if (empty($_POST['nama_lengkap']) || empty($_POST['alamat']) 
	   		|| empty($_POST['telpon']) || empty($_POST['email']) 
	   		|| empty($_POST['password']) || empty($_POST['confirmed']) 
	   		|| $_POST['city'] == 0 || $_POST['shipping'] == 0) {
	   			
	   			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Kolom yang bertanda asterik(*)harus diisi!
					</blockquote>';
	   			
	   			$html[] = '<script type="text/javascript">function leave() { window.location = "checkout-shopping";} setTimeout("leave()", 2640);</script>';
	   			 
	   } elseif (!preg_match('/^[A-Z \'.-]{2,120}$/i', $_POST['nama_lengkap'])) {
	   	
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi nama lengkap anda dengan benar
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   	
	   } elseif (!preg_match('/^[A-Z0-9 \',.#-]{2,255}$/i', $_POST['alamat'])) {
	   	
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi alamat anda dengan benar, maksimal 255 kata.
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() { window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   	
	   } elseif (!is_numeric($_POST['telpon'])) {
	   	    
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi nomor telepon dengan benar
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() { window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   	
	   } elseif (is_valid_email_address($_POST['email']) == 0) {
	   	  
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi alamat E-mail anda dengan benar
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   		
	   } elseif ($customer -> emailExists($_POST['email']) == true) {
	   
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Alamat email sudah dipakai.Silahkan Masuk sebagai kustomer lama
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   
	   } elseif (!isset($_POST['password']) || !isset($_POST['confirmed']) 
	   		|| !$_POST['password'] || !$_POST['confirmed'] 
	   		|| $_POST['password'] != $_POST['confirmed']) {
	   
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password yang anda ketik tidak sama.
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   		
	   } elseif (is_numeric($_POST['password'])) {
	   
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password yang anda ketik tidak valid.
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   
	   } elseif (strlen($_POST['password']) < 6) { 
	   
	   	$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password tidak boleh kurang dari 6 karakter!
					</blockquote>';
	   	$html[] = '<script type="text/javascript">function leave() {  window.location = "checkout-shopping";} setTimeout("leave()", 3640);</script>';
	   
	  
	   } else {
	   	   
	   	// proses transaksi kustomer baru
	   	$data_kustomer = array(
	   	
	   			'fullname' => preventInject($_POST['nama_lengkap']),
	   			'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
	   			'password' => preventInject($_POST['password']),
	   			'address' => $_POST['alamat'],
	   			'phone' => $_POST['telpon'],
	   			'district_id' => (int)$_POST['city'],
	   			'shipping_id' => (int)$_POST['shipping'],
	   			'customer_type' => $_POST['tipe'],
	   			'customer_session' => generateSessionKey($_POST['email']),
	   			'date_registered' => $tgl_skrg = date("Ymd"),
	   			'time_registered' => $jam_skrg = date("H:i:s")
	   	);
	   	
	   	$kustomer_baru = new Customer($data_kustomer);
	   	$customerID = $kustomer_baru -> addCustomer();
	   		
	   	if (isset($customerID)) {
	   			
	   		$transaksi_baru = $order -> simpanTransaksi($customerID);
	   			
	   		$html[] = '<h2>Proses Transaksi Selesai</h2>
					<div class="cleaner h20"></div>
	   	
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Segera cek Email anda!</h3>
					<p>Data pembelian serta nomor rekening transfer sudah terkirim ke email Anda. <br />
					Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p>
					</blockquote>';
	   			
	   		$html[] = '<script type="text/javascript">function leave() { window.location = "./";} setTimeout("leave()", 56400);</script>';
	   			
	   	}
	   	
	   }
		
	
		
	} 

	return implode("\n", $html);
	
}



// *********************Konten Testimoni***************************** //

function testimoni() 
{
	
	global $sanitasi;
	
	$html = array();
	
	$dbh = new Pldb;
	
	$pages = new Paginator('5', 'p');
	
	try {
		
		$stmt = $dbh -> query("SELECT testimoni_id FROM pl_testimoni WHERE actived = 'Y' ");
		
		// pass number of records
		
		$pages -> set_total($stmt -> rowCount());
		
		$sth = $dbh -> query("SELECT t.testimoni_id, t.customer_id, t.testimoni,
			t.submission_date, t.actived,
			c.ID, c.fullname, c.email, c.password,
			c.address, c.phone, c.district_id
			FROM pl_testimoni AS t
			INNER JOIN pl_customers AS c ON t.customer_id = c.ID
			WHERE t.actived = 'Y'
			ORDER BY t.testimoni_id DESC ".$pages -> get_limit());
		
		// check jumlah testimoni
		$check_jumlah_testi = $sth -> rowCount();
		
		if ( $check_jumlah_testi == 0 )
		{
			$html[] = '<h2>Sorry ... </h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Testimoni Kosong!</h3>
					Data Testimoni tidak ditemukan
					</blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
		}
		else 
		{
			// tampilkan testimoni pelanggan
			$html[] = '<h2>Testimoni</h2>';
			
			while ( $row = $sth -> fetchObject()) {
			
				$stmt = $dbh -> query('SELECT c.ID, c.fullname, 
						            c.district_id, cty.district_id, 
						            cty.district_name
					                FROM pl_customers AS c
					                INNER JOIN pl_district AS cty ON c.district_id = cty.district_id
					                WHERE c.district_id = '.$sanitasi -> sanitasi($row -> district_id, 'sql').' 
						            AND c.ID = '.$sanitasi -> sanitasi($row -> customer_id, 'sql')
				);
				
				$dataCustomer = $stmt -> fetchObject();
				
				$html[] = '<h3>'.htmlspecialchars($row -> fullname). '|' .$dataCustomer -> district_name.'</h3>';
				
				$isi_testimoni = cleanOutput($row -> testimoni);
				$testimoni = auto_link($isi_testimoni);
				
				$html[] = '<p>'.$testimoni.'<p>';
				
			}
		}
		
	} catch (PDOException $e) {
	
		$html[] = '<h2>Error</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>'
				.$e -> getMessage().'
					</blockquote>';
		
		$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
		
	}
	
	return implode("\n", $html);
	
}

// **********************Konten detail halaman************************ //
function detailPage() 
{

	global $contentError, $sanitasi, $pageId, $comments;
	
	$html = array();
	
	$dbh =  new Pldb;
	
	$sql = "SELECT pg.ID, pg.post_image, pg.post_author,
			pg.post_date, pg.post_title, pg.post_slug, pg.post_content,
			pg.post_status, pg.post_type, pg.comment_status,
			img.filename, img.caption, img.slug
			FROM pl_post AS pg
			INNER JOIN pl_post_img AS img ON pg.post_image = img.ID
			WHERE pg.post_slug = ? AND pg.post_type = 'page'";
	
	$cleaned = $sanitasi -> sanitasi($pageId, 'xss');
	
	$data = array($cleaned);
	
	$sth = $dbh -> pstate($sql, $data);
	
	$result = $sth -> fetchObject();
	
	if ($result->ID == '')
	{
		require_once($contentError);
	}
	elseif (isset($_POST['send']) && $_POST['send'] == 'Kirim')
	{
	
		if (empty($_POST['nama_komentar']) || empty($_POST['isi_komentar']))
		{
			$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					   Semua kolom bertanda asterik(*) harus diisi.
					 </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
		}
		else
		{
			//Check Token
			$badToken = true;
				
			if ( !preg_match('/^[A-Z \'.-]{2,90}$/i', $_POST['nama_komentar']))
			{
				$html[] = '<blockquote>
				          <h3>Terjadi Kesalahan!</h3>
					      Masukkan nama hanya dengan menggunakan huruf.
					      </blockquote>';
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
			}
			elseif ( strlen($_POST['isi_komentar']) > 500)
			{
				$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					    Isi komentar tidak boleh lebih dari 500 kata.
					 </blockquote>';
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
			}
			elseif (!isset($_POST['token']) || !isset($_SESSION['token'])
					|| empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
			{
				$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					    Sorry, Go back and try again.there was security issue
					 </blockquote>';
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
				$badToken = true;
			}
			else
			{
				$badToken = false;
				unset($_SESSION['token']);
	
				// mengatasi input komentar tanpa spasi
				$split_text = explode(" ", $_POST['isi_komentar']);
				$split_count = count($split_text);
				$max = 57;
					
				for($i = 0; $i <= $split_count; $i++){
					if(strlen($split_text[$i]) >= $max){
						for($j = 0; $j <= strlen($split_text[$i]); $j++){
							$char[$j] = substr($split_text[$i],$j,1);
							if(($j % $max == 0) && ($j != 0)){
								$v_text .= $char[$j] . ' ';
							}else{
								$v_text .= $char[$j];
							}
						}
					}else{
						$v_text .= " " . $split_text[$i] . " ";
					}
				} // end of for
	
				$tgl_sekarang = date("Ymd");
				$jam_sekarang = date("H:i:s");
					
				$data = array(
	
						'post_id' => isset($_POST['post_id']) ? abs((int)$_POST['post_id']) : '',
						'fullname' => isset($_POST['nama_komentar']) ? preventInject($_POST['nama_komentar']) : '',
						'url' => isset($_POST['situs_web']) ? preventInject($_POST['situs_web']) : '',
						'comment' => isset($v_text) ? preventInject($v_text) : '',
						'date_created' => $tgl_sekarang,
						'time_created' => $jam_sekarang,
						'ip' => $_SERVER['REMOTE_ADDR']
				);
	
				$add_comment = new PostComment($data);
				$add_comment -> createComment();
	
				$html[] = '<meta http-equiv="refresh" content=0; url="'.$_POST['post_slug'].'.html">';
			}
		}
	
	}
	else 
	{
		$html[] = '<h2>'.$result->post_title.'</h2>';
		if ( $result -> filename != '')
		{
			$html[] = '<img src="content/uploads/images/'.$result -> filename.'" alt="'.$result->post_title.'" height="300" width="600" />';
		}
		$html[] = cleanOutput(createParagraph($result->post_content));
		
		$html[] = '<div class="cleaner"></div>';
		
		// hitung jumlah komentar
		$totalComments = $comments -> totalComment_ByPostId($sanitasi -> sanitasi($result->ID, 'sql'));
		
		if ( $totalComments > 0)
		{
		  $html[] = '<h4>'.$totalComments.' Komentar:</h4>';
		}
		
		$pages = new Paginator('5', 'p');
		
		$st = $dbh -> query('SELECT comment_id FROM pl_post_comment');
		
		// pass number of records to
		$pages -> set_total($st -> rowCount());
		
		$st = $dbh -> query("SELECT c.comment_id, c.post_id,
				      c.fullname, c.url, c.comment, c.date_created,
				      c.time_created, c.actived, c.ip, p.ID, p.post_title,
				      p.post_slug
				      FROM pl_post_comment AS c
				      INNER JOIN pl_post AS p ON c.post_id = p.ID
				      WHERE c.post_id='".$sanitasi -> sanitasi($result -> ID, 'sql')."'
				      AND actived='Y'
				      ORDER BY c.comment_id DESC " .$pages -> get_limit());
		
		if ( $st -> rowCount() > 0)
		{
			while ( $row = $st -> fetchObject()) {
		
				$tanggal = tgl_Lokal( $row -> date_created);
		
				$html[] = '<blockquote>';
				if ( $row -> url != '')
				{
					$html[] = '<h5><a href="http://'.$row -> url.'" rel="nofollow" targer="_blank" title="'.htmlspecialchars($row -> fullname).'">'.htmlspecialchars($row -> fullname).'</a></h5>';
				}
				else
				{
					$html[] = '<h5>'.htmlspecialchars($row -> fullname).'</h5>';
				}
		
				$html[] = '<div class="tanggal">'.htmlspecialchars($tanggal).'-'.$row -> time_created.'</div>';
		
				$isi_komentar = cleanOutput($row -> comment); // membuat paragraf pada isi komentar
				$komentar = auto_link($isi_komentar); // buat link jika terdapat pada paragraf
		
				$html[] = $komentar;
				$html[] = '</blockquote>';
		
			}
				
			$html[] = $pages -> page_links('read-'.$result -> post_slug.'&');
				
		}
		
		$html[] = '<div class="cleaner h20"></div>
		          <div class="cleaner"></div>';
		
		if ( $result -> comment_status == 'open') // cek apakah status komentar diperbolehkan
		{
			// form komentar
			$html[] = '<div class="col col_13">';
			$html[] = '<h4>Tinggalkan Komentar :</h4>';
			$html[] = '<div id="contact_form">';
			$html[] = '<form method="post" action="'.$pageId.'.html">';
			$html[] = '<input type="hidden" name="post_id" value="'.$sanitasi -> sanitasi($result -> ID, 'sql').'">';
			$html[] = '<input type="hidden" name="post_slug" value="'.$result -> post_slug.'">';
			$html[] = '<label for="nama_komentar">* Nama:</label>
        		   <input type="text" id="nama_komentar" name="nama_komentar" class="required input_field" maxlength="90" />
                   <div class="cleaner h10"></div>';
			$html[] = '<label for="website">* Website:</label>
        		   <input type="text" id="website" name="situs_web" class="validate-email required input_field" maxlength="50" />
                   <div class="cleaner h10"></div>';
			$html[] =  '<label for="text">* Komentar:</label>
        		   <textarea id="text" name="isi_komentar" rows="0" cols="0" class="required"></textarea>
                    <div class="cleaner h10"></div>';
				
			// create token
			$salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*abcdefghijklmnopqrstuvwxyz';
			$token = sha1(mt_rand(1, 1000000) . $salt);
			$_SESSION['token'] = $token;
				
			$html[] = '<input type="hidden" name="token" value="'.$token.'" />';
			$html[] = '<input type="submit" value="Kirim" id="submit" name="send" class="submit_btn float_l" />';
			$html[] = '<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />';
			$html[] = '</form></div></div>';
		}
		else
		{
			$html[] = '<blockquote>
				     <h3>Comment Closed!</h3>
					  Komentar ditutup
					 </blockquote>';
		}
		
		$html[] = '<div class="cleaner h30"></div>';
		
		
		return implode("\n", $html);
	}
	
}

// *************Konten Blog Semua Tulisan*************** // 
function blog() 
{
	global $blogId;
	
	$html = array();
	
	$dbh = new Pldb;
	
	$pages = new Paginator('10', 'p');
	
	try {
		
		$stmt = $dbh -> query("SELECT ID FROM pl_post WHERE post_type = 'blog'");
		
		// pass number of records
		
		$pages -> set_total($stmt -> rowCount());
		
		$sth = $dbh -> query("SELECT p.ID, p.post_image, p.post_cat, p.post_author,
				p.post_date, p.post_title, p.post_slug, p.post_content,
				p.post_status, p.post_type, p.comment_status,
				i.filename, i.caption, i.slug, pc.postCat_name, a.admin_login
				FROM `pl_post` AS p
				INNER JOIN `pl_post_img` AS i ON p.post_image = i.ID
				INNER JOIN pl_post_category AS pc ON  p.post_cat = pc.ID
				INNER JOIN pl_admin AS a ON p.post_author = a.ID
				WHERE p.post_type = 'blog'
				ORDER BY p.ID DESC ".$pages -> get_limit());
	
		$check_jumlah_artikel = $sth -> rowCount();
		
		if ( $check_jumlah_artikel == 0)
		{
			
			$html[] = '<h2>Sorry ... </h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Artikel Kosong!</h3>
					Data Artikel tidak ditemukan
					</blockquote>';
			
		    $html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
			
		}
		else 
		{
			$html[] = '<h2>Semua Tulisan</h2>';
	
		  while ($row = $sth -> fetch()) {
			
			$html[] = '<div class="cleaner h20"></div>';
			$html[] = '<h3><a href="read-'.$row['post_slug'].'" title="'.$row['post_title'].'">'.htmlspecialchars($row['post_title']).'</a></h3>';
			
			// tampilkan hanya sebagian isi artikel
			$isi_artikel = strip_tags($row['post_content']);
			$isi = substr($isi_artikel, 0, 220);
			$isi = substr($isi_artikel, 0, strrpos($isi, " "));
			
			$html[] = '<p>'.html_entity_decode($isi).' ...</p>';
			$html[] = '<div class="cleaner"></div>'; // end of .cleaner
			
		 }
		
		   $html[] = $pages -> page_links('blog'.$blogId.'&'); // Blog pagination
		
	  }
		
	} catch (PDOException $e) {
		
		$html[] = '<h2>Error</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>'
					.$e -> getMessage().'
					</blockquote>';
		
					$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
		
	}
	
	return implode("\n", $html);
	
}

// *******************Konten tulisan per kategori*********************** //
function article() 
{

	global $blogcat_slug, $sanitasi, $contentError;
		
	$html = array();
	
	$dbh = new Pldb;
	
	// tampilkan nama kategori tulisan
	$getBlogcat = "SELECT ID, postCat_name, slug, description, actived
				   FROM pl_post_category WHERE slug = ?";
	
	$cleaned = $sanitasi -> sanitasi($blogcat_slug, 'xss');

	$data_blogcat = array($cleaned);
	
	$sth = $dbh -> pstate($getBlogcat, $data_blogcat);
	
	$row = $sth -> fetch();
	
	if ( $row['ID'] == '' && $row['actived'] == 'N')
	{
		require_once($contentError);
	}
	
	try {
		
		
		$pages = new Paginator('5','p');
		
		$id_article = $sanitasi -> sanitasi($row['ID'], 'sql');
		
		$stmt = $dbh -> prepare("SELECT ID FROM pl_post WHERE post_type='blog' AND post_cat = :post_cat");
		
		$stmt -> execute(array(":post_cat" => $id_article));
		
		$pages -> set_total($stmt -> rowCount());
		
		$stmt = $dbh -> prepare("SELECT p.ID, p.post_image, p.post_cat, p.post_author,
		p.post_date, p.post_title, p.post_slug, p.post_content,
		p.post_status, p.post_type, p.comment_status,
		i.filename, i.caption, i.slug, pc.postCat_name, a.admin_login
		FROM `pl_post` AS p
		INNER JOIN `pl_post_img` AS i ON p.post_image = i.ID
		INNER JOIN pl_post_category AS pc ON  p.post_cat = pc.ID
		INNER JOIN pl_admin AS a ON p.post_author = a.ID
		WHERE p.post_type='blog' 
		AND p.post_cat= :post_cat 
		AND p.post_status='publish' 
		ORDER BY p.ID DESC " . $pages -> get_limit());
		
		$stmt -> execute(array(':post_cat' => $id_article));
		
		$check_kategori_artikel = $stmt -> rowCount();
		
		if ( $check_kategori_artikel == 0 )
		{
			$html[] = '<h2>Sorry ... </h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Artikel Kosong!</h3>
					Data Artikel tidak ditemukan
					</blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
				
		}
		else 
		{
			$html[] = '<h2>'.$row['postCat_name'].'</h2>';
			
			while ( $result = $stmt -> fetch()) {
					
				$html[] = '<div class="cleaner h20"></div>';
				$html[] = '<h3><a href="read-'.$result['post_slug'].'" title="'.$result['post_title'].'">'.htmlspecialchars($result['post_title']).'</a></h3>';
					
				// tampilkan sebagian isi artikel
				$isi_artikel = strip_tags($result['post_content']); // membuat paragraf
				$isi = substr($isi_artikel, 0, 220);
				$isi = substr($isi_artikel, 0, strrpos($isi, " "));
			
				$html[] = '<p>'.html_entity_decode($isi).' ...</p>';
				$html[] = '<div class="cleaner"></div>'; // end of .cleaner
			
			}
			
			$html[] = $pages -> page_links('article-'.$_GET['articleid'].'&');
			
		}
		
	} catch (PDOException $e) {
		
		$html[] = '<h2>Error</h2>
					<div class="cleaner h20"></div>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>'
				.$e -> getMessage().'
					</blockquote>';
		
	     $html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 50000);</script>';
		
	}
	
	return implode("\n", $html);
}


// **************** Konten detail tulisan ****************** //

function blogDetail() 
{
	
	global $contentError, $sanitasi, $blogId, $comments, $captcha;
	
	$html = array();
	
	$dbh = new Pldb;
	
	$sql = "SELECT p.ID, p.post_image, p.post_cat, p.post_author,
		    p.post_date, p.post_title, p.post_slug, p.post_content,
		    p.post_status, p.post_type, p.comment_status, p.post_tag,
			i.filename, i.caption, i.slug, 
			pc.postCat_name, pc.slug, a.admin_login
			FROM `pl_post` AS p
			INNER JOIN `pl_post_img` AS i ON p.post_image = i.ID
			INNER JOIN pl_post_category AS pc ON  p.post_cat = pc.ID
			INNER JOIN pl_admin AS a ON p.post_author = a.ID
			WHERE p.post_slug = ? AND p.post_type = 'blog'";
	
	
	$cleaned = $sanitasi -> sanitasi($blogId, 'xss');
	
	$data = array($cleaned);
	
	$sth = $dbh -> pstate($sql, $data);
	
	$result = $sth -> fetchObject();
	
	if ( $result -> ID == '') {
		
		require($contentError);
		
	} elseif (isset($_POST['send']) && $_POST['send'] == 'Kirim') {
		
		//Check Token
		$badToken = true;
		
		if (!isset($_POST['token']) || !isset($_SESSION['token'])
		   || empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
		   	
			$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					    Sorry, Go back and try again.there was security issue
					 </blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
			
			$badToken = true;
			
		}
		
		if (empty($_POST['nama_komentar']) || empty($_POST['isi_komentar']) 
			|| empty($_POST['captcha'])) {
				
			$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					   Semua kolom bertanda asterik(*) harus diisi.
					 </blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
		
		} else {
			
			if ( !preg_match('/^[A-Z \'.-]{2,90}$/i', $_POST['nama_komentar']))
			{
				$html[] = '<blockquote>
				          <h3>Terjadi Kesalahan!</h3>
					      Masukkan nama hanya dengan menggunakan huruf.
					      </blockquote>';
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
			}
			if ( strlen($_POST['isi_komentar']) > 500)
			{
				$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					    Isi komentar tidak boleh lebih dari 500 kata.
					 </blockquote>';
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
			
			}
			elseif (isset( $_POST['captcha']))
			{
			    
				$challenge = $_POST['captcha'];
				
				try {
					
					if ($captcha -> checkAnswer($challenge))
					{
						
						// mengatasi input komentar tanpa spasi
						$split_text = explode(" ", $_POST['isi_komentar']);
						$split_count = count($split_text);
						$max = 57;
						
						for($i = 0; $i <= $split_count; $i++){
							if(strlen($split_text[$i]) >= $max){
								for($j = 0; $j <= strlen($split_text[$i]); $j++){
									$char[$j] = substr($split_text[$i],$j,1);
									if(($j % $max == 0) && ($j != 0)){
										$v_text .= $char[$j] . ' ';
									}else{
										$v_text .= $char[$j];
									}
								}
							}else{
								$v_text .= " " . $split_text[$i] . " ";
							}
						} // end of for
						
						$tgl_sekarang = date("Ymd");
						$jam_sekarang = date("H:i:s");
						
						$data = array(
						
								'post_id' => isset($_POST['post_id']) ? abs((int)$_POST['post_id']) : '',
								'fullname' => isset($_POST['nama_komentar']) ? preventInject($_POST['nama_komentar']) : '',
								'url' => isset($_POST['situs_web']) ? preventInject($_POST['situs_web']) : '',
								'comment' => isset($v_text) ? preventInject($v_text) : '',
								'date_created' => $tgl_sekarang,
								'time_created' => $jam_sekarang,
								'ip' => $_SERVER['REMOTE_ADDR']
						);
						
						
						$badToken = false;
						unset($_SESSION['token']);
						
						$add_comment = new PostComment($data);
						$add_comment -> createComment();
						

						$data_notifikasi = array(
									
								'notify_title' => "newComment",
								'date_submited' => $tgl_sekarang,
								'time_submited' => $jam_sekarang,
								'content' => preventInject($v_text)
									
						);
							
						pushNotification($data_notifikasi);
							
						$html[] = "<meta http-equiv='refresh' content='0'; url=read-".$_POST['post_slug'].">";
							
					}
					
				} catch (Exception $e) {
					
					$html[] = '<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					  Maaf, Jawaban anda salah!
					 </blockquote>';
					$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'read-'.$blogId.'";} setTimeout("leave()", 3640);</script>';
						
				}
				
			}
				
		}
		
	}
	else 
	{
		$html[] = '<h1>'.$result -> post_title.'</h1>';
		$html[] = '<h6> Ditulis oleh : '. $result -> admin_login .' | '. tgl_Lokal($result -> post_date). ' | 
				   Kategori : <a href="article-'.$result->slug.'" title="'.$result -> postCat_name.'">'. $result -> postCat_name . '</a></h6>';
		if ( $result -> filename != '')
		{
			$html[] = '<img src="content/uploads/images/'.$result -> filename.'" alt="'.$result->post_title.'" height="300" width="600" />';
		}
		$html[] = '<p>'.html_entity_decode($result -> post_content).'</p>';
		
		// artikel terkait
		$html[] = '<div class="cleaner h20"></div>';
		$html[] = '<h3>Baca Juga</h3>';
		
		$separate_word = explode(",", $result -> post_tag);
		$total_word_separated = (integer)count($separate_word);
		
		$total_word = $total_word_separated-1;
		$get_id = substr($sanitasi -> sanitasi($result->ID, 'sql'),0,4);
		
		// looping sebanyak jumlah kata
		$find = "SELECT ID, post_image, post_cat, 
		         post_author, post_date, 
		         post_title, post_slug, 
		         post_content, post_status, post_type,
		         comment_status, post_tag 
		         FROM `pl_post` 
			     WHERE post_type='blog' 
			     AND (ID<'$get_id') AND (ID!='$get_id') 
		         AND (";
		
		for ($i=0; $i<=$total_word; $i++)
		{
			$find .= "post_tag LIKE '%$separate_word[$i]%'";
			if ($i < $total_word ){
				$find .= " OR ";
			}
		}
		
		$find .= ") ORDER BY ID DESC LIMIT 5";
		
		$stmt = $dbh -> query($find);
		$html[] = '<ul class="tmo_list">';
		while ($terkait = $stmt -> fetchObject()) {
			
			 $html[] = '<li><a href="read-'.$terkait->post_slug.'" title="'.$terkait->post_title.'">'.htmlspecialchars($terkait->post_title).'</a></li>';
		}
		$html[] = '</ul>';
			
		$html[] = '<div class="cleaner"></div>';
		
		// hitung jumlah komentar
		$totalComments = $comments -> totalComment_ByPostId($sanitasi -> sanitasi($result->ID, 'sql'));
		
		if ( $totalComments > 0)
		{
			$html[] = '<h4>'.$totalComments.' Komentar:</h4>';
		}
		
		$pages = new Paginator('5', 'p');
		
		$st = $dbh -> query('SELECT comment_id FROM pl_post_comment');
		
		// pass number of records to
		$pages -> set_total($st -> rowCount());
		
		$st = $dbh -> query("SELECT c.comment_id, c.post_id, 
				      c.fullname, c.url, c.comment, c.date_created, 
				      c.time_created, c.actived, c.ip, p.ID, p.post_title, 
				      p.post_slug
				      FROM pl_post_comment AS c
				      INNER JOIN pl_post AS p ON c.post_id = p.ID
				      WHERE c.post_id='".$sanitasi -> sanitasi($result -> ID, 'sql')."' 
				      AND actived='Y'
				      ORDER BY c.comment_id DESC " .$pages -> get_limit());
		
		if ( $st -> rowCount() > 0)
		{
			while ( $row = $st -> fetchObject()) {
				
				$tanggal = tgl_Lokal( $row -> date_created); // tanggal komentar ketika ditulis
				
				// get data reply comment
				$reply_comment = new ReplyComment();
				$data_reply = $reply_comment -> setReplyId($row -> comment_id);
				$reply_id = $data_reply -> reply_id;
				
				$balas_komentar = $reply_comment -> findReply($reply_id);
				$activedReply = $balas_komentar -> getActived();
				
				$html[] = '<blockquote>';
				if ( $row -> url != '')
				{
					$html[] = '<h5><a href="http://'.$row -> url.'" rel="nofollow" targer="_blank" title="'.htmlspecialchars($row -> fullname).'">'.htmlspecialchars($row -> fullname).'</a></h5>';
				}
				else 
				{
					$html[] = '<h5>'.htmlspecialchars($row -> fullname).'</h5>';
				}
				
				$html[] = '<div class="tanggal">'.htmlspecialchars($tanggal).' - '.$row -> time_created.' </div><br />';
				
				$isi_komentar = cleanOutput($row -> comment); // membuat paragraf pada isi komentar
				$komentar = auto_link($isi_komentar); // buat link jika terdapat pada paragraf
			
				$html[] = $komentar; // isi komentar ditampilkan
				
				if ( $activedReply == 'Y')
				{
						
					$html[] = '<blockquote>';
					$html[] = '<h5>Reply:</h5>';
					$html[] = '<div class="tanggal">'.tgl_Lokal($balas_komentar -> getReply_dateCreated()).'</div>';
		            
					$isi_balasan_komentar = cleanOutput($balas_komentar -> getReply()); // membuat paragraf pada isi balasan komentar
				    $replied_comment = auto_link($isi_balasan_komentar); // buat link jika terdapat pada paragraf isi balasan
				    
				    $html[] = $replied_comment;
					$html[] = '</blockquote>';
					
				}
				
				$html[] = '</blockquote>';
				
			}
			
			$html[] = $pages -> page_links('read-'.$result -> post_slug.'&');
			
		}
		
		$html[] = '<div class="cleaner h20"></div>
		          <div class="cleaner"></div>';
		
		if ( $result -> comment_status == 'open') // cek apakah status komentar diperbolehkan
		{
			// form komentar
			$html[] = '<div class="col col_13">';
			$html[] = '<h4>Tinggalkan Komentar :</h4>';
			$html[] = '<div id="contact_form">';
			$html[] = '<form method="post" name="comment" action="read-'.$blogId.'" onSubmit="return validateCommentForm(this)">';
			$html[] = '<input type="hidden" name="post_id" value="'.$sanitasi -> sanitasi($result -> ID, 'sql').'">';
			$html[] = '<input type="hidden" name="post_slug" value="'.$result -> post_slug.'">';
			$html[] = '<label for="nama_komentar">* Nama:</label>
        		   <input type="text" id="nama_komentar" name="nama_komentar" class="required input_field" maxlength="90" />
                   <div class="cleaner h10"></div>';
			$html[] = '<label for="website">Website:</label>
        		   <input type="text" id="website" name="situs_web" class="validate-email required input_field" maxlength="50" />
                   <div class="cleaner h10"></div>';
			$html[] =  '<label for="text">* Komentar:</label>
        		   <textarea id="text" name="isi_komentar" rows="0" cols="0" class="required"></textarea>
                    <div class="cleaner h10"></div>';
			
			$html[] =  '<label for="text">* '.$captcha -> getNewQuestion().':</label>
        		       <input type="text" id="captcha" name="captcha" class="required input_field"  />
                    <div class="cleaner h10"></div>';
			// create token
			$salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*abcdefghijklmnopqrstuvwxyz';
			$token = sha1(mt_rand(1, 1000000) . $salt);
			$_SESSION['token'] = $token;
			
			$html[] = '<input type="hidden" name="token" value="'.$token.'" />';
			$html[] = '<input type="submit" value="Kirim" id="submit" name="send" class="submit_btn float_l" />';
			$html[] = '<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />';
			$html[] = '</form></div></div>';
		}
		else 
		{
			$html[] = '<blockquote>
				     <h3>Comment Closed!</h3>
					  Komentar ditutup
					 </blockquote>';
		}
        
		$html[] = '<div class="cleaner h30"></div>';
		
	}
	
	return implode("\n", $html);
}

// *******************Konten Registrasi Customer************************ //
function daftarMember() 
{
	
	global $customer;
		
	$html = array();
	
	if ( isset($_POST['kirim']) && $_POST['kirim'] == 'Daftar')
	{
		
		$nama_lengkap = isset($_POST['nama_lengkap']) ? preventInject($_POST['nama_lengkap']) : '';
		$email = isset($_POST['email_member']) ? preventInject($_POST['email_member']) : '';
		$password = isset($_POST['password']) ? preventInject($_POST['password']) : '';
		$confirmed = isset($_POST['confirmed']) ? $_POST['confirmed'] : '';
		
		$badCSRF = true; // check CSRF
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
			|| $_POST['csrf'] !== $_SESSION['CSRF']) {
					
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
				
			$badCSRF = true;
		}
		
		if ( empty($nama_lengkap) || empty($email) || empty($password) || empty($confirmed))
		{
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Semua kolom harus diisi !</b>
					  </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
			
		}
		// cek email
		elseif ( $customer -> emailExists($email) == true )
		{
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Email sudah dipakai, silahkan gunakan alamat email yang lain.</b>
					  </blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
				
		}
		// cek validitas penulisan email
		elseif (is_valid_email_address(trim($email)) == 0) {
				
				$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Alamat E-mail tidak valid!</b>
					  </blockquote>';
					
				$html[] = '<script type="text/javascript">function leave() { window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
							
		}
		
		// cek password
		if (!isset($password) || !isset($confirmed) || $password != $confirmed )
		{
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					  Password yang anda ketik tidak sama.
					  </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
		
		}
		elseif (strlen($password) < 6)
		{
		
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					Password tidak boleh kurang dari 6 karakter!
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
		
		}
		elseif (strlen($confirmed) < 6)
		{
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				      <h3>Terjadi Kesalahan!</h3>
					  Password tidak boleh kurang dari 6 karakter!
					  </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
		
		}
	    elseif (!ctype_alnum($password)) // cek validitas penulisan password
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Terjadi Kesalahan!</h3>
					Password anda tidak valid!
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
			
		}
		else 
		{
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$data = array(
			
					'fullname' => $nama_lengkap,
					'email' => $email,
					'password' => $password,
					'customer_type' => 'member',
					'customer_session' => $password,
					'date_registered' => date("Y-m-d"),
					'time_registered' => date("H:i:s")
			
			);
			
			$new_member = new Customer($data);
			$new_member -> registerMember();
			
			$html[] = '<h2>Pendaftaran Member Berhasil!</h2>
						  <div class="cleaner h20"></div>
						  <div class="cleaner"></div>
						  <blockquote>
					   <h3>Terimakasih telah menjadi Member</h3>
						 Data pendaftaran member telah kami kirim ke email anda. Segera cek email anda!
						  </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 5000);</script>';
			
		}
		
	}
	else 
	{
		$html[] = '<h2>Registrasi Member</h2>';
		$html[] = '<div class="col col_13">';
		$html[] = '<p>Form Registrasi Member</p>';
		
		$html[] = '<div id="contact_form">';
		$html[] = '<form method="post" name="regMember" action="daftar-member" onSubmit="return validateRegister(this)" >';
		
		// Nama Lengkap
		$html[] = '<label for="author">Nama Lengkap:</label> 
				<input type="text" id="author" name="nama_lengkap" class="required input_field" />
				<div class="cleaner h10"></div>';
		
		// email kustomer
		$html[] = '<label for="email">Email:</label>
				 <input type="text" name="email_member" class="required input_field" >
				 <div class="cleaner h10"></div>';
		
		// password
		$html[] = '<label for="password">Kata sandi:</label> <input type="password" id="password" name="password" class="required input_field" />
				<div class="cleaner h10"></div>';
		
		$html[] = '<label for="confirmed">Ketik ulang kata sandi:</label> <input type="password" id="confirmed" name="confirmed" class="required input_field" />
				<div class="cleaner h10"></div>';
	
		// create token for prevent CSRF
		$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$CSRF = sha1(mt_rand(0,999) . $key);
		$_SESSION['CSRF'] = $CSRF;
		
		$html[] = ' <input type="hidden" name="csrf" value="'.$CSRF.'"/>';
		$html[] = '<input type="submit" value="Daftar" name="kirim" class="submit_btn float_l" />';
		$html[] = '<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />';
		$html[] = '</form>'; // end of form
		$html[] = '</div>'; // end of #contact_form
		$html[] = '</div>'; // end of .class col col_13
		
	}
		
	return implode("\n", $html);
	
}

// ********************Konten edit profile member********************* //
function editProfile() 
{
	
	global $customer, $sanitasi, $memberId, $memberToken, $contentError;
	
	$dbh = new Pldb;
	
	$html = array();
	
	if ( isset($_POST['saveChange']) && $_POST['saveChange'] == 'Simpan') {
	
		$id_member = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$alamat_member = isset($_POST['address']) ? $_POST['address'] : "";
		$telp_member = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
		$kota = isset($_POST['city']) ? (int)$_POST['city'] : 0;
		$pengiriman = isset($_POST['shipping']) ? (int)$_POST['shipping'] : 0;
	
		$badCSRF = true; // check CSRF
	
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
				|| $_POST['csrf'] !== $_SESSION['CSRF']) {
					
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
	
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
	
			$badCSRF = true;
				
		} elseif ( empty($alamat_member) || empty($telp_member) 
				|| empty($_POST['fullname']) || empty($_POST['email'])) {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Semua Kolom harus diisi !</b>
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		} elseif (!preg_match('/^[A-Z \'.-]{2,120}$/i', $_POST['fullname'])) {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi nama lengkap anda dengan benar
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		} elseif (is_valid_email_address($_POST['email']) == 0) {
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Alamat E-mail tidak valid!</b>
					  </blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "daftar-member";} setTimeout("leave()", 3640);</script>';
				
		} elseif (!preg_match('/^[A-Z0-9 \',.#-]{2,255}$/i', $_POST['address'])) {
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi alamat anda dengan benar, maksimal 500 kata.
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		}
		// cek no_telpon
		elseif (!preg_match('/^[0-9]{10,13}$/', $telp_member))
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Silahkan isi nomor telepon dengan benar
					</blockquote>';
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
		
		} else {
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
				
			$data = array(
			
					'ID' => $id_member,
					'fullname' => isset($_POST['fullname']) ? preventInject($_POST['fullname']) : '',
					'email' => isset($_POST['email']) ? preventInject($_POST['email']) : '',
					'address' => $alamat_member,
					'phone' => $telp_member,
					'district_id' => $kota,
					'shipping_id' => $pengiriman,
			);
			
				
			$edit_member = new Customer($data);
	
			if ( $edit_member -> updateMemberById())
			{
				$html[] = '<h2>Berhasil</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Data Profil Member Berhasil di update!</h3>
					Terima kasih telah melengkapi data profil anda !
					</blockquote>';
				
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
	
			}
				
		}
	
	} else {
		
		$cleaned = $sanitasi -> sanitasi($memberId, 'sql');
		$data_member = $customer -> getCustomerById($cleaned);
		
		if ( isset($data_member) && $data_member -> getId() !== $_SESSION['member_id']) {
			
			require_once ( $contentError );
			
		} elseif ( isset($memberId) && $_SESSION['member_id'] != $memberId) {
			
			require_once ( $contentError );
			
		} else {
			
			$html[] = '<h2>Edit Profile</h2>';
			$html[] = '<div class="col col_13">';
			$html[] = '<p>Data profil Member</p>';
			
			$html[] = '<div id="contact_form">';
			$html[] = '<form method="post" name="editMember" action="my-profile&memberId='.$data_member -> getId().'&memberToken='.$data_member -> getCustomer_Session().'" onSubmit="return validateEditMember(this)" />';
			
			// Nama Lengkap
			$html[] = '<label for="author">Nama Lengkap:</label>
				<input type="text" id="author" name="fullname" class="required input_field" value="'.htmlspecialchars($data_member -> getCustomerFullname()).'" />
				<div class="cleaner h10"></div>';
			
			// email kustomer
			$html[] = '<label for="email">Email:</label>
				 <input type="text" name="email" class="required input_field"  value="'.htmlspecialchars($data_member -> getCustomerEmail()).'">
				 <div class="cleaner h10"></div>';
			
			// alamat
			$html[] = '<label for="address">Alamat:</label>
				  <input type="text" maxlength="255" id="address" name="address" class="required input_field" value="'.htmlspecialchars($data_member -> getCustomerAddress() ).'" />
				  <div class="cleaner h10"></div>';
			
			// phone
			$html[] = '<label for="phone">Telepon:</label>
				  <input type="text" id="phone" name="phone" class="required input_field" value="'.htmlspecialchars($data_member -> getCustomerPhone() ).'" />
				<div class="cleaner h10"></div>';
			
			// kota tujuan
			$html[] = '<label for="city">Kota Tujuan:</label>';
			$html[] = '<select name="city">';
			
			$getCity = "SELECT DISTINCT district_id, district_name
				    FROM pl_district ORDER BY district_name";
			
			if ( $data_member -> getDistrictId() == 0) {
				
				$html[] = '<option value=0 selected>- Pilih Kota/Kabupaten -</option>';
			}
			
			foreach ( $dbh -> query($getCity) as $city ) {
					
				if ( $data_member -> getDistrictId() == $city['district_id']) {
					
					$html[] = '<option value="'.$city['district_id'].'" selected>'.$city['district_name'].'</option>';
				
				} else {
					
					$html[] = '<option value="'.$city['district_id'].'">'.$city['district_name'].'</option>';
				}
					
			}
			
			$html[] = '</select><div class="cleaner h10"></div>';
			
			// Kurir Pengiriman
			$html[] = '<label for="shipping">Jasa pengiriman:</label>';
			$html[] = '<select name="shipping">';
			
			$getShipping = "SELECT shipping_id, shipping_name, shipping_logo
				       FROM pl_shipping ORDER BY shipping_name";
			
			if ( $data_member -> getShippingId() == 0)
			{
				$html[] = '<option value=0 selected>- Pilih Jasa Pengiriman -</option>';
			}
				
			foreach ( $dbh -> query($getShipping) as $shipping) {
				
				if ( $data_member -> getShippingId() == $shipping['shipping_id']) {
			
					$html[] = "<option value=$shipping[shipping_id] selected>$shipping[shipping_name]</option>";
						
				} else {
					 
					$html[] = "<option value=$shipping[shipping_id]>$shipping[shipping_name]</option>";
						
				}
				
			}
				
			$html[] = '</select><div class="cleaner h10"></div>';
			
			$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
			
			// create token for prevent CSRF
			$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$CSRF = sha1(mt_rand(0,999) . $key);
			$_SESSION['CSRF'] = $CSRF;
			
			$html[] = '<input type="hidden" name="csrf" value="'.$CSRF.'"/>';
			$html[] = '<input type="hidden" name="id" value="'.(int)$data_member -> getId().'"/>';
			
			$html[] = '<input type="submit" value="Simpan" name="saveChange" class="submit_btn float_l" />';
			$html[] = '<button type="button" class="submit_btn float_r" onClick="self.history.back();">Batal</button>';
			$html[] = '</form>'; // end of form
			$html[] = '</div>'; // end of #contact_form
			$html[] = '</div>'; // end of .class col col_13
					
		}
				
	}
	
	return implode("\n", $html);
	
}

// ************************Konten Ganti Password**************************** //
function changePass() 
{
	
	global $customer, $shoppingCart, $sanitasi, $memberId, $memberToken, $contentError;
	
	$html = array();
	
	if (isset($_POST['reset']) && $_POST['reset'] == 'Ganti Password')
	{
		$password = isset($_POST['password']) ? preventInject($_POST['password']) : "";
		$confirmed = isset($_POST['confirmed']) ? preventInject($_POST['confirmed']) : "";
		
		$badCSRF = true; // check CSRF
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
				|| $_POST['csrf'] !== $_SESSION['CSRF'])
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
			$badCSRF = true;
		
		}
		
		if (empty($password) || empty($confirmed))
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Semua kolom harus diisi !</b>
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
		
		}
		elseif( !isset($password) || !isset($confirmed) || !$password || !$confirmed || $password != $confirmed)
		{
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					  Password yang anda ketik tidak sama.
					  </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		}
		elseif (strlen($password) < 6)
		{
		
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					Password tidak boleh kurang dari 6 karakter!
					</blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
		
		}
		elseif (strlen($confirmed) < 6)
		{
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				      <h3>Terjadi Kesalahan!</h3>
					  Password tidak boleh kurang dari 6 karakter!
					  </blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		}
		elseif (!ctype_alnum($password)) // cek validitas penulisan password
		{
				
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Terjadi Kesalahan!</h3>
					Password anda tidak valid!
					</blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		}
		else 
		{
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$data = array('ID' => (int)$_POST['customer_id'], 'password' => $password );
			
			$ganti_password = new Customer($data);
			
			if ( $ganti_password -> updatePasswordById())
			{
				
				$html[] = '<h2>Berhasil</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Kata Sandi berhasil diganti!</h3>
					   Silahkan Login kembali dengan kata sandi baru Anda !
					</blockquote>';
				
				
				$customer -> signOutMember();
				
				$sid = session_id();
				
				// cek sesi temporer produk dan hitung jumlahnya
				$ketemu = $shoppingCart -> checkItems($sid);
				$isiKeranjang = $shoppingCart -> getIsiKeranjang($sid);
				$jml = count($isiKeranjang);
				
				if ( $ketemu > 0)
				{
					for ( $i = 0; $i < $jml; $i++ )
					{
						$sth  = $dbh -> query("DELETE FROM pl_orders_temp WHERE orders_temp_id = {$isiKeranjang[$i]['orders_temp_id']}");
					}
				}
			
				session_destroy();
				
				$html[] = '<script type="text/javascript">function leave() { window.location = "member-login";} setTimeout("leave()", 5000);</script>';
				
			}
		
		}
		
	}
	else 
	{
		$cleaned = $sanitasi -> sanitasi($memberId, 'sql');
		$data_member = $customer -> getCustomerById($cleaned);
		
		if ( isset($data_member) && $data_member -> getId() !== $_SESSION['member_id'])
		{
			require_once ( $contentError );
		}
		else 
		{
			
			$html[] = '<h2>Ganti Password</h2>';
			$html[] = '<div class="col col_13">';
			$html[] = '<p>Ketik Password baru Anda !</p>';
			$html[] = '<div id="contact_form">';
			$html[] = '<form method="post" name="gantiPasswod" action="ganti-password&memberId='.$data_member -> getId().'&memberToken='.$data_member -> getCustomer_Session().'" onSubmit="return validateChangePassword(this)" >';
			$html[] = '<label for="password">Password:</label> 
					  <input type="password" id="password" name="password" class="validate-password required input_field" />
			          <div class="cleaner h10"></div>';
			
			$html[] = '<label for="confirmed">Ketik ulang Password:</label>
					  <input type="password" id="confirmed" name="confirmed" class="validate-password required input_field" />
			          <div class="cleaner h10"></div>';
			
			// create token for prevent CSRF
			$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$CSRF = sha1(mt_rand(0,999) . $key);
			$_SESSION['CSRF'] = $CSRF;
			
			$html[] = ' <input type="hidden" name="csrf" value="'.$CSRF.'"/>';
			$html[] = ' <input type="hidden" name="customer_id" value="'.$data_member -> getId().'"/>';
			$html[] = '<input type="submit" name="reset" class="button" value="Ganti Password">';
			
			
			$html[] = '</form>';
			$html[] = '</div></div>';
		}
		
	}
	
	return implode("\n", $html);
	
}

// *******************Konten Riwayat Belanja************************ //
function shopHistory() 
{
	 
	global $order, $customer, $sanitasi,  $memberId, $memberToken, $contentError;
	
	$html = array();
	
	$cleaned = $sanitasi -> sanitasi($memberId, 'sql');
	$data_member = $customer -> getCustomerById($cleaned);
	
	if ( isset($data_member) 
		&& $data_member -> getId() !== $_SESSION['member_id'] ) {
			
		require_once($contentError);
		
	} elseif (isset($memberId) && $_SESSION['member_id'] != $memberId) {
		
		require_once($contentError);
		
	} else {
	
		$customer_id = $_SESSION['member_id'];
		
		$data_riwayatBelanja = $order -> getShoppingHistory($customer_id);
		
		$shopping_histories = $data_riwayatBelanja['results'];
		$total_riwayatBelanja = $data_riwayatBelanja['totalRows'];
		
		if ( $total_riwayatBelanja > 0)
		{
			$html[] =  '<h2><img src="content/themes/default/images/cart.png" > '.$total_riwayatBelanja.' Riwayat Belanja</h2>';
			$html[] =  '<table width="700px" cellspacing="0" cellpadding="5">
					<tr bgcolor="#CCCCCC">
			
					<th width="20" align="left">No.Pembelian</th>
					<th width="20" align="left">Tanggal</th>
					<th width="60" align="center">Jam </th>
					<th width="60" align="left">Status</th>';
			
			foreach ( $shopping_histories as $shopping_history) :
			$html[] = '<tr>';
			$html[] = '<td>'.$shopping_history -> getOrderId().'</td>';
			$html[] = '<td>'.tgl_Lokal($shopping_history -> getDateOrder()).'</td>';
			$html[] = '<td align="center">'.$shopping_history -> getTimeOrder().'</td>';
			$html[] = '<td>'.$shopping_history -> getOrderStatus().'</td>';
			$html[] = '</tr>';
			
			endforeach;
			
			$html[] = '</table>';
			$html[] = '<div class="cleaner h20"></div>
		               <div class="cleaner"></div>';
			
		}
		else 
		{
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Riwayat Belanja Kosong!</h3>
					   Anda belum pernah melakukan pembelian produk !
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
				
		}
		
		return implode("\n", $html);
		
	}
	
}

// ************************Konten Kirim Testimoni**************************** //
function sendTestimony() 
{
	
	global $customer, $sanitasi, $memberId, $memberToken, $contentError;
	
	$html = array();
	
	if ( isset($_POST['send']) && $_POST['send'] == 'Kirim')
	{
		$testimony = isset($_POST['testi']) ? preventInject($_POST['testi']) : "";
		
		$badCSRF = true; // check CSRF
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
				|| $_POST['csrf'] !== $_SESSION['CSRF'])
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
			$badCSRF = true;
		
		}
		
		if ( empty($testimony) )
		{
			$html[] = '<h2>Error</h2>
					 <div class="cleaner"></div>
					 <blockquote><h3>Terjadi Kesalahan!</h3>
					 <b>Kolom Testimoni harus diisi !</b>
					 </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "javascript:history.go(-1)";} setTimeout("leave()", 3640);</script>';
			
		}
		else 
		{
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$data = array(
					
					'customer_id' => isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : '',
					'testimoni' => $testimony,
					'submission_date' => date("Y-m-d")
				
			);
			
			$kirim_testimoni = new Testimoni($data);
			
			if ( $kirim_testimoni -> createTestimoni())
			{
				
				$data_notifikasi = array(
				
						'notify_title' => "newTestimony",
						'date_submited' => date("Y-m-d"),
						'time_submited' => date("H:i:s"),
						'content' => preventInject($testimony)
				
				);
				
				pushNotification($data_notifikasi);
				
				$html[] = '<h2>Berhasil</h2>
					<div class="cleaner"></div>
					<blockquote><h3>Testimoni berhasil dikirim!</h3>
					<b>Terima Kasih telah mengirimkan testimoni kepada kami !</b>
					</blockquote>';
					
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
					
				
			}
			
		}
		
	}
	else 
	{
		
		$cleaned = $sanitasi -> sanitasi($memberId, 'sql');
		$data_member = $customer -> getCustomerById($cleaned);
		
		if ( isset($data_member) && $data_member -> getId() !== $_SESSION['member_id'])
		{
			require_once ( $contentError );
		}
		elseif ( isset($memberId) && $_SESSION['member_id'] != $memberId)
		{
			require_once ( $contentError );
		}
		else 
		{
			
			$html[] = '<h2>Kirim Testimoni</h2>';
			$html[] = '<div class="col col_13">';
			$html[] = '<p>Tuliskan testimoni tentang layanan atau produk kami pada form dibawah ini:</p>';
			$html[] = '<div id="contact_form">';
			$html[] = '<form method="post" name="testimoni" action="kirim-testimoni&memberId='.$data_member -> getId().'&memberToken='.$data_member -> getCustomer_Session().'" onSubmit="return validateTestimoniForm(this)" >';
			
			$html[] = '<label for="testimoni">Testimoni:</label>
				       <textarea id="text" name="testi" rows="0" cols="0" maxlength="1000" class="required"></textarea>
				       <div class="cleaner h10"></div>';
			
			// create token for prevent CSRF
			$key= 'ABCDE1FGHI06JKLMNOPQRST88UVWXYZ1234567890!@#$%^&*()~`+-_|{}';
			$CSRF = sha1(mt_rand(1,1000000) . $key);
			$_SESSION['CSRF'] = $CSRF;
			
			$html[] = '<input type="hidden" name="csrf" value="'.$CSRF.'"/>';
			$html[] = '<input type="hidden" name="customer_id" value="'.$data_member -> getId().'"/>';
			$html[] = '<input type="submit" value="Kirim" id="submit" name="send" class="submit_btn float_l" />';
			$html[] = '<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />';
			$html[] = '</form>';
			$html[] = '</div></div>';
			
		}
		
	}
	
	return implode("\n", $html);
	
}

// ************************Konten Member Login**************************** //
function memberLogin() 
{
	
	global $customer;
	
	$html = array();
	
	if (isset($_POST['login']) && $_POST['login'] == 'Masuk') {
		
		$email = isset($_POST['email']) ? preventInject($_POST['email']) : "";
		$password = isset($_POST['password']) ? preventInject($_POST['password']) : "";
	
		$badCSRF = true; // check CSRF
		
		$_SESSION['memberLoggedIn'] = false;
		
		$member = new Customer(array('email' => $email, 'password' => $password));
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
				|| $_POST['csrf'] !== $_SESSION['CSRF']) {
					
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					   <b>Sorry, there was a security issue.</b>
					   </blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
			$badCSRF = true;
			
		}
		// cek kolom pengisian email dan password
		if (empty($email) || empty($password)) {
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					<blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					Kolom e-mail dan password harus diisi!
					</blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() { window.location = "member-login";} setTimeout("leave()", 2640);</script>';
		
		} elseif ($customer -> emailExists($email) == false) { // cek keberadaan email
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote>
				     <h3>Terjadi Kesalahan!</h3>
					Alamat email tidak terdaftar. Silahkan Daftar sebagai Member !
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "member-login";} setTimeout("leave()", 3640);</script>';
		
		} elseif (is_valid_email_address(trim($email)) == 0) {
				
				$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
		            <blockquote>
		             <h3>Terjadi Kesalahan!</h3>
		               Silahkan isi alamat E-mail anda dengan benar
		               </blockquote>';
					
				$html[] = '<script type="text/javascript">function leave() {  window.location = "member-login";} setTimeout("leave()", 3640);</script>';
				
		} elseif (!ctype_alnum($password)) { // cek penulisan kata sandi
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Terjadi Kesalahan!</h3>
					Password anda tidak valid!
					</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "member-login";} setTimeout("leave()", 3640);</script>';
		
		}
		elseif (strlen($password) < 6) // cek jumlah karakter password
		{
		
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					<blockquote>
				    <h3>Terjadi Kesalahan!</h3>
					Password kurang dari 6 karakter!
					</blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() {  window.location = "member-login";} setTimeout("leave()", 3640);</script>';
				
		
		}
		elseif (!$loggedInMember = $member -> validateCustomer())
		{
			
			$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				    <h3>Terjadi Kesalahan!</h3>
				   Email atau password anda tidak benar!
				</blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() {  window.location = "member-login";} setTimeout("leave()", 3640);</script>';
		
		}
		else 
		{
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$_SESSION['memberLoggedIn'] = true;
			
			$sesi_lama = session_id();
			
			session_regenerate_id();
			
			$sesi_baru = session_id();
			
			$update_sesiMember = $customer ->updateMemberSession($sesi_baru, $email);
			
		}
		
	}
	else 
	{
			
			$html[] = '<h2>Log In Member</h2>';
			$html[] = '<p>Silahkan Masuk !</p>';
			$html[] = '<form name="member" method="post" action="member-login" onSubmit="return validasiMember(this)" >';
			// Email member
			$html[] = '<div class="col col_13 checkout">';
			$html[] = 'Email:
				      <input type="text" name="email"  style="width:300px;"  maxlength="150" />';
			$html[] = '</div>';
			// Password member
			$html[] = '<div class="col col_13 checkout">
				      Password:
				      <input type="password" name="password"  style="width:300px;" maxlength="32"  />
				      <span style="font-size:10px"><a href="lupa-katasandi">Lupa Password ?</a></span>
				</div>';
			
			// create token for prevent CSRF
			$key= 'PiLu5!@#$%^&*0nLinEShoP';
			$CSRF = sha1(mt_rand(1,1000000) . $key);
			$_SESSION['CSRF'] = $CSRF;
			
			$html[] = '<input type="hidden" name="csrf" value="'.$CSRF.'"/>';
			$html[] = '<input type="submit" name="login" class="button" value="Masuk"><br />';
			$html[] = '</form>';
		
	}
	
	return implode("\n", $html);
}

// Member Logout
function memberLogout() 
{
	
	global $customer, $shoppingCart;
	
	$dbh = new Pldb;
	
	$customer -> signOutMember();
	
	$sid = session_id();
	
	// cek sesi temporer produk dan hitung jumlahnya
	$ketemu = $shoppingCart -> checkItems($sid);
	$isiKeranjang = $shoppingCart -> getIsiKeranjang($sid);
	$jml = count($isiKeranjang);
	
	if ( $ketemu > 0) {
		
		for ( $i = 0; $i < $jml; $i++ ) {
			$sth  = $dbh -> query("DELETE FROM pl_orders_temp WHERE orders_temp_id = {$isiKeranjang[$i]['orders_temp_id']}");
		}
		
	}
	
	session_start();
	session_destroy();
	
	//Redirect to Homepage
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL="'.directPage().'">';
	
	exit();
	
}

// ************************Konten Lupa Kata sandi**************************** //
function forgetPassword() 
{
	
	global $customer, $option;
	
	$html = array();
	
	if (isset($_POST['reset']) && $_POST['reset'] == 'Reset Kata Sandi') {
		
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		
		$badCSRF = true; // check CSRF
		
		if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) 
			|| empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['CSRF']) {
					
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					<blockquote><h3>Terjadi Kesalahan!</h3>
					<b>Sorry, there was a security issue.</b>
					</blockquote>';
		
			$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		
			$badCSRF = true;
			
		}
		
		if (empty($email)) {
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Alamat E-mail harus diisi!.</b>
					   </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "lupa-katasandi";} setTimeout("leave()", 3640);</script>';
			
		} elseif ( $customer -> emailExists($email) == false) {
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>E-mail tidak terdaftar!.</b>
					   </blockquote>';
				
			$html[] = '<script type="text/javascript">function leave() { window.location = "lupa-katasandi";} setTimeout("leave()", 3640);</script>';
			
		} elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
			
			$html[] = '<h2>Error</h2>
					  <div class="cleaner"></div>
					  <blockquote><h3>Terjadi Kesalahan!</h3>
					  <b>Penulisan E-mail tidak valid!.</b>
					   </blockquote>';
			
			$html[] = '<script type="text/javascript">function leave() { window.location = "lupa-katasandi";} setTimeout("leave()", 3640);</script>';
				
		} else {
			
			$badCSRF = false;
			unset($_SESSION['CSRF']);
			
			$dbc = new Pldb;
			
			$tempToken = random_generator(64);
			
			$sql = "UPDATE pl_customers SET customer_resetKey = :customer_resetKey,
				   customer_resetComplete = 'No' WHERE email = :email ";
			
			$stmt = $dbc -> prepare($sql);
			$stmt -> bindValue(":customer_resetKey", $tempToken);
			$stmt -> bindValue(":email", $email);
			
			try {
				
				$stmt -> execute();
				
				if ( $row = $stmt -> rowCount() == 1) {
					
					//Mengambil data pemilik toko
					$metaowner = '';
					
					$data_owner = $option -> getOptions();
					
					$metaowner = $data_owner['results'];
					
					foreach ( $metaowner as $owner ) {
						
						$namaToko = $owner -> getSite_Name();
					}
					
					// send an email
					$toMember = safeEmail($email);
					$subjek = "Password Reset";
					$pesan = "<html><body>
                             Jika anda tidak pernah meminta pesan informasi tentang lupa password, silahkan untuk menghiraukan email ini.<br />
						     Tetapi jika anda memang yang meminta pesan informasi ini, maka silahkan untuk mengklik tautan (link) di bawah ini :<br /><br />
						     <a href=".PL_DIR."ganti-katasandi&tempToken=$tempToken >Recover Password</a><br /><br />
						     Terima Kasih,<br />
						     <b>Tim Pengembang Pilus Open Source E-commerce Software</b>
							 </body></html>";
					
					// Kirim Email dalam format HTML -- ke member
					$kirim_email = new Mailer();
					$kirim_email -> setSendText(false);
					$kirim_email -> setSendTo($toMember);
					$kirim_email -> setFrom($namaToko);
					$kirim_email -> setSubject($subjek);
					$kirim_email -> setHTMLBody($pesan);
					
					if ($kirim_email -> send()) {
						
						$html[] = '<h2>Reset Password Berhasil Terkirim!</h2>
					               <div class="cleaner"></div>
								  <blockquote><h3>Permintaan reset password berhasil terkirim ke Email!</h3>
					               <b>Silahkan cek email anda, untuk mendapatkan kata sandi baru</b>
					               </blockquote>';
						
						$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 10000);</script>';
							
					}
					
				}
				
			} catch (PDOException $e) {
				
				LogError::newMessage($e);
				LogError::customErrorMessage();
			}
			
		}
		
	} else {
		
		$html[] = '<h2>Reset Password</h2>';
		$html[] = '<div class="col col_13">';
		$html[] = '<p>Masukkan e-mail anda pada kolom di bawah ini !</p>';
		$html[] = '<div id="contact_form">';
		$html[] = '<form method="post" name="forgetPass" action="lupa-katasandi" onSubmit="return validateForgetPassword(this)" >';
		$html[] = '<label for="email">E-mail:</label> <input type="text" id="email" name="email" class="validate-email required input_field" />
			      <div class="cleaner h10"></div>';
		
		// create token for prevent CSRF
		$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$CSRF = sha1(mt_rand(0,999) . $key);
		$_SESSION['CSRF'] = $CSRF;
		
		$html[] = ' <input type="hidden" name="csrf" value="'.$CSRF.'"/>';
		$html[] = '<input type="submit" name="reset" class="button" value="Reset Kata Sandi">';
		$html[] = '<span style="font-size:10px"><a href="javascript:history.go(-1)">Masuk sebagai Member ?</a></span>';
		
		$html[] = '</form>';
		$html[] = '</div></div>';
	}
	
	return implode("\n", $html);
	
}

// ************************Konten Ganti Kata Sandi**************************** //
function recoverPassword() 
{
	
	global $customer, $tempToken;
	
	$html = array();
	
	if ( isset($_POST['recover']) && $_POST['recover'] == 'Ganti Password') {
		
		$password = isset($_POST['password']) ? preventInject($_POST['password']) : "";
        $confirmed = isset($_POST['confirmed']) ? preventInject($_POST['confirmed']) : "";
		$token = isset($_POST['token']) ? preventInject($_POST['token']) : "";
		$email = isset($_POST['email']) ? preventInject($_POST['email']) : "";
			
		$badCSRF = true; // check CSRF
		
	 if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
					|| $_POST['csrf'] !== $_SESSION['CSRF']) {
				$html[] = '<h2>Error</h2>
					      <div class="cleaner"></div>
					      <blockquote><h3>Terjadi Kesalahan!</h3>
					      <b>Sorry, there was a security issue.</b>
					     </blockquote>';
					
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
					
				$badCSRF = true;
			}
				
			if ( empty($password) || empty($confirmed)) { // cek password
				
				$html[] = '<h2>Error</h2>
					     <div class="cleaner"></div>
						 <blockquote><h3>Terjadi Kesalahan!</h3>
					     <b>Semua Kolom harus diisi!.</b>
					     </blockquote>';
		
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
				
			} elseif (!isset($password) || !isset($confirmed) 
			 || !$password || !$confirmed || $password != $confirmed) {
				
				$html[] = '<h2>Error</h2>
					      <div class="cleaner"></div>
					      <blockquote>
				          <h3>Terjadi Kesalahan!</h3>
					      Password yang anda ketik tidak sama.
					     </blockquote>';
				
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
			
			} elseif (strlen($password) < 6) {
					
				$html[] = '<h2>Error</h2>
					<div class="cleaner"></div>
					<blockquote>
				 <h3>Terjadi Kesalahan!</h3>
					Password tidak boleh kurang dari 6 karakter!
					</blockquote>';
				
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
					
			} elseif (strlen($confirmed) < 6) {
				$html[] = '<h2>Error</h2>
					     <div class="cleaner"></div>
					     <blockquote>
				         <h3>Terjadi Kesalahan!</h3>
					        Password tidak boleh kurang dari 6 karakter!
					     </blockquote>';
				
				$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
					
			} else {
				
				$badCSRF = false;
				unset($_SESSION['CSRF']);
				
				$recover_password = $customer -> recoverPassword($email, $password, $token);
				
				if ($recover_password == 1) {
					
					$html[] = '<h2>Ganti Kata Sandi Berhasil!</h2>
						  <div class="cleaner h20"></div>
						  <div class="cleaner"></div>
						  <blockquote>
						  <h3>Kata sandi sudah diubah!</h3>
						  <b>Anda dapat menggunakan kata sandi baru untuk masuk ke Log In member<b>
						  </blockquote>';
						
					$html[] = '<script type="text/javascript">function leave() { window.location = "member-login";} setTimeout("leave()", 5000);</script>';
						
				}
				
			}
			
		} else {
			
			$dbc = new Pldb;
			
			$stmt = $dbc -> prepare("SELECT email, customer_resetKey, customer_resetComplete
			                FROM pl_customers WHERE customer_resetKey = :token ");
			
			$stmt -> execute(array(":token" => $tempToken));
			
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			
			if (empty($row['customer_resetKey'])) {
				 
				$html[] = '<h2>Error</h2>
					     <div class="cleaner"></div>
				 		 <blockquote><h3>Terjadi Kesalahan!</h3>
					      <b>Token tidak valid.Cek Email Anda!</b>
					      </blockquote>';
			
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
			
			} elseif ( $row['customer_resetComplete'] == 'Yes') {
				
				$html[] = '<h2>Error</h2>
					    <div class="cleaner"></div>
						<blockquote><h3>Terjadi Kesalahan!</h3>
					    <b>Password Anda sudah diubah!</b>
					    </blockquote>';
			
				$html[] = '<script type="text/javascript">function leave() { window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
			
			} else {
				
				$html[] = '<h2>Ganti Kata Sandi</h2>';
				$html[] = '<div class="col col_13">';
				$html[] = '<p>Masukkan kata sandi baru:</p>';
				$html[] = '<div id="contact_form">';
				$html[] = '<form method="post" name="forgetPass" action="ganti-katasandi" onSubmit="return validateForgetPassword(this)" >';
				
				// password
				$html[] = '<label for="password">Kata sandi:</label> <input type="password" id="password" name="password" class="required input_field" />
				<div class="cleaner h10"></div>';
				
				$html[] = '<label for="confirmed">Ketik ulang kata sandi:</label> <input type="password" id="confirmed" name="confirmed" class="required input_field" />
				<div class="cleaner h10"></div>';
				
				// create token for prevent CSRF
				$key= 'ABCDE!FGHI@6JKLMNOPQRST8UVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
				$CSRF = sha1(mt_rand(0,999) . $key);
				$_SESSION['CSRF'] = $CSRF;
				
				$html[] = ' <input type="hidden" name="csrf" value="'.$CSRF.'"/>';
				$html[] = ' <input type="hidden" name="token" value="'.$row['customer_resetKey'].'"/>';
				$html[] = ' <input type="hidden" name="email" value="'.$row['email'].'"/>';
				$html[] = '<input type="submit" name="recover" class="button" value="Ganti Password"><br />';
				$html[] = '</form>';
				$html[] = '</div></div>';
				
			}
			
		}
		
		return implode("\n", $html);
		
}



// ********************Konten pencarian artikel blog********************** //

function searchArticle()
{

	global $keyword;
	
	$dbh = new Pldb;
	
	$html = array();
	
	if ( empty($keyword))
	{

		
		$html[] = '<h2>Terjadi Kesalahan!</h2>';
		$html[] = '<div class="cleaner"></div>';
		$html[] = '<blockquote>
				   <h3>Kata kunci pencarian harus diisi!</h3>
				    we can not deliver your purpose
				   </blockquote>';
		$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
	
	}
	else
	{
		$data_pencarian = $dbh -> searchArticle($keyword);
	
		$pencarian = $data_pencarian['results']; // hasil pencarian artikel dengan :keyword
	
		$found = $data_pencarian['totalRows']; // jumlah artikel yang ditemukan berdasarkan :keyword
	
		if ( $found > 0 )
		{
			$html[] = '<h2>Hasil Pencarian Artikel:</h2>';
			$html[] = '<p>Ditemukan '.$found.' artikel dengan kata <font style="background-color:#00FFFF"><b>'.$keyword.'</b></font></p>';
				
				
			foreach ($pencarian as $data_cari)
			{
					
				//tampilkan hanya sebagian isi artiekl
				$isi = html_entity_decode($data_cari['post_content']);
				$isi_artikel = strip_tags($isi);
				$isi_artikel = substr($isi_artikel,0,250); // ambil sebanyak 250 karakter
				$isi_artikel = substr($isi_artikel,0,strrpos($isi_artikel," ")); // potong per spasi kalimat
					
				$html[] = '<div class="cleaner h20"></div>';
				$html[] = '<h3><a href="read-'.$data_cari['post_slug'].'" title="'.$data_cari['post_title'].'">'.$data_cari['post_title'].'</a></h3>';
				$html[] = '<p>'.$isi_artikel.'...<a href="read-'.$data_cari['post_slug'].'" title="'.$data_cari['post_title'].'"><b>Selengkapnya</b></a></p>';
				$html[] = '<div class="cleaner"></div>';
					
			}
	
		}
		else
		{
	
			$html[] = '<blockquote>
					   <h3>Not Found!</h3>
					   Tidak ditemukan artikel dengan kata <b>'. $keyword .'</b>
					</blockquote>';
		}
	
	}
	
	return implode("\n", $html);
}


// ********************Konten pencarian produk********************** //

function searchProduct()
{
	global $keyword;
	
	$dbh = new Pldb;
	
	$html = array();
	
	if ( empty($keyword)) {
	
		$html[] = '<h2>Terjadi Kesalahan!</h2>';
		$html[] = '<div class="cleaner"></div>';
		$html[] = '<blockquote>
				  <h3>Kata kunci pencarian harus diisi!</h3>
				  we can not deliver your purpose
				  </blockquote>';
		$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
	
	} else {
	
		$data_pencarian = $dbh -> searchProduct($keyword);
	
		$pencarian = $data_pencarian['results']; // hasil pencarian produk dengan :keyword
	
		$found = $data_pencarian['totalRows']; // jumlah produk yang ditemukan berdasarkan :keyword
	
		if ( $found > 0 )
		{
			$html[] = '<h2>Hasil Pencarian Produk:</h2>';
			$html[] = '<p>Ditemukan '.$found.' produk dengan kata <font style="background-color:#00FFFF"><b>'.$keyword.'</b></font></p>';
	
	
			foreach ($pencarian as $data_cari)
			{
	
				//tampilkan hanya sebagian isi produk
				$isi = $data_cari['description'];
				$isi_produk = html_entity_decode($isi);
				$isi_produk = strip_tags($isi_produk);
				$isi_produk = substr($isi_produk,0,250); // ambil sebanyak 250 karakter
				$isi_produk  = substr($isi_produk,0,strrpos($isi_produk," ")); // potong per spasi kalimat
	
				$html[] = '<div class="cleaner h20"></div>';
				$html[] = '<h3><a href="produk-'.htmlspecialchars($data_cari['slug']).'" title="'.htmlspecialchars($data_cari['product_name']).'">'.$data_cari['product_name'].'</a></h3>';
				$html[] = '<p>'.$isi_produk.' ... <a href="produk-'.htmlspecialchars($data_cari['slug']).'" title="'.htmlspecialchars($data_cari['product_name']).'"><b>Selengkapnya</b></a></p>';
				$html[] = '<div class="cleaner"></div>';
	
			}
	
		}
		else
		{
	
			$html[] = '<blockquote>
					<h3>Not Found!</h3>
					Tidak ditemukan produk dengan kata <b>'. $keyword .'</b>
							</blockquote>';
			$html[] = '<script type="text/javascript">function leave() {  window.location = "'.PL_DIR.'";} setTimeout("leave()", 3640);</script>';
		}
	}
	
	return implode("\n", $html);
	
}