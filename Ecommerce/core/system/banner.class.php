<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Banner
 * Mapping table pl_banner
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Banner 
{
	/**
	 * banner_id
	 * @var integer
	 */
	protected $banner_id;

	/**
	 * banner's title
	 * @var string
	 */
	protected $title;

	/**
	 * banner's url
	 * @var string
	 */
	protected $url;

	/**
	 * banner's image
	 * @var string
	 */
	protected $image;

	/**
	 * date uploaded
	 * @var string
	 */
	protected $uploadedOn;


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
	 * get banner's id
	 * @return integer
	 */
	public function getBanner_Id()
	{
		return $this->banner_id;
	}

	/**
	 * get banner's title
	 * @return string
	 */
	public function getBanner_Label()
	{
		return $this->title;
	}

	/**
	 * get banner's url
	 * @return string
	 */
	public function getBanner_Url()
	{
		return $this->url;
	}

	/**
	 * get banner's image
	 * @return string
	 */
	public function getBanner_Image()
	{
		return $this->image;
	}

	/**
	 * get date created
	 * @return string
	 */
	public function getBanner_Dateposted()
	{
		return $this-> uploadedOn;
	}

	/**
	 * Insert new record on
	 * banner table
	 * 
	 * @method createBanner
	 */
	public function createBanner()
	{
		$dbh = new Pldb;

		if (!empty($this->image))
		{
			$sql = "INSERT INTO pl_banner(title, url, image, uploadedOn)
					VALUES(?, ?, ?, ?)";
				
			$data = array($this->title, $this->url, $this->image, $this->uploadedOn);
				
		}

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method to update banner
	 * to update record
	 */
	public function updateBanner()
	{
		$dbh = new Pldb;

		if ($this->getBanner_Image())
		{
			$sql = "UPDATE pl_banner SET title = ?, url = ?, image = ? WHERE banner_id = ?";
				
			$data = array($this->title, $this->url, $this->image, $this->banner_id );
		}
		else
		{
			$sql = "UPDATE pl_banner SET title = ?, url = ? WHERE banner_id = ?";

			$data = array($this->title, $this->url, $this->banner_id );
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method to delete banner
	 * delete record on
	 * banner table
	 */
	public function deleteBanner()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_banner WHERE banner_id = ?";

		$data = array($this->banner_id);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method to retrieve data
	 * retrieve all record from
	 * banner table
	 * @return multitype:Banner
	 */
	public static function getBanners($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT banner_id, title, url, image, uploadedOn
				FROM pl_banner ORDER BY title LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindParam(":position", $position, PDO::PARAM_INT);
		$sth -> bindParam(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
				
			while ($result = $sth -> fetch()) {

				$banners = new Banner($result);
				$list[] = $banners;
			}
				
			$numbers = "SELECT banner_id FROM pl_banner";
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
	 * Method to retrieve
	 * spesific data selected
	 * based on their Id
	 * @param integer $id
	 * @return Banner
	 */
	public static function getBanner($id)
	{

		$dbh = new Pldb;

		$sql = "SELECT banner_id, title, url, image, uploadedOn
				FROM pl_banner WHERE banner_id = :banner_id";

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(":banner_id", $id, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$row = $sth -> fetch();
				
			if ($row) return new Banner($row);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * Method bannerExists
	 * checking an existing banner
	 * @param string $banner_label
	 * @return boolean
	 */
	public static function bannerExists($banner_label)
	{
		$dbh  = new Pldb;

		$sql = "SELECT COUNT(`banner_id`) FROM `pl_banner` WHERE `title`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $banner_label);

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
	 * Method findById
	 * @param integer $bannerId
	 * @return mixed
	 */
	public static function findById($bannerId)
	{
		$dbh = new Pldb;

		$sql = "SELECT banner_id, title, url, image, uploadedOn
				FROM pl_banner WHERE banner_id = ?";

		$data = array($bannerId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}

}