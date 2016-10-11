<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Prodcat extends Pbase
 * Mapping table product category
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Prodcat extends Plbase 
{

	/**
	 * product's category
	 * @var string
	 */
	protected $product_cat;

	/**
	 * product category's description
	 * @var string
	 */
	protected $description;

	/**
	 * product's category status
	 * @var string
	 */
	protected $actived;

	/**
	 *
	 * @var string
	 */
	protected $cat_image;

	/**
	 * Product's category Slug
	 * for store url seo friendly
	 * @var string
	 */
	protected $slug;


	/**
	 * Inisialisasi object product category dengan
	 * data tabel product category dari database
	 * @param array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get product category's name
	 * @return string
	 */
	public function getProdcat_Name()
	{
		return $this->product_cat;
	}

	/**
	 * get Product category's
	 * description
	 * @return string
	 */
	public function getProdcat_Desc()
	{
		return $this->description;
	}

	/**
	 * get Product category's
	 * activation status
	 * @return string
	 */
	public function getProdcat_Status()
	{
		return $this->actived;
	}

	/**
	 * get product category's image
	 * @return string
	 */
	public function getProdcat_Image()
	{
		return $this->cat_image;
	}

	/**
	 * get Product category's
	 * @return string
	 */
	public function getProdcat_Slug()
	{
		return $this->slug;
	}

	/**
	 * Method createProdcat
	 * insert a new record
	 * into product category table
	 */
	public function createProdcat()
	{
		$dbh = parent::hook();

		if (!empty($this->cat_image))
		{

			$sql = "INSERT INTO pl_product_category(product_cat, description, actived, cat_image, slug)
					VALUES(?, ?, ?, ?, ?)";

			$data = array(

					$this->product_cat,
					$this->description, $this->actived, $this->cat_image,
					$this->slug);
		}
		else
		{
			$sql = "INSERT INTO pl_product_category
					(product_cat, description, actived,
					slug)
					VALUES(?, ?, ?, ?)";

			$data = array(
					$this->product_cat, 
                                        $this->description, $this->actived,
					$this->slug

			);
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method updateProdcat
	 * to update an existing record
	 * to product category table
	 */
	public function updateProdcat()
	{
		$dbh = parent::hook();

		if ($this -> getProdcat_Image())
		{
			$sql = "UPDATE pl_product_category SET product_cat = ?,
					description = ?, actived = ?, cat_image = ?,
					slug = ? WHERE ID = ?";

			$data = array($this->product_cat, $this->description, $this->actived, $this->cat_image,
					$this->slug, $this->ID);

		}
		else
		{
			$sql = "UPDATE pl_product_category SET product_cat = ?,
					description = ?, actived = ?, slug = ?
					WHERE ID =  ?";
			$data = array($this->product_cat, $this->description, $this->actived,
					$this->slug, $this->ID );
		}

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method deleteProdcat
	 * to delete an existing record
	 * from product category table
	 */
	public function deleteProdcat()
	{
		if (is_null( $this->ID)) trigger_error("Product Category::deleteProdcat(): Attempt to delete a Product Category object that does not have its ID property set.", E_USER_ERROR);

		$dbh  = parent::hook();

		$sql = "DELETE FROM pl_product_category WHERE ID = ?";

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getCategories
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function getProduct_Categories($position, $limit)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, product_cat, description, actived,
				cat_image, slug FROM pl_product_category
				ORDER BY product_cat LIMIT :position, :limit ";

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();

			while ($result = $sth -> fetch()) {

				$prodcats = new Prodcat($result);
				$list[] = $prodcats;

			}

			$numbers = "SELECT ID FROM pl_product_category";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;

			return (array("results" => $list, "totalRows" => $totalRows ));

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * Method getProduct_category
	 * retrieve a record based
	 * on their ID
	 * @param int $id
	 * @return Prodcat
	 */
	public static function getProduct_category($id)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, product_cat, description,
				actived, cat_image,
				slug FROM pl_product_category
				WHERE ID = ? ";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Prodcat($row);

	}

	/**
	 * Method getProduct_categories
	 * retrieve all records
	 * this method used by
	 * dropdown listbox method
	 * to fetch info from
	 * table product category
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function setProduct_Categories()
	{
		$dbh  = parent::hook();
	
		$sql = "SELECT ID, product_cat, description, actived, cat_image,
				slug FROM pl_product_category ORDER BY product_cat";
	
		$sth = $dbh -> query($sql);
	
		$list = array();
	
		try {
	
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {
	
				$categories = new Prodcat($result);
				$list[] = $categories;
			}
	
			return ($list);
	
		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}
	
	/**
	 * Method getProduct_Categories
	 * dropdown listbox
	 * @param integer $selected
	 * @return string
	 */
	public static function getProdcat_Dropdown($selected = '')
	{
		// set up first option for selection if none selected

		$option_selected = '';

		if (!$selected) {
			$option_selected = ' selected="selected"';
		}

		//get categories
		$categories = self::setProduct_Categories();

		$html  = array();

		$html[] = '<label>Pilih Kategori Produk</label>';
		$html[] = '<select class="form-control" name="cat_id">';

		foreach ($categories as $c => $category)
		{
			if ((int) $selected == (int) $category -> getId()) {

				$option_selected = ' selected="selected"';
			}

			$html[] =  '<option value="' . $category -> getId() . '"' . $option_selected . '>' . $category -> getProdcat_name() . '</option>';

			// clear out the selected option flag
			$option_selected = '';
		}

		if (empty($selected) OR (int)$category -> getId() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Kategori --<option>';
		}

		$html[] = '</select>';

		return implode("\n", $html);

	}

	/**
	 * Method prodcatExists
	 * to check an existing prodcat_name
	 * @param string $prodcat_name
	 * @return boolean
	 */
	public static function prodcatExists($prodcat_name)
	{
		$dbh  = new Pldb;

		$sql = "SELECT COUNT(`ID`) FROM `pl_product_category` WHERE `product_cat`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $prodcat_name);

		try {

			$sth -> execute();
			$rows = $sth -> fetchColumn();

			if ($rows == 1)
			{
				return true;
			}
			else {
				return false;
			}
		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 *
	 * @param integer $prodcat_Id
	 * @return mixed
	 */
	public static function findById($catId)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, product_cat,
				description, actived,
				cat_image, slug
				FROM pl_product_category WHERE ID = ?";

		$data = array($catId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}
}
