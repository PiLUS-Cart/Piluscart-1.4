<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Shipping - jasa pengiriman
 * Mapping table pl_shipping
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Shipping 
{

	/**
	 * shipping's ID
	 * @var integer
	 */
	protected $shipping_id;

	/**
	 * shipping's name
	 * @var string
	 */
	protected $shipping_name;

	/**
	 * shipping's logo
	 * @var string
	 */
	protected $shipping_logo;

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
	 * get shipping's ID
	 * @return number
	 */
	public function getShippingId()
	{
		return $this->shipping_id;
	}

	/**
	 * get shipping's name
	 * @return string
	 */
	public function getShippingName()
	{
		return $this->shipping_name;
	}

	/**
	 * get shipping Logo
	 * @return string
	 */
	public function getShippingLogo()
	{
		return $this->shipping_logo;
	}

	/**
	 * insert a new record
	 * to pl_shipping table
	 * 
	 * @method createShipping
	 */
	public function createShipping()
	{
		$dbh = new Pldb;

		if (!empty($this->shipping_logo))
		{
			$sql = "INSERT INTO pl_shipping(shipping_name, shipping_logo)
					VALUES(?, ?)";
				
			$data = array($this->shipping_name, $this->shipping_logo);
		}
		else
		{
			$sql = "INSERT INTO pl_shipping(shipping_name)VALUES(?)";
				
			$data = array($this->shipping_name);
		}

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * update an existing record
	 * based on shipping's ID
	 * 
	 * @method updateShipping
	 */
	public function updateShipping()
	{
		$dbh = new Pldb;

		if ($this->getShippingLogo())
		{
			$sql = 'UPDATE pl_shipping SET shipping_name = ?,
					shipping_logo = ?
					WHERE shipping_id = ?';
				
			$data = array(
					$this->shipping_name,
					$this->shipping_logo,
					$this->shipping_id);
		}
		else
		{
			$sql = 'UPDATE pl_shipping SET shipping_name = ?
					WHERE shipping_id = ?';

			$data = array($this->shipping_name, $this->shipping_id);
				
		}

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * delete an existing record
	 * from table pl_shipping
	 * 
	 * @method deleteShipping
	 */
	public function deleteShipping()
	{
		
		$dbh = new Pldb;

		$sql = 'DELETE FROM pl_shipping WHERE shipping_id = ?';

		$sth = $dbh -> pstate($sql, $data);
		
	}

	/**
	 * @method setShipping
	 * @return multitype:Shipping
	 */
	public static function setShipping()
	{
		$dbh = new Pldb;

		$sql = "SELECT shipping_id, shipping_name FROM pl_shipping 
				ORDER BY shipping_id";
		
		$list = array();

		try {
			
			$sth = $dbh -> query($sql);
			
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$shippingNames = new Shipping($result);
				$list[] = $shippingNames;
			}
				
			return ($list);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');

		}

	}

	/**
	 * @method setShippingDropDown
	 * @param string $selected
	 * @return string
	 */
	public static function setShippingDropDown($selected = '')
	{
		$option_selected = '';
		
		if ($selected) {
		
			$option_selected = 'selected="selected"';
		}
		
		//get shipping
		$shipments = self::setShipping();

		$html = array();

		$html[] = '<label for="image_id">Jasa Pengiriman</label>';
		$html[] = '<select class="form-control" name="shipping">';

		foreach ( $shipments as $s => $shipment )
		{
			if ((int)$selected == (int)$shipment -> getShippingId())
			{
				$option_selected=' selected="selected"';
			}

			$html[] = '<option value="'.$shipment -> getShippingId().'"' . $option_selected . '>'.$shipment -> getShippingName().'</option>';
				
			// clear out the selected option flag
			$option_selected = '';
		}

		if (empty($selected) OR (int)$shipment -> getShippingId() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Jasa Pengiriman --<option>';
		}

		$html[] = '</select>';

		return implode("\n", $html);

	}

	/**
	 * @method getListShipping
	 * retrieve all records from
	 * table pl_shipping
	 * @param unknown $position
	 * @param unknown $limit
	 * @return multitype:multitype:Shipping  number
	 */
	public static function getListShipping($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT shipping_id, shipping_name, shipping_logo
				FROM pl_shipping ORDER BY shipping_name
				LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
			$list = array();
				
			while ($results = $sth -> fetch()) {

				$shippingNames = new Shipping($results);
				$list[] = $shippingNames;
			}
				
			$numbers = "SELECT shipping_id FROM pl_shipping";
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
	 * @method getShippingById
	 * retrieve detail records
	 * based on their ID
	 * @param unknown $id
	 */
	public static function getShippingById($id)
	{
		$dbh = new Pldb;

		$sql = "SELECT shipping_id, shipping_name, shipping_logo
				FROM pl_shipping WHERE shipping_id = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Shipping($row);

	}

	/**
	 * @method findById
	 * @param integer $bannerId
	 * @return mixed
	 */
	public static function findById($courierId)
	{
		$dbh = new Pldb;

		$sql = "SELECT shipping_id, shipping_name, shipping_logo
				FROM pl_shipping WHERE shipping_id = ?";

		$data = array($courierId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}

}