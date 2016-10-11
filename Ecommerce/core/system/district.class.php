<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas District
 * Mapping table pl_district - kabupaten
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class District
{
	/**
	 * district ID
	 * @var integer
	 */
	protected $district_id;

	/**
	 * province's ID
	 * @var integer
	 */
	protected $province_id;
	
	/**
	 * 
	 * @var string
	 */
	protected $province_name;
	
	/**
	 * shipping ID
	 * @var integer
	 */
	protected $shipping_id;

	/**
	 * shipping_name
	 * @var string
	 */
	protected $shipping_name;

	/**
	 * district name
	 * @var string
	 */
	protected $district_name;

	/**
	 * shipping cost
	 * @var integer
	 */
	protected $shipping_cost;

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
	 * get district's Id
	 * 
	 * @method getDistrict_Id
	 * @return integer
	 */
	public function getDistrict_Id()
	{
		return $this->district_id;
	}
	
	/**
	 * get province's Id
	 * 
	 * @method getProvince_Id
	 * @return number
	 */
	public function getProvince_Id()
	{
		return $this->province_id;
	}
	
	/**
	 * get Province's name
	 * 
	 * @method getProvince_Name
	 * @return string
	 */
	public function getProvince_Name()
	{
		
		
		
		return $this->province_name;
	}
	
	
	/**
	 * get shipping's ID
	 * @return number
	 */
	public function getShipping_Id()
	{
		return $this->shipping_id;
	}

	/**
	 * get shipping's name
	 * @return string
	 */
	public function getShipping_Name()
	{
		return $this->shipping_name;
	}

	/**
	 * get district's name
	 * @return string
	 */
	public function getDistrict_Name()
	{
		return $this->district_name;
	}

	/**
	 * get shipping's cost
	 * @return number
	 */
	public function getShipping_Cost()
	{
		return $this->shipping_cost;
	}

	/**
	 * @method create district
	 * to create new record
	 */
	public function createdistrict()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_district( province_id, shipping_id, district_name, shipping_cost)
				VALUES(?, ?, ?, ?)";

		$data = array($this->province_id, $this->shipping_id, $this->district_name, $this->shipping_cost);


		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method update district
	 * to update record
	 */
	public function updatedistrict()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_district SET province_id = ?, shipping_id = ?,
				district_name = ?, shipping_cost = ?
				WHERE district_id = ?";

		$data = array( $this->province_id, $this->shipping_id, $this->district_name, $this->shipping_cost, $this->district_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method delete district
	 * to delete record
	 */
	public function deletedistrict()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_district WHERE district_id = ?";

		$data = array($this->district_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * retrieve all record
	 * from table pl_district
	 * 
	 * @method getDistricts
	 * @return multitype:district
	 */
	public static function getDistricts($position, $limit)
	{
		$dbh = new Pldb;

		$cities = array();

		$sql = "SELECT d.district_id, d.province_id, 
				d.shipping_id, d.district_name, d.shipping_cost,
				p.province_id,  p.province_name
				FROM pl_district AS d
				INNER JOIN pl_province AS p ON d.province_id = p.province_id
				ORDER BY d.province_id LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			
			$sth -> execute();
			
			foreach ($sth -> fetchAll() as $row)
			{
				$districts = new District($row);
				$cities[] = $districts;
			}
			
			
			$numbers = "SELECT district_id FROM pl_district";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			
			$dbh = null;
			
			return (array("results" => $cities, "totalRows" => $totalRows));
				
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		
		}

	}

	/**
	 * retrieve specific record
	 * based on their id
	 * 
	 * @method getDistrictById
	 * @param int $id
	 * @return district
	 */
	public static function getDistrictById($id)
	{
		$dbh = new Pldb;

		$sql = "SELECT district_id, province_id, 
				shipping_id, district_name, shipping_cost 
				FROM pl_district WHERE district_id = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();

		if ($row) return new District($row);
	}
	
	/**
	 * this method will be used
	 * in method setDistrict_Dropdown
	 * 
	 * @method setDistricts
	 * @return District[]
	 */
	public static function setDistricts()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT district_id, province_id, shipping_id, 
				district_name, shipping_cost
				FROM pl_district ORDER BY district_id";
		
		$districts = array();
		
		try {
			
			$sth = $dbh -> query($sql);
			
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {
			
				$kabupaten = new District($result);
				$districts[] = $kabupaten;
					
			}
			
			return ($districts);
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
		
	}
	
	/**
	 * creating combobox for district
	 * with retrieve data from district table
	 *  
	 * @method setDistrict_Dropdown
	 * @param string $selected
	 */
	public static function setDistrict_Dropdown($selected = '')
	{
		$option_selected = '';
		
		if ($selected) {
		
			$option_selected = 'selected="selected"';
		}
		
		//get post category
		$districts = self::setDistricts();
		
		$html  = array();
		
		$html[] = '<label>Pilih Kabupaten/Kota</label>';
		$html[] = '<select class="form-control" name="kab_id">';
		
		foreach ( $districts as $d => $district)
		{
			if ((int) $selected  == (int) $district-> getDistrict_Id())
			{
				$option_selected='selected="selected"';
			}
		
			$html[] =  '<option value="' . $district -> getDistrict_Id() . '"' . $option_selected . '>' . $district -> getDistrict_Name() . '</option>';
		
			// clear out the selected option flag
			$option_selected = '';
		}
		
		if ( empty($selected) OR (int)$district -> getDistrict_Id() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Kabupaten/Kota --<option>';
		}
		
		$html[] = '</select>';
		
		return implode("\n", $html);
	}
	
	/**
	 * @method fetchById
	 * @param integer $id
	 */
	public static function findById($districtId)
	{
		$dbh = new Pldb;

		$sql = "SELECT district_id, district_name, shipping_cost
				FROM pl_district WHERE district_id = ?";

		$data = array($districtId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}



	/**
	 * Method districtExists
	 * checking an existing district name
	 * @param string $district_name
	 * @return boolean
	 */
	public static function districtExists($district_name)
	{
		$dbh  = new Pldb;

		$sql = "SELECT COUNT(`district_id`) FROM `pl_district` WHERE `district_name` = ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $district_name);

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

}