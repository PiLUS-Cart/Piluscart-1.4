<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Report 
 * Mapping table pl_orders, 
 * pl_product, and pl_orders_detail
 * untuk dijadikan laporan penjualan
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Report 
{
	
	/**
	 * order's Id
	 * @var integer
	 */
	protected $orders_id;
	
	/**
	 * faktur
	 * @var integer
	 */
	protected $faktur;
	
	/**
	 * date_order
	 * @var string
	 */
	protected $date_order;
	
	/**
	 * product_name
	 * @var string
	 */
	protected $product_name;
	
	/**
	 * product's price
	 * @var integer
	 */
	protected $price;
	
	/**
	 * quantity
	 * @var integer
	 */
	protected $quantity;
	
	/**
	 * Initialize object properties
	 * @param string $input
	 */
	public function __construct($input = false) {
		if (is_array($input)) {
			foreach ($input as $key => $val) {
	
				$this->$key = $val;
			}
		}
	}
	
	/**
	 * Order Id
	 * 
	 * @method getOrderId
	 * 
	 */
	public function getOrderId() {
		
		return $this->orders_id;
	}
	
	public function getFaktur() {
		
		return $this->faktur;
	}
	
	/**
	 * tanggal pemesanan
	 * 
	 * @method getDateOrder
	 */
	public function getDateOrder() {
		 
		return $this->date_order;
	}
	
	/**
	 * nama produk
	 * 
	 * @method getProductName
	 */
	public function getProductName() {
		
		return $this->product_name;
	}
	
	/**
	 * harga produk
	 * 
	 * @method getProductPrice
	 */
	public function getProductPrice() {
		 
		return $this->price;
	}
	
	/**
	 * jumlah produk dipesan
	 * 
	 * @method getOrderQuantity
	 */
	public function getOrderQuantity() {
		
		return $this->quantity;
	}
	
	/**
	 * @method cekTransaksiHarian
	 * @param string $tglSekarang
	 */
	public function cekTransaksiHarian($tglSekarang) {
		
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id AS faktur, DATE_FORMAT(o.date_order, '%d-%m-%Y') AS tanggal,
		p.product_name, p.price, od.quantity
		FROM pl_orders AS o
		INNER JOIN pl_orders_detail AS od ON o.orders_id = od.orders_id
		INNER JOIN pl_product AS p ON od.product_id = p.ID
		WHERE o.status='Lunas' AND o.date_order = '$tglSekarang'";
		
		$sth = $dbh -> query($sql);
		
		return $sth -> rowCount();
		
	}
	
	/**
	 * @method cekTransaksiPeriodik
	 * @param string $awal
	 * @param string $akhir
	 */
	public function cekTransaksiPeriodik($awal, $akhir) {
		
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id AS faktur, DATE_FORMAT(o.date_order, '%d-%m-%Y') AS tanggal,
		p.product_name, p.price, od.quantity
		FROM pl_orders AS o
		INNER JOIN pl_orders_detail AS od ON o.orders_id = od.orders_id
		INNER JOIN pl_product AS p ON od.product_id = p.ID
		WHERE o.status='Lunas' AND o.date_order BETWEEN '$awal' AND '$akhir' ";
		
		$sth = $dbh -> query($sql);
		
		return $sth -> rowCount();
	}
	
	/**
	 * Laporan harian
	 * 
	 * @method getReportToday
	 * @param string $tglSekarang
	 * @return Report
	 */
	public static function getReportToday($tglSekarang) {
		
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id AS faktur, DATE_FORMAT(o.date_order, '%d-%m-%Y') AS tanggal,
		p.product_name, p.price, od.quantity
		FROM pl_orders AS o
		INNER JOIN pl_orders_detail AS od ON o.orders_id = od.orders_id
		INNER JOIN pl_product AS p ON od.product_id = p.ID
		WHERE o.status='Lunas' AND o.date_order = :date_order";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":date_order", $tglSekarang);
		
		try {
			
			$sth -> execute();
			$list = array();
			
			while ($results = $sth -> fetch()) {
				
				 $latestReport = new Report($results);
				 $list[] = $latestReport;
			}
			
			$numbers = "SELECT orders_id FROM pl_orders_detail";
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
	 * Laporan per periode
	 * 
	 * @method getReportPeriodic
	 * @param string $awal
	 * @param string $akhir
	 */
	public static function getReportPeriodic($awal, $akhir) {
		
		$dbh = new Pldb;
		
		$sql = "SELECT o.orders_id AS faktur, DATE_FORMAT(o.date_order, '%d-%m-%Y') AS tanggal,
		p.product_name, p.price, od.quantity
		FROM pl_orders AS o
		INNER JOIN pl_orders_detail AS od ON o.orders_id = od.orders_id
		INNER JOIN pl_product AS p ON od.product_id = p.ID
		WHERE o.status='Lunas' AND o.date_order BETWEEN :awal AND :akhir ";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":awal", $awal);
		$sth -> bindValue(":akhir", $akhir);
		
		try {
			
			$sth -> execute();
			$list = array();
			
			while ($results =  $sth -> fetch()) {
				
				 $reports = new Report($results);
				 $list[] = $reports;
			}
			
			$numbers = "SELECT orders_id FROM pl_orders_detail";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
			
			return (array("results" => $list, "totalRows" => $totalRows));
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		
		}
	}
}