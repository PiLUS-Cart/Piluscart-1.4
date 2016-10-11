<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Option
 * Mapping option table
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Option 
{

	/**
	 * option's Id
	 * @var integer
	 */
	protected $option_id;

	/**
	 * sitename
	 * @var string
	 */
	protected $site_name;

	/**
	 * meta description
	 * @var string
	 */
	protected $meta_description;

	/**
	 * meta keywords
	 * @var string
	 */
	protected $meta_keywords;

	/**
	 * shop name
	 * @var string
	 */
	protected $tagline;

	/**
	 * shop address
	 * @var string
	 */
	protected $shop_address;

	/**
	 * owner's email
	 * @var string
	 */
	protected $owner_email;

	/**
	 * nomor rekening
	 * @var string
	 */
	protected $nomor_rekening;

	/**
	 * nomor telpon
	 * @var string
	 */
	protected $nomor_telpon;

	/**
	 * nomor faksimile
	 * @var string
	 */
	protected $nomor_fax;
	
	/**
	 * akun instagram
	 * @var string
	 */
	protected $instagram;
	
	/**
	 * akun twitter
	 * @var string
	 */
	protected $twitter;
	
	/**
	 * akun facebook
	 * @var string
	 */
	protected $facebook;
	
	/**
	 * pin Blackberry Messenger
	 * @var string
	 */
	protected $pin_bb;

	/**
	 * favicon
	 * @var favicon
	 */
	protected $favicon;

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
	 * get option Id
	 * @return integer
	 */
	public function getOption_Id()
	{
		return $this->option_id;
	}

	/**
	 * get Site_name
	 * @return string
	 */
	public function getSite_Name()
	{
		return $this->site_name;
	}

	/**
	 * get meta description
	 * @return string
	 */
	public function getMeta_Description()
	{
		return $this->meta_description;
	}

	/**
	 * get meta keywords
	 * @return string
	 */
	public function getMeta_Keywords()
	{
		return $this->meta_keywords;
	}

	/**
	 * get shop name
	 * @return string
	 */
	public function getTagline()
	{
		return $this -> tagline;
	}

	/**
	 * get shop address
	 * @return string
	 */
	public function getShopAddress()
	{
		return $this -> shop_address;
	}

	/**
	 * get Owner Email
	 * @return string
	 */
	public function getOwnerEmail()
	{
		return $this->owner_email;
	}

	/**
	 * get nomor rekening
	 * @return string
	 */
	public function getNoRekening()
	{
		return $this->nomor_rekening;
	}

	/**
	 * get nomor telpon
	 * @return string
	 */
	public function getNoTelpon()
	{
		return $this->nomor_telpon;
	}
	
	/**
	 * get nomor faksimile
	 * @return string
	 */
	public function getNoFaximile()
	{
		return $this->nomor_fax;
	}

	/**
	 * get instagram account
	 * @return string
	 */
	public function getInstagramAccount()
	{
		return $this->instagram;
	}
	
	/**
	 * get twitter account
	 * @return string
	 */
	public function getTwitterAccount()
	{
		return $this->twitter;
	}
	
	/**
	 * get facebook account
	 * @return string
	 */
	public function getFacebookAccount()
	{
		return $this->facebook;
	}
	
	/**
	 * get Pin Blackberry Messenger
	 * @return string
	 */
	public function getPinBB()
	{
		return $this->pin_bb;
	}

	/**
	 * get favicon
	 * @return favicon
	 */
	public function getFavicon()
	{
		return $this->favicon;
	}

	/**
	 * Method create option
	 * to create a new record
	 */
	public function createOption()
	{
		$dbh = new Pldb;

		if ($this -> getFavicon()) {
			$sql = "INSERT INTO pl_option(site_name, meta_description, meta_keywords,
					tagline, shop_address,
					owner_email, nomor_rekening, nomor_telpon, 
					nomor_fax, instagram, twitter, facebook,  pin_bb, favicon)
					VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				
			$data = array(
					$this->site_name, $this->meta_description, $this->meta_keywords,
					$this->tagline, $this->shop_address, $this->owner_email,
					$this->nomor_rekening, $this->nomor_telpon, $this->nomor_fax, 
					$this->instagram, $this->twitter, $this->facebook,
					$this->pin_bb, $this->favicon);
				
		} else {
			
			$sql = "INSERT INTO pl_option(site_name, meta_description, meta_keywords,
					tagline, shop_address,
					owner_email, nomor_rekening, nomor_telpon, nomor_fax, instagram, 
					twitter, facebook, pin_bb)
					VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$data = array(
					$this->site_name, $this->meta_description, $this->meta_keywords,
					$this->tagline, $this->shop_address, $this->owner_email,
					$this->nomor_rekening, $this->nomor_telpon, 
					$this->nomor_fax, $this->instagram, $this->twitter, $this->facebook,
					$this->pin_bb);

		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method update option
	 * to update an existing record
	 */
	public function updateOption()
	{
		$dbh = new Pldb;

		if ($this->getFavicon()) {

			$sql = "UPDATE pl_option SET site_name = ?,
					meta_description = ?, meta_keywords = ?,
					tagline = ?, shop_address = ?,
					owner_email = ?, nomor_rekening = ?,
					nomor_telpon = ?, nomor_fax = ?, 
					instagram = ?, twitter = ?, facebook = ?, pin_bb = ?,
					favicon = ? WHERE option_id = ?";
				
			$data = array(
					$this->site_name, $this->meta_description, $this->meta_keywords,
					$this->tagline, $this->shop_address, $this->owner_email,
					$this->nomor_rekening, $this->nomor_telpon, $this->nomor_fax, 
					$this->instagram, $this->twitter, $this->facebook,
					$this->pin_bb, $this->favicon, $this->option_id);
				
		} else {
			
			$sql = "UPDATE pl_option SET site_name = ?,
					meta_description = ?, meta_keywords = ?,
					tagline = ?, shop_address = ?,
					owner_email = ?, nomor_rekening = ?,
					nomor_telpon = ?, pin_bb = ?
					WHERE option_id = ?";
				
			$data = array(
					$this->site_name, $this->meta_description, $this->meta_keywords,
					$this->tagline, $this->shop_address, $this->owner_email,
					$this->nomor_rekening, $this->nomor_telpon,
					$this->pin_bb, $this->option_id);
				
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method delete option
	 * to delete record
	 */
	public function deleteOption()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_option WHERE option_id = ? ";

		$data = array($this->option_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method get option
	 * to retrieve record
	 * based on their Id
	 * @return Option
	 */
	public static function getOption($id)
	{
		$dbh = new Pldb;

		$sql =  "SELECT option_id, site_name,
				meta_description, meta_keywords,
				tagline, shop_address, owner_email,
				nomor_rekening, nomor_telpon, nomor_fax, instagram,  
				twitter, facebook, pin_bb, favicon
				FROM pl_option WHERE option_id = :option_id ";

		$sth = $dbh  -> prepare( $sql );
		$sth -> bindValue( ":option_id", $id);

		try {
				
			$sth -> execute();
			$row = $sth -> fetch();
				
			if ($row) return new Option($row);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * Method getOptions
	 * retrieve all record
	 * from table pl_option
	 * @return multitype:multitype:Option
	 */
	public static function getOptions()
	{
		$dbh  = new Pldb;

		$sql = "SELECT option_id, site_name, meta_description, meta_keywords,
				tagline, shop_address, owner_email, nomor_rekening,
				nomor_telpon, nomor_fax, instagram, twitter, facebook, pin_bb,
				favicon FROM pl_option LIMIT 1";

		try {
			$sth = $dbh -> query($sql);
			$list = array();
			while ($row = $sth -> fetch()) {

				$options = new Option($row);
				$list[] = $options;
			}
			$dbh  = null;
			return (array("results" => $list));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method findById
	 * @param integer $option_id
	 * @return mixed
	 */
	public static function findById( $option_id )
	{
		$dbh = new Pldb;

		$sql = "SELECT  option_id,
				site_name, meta_description, meta_keywords,
				tagline, shop_address, owner_email, nomor_rekening,
				nomor_telpon, nomor_fax, instagram, twitter, 
				facebook, pin_bb, favicon
				FROM pl_option WHERE option_id = ? ORDER BY option_id LIMIT 1";

		$data = array($option_id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}
}