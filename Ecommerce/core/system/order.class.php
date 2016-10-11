<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Order
 * Mapping table pl_orders
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright Copyright (c) 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Order 
{

	/**
	 * order's id
	 * @var integer
	 */
	protected $orders_id;

	/**
	 * quantity
	 * jumlah produk yang dipesan
	 * 
	 * @var integer
	 */
	protected $quantity;
	
	/**
	 * status order
	 * @var string
	 */
	protected $status;

	/**
	 * time order
	 * @var string
	 */
	protected $time_order;

	/**
	 * date order
	 * @var string
	 */
	protected $date_order;

	/**
	 * customer's ID
	 * @var customer's ID
	 */
	protected $customer_id;

	/**
	 * customer's fullname
	 * @var string
	 */
	protected $fullname;

	/**
	 * customer's email
	 * @var string
	 */
	protected $email;

	/**
	 * customer's address
	 * @var string
	 */
	protected $address;
	
	/**
	 * customer's phone
	 * @var string
	 */
	protected $phone;
	/**
	 * product's name
	 * @var string
	 */
	protected $product_name;
	
	/**
	 * product's price
	 * @var integer
	 */
	protected $price;
	
	/**
	 * product's stock
	 * @var integer
	 */
	protected $stock;
	
	/**
	 * product's weight
	 * @var integer
	 */
	protected $weight;
	
	/**
	 * product's discount
	 * @var integer
	 */
	protected $discount;
	
	/**
	 * district's Id
	 * @var integer
	 */
	protected $district_id;
	
	/**
	 * shipping's Id
	 * @var integer
	 */
	protected $shipping_id;
	
	/**
	 * shipping's name
	 * @var string
	 */
	protected $shipping_name;
	
	/**
	 * 
	 * @var integer
	 */
	protected $shipping_cost;
	
	/**
	 * Initialize object properties
	 * @param string $input
	 */
	public function __construct($input = false) 
	{
		if (is_array($input)) {
			foreach ($input as $key => $val) {
	
				$this->$key = $val;
			}
		}
	}

	/**
	 * get orders's Id
	 * @return number
	 */
	public function getOrderId()
	{
		return $this->orders_id;
	}

	/**
	 * get quantities's product ordered
	 * 
	 * @return integer
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	/**
	 * get order's status
	 * @return string
	 */
	public function getOrderStatus()
	{
		return $this->status;
	}

	/**
	 * get time's order
	 * @return string
	 */
	public function getTimeOrder()
	{
		return $this->time_order;
	}

	/**
	 * get date's order
	 * @return string
	 */

	public function getDateOrder()
	{
		return $this->date_order;
	}

	/**
	 * get customer's Id
	 * @return integer
	 */
	public function getCustomerId()
	{
		return $this->customer_id;
	}

	/**
	 * get customer's fullname
	 * @return string
	 */
	public function getCustomerFullname()
	{
		return $this->fullname;
	}

	/**
	 * get customer's Email
	 * @return email
	 */
	public function getCustomerEmail()
	{
		return $this->email;
	}
	
	/**
	 * get cutomer's address
	 * @return string
	 */
	public function getCustomerAddress()
	{
		return $this->address;
	}
	
	/**
	 * get customer's phone
	 * @return string
	 */
	public function getCustomerPhone()
	{
		return $this->phone;
	}
	
	/**
	 * get product's name
	 * @return string
	 */
	public function getProductName()
	{
		return $this->product_name;
	}
	
	/**
	 * get product's price
	 * @return integer
	 */
	public function getProductPrice()
	{
		return $this->price;
	}
	
	/**
	 * get product's stock
	 * @return integer
	 */
	public function getProductStock()
	{
		return $this->stock;
	}
	
	/**
	 * get product's weight
	 * 
	 * @return integer
	 */
	public function getProductWeight()
	{
		return $this->weight;
	}
	
	/**
	 * get product's discount
	 * 
	 * @return integer
	 */
	public function getProductDiscount()
	{
		return $this->discount;
	}
	
	/**
	 * get district's Id
	 * 
	 * @return number
	 */
	public function getDistrictId()
	{
		return $this->district_id;
	}
	
	/**
	 * get shipping's Id
	 * 
	 * @return number
	 */
	public function getShippingId()
	{
		return $this->shipping_id;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getShippingName()
	{
		return $this->shipping_name;
	}
	
	/**
	 * get shipping cost
	 * @return number
	 */
	public function getOngkir()
	{
		return $this->shipping_cost;
	}
	
	/**
	 * @method simpanTransaksi
	 * digunakan untuk transaksi pembelian
	 * member pelanggan baru
	 * insert new record to
	 * table pl_orders
	 */
	public function simpanTransaksi($customerID)
	{
		global $option, $shippingCost, $shoppingCart;

		$dbh = new Pldb;

		$transaction = false;

		$tgl_skrg = date("Ymd");
		$jam_skrg = date("H:i:s");

	
		if (isset($customerID)) {
		    
			$getDataCustomer = "SELECT ID, fullname, email, password, address, phone, 
					         district_id, shipping_id, customer_type
				           FROM pl_customers WHERE ID = :ID";
			
			$stmt = $dbh -> prepare($getDataCustomer);
			$stmt -> bindParam(":ID", $customerID, PDO::PARAM_INT);
			$stmt -> execute();
			
			$result = $stmt -> fetchObject();
			
			$ID_kustomer = $result -> ID;
			$nama_kustomer = $result -> fullname;
			$email_kustomer = $result -> email;
			$alamat_kustomer = $result -> address;
			$telpon_kustomer = $result -> phone;
			$pass_kustomer = $result -> password;
			$distrik_kustomer = $result -> district_id;
		
		}
		
		//simpan data order
		$sql = "INSERT INTO pl_orders(time_order, date_order, customer_id)
				VALUES(?, ?, ?)";
			
			
		$data = array($jam_skrg, $tgl_skrg, $ID_kustomer);
			
		$sth = $dbh -> pstate($sql, $data);
			
		//mendapatkan nomor orders
		$id_orders = $dbh -> lastId();
		
		
		//hitung jumlah produk yang dibeli yang terdapat dalam keranjang belanja
		$sid = session_id();
		$isiKeranjang = $shoppingCart -> getIsiKeranjang($sid);
		$jml = count($isiKeranjang);
			
		//simpan detail order
		for ( $i = 0; $i < $jml; $i++) {
			
			$pid = $isiKeranjang[$i]['product_id'];
			$quantity = $isiKeranjang[$i]['quantity'];
			$saveOrder = "INSERT INTO pl_orders_detail(orders_id, product_id, quantity)
			VALUES('$id_orders', '{$isiKeranjang[$i]['product_id']}', '{$isiKeranjang[$i]['quantity']}')";

			$sth2 = $dbh -> query($saveOrder);

		}

		// setelah data order tersimpan, hapus data pembelian di shopping cart(tabel pl_orders_temp)
		for ($i = 0; $i < $jml; $i++) {
			$sth3  = $dbh -> query("DELETE FROM pl_orders_temp WHERE orders_temp_id = {$isiKeranjang[$i]['orders_temp_id']}");
		}

		$daftarProduk = "SELECT od.orders_id, od.product_id, od.quantity,
				p.ID, p.product_catId, p.product_name,
				p.slug, p.description, p.price, p.stock, p.weight,
				p.date_submited, p.bought, p.discount, p.image
				FROM pl_orders_detail AS od, pl_product AS p
				WHERE od.product_id = p.ID AND od.orders_id = ? ";

		$data_order = array($id_orders);
		$sth4 = $dbh -> pstate($daftarProduk, $data_order);
		
		//Mengambil data pemilik toko
		$metaowner = '';

		$data_owner = $option -> getOptions();

		$metaowner = $data_owner['results'];

		foreach ( $metaowner as $owner )
		{
			$owner_email = $owner -> getOwnerEmail();
			$no_rekening = $owner -> getNoRekening();
			$nomor_telp = $owner -> getNoTelpon();
			$pinBB = $owner -> getPinBB();
			$namaToko = $owner -> getSite_Name();
		}

		
		
		//Kirim Email Pemberitahuan
		$pesan = "Terima kasih telah melakukan pembelian di toko online $namaToko<br /><br />
		Nama: $nama_kustomer <br />
		Email: $email_kustomer <br />
		Password: $pass_kustomer <br />
		Alamat: $alamat_kustomer <br />
		Telepon: $telpon_kustomer <br /><hr />

		No.Pembelian: $id_orders<br />
		Data Pembelian anda adalah sebagai berikut: <br /><br />";

		$no = 1;

		while ($d = $sth4 -> fetchObject()) {

			$disc = ( $d->discount/100 ) * $d->price;
			$hargadisc = number_format(($d->price - $disc),0,",",".");
			$subtotal = ($d->price - $disc) * $d->quantity;

			$subtotalberat = $d -> weight * $d -> quantity; //total berat per item produk
			$totalberat = $totalberat + $subtotalberat; //berat total semua produk yang dibeli

			$total = $total + $subtotal;
			$subtotal_rp = idrFormat($subtotal);
			$total_rp = idrFormat($total);
			$harga = idrFormat($d->price);


			$pesan .= $d->quantity . $d->product_name . "->" ."Rp" . $harga ."->" . "subtotal: Rp." . $subtotal_rp . "<br />";
			$no++;
				
		}

		$ongkos = $shippingCost -> getDistrictById($distrik_kustomer);
		$pengiriman = $ongkos -> getShipping_Cost(); 
		$ongkosKirim = $pengiriman * $totalberat;

		if ( $ongkosKirim < $pengiriman)
		{
			$grandTotal = $total + $pengiriman;
		}
		else 
		{
			$grandTotal = $total + $ongkosKirim;
		}
		
		$ongkoskirim_rp = idrFormat($ongkosKirim);
		$pengiriman_rp = idrFormat($pengiriman);
		$grandTotalRp = idrFormat($grandTotal);

		$pesan .= "<br />Total : Rp. $total_rp
		<br />Ongkos kirim untuk tujuan kota anda: Rp. $pengiriman_rp /Kg
		<br />Berat Total : $totalberat Kg";
		
		if ( $ongkosKirim < $pengiriman ) {
		    $pesan .="<br />Total Ongkos Kirim : Rp. $pengiriman_rp";
		}
		else 
		{
			$pesan .= "<br />Total Ongkos Kirim: Rp. $ongkoskirim_rp";
		}
		
		$pesan .="<br />Jumlah Pembayaran : Rp. $grandTotalRp
		<br /><br /><b>Silahkan lakukan pembayaran sebanyak jumlah pembayaran yang tercantum, ke rekening:
		$no_rekening
		<br />Apabila sudah transfer, segera konfirmasi pembayaran ke nomor : $nomor_telp atau melalui BBM : $pinBB";

		$subjek = "Pembelian -- Pemesanan Online di Toko $namaToko";
		
		// Kirim Email dalam format HTML -- ke kustomer
		$kirim_email = new Mailer();
		$kirim_email -> setSendText(false);
		$kirim_email -> setSendTo($email_kustomer);
		$kirim_email -> setFrom($namaToko);
		$kirim_email -> setSubject($subjek);
		$kirim_email -> setHTMLBody($pesan);

		if ($kirim_email -> send())
		{
			$data_notifikasi = array(
						
					'notify_title' => "newOrder",
					'date_submited' => $tgl_skrg,
					'time_submited' => $jam_skrg,
					'content' => preventInject($pesan)
			);
				
			pushNotification($data_notifikasi); // Kirim Email dalam format HTML -- ke owner
		}

	}

	/**
	 * Digunakan untuk menyimpan 
	 * transaksi pembelian
	 * member yang telah terdaftar
	 * 
	 * @method simpanTransaksiMember
	 * @param string $email
	 * @param string $password
	 */
	public function simpanTransaksiMember($email = null, $password = null)
	{
		global $option, $shippingCost, $shoppingCart, $customer;

		$dbh = new Pldb;

		$tgl_skrg = date("Ymd");
		$jam_skrg = date("H:i:s");
		
		// mendapatkan data kustomer
		
		// member belanja dulu --> login
		if ( !$customer -> isMemberLoggedIn()) 
		{
			$data_kustomer = $customer -> getCustomer($email, $password);
			$id_kustomer = $data_kustomer -> ID; // mendapatkan ID kustomer
			$nama_kustomer = $data_kustomer -> fullname;
			$alamat_kustomer = $data_kustomer -> address;
			$telpon = $data_kustomer -> phone;
			$kota = $data_kustomer -> district_id;
		}
		else  // member login --> belanja
		{
			$data_kustomer = $customer -> getCustomerBySession($_SESSION['member_session']);
			
			$id_kustomer = $data_kustomer -> getId();
			$nama_kustomer = $data_kustomer -> getCustomerFullname();
			$alamat_kustomer = $data_kustomer -> getCustomerAddress();
			$telpon = $data_kustomer -> getCustomerPhone();
			$kota = $data_kustomer -> getDistrictId();
			
		}
		
		
		// save order
		$sql = "INSERT INTO pl_orders(time_order, date_order, customer_id)VALUES(?, ?, ?)";
		
		$data = array($jam_skrg, $tgl_skrg, $id_kustomer);
		
		$sth = $dbh -> pstate($sql, $data);
		
		//mendapatkan nomor orders
		$id_orders = $dbh -> lastId();
		
		// hitung jumlah produk yang dipesan 
		$sid = session_id();
		$isiKeranjang = $shoppingCart -> getIsiKeranjang($sid);
		$jml = count($isiKeranjang);
		
		//simpan detail order
		for ( $i = 0; $i < $jml; $i++)
		{
			$saveOrder = "INSERT INTO pl_orders_detail(orders_id, product_id, quantity)
			VALUES(?, {$isiKeranjang[$i]['product_id']}, {$isiKeranjang[$i]['quantity']} )";
		
			$data_order = array($id_orders);
				
			$sth2 = $dbh -> pstate($saveOrder, $data_order);
		}
		
		// setelah data order tersimpan, hapus data pembelian di shopping cart(tabel pl_orders_temp)
		for ( $i = 0; $i < $jml; $i++ )
		{
			$sth3  = $dbh -> query("DELETE FROM pl_orders_temp WHERE orders_temp_id = {$isiKeranjang[$i]['orders_temp_id']}");
		}
		
		$daftarProduk = "SELECT od.orders_id, od.product_id, od.quantity,
					p.ID, p.product_catId, p.product_name,
					p.slug, p.description, p.price, p.stock, p.weight,
					p.date_submited, p.bought, p.discount, p.image
					FROM pl_orders_detail AS od
					INNER JOIN pl_product AS p ON od.product_id = p.ID
					WHERE od.orders_id = :orders_id";
		
		$sth4 = $dbh -> prepare($daftarProduk);
		$sth4 -> bindValue(":orders_id", $id_orders);
		
		$sth4 -> execute();
		
		//Mengambil data pemilik toko
		$owner = array();
		
		$data_owner = $option -> getOptions();
		
		$owner['managers'] = $data_owner['results'];
		
		foreach ( $owner['managers'] as $manager )
		{
			$owner_email = $manager -> getOwnerEmail();
			$no_rekening = $manager -> getNoRekening();
			$nomor_telp = $manager -> getNoTelpon();
			$pinBB = $manager -> getPinBB();
			$namaToko = $manager -> getSite_Name();
		}
		
		//Kirim Email Pemberitahuan
		$pesan = "Terima kasih telah melakukan pembelian di toko online $namaToko<br /><br />
		Nama:  $nama_kustomer<br />
		Alamat: $alamat_kustomer<br />
		Telepon: $telpon <br /><hr />
		
		No.Pembelian: $id_orders<br />
		Data Pembelian anda adalah sebagai berikut: <br /><br />";
		
		$no = 1;
		
		while ($d = $sth4 -> fetchObject()) {
				
			$disc = ($d->discount/100)*$d->price;
			$hargadisc = number_format(($d->price - $disc),0,",",".");
			$subtotal = ($d->price-$disc) * $d->quantity;
				
			$subtotalberat = $d->weight * $d->quantity; //total berat per item produk
			$totalberat = $totalberat + $subtotalberat; //berat total semua produk yang dibeli
				
			$total = $total + $subtotal;
			$subtotal_rp = idrFormat($subtotal);
			$total_rp = idrFormat($total);
			$harga = idrFormat($d->price);
				
			$pesan .= $d->quantity . $d->product_name . "->" ."Rp" . $harga ."->" . "subtotal: Rp." . $subtotal_rp . "<br />";
			$no++;
		
		}
		
		$ongkos = $shippingCost -> getDistrictById($kota);
		$pengiriman = $ongkos -> getShipping_Cost();
		$ongkosKirim = $pengiriman * $totalberat;
		
	    if ( $ongkosKirim < $pengiriman)
		{
			$grandTotal = $total + $pengiriman;
		}
		else 
		{
			$grandTotal = $total + $ongkosKirim;
		}
		
		$ongkoskirim_rp = idrFormat($ongkosKirim);
		$pengiriman_rp = idrFormat($pengiriman);
		$grandTotalRp = idrFormat($grandTotal);
		
		$pesan .= "<br /><br />Total : Rp. $total_rp
		<br />Ongkos kirim untuk tujuan kota anda: Rp. $pengiriman_rp/Kg
		<br />Berat Total : $totalberat gram ";
		
		if ( $ongkosKirim < $pengiriman ) {
			$pesan .="<br />Total Ongkos Kirim : Rp. $pengiriman_rp";
		}
		else
		{
			$pesan .= "<br />Total Ongkos Kirim: Rp. $ongkoskirim_rp";
		}
		
		$pesan .="<br />Jumlah Pembayaran : Rp. $grandTotalRp
		<br /><b>Silahkan lakukan pembayaran sebanyak jumlah pembayaran yang tercantum, ke rekening:<br />
		$no_rekening
		<br />Apabila sudah transfer, segera konfirmasi pembayaran ke nomor : $nomor_telp atau melalui BBM : $pinBB";
		
		$subjek = "Pembelian -- Pemesanan Online di Toko $namaToko";
		$tokustomer = safeEmail($email);
		
		// Kirim Email dalam format HTML -- ke kustomer
		
		$kirim_email = new Mailer();
		$kirim_email -> setSendText(false);
		$kirim_email -> setSendTo($tokustomer);
		$kirim_email -> setFrom($namaToko);
		$kirim_email -> setSubject($subjek);
		$kirim_email -> setHTMLBody($pesan);
		
		if ($kirim_email -> send()) {
			$data_notifikasi = array(
		
					'notify_title' => "newOrder",
					'date_submited' => $tgl_skrg,
					'time_submited' => $jam_skrg,
					'content' => preventInject($pesan)
			);
		
			pushNotification($data_notifikasi); // Kirim Email dalam format HTML -- ke owner
		
		}
		
	}

	/**
	 * @method kurangiStock
	 */
	public function kurangiStock()
	{
		$dbh = new Pldb;
		
		// update untuk mengurangi stock
		$stock = "UPDATE pl_product,pl_orders_detail
			     SET pl_product.stock=pl_product.stock-pl_orders_detail.quantity
			     WHERE pl_product.ID=pl_orders_detail.product_id
			     AND pl_orders_detail.orders_id = ? ";
			
		$data_stock = array($this->orders_id);
		
		$sth = $dbh -> pstate($stock, $data_stock);
		
	}
	
	/**
	 * @method kurangiBestSeller
	 */
	public function kurangiBestSeller()
	{
		$dbh = new Pldb;
		
		// update best seller
		$bestSeller = "UPDATE pl_product,pl_orders_detail
			SET pl_product.bought=pl_product.bought-pl_orders_detail.quantity
			WHERE pl_product.ID = pl_orders_detail.product_id
			AND pl_orders_detail.orders_id = ? ";
			
		$data_bestSeller = array($this -> orders_id);
		
		$sth = $dbh -> pstate($bestSeller, $data_bestSeller);
	}
	
	/**
	 * @method tambahStock
	 */
	public function tambahStock()
	{
		$dbh = new Pldb;
		
		// update untuk menambah stock
		$stock = "UPDATE pl_product,pl_orders_detail
			SET pl_product.stock=pl_product.stock+pl_orders_detail.quantity
			WHERE pl_product.ID=pl_orders_detail . product_id
			AND pl_orders_detail.orders_id = ? ";
			
		$data_stock = array($this->orders_id);
		
		$sth = $dbh -> pstate($stock, $data_stock);
	}
	
	/**
	 * @method tambahBestSeller
	 */
	public function tambahBestSeller()
	{
		$dbh =  new Pldb;
		
		$bestSeller = "UPDATE pl_product,pl_orders_detail
			          SET pl_product.bought=pl_product.bought+pl_orders_detail.quantity
			          WHERE pl_product.ID=pl_orders_detail.product_id
			          AND pl_orders_detail.orders_id = ? ";
		
		$data_bestSeller = array($this -> orders_id);
		
		$sth = $dbh -> pstate($bestSeller, $data_bestSeller);
	}
	
	/**
	 * @method updateStatusOrder
	 */
	public function updateStatusOrder()
	{
		$dbh = new Pldb;
		
		// update status order
		$sql  = "UPDATE pl_orders SET status = ?
			               WHERE orders_id = ? ";
			
		$data = array($this->status, $this->orders_id);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> rowCount == 1;
	}
	
	/**
	 * @method getOrders
	 * retrieve all of orders
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:Order  number
	 */
	public static function getOrders($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT o.orders_id, o.status, o.time_order, o.date_order,
				o.customer_id, c.fullname, c.email, c.address,
				c.phone, c.district_id
				FROM pl_orders AS o
				INNER JOIN pl_customers AS c ON o.customer_id = c.ID
				ORDER BY o.orders_id DESC LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
			$list = array();
				
			while ($result = $sth -> fetch()) {

				$orders = new Order($result);
				$list[] = $orders;
			}
				
			$numbers = "SELECT orders_id FROM pl_orders";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
				
			return (array("results" => $list, "totalRows" => $totalRows));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}

	}

	/**
	 * @method getShoppingHistory
	 * @param integer $customer_id
	 */
	public static function getShoppingHistory($customer_id)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id, o.status, 
			    o.time_order, o.date_order,
				o.customer_id, c.fullname, c.email, c.address,
				c.phone, c.district_id
				FROM pl_orders AS o
				INNER JOIN pl_customers AS c ON o.customer_id = c.ID
				WHERE o.customer_id = :customer_id
				ORDER BY o.orders_id ";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":customer_id", $customer_id, PDO::PARAM_INT);
		
		try {
			
			$histories = array();
			$sth -> execute();
			
			foreach ( $sth -> fetchAll() as $results )
			{
				$histories[] = new Order($results);
			}
			
			$numbers = "SELECT orders_id FROM pl_orders WHERE customer_id = '$customer_id'";
			$sth = $dbh -> query($numbers);
			$totalRows= $sth -> rowCount();
			$dbh = null;
			
			return (array("results" => $histories, "totalRows" => $totalRows));
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}
		
	}
	
	/**
	 * Mengambil data detail order 
	 * yang meliputi data kustomer, data produk,
	 * pengiriman, status order dan kota tujuan
	 * 
	 * @method getOrder($order_id)
	 * @param integer $order_id
	 * @return Order
	 */
	public static function getOrder($order_id)
	{
		global $sanitasi;
		
		$dbh = new Pldb();
		
		$sql = "SELECT d.district_id, d.shipping_cost, d.shipping_id, 
				s.shipping_name, s.shipping_id, od.orders_id, 
				od.status, od.time_order, 
				od.date_order,
				od.customer_id, 
				c.fullname, c.email, 
				c.address,
				c.phone, c.district_id
				FROM pl_district AS d, pl_shipping AS s, pl_customers AS c, pl_orders AS od 
				WHERE c.district_id=d.district_id AND od.customer_id=c.ID
				AND od.orders_id = ? ";

		$cleaned = $sanitasi -> sanitasi($order_id, 'sql');
		
		$data = array($cleaned);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();
		
		if ($row) return new Order($row);
			
	}
	
	/**
	 * @method setStatus
	 * @return array
	 */
	public static function setStatus()
	{
		$dbh = new Pldb();
	
		$sanitasi = new Sanitize();
	
		$sql = "SELECT od.orders_id, od.status, od.time_order,
				od.date_order,
				od.customer_id,
				c.fullname, c.email 
				FROM pl_orders AS od
				INNER JOIN pl_customers AS c ON od.customer_id = c.ID
				WHERE od.customer_id=c.ID ORDER BY orders_id ";
	
	
		$sth = $dbh -> pstate($sql); 
		
		return $sth -> fetch();
		
	}
	
	/**
	 * @method setStatusOrder
	 * @param string $selected
	 * @return string
	 */
	public static function statusDropDown($selected = '')
	{
		// set up first option for selection if none selected
		
		$option_selected = '';
		
		if (!$selected) {
			$option_selected = ' selected="selected"';
		}
		
		// get Status
		$statusOrders = self::setStatus();
	
		$html = array();
	
		$html[] = '<td>Status Pemesanan</td>';
		$html[] = '<td><select name="status_order" >';
	
		if ($statusOrders['status'] == 'Baru')
		{
			$pilihan_status = array('Baru', 'Lunas', 'Batal');
		}
		elseif ($statusOrders['status'] == 'Lunas')
		{
			$pilihan_status = array('Lunas', 'Batal');
		}
		else 
		{
			$pilihan_status = array('Baru', 'Lunas', 'Batal');
		}
		
		
		foreach ( $pilihan_status as $statusOption )
		{
			if ($statusOption == $statusOrders['status'] )
			{
				$option_selected = ' selected="selected"';
			}
			
			// set up the option line
			$html[]  =  '<option value="' . $statusOption . '"' . $option_selected . '>' . $statusOption . '</option>';
			
			// clear out the selected option flag
			$option_selected = '';
		}
		
		$html[] = '</select></td>';
	
		return implode("\n", $html);
	}
	
	/**
	 * Mendapatkan detail produk
	 * yang dipesan berdasarkan id order
	 * 
	 * @method getDetailOrder
	 * @param integer $order_id
	 */
	public static function getDetailOrder($order_id)
	{
		global $sanitasi;
		
		$dbh = new Pldb;
		
		$sql = "SELECT od.orders_id, od.product_id, od.quantity, 
				p.product_name, p.price, p.stock,
				p.weight, p.date_submited, p.bought, p.discount, p.image
				FROM pl_orders_detail AS od
				INNER JOIN pl_product AS p ON od.product_id = p.ID 
				WHERE od.orders_id = ? ";
		
		$sanitized = $sanitasi -> sanitasi($order_id, 'sql');
		
		$data = array($sanitized);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$list_orders = array();
		
		while ($result = $sth -> fetch()) {
			
			 $detail_order = new Order($result);
			 
			 $list_orders[] = $detail_order;
			 
		}
		
		return (array("results" => $list_orders));
	}
	
	/**
	 * @method getShippingCost
	 * @param integer $order_id
	 */
	public static function getShippingCost($order_id)
	{
		global $sanitasi;
		
		$dbh = new Pldb;
		
		$sql = "SELECT d.district_id, d.shipping_cost, c.ID, c.district_id, o.orders_id, o.customer_id 
				FROM pl_district AS d, pl_customers AS c, pl_orders AS o
				WHERE c.district_id=d.district_id AND o.customer_id=c.ID
				AND o.orders_id = ?";
		
		$sanitized = $sanitasi -> sanitasi($order_id, 'sql');
		
		$data = array($sanitized);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();
		
		if ( $row) return new Order($row);
		
	}
	
	/**
	 * @method countOrders
	 * hitung jumlah orders
	 * @return number
	 */
	public static function countOrders()
	{
		$dbh = new Pldb;

		$sql = "SELECT orders_id, status, time_order, date_order, customer_id
				FROM pl_orders";

		$sth = $dbh -> query($sql);

		return $sth -> rowCount();
	}
	
	/**
	 * Method ini berperan
	 * menyampaikan notifikasi 
	 * order yang masuk
	 * 
	 * @method orderNotifications
	 * @return Order[][]|number[]
	 */
	public static function orderNotifications()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id, o.status, o.time_order, o.date_order,
				o.customer_id, c.fullname, c.email, c.address,
				c.phone, c.district_id
				FROM pl_orders AS o
				INNER JOIN pl_customers AS c ON o.customer_id = c.ID
				ORDER BY o.time_order DESC LIMIT 5 ";
		
		$list = array();
		
		try {
			 
		  $sth = $dbh -> query($sql);
			
		    foreach ( $sth -> fetchAll() as $results )
		    {
		    	$list[] = new Order($results);
		    	
		    }
		   
		    return (array("results" => $list));
		    	
		} catch (PDOException $e) {
			
			LogError::newMessage($e);
			LogError::customErrorMessage();
		}
	}
	
	/**
	 * @method findById
	 * @param unknown $orderId
	 */
	public static function findById($orderId)
	{
		$dbh = new Pldb;

		$sql = "SELECT orders_id, status, time_order, date_order, customer_id
				FROM pl_orders WHERE orders_id = ?";

		$data = array($orderId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}
}