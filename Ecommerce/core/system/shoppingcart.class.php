<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Shoppingcart
 * Mapping table pl_orders_temp
 * as shopping cart
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class ShoppingCart 
{

	/**
	 * orders temp's ID as
	 * item's ID in cart
	 * @var integer
	 */
	protected $orders_temp_id;

	/**
	 * product's id
	 * @var integer
	 */
	protected $product_id;

	/**
	 * product's name
	 * @var string
	 */
	protected $product_name;

	/**
	 * product's slug
	 * @var unknown
	 */
	protected $slug;

	/**
	 * product's description
	 * @var unknown
	 */
	protected $description;

	/**
	 * product's price
	 * @var unknown
	 */
	protected $price;

	/**
	 * product's stock
	 * @var unknown
	 */
	protected $stock;

	/**
	 * product's weight
	 * @var unknown
	 */
	protected $weight;

	/**
	 * how many product was bought?
	 * @var unknown
	 */
	protected $bought;

	/**
	 * product's discount
	 * @var string
	 */
	protected $discount;

	/**
	 * product's image
	 * @var string
	 */
	protected $image;

	/**
	 * item's session in
	 * cart - orders_temp
	 * @var string
	 */
	protected $temp_session;

	/**
	 * item's quantity
	 * @var integer
	 */
	protected $quantity;

	/**
	 * order's date temp
	 * @var string
	 */
	protected $date_orders_temp;

	/**
	 * orders time temp
	 * @var string
	 */
	protected $time_orders_temp;

	/**
	 * stock temporary
	 * @var string
	 */
	protected $temp_stock;

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
	 * get order's temp id
	 * @return number
	 */
	public function getOrderTempId()
	{
		return $this->orders_temp_id;
	}

	/**
	 * get product's id
	 * @return number
	 */
	public function getProductId()
	{
		return $this->product_id;
	}

	/**
	 * get product's name
	 * @return string
	 */
	public function getProduct_Name()
	{
		return $this->product_name;
	}

	/**
	 * get product's slug
	 * @return string
	 */
	public function getProduct_Slug()
	{
		return $this->slug;
	}

	/**
	 * get product's description
	 * @return string
	 */
	public function getProduct_Description()
	{
		return $this->description;
	}

	/**
	 * get product's price
	 * @return integer
	 */
	public function getProduct_Price()
	{
		return $this->price;
	}

	/**
	 * get product's stock
	 * @return unknown
	 */
	public function getProduct_Stock()
	{
		return $this->stock;

	}

	/**
	 * getProduct's weight
	 * @return unknown
	 */
	public function getProduct_Weight()
	{
		return $this->weight;
	}

	/**
	 * get how many product was bought
	 * @return unknown
	 */
	public function getProduct_Bought()
	{
		return $this->bought;

	}

	/**
	 * get product's discount
	 * @return string
	 */
	public function getProduct_Discount()
	{
		return $this->discount;
	}

	/**
	 * get product's images
	 * @return string
	 */
	public function getProduct_Image()
	{
		return $this->image;
	}

	/**
	 * get item temporary's session
	 * @return string
	 */
	public function getTempSession()
	{
		return $this->temp_session;
	}

	/**
	 * get item's quantity
	 * @return number
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * get order's date
	 * @return string
	 */
	public function getDateOrderTemp()
	{
		return $this->date_orders_temp;
	}

	/**
	 * get order's time
	 * @return string
	 */
	public function getTimeOrdertemp()
	{
		return $this->time_orders_temp;
	}

	/**
	 * get temporary stock
	 * @return string
	 */
	public function getTempStock()
	{
		return $this->temp_stock;
	}

	/**
	 * Method addItem
	 * this method add item to cart
	 * or orders_temp table
	 * @param integer $product_id
	 */
	public function addItem()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_orders_temp(product_id, temp_session,
				quantity, date_orders_temp,
				time_orders_temp, temp_stock)
				VALUES(?, ?, ?, ?, ?, ? )";

		$data = array(
					
				$this->product_id, $this->temp_session, $this -> quantity,
				$this->date_orders_temp, $this->time_orders_temp,
				$this->temp_stock
		);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * @method updateQuantity
	 * update an existing
	 * product's quantity in
	 * cart table a.k.a pl_orders_temp
	 */
	public function updateQuantity()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_orders_temp SET quantity = quantity + 1
				WHERE temp_session = ? AND product_id = ? ";

		$data = array($this->temp_session, $this->product_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method updateItem
	 * @param integer $orderTempId
	 * @param integer $total
	 */
	public function updateItem()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_orders_temp SET quantity = ?
				WHERE orders_temp_id = ?";

		$data = array($this->quantity, $this->orders_temp_id);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * @method deleteItem
	 * delete an existing record
	 * from table pl_orders_temp
	 */
	public function deleteItem()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_orders_temp WHERE orders_temp_id = ? ";

		$data = array($this->orders_temp_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Delete all cart entries older than one day
	 *
	 * @method cleanCart
	 * @package PiLus CMS
	 * @since version 1.4.0
	 */
	public function cleanCart()
	{
		$dbh = new Pldb;
	
		$yesterday = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 3, date('Y')));
	
		$order_lama = "SELECT orders_id, status, time_order, date_order, customer_id
				       FROM pl_orders WHERE status = 'baru' ORDER BY date_order";
		
		$sth = $dbh -> query($order_lama);
		$jml = $sth -> rowCount();
		
		if ( $jml > 0) 
		{
			while ($row = $sth -> fetchObject()) {
					
				if ( $row -> date_order == $yesterday)
				{
					for ($i = 0; $i < $jml; $i++)
					{
						$sth2 = $dbh -> query("DELETE FROM pl_orders WHERE date_orders < '$yesterday' AND status='baru'");
					}
				}
				else 
				{
					$sth3 = $dbh -> query("DELETE FROM pl_orders_temp WHERE date_orders_temp < '$yesterday'");
				}
			
			}
		}
		
	}
	
	/**
	 * @method getItems
	 * menampilkan items dan
	 * mengecek apakah ada
	 * sesi yang valid
	 * @param unknown $sesi
	 * @return number
	 */
	public static function checkItems($sesi)
	{
		$dbh = new Pldb;

		$sql = "SELECT * FROM pl_orders_temp, pl_product
		WHERE temp_session = '$sesi'
		AND pl_orders_temp.product_id = pl_product.ID";

		$sth = $dbh -> query($sql);

		return $sth -> rowCount();
	}

	/**
	 * @method getBasket
	 * @param string $sesi
	 * @param integer $limit
	 * @return multitype:multitype:ShoppingCart  number
	 */
	public static function getBasket($sesi)
	{
		$dbh = new Pldb;

		$sql = "SELECT b.orders_temp_id, b.product_id, b.temp_session,
				b.quantity, b.date_orders_temp,
				b.time_orders_temp, b.temp_stock,
				p.product_name, p.slug, p.description, p.price, p.stock,
				p.weight, p.bought, p.discount, p.image
				FROM pl_orders_temp AS b
				INNER  JOIN pl_product AS p
				WHERE b.temp_session = :sesi AND b.product_id=p.ID";

		$sth = $dbh -> prepare($sql);

		try {

			$sth -> execute(array(":sesi" => $sesi));
			$items = array();

			while ($result = $sth -> fetch()) {
					
				$items[] = new ShoppingCart($result);

			}

			$numbers = "SELECT orders_temp_id FROM pl_orders_temp";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;

			return (array("results" => $items, "totalRows" => $totalRows));

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * @method getIsiKeranjang
	 * untuk mendapatkan isi keranjang belanja
	 * @return multitype:ShoppingCart
	 */
	public static function getIsiKeranjang($sesi)
	{
		$dbh = new Pldb;

		$sql = "SELECT orders_temp_id,
		product_id, temp_session, quantity, date_orders_temp,
		time_orders_temp, temp_stock
		FROM pl_orders_temp WHERE temp_session = '$sesi' ";

		$sth = $dbh -> query($sql);

		$items = array();
		while ($result = $sth -> fetch()) {
				
			$items[] = $result;
		}

		return $items;
	}

}