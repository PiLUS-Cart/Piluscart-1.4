<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Download
 * Mapping table pl_download
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Download 
{

	/**
	 * Download's Id
	 * @var integer
	 */
	protected $download_id;

	/**
	 * Download's title
	 * @var string
	 */
	protected $title;

	/**
	 * Download's filename
	 * @var string
	 */
	protected $filename;

	/**
	 * date uploaded
	 * @var integer
	 */
	protected $date_uploaded;

	/**
	 * hits
	 * @var integer
	 */
	protected $hits;

	/**
	 *
	 * @var string
	 */
	protected $slug;

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
	 * get download's Id
	 * @return integer
	 */
	public function getDownload_Id()
	{
		return $this->download_id;
	}

	/**
	 * get download's title
	 * @return string
	 */
	public function getDownload_Title()
	{
		return $this->title;
	}

	/**
	 * get download's filename
	 * @return string
	 */
	public function getDownload_Filename()
	{
		return $this->filename;
	}

	/**
	 * get date_uploaded
	 * @return number
	 */
	public function getDate_uploaded()
	{
		return $this->date_uploaded;
	}

	/**
	 * get hits
	 * @return number
	 */
	public function getHits()
	{
		return $this->hits;
	}

	/**
	 * get slugs
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Method create download
	 * to create a new record
	 */
	public function createDownload()
	{

		$dbh = new Pldb;

		$sql = "INSERT INTO pl_download(
				title, filename, date_uploaded, slug)
				VALUES(?, ?, ?, ?)";

		$data = array($this->title, $this->filename, $this->date_uploaded, $this->slug);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method update download
	 * to update an existing record
	 */
	public function updateDownload()
	{
		$dbh = new Pldb;

		if ($this -> getDownload_Filename())
		{

			$sql = "UPDATE pl_download SET title = ?, filename = ?, slug = ?
					WHERE download_id = ?";
				
			$data = array($this->title, $this->filename, $this->slug, $this->download_id);
				
		}
		else
		{
			$sql = "UPDATE pl_download SET title = ?, slug = ?
					WHERE download_id = ?";
				
			$data = array($this->title, $this->slug, $this->download_id);
				
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method delete download
	 * to delete an existing record
	 */
	public function deleteDownload()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_download WHERE download_id = ? AND filename = ?";

		$data = array($this->download_id, $this->filename);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method updateHits
	 * @param string $filename
	 */
	public function updateHits($filename)
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_download SET hits=hits+1 WHERE filename = ?";

		$data = array($filename);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 *
	 * Method getDownloads
	 * to retrieve all record
	 * from download table
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:Download
	 */
	public static function getDownloads($position, $limit)
	{
		$list_download = array();

		$dbh = new Pldb;

		$sql = "SELECT download_id, title, filename, date_uploaded, hits, slug
				FROM pl_download ORDER BY title DESC LIMIT :position, :limit ";
		$sth = $dbh -> prepare( $sql );
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list_download = array();
			foreach ($sth -> fetchAll() as $row)
			{
				$download = new Download($row);
				$list_download[] = $download;
			}
			$numbers = "SELECT download_id FROM pl_download";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
			return (array('results' => $list_download, 'totalRows' => $totalRows ));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
	}

	/**
	 * Method get download
	 * to retrieve spesific record
	 * based on their Id
	 * @param integer $id
	 * @return Download
	 */
	public static function getDownload($id)
	{
		$dbh = new Pldb;

		$sql = "SELECT download_id, title, filename, date_uploaded, hits, slug
				FROM pl_download WHERE download_id = :id";
		$clean_id = abs((int)$id);

		$sth = $dbh -> prepare( $sql );
		$sth -> bindValue(":id", $clean_id, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$row = $sth -> fetch();
			if ($row) return new Download($row);
		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}



	/**
	 * Method findById
	 * @param integer $fileId
	 * @return mixed
	 */
	public static function findById($fileId)
	{
		$dbh = new Pldb;

		$sql = "SELECT download_id, title, filename, date_uploaded
				FROM pl_download WHERE download_id = ?";

		$data = array($fileId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}
}