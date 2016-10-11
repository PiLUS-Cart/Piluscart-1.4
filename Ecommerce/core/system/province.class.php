<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Province
 * Mapping table pl_province
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Province
{
	/**
	 * province's Id
	 * @var integer
	 */
	protected $province_id;
	
	/**
	 * province's name
	 * @var string
	 */
	protected $province_name;
	
	/**
	 * Initialize object properties
	 * @method __contruct
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
	 * @method getProvinceId
	 * @return number
	 */
	public function getProvinceId()
	{
		return $this->province_id;
	}
	
	/**
	 * @method getProvinceName
	 * @return number
	 */
	public function getProvinceName() 
	{
		return $this->province_name;
		
	}
	
	/**
	 * @method createProvince
	 */
	public function createProvince() 
	{
		$dbh = new Pldb;
		
		$sql = "INSERT INTO pl_province( province_name )VALUES(?)";
		
		$data = array($this->province_name);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $dbh -> lastId();
	}
	
	/**
	 * @method updateProvince
	 */
	public function updateProvince() 
	{
		$dbh = new Pldb;
		
		$sql = "UPDATE pl_province SET province_name = ? WHERE province_id = ?";
		
		$data = array( $this->province_name, $this->province_id);
		
		$sth = $dbh -> pstate($sql, $data);
		
	}
	
	/**
	 * @method deleteProvince
	 */
	public function deleteProvince()
	{
		$dbh = new Pldb;
		
		$sql = "DELETE FROM pl_province WHERE province_id = ?";
		
		$data = array( $this -> province_id);
		
		$sth = $dbh -> pstate($sql, $data);
	}
	
	/**
	 * @method getListProvinces
	 * @param integer $position
	 * @param integer $limit
	 * @return Province[][]|number[]
	 */
	public static function getListProvinces($position, $limit) 
	{
		$dbh = new Pldb;
		
		$sql = "SELECT province_id, province_name 
				FROM pl_province ORDER BY province_id 
				LIMIT :position, :limit";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);
		
		try {
			$sth -> execute();
			$list = array();
		
			foreach ($sth -> fetchAll() as $row)
			{
				$provinces = new Province($row);
				$list[] = $provinces;
			}
		
			$numbers = "SELECT province_id FROM pl_province";
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
	 * @method getProvinceById
	 * @param integer $id
	 */
	public static function getProvinceById($id)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT province_id, province_name
				FROM pl_province
				WHERE province_id = ?";
		
		$cleanId = abs((int)$id);
		
		$data = array($cleanId);
		
		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();
		
		if ($row) return new Province($row);
	}
	
	/**
	 * retrieve all records
	 * from pl_province then 
	 * used by setProvince_Dropdown
	 * 
	 * @method setProvinces
	 * @return Province[]
	 */
	public static function setProvinces()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT province_id, province_name FROM pl_province 
				ORDER BY province_id";
		
		$list = array();
		
		try {
		
			$sth = $dbh -> query($sql);
				
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {
		
				$provinces = new Province($result);
				$list[] = $provinces;
					
			}
		
			return ($list);
		
		} catch (PDOException $e) {
		
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		
		}
		
	}
	
	/**
	 * @method setProvince_Dropdown
	 * @param string $selected
	 */
	public static function setProvince_Dropdown($selected = '')
	{
		$option_selected = '';
		
		if ($selected) {
		
			$option_selected = 'selected="selected"';
		}
		
		//get post category
		$provinces = self::setProvinces();
		
		$html  = array();
		
		$html[] = '<label>Pilih Provinsi</label>';
		$html[] = '<select class="form-control" name="prov_id"  >';
		
		foreach ( $provinces as $p => $province)
		{
			if ((int) $selected  == (int) $province-> getProvinceId())
			{
				$option_selected='selected="selected"';
			}
		
			$html[] =  '<option value="' . $province -> getProvinceId() . '"' . $option_selected . '>' . $province -> getProvinceName() . '</option>';
		
			// clear out the selected option flag
			$option_selected = '';
		}
		
		if ( empty($selected) OR (int)$province -> getProvinceId() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Provinsi --<option>';
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
	
		$sql = "SELECT province_id, province_name
				FROM pl_province
				WHERE province_id = ?";
	
		$data = array($districtId);
	
		$sth = $dbh -> pstate($sql, $data);
	
		return $sth -> fetch();
	
	}
	
}