<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Product extends Pbase
 * Mapping table product
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Product extends Plbase 
{

	/**
	 * Product category's ID
	 * @var integer
	 */
	protected $product_catId;

	/**
	 * Product category's name
	 * @var string
	 */
	protected $product_cat;

	/**
	 * product's name
	 * @var string
	 */
	protected $product_name;
	/**
	 * product's slug
	 * @var string
	 */
	protected $slug;

	/**
	 * product's description
	 * @var string
	 */
	protected $description;

	/**
	 * product's price
	 * @var int
	 */
	protected $price;

	/**
	 * product's stock
	 * @var sring
	 */
	protected $stock;

	/**
	 * product's weight
	 * @var integer
	 */
	protected $weight;

	/**
	 * product's submited
	 * @var string
	 */
	protected $date_submited;

	/**
	 * How many product was bought?
	 * @var int
	 */
	protected $bought;

	/**
	 * Product's discount
	 * @var integer
	 */
	protected $discount;

	/**
	 * Product's Image
	 * @var string
	 */
	protected $image;

	/**
	 * Inisialisasi product dengan
	 * data dari database
	 * @param array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get Product Category's ID
	 * @return int
	 */
	public function getProduct_CatId()
	{
		return $this->product_catId;
	}

	/**
	 * get Product Category's name
	 * @return string
	 */
	public function getProductCatName()
	{
		return $this->product_cat;
	}

	/**
	 * get Product's name
	 * @return string
	 */
	public function getProduct_Name()
	{
		return $this->product_name;
	}

	/**
	 * get Product's Slug
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
	 * get Product's price
	 * @return int
	 */
	public function getProduct_Price()
	{
		return $this->price;
	}

	/**
	 * get Product's stock
	 * @return sring
	 */
	public function getProduct_Stock()
	{
		return $this->stock;
	}

	/**
	 * get Product's weight
	 * @return int
	 */
	public function getProduct_Weight()
	{
		return $this->weight;
	}

	/**
	 * get Product's submited
	 * @return int
	 */
	public function getProduct_Submited()
	{
		return $this->date_submited;
	}

	/**
	 * get How many product was bought
	 * @return int
	 */
	public function getProduct_Bought()
	{
		return $this->bought;
	}

	/**
	 * get Product's discount
	 * @return
	 */
	public function getProduct_Discount()
	{
		return $this->discount;
	}

	/**
	 * get Product's Image
	 * @return string
	 */
	public function getProduct_Image()
	{
		return $this->image;
	}

	/**
	 * Method createProduct
	 * to insert a new record
	 */
	public function createProduct()
	{

		$dbh  = parent::hook();

		$sql = "INSERT INTO pl_product(product_catId, product_name, slug,
				description, price, stock, weight, date_submited,
				discount, image)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$data = array($this->product_catId, $this->product_name, $this->slug,
				$this->description, $this->price, $this->stock, $this->weight,
				$this->date_submited, $this->discount, $this->image
		);

		$sth = $dbh -> pstate($sql, $data);
		$this->ID = $dbh -> lastId();
	}

	/**
	 * Method updateProduct
	 * to update an existing record
	 * from table pl_product
	 */
	public function updateProduct()
	{

		$dbh = parent::hook();

		if ($this -> getProduct_Image()) {
				
			$sql = "UPDATE `pl_product`
					SET product_catId = ?, product_name = ?,
					slug = ?, description = ?,
					price = ?, stock = ?, weight = ?,
					discount = ?, image = ?
					WHERE ID = ?";
				
			$data = array(
					$this->product_catId, $this->product_name,  $this->slug,
					$this->description, $this->price, $this->stock, $this->weight,
					$this->discount, $this->image,
					$this->ID
			);

				
		} else {
			
			$sql = 'UPDATE `pl_product` SET product_catId = ?, product_name = ?,
					slug = ?, description = ?, price = ?, stock = ?, weight = ?,
					discount = ?
					WHERE ID = ?';

			$data = array(
					
					$this->product_catId, $this->product_name,  $this->slug,
					$this->description, $this->price, $this->stock, $this->weight,
					$this->discount,
					$this->ID
			);
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method deleteProduct
	 * to delete an existing record
	 */
	public function deleteProduct()
	{

		$dbh = parent::hook();

		$sql = "DELETE FROM pl_product WHERE ID = ?";

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getProducts
	 * retrieve all record
	 * from table product
	 * @param int $position
	 * @param int $limit
	 */
	public static function getProducts ($cat_id, $position, $limit)
	{
		$dbh = parent::hook();

		$sql = 'SELECT ID, product_catId, product_name,
				slug, price, stock,
				weight, date_submited,
				discount, image 
				FROM pl_product
				WHERE product_catId = "'.$cat_id.'"
				ORDER BY ID DESC LIMIT :position, :limit ';

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$products =  array();

			foreach ( $sth -> fetchAll() as $row) {
				
				$products[] = new Product($row);
			}

			$numbers = "SELECT ID FROM pl_product WHERE product_catId = '$cat_id'";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
				
			return (array("results"=>$products, "totalRows"=>$totalRows));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
	}

	/**
	 * @Method getProduct
	 * retrieve product's record by ID
	 * @param int $id
	 * @return multitype:Product
	 */
	public static function getProduct($productId)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, product_catId, product_name, slug,
				description, price, stock,
				weight, date_submited, bought,
				discount, image
				FROM pl_product
				WHERE ID = ?";

		$data = array($productId);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Product($row);
	}

	/**
	 * Method getProductRandom
	 * retrieving product record
	 * randomly
	 * @return multitype:Product
	 */
	public static function getProductRandom()
	{
		$dbh = new Pldb;

		$random = array();

		$sql = 'SELECT ID, product_catId, product_name, slug, description, price, stock, weight, date_submited,
				bought, discount, image FROM pl_product ORDER BY rand() LIMIT 3 ';

		try {
			$sth = $dbh -> query($sql);
				
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$random[] = new Product($result);
			}
				
			$dbh = null;
			return ($random);
				
		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * @method countProducts
	 * hitung jumlah produk
	 * @return number
	 */
	public static function countProducts()
	{
		$dbh = parent::hook();

		$sql = "SELECT ID FROM pl_product";

		$sth = $dbh -> query($sql);

		return $sth -> rowCount();
	}

	/**
	 * Method findById
	 * @param integer $product_id
	 * @return mixed
	 */
	public static function findById($product_id)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, product_catId, product_name, slug,
				description, price, stock, weight, date_submited,
				bought, discount, image
				FROM pl_product WHERE ID = ?";

		$data = array($product_id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}
}