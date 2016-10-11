<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas YMChat
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class YMChat
{
	/**
	 * ymchat_id
	 * @var integer
	 */
	protected $ymchat_id;

	/**
	 * operator's name
	 * @var name
	 */
	protected $name;

	/**
	 * open ID
	 * @var openID
	 */
	protected $openID;

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
	 * get YMChat Id
	 * @return number
	 */
	public function getYMChatId()
	{
		return $this->ymchat_id;
	}

	/**
	 * get Customer care
	 * @return name
	 */
	public function getCustomerCare()
	{
		return $this->name;
	}

	/**
	 * get OpenId
	 * @return openID
	 */
	public function getOpenId()
	{
		return $this->openID;
	}

	/**
	 * Method create YMChat
	 * to create a new record
	 */
	public function createYMChat()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_ymchat(name, openID)VALUES(?, ?)";

		$data = array($this->name, $this->openID);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * update an existing record
	 * from table pl_ymchat
	 * 
	 * @method updateYMChat
	 */
	public function updateYMChat()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_ymchat SET name = ?, openID = ?
				WHERE ymchat_id = ?";

		$data = array($this->name, $this->openID, $this->ymchat_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method deleteYmsupport
	 * to delete record
	 */
	public function deleteYMChat()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_ymchat WHERE ymchat_id = ?";

		$data = array($this->ymchat_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method getYMChats
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:YMChat  number
	 */
	public static function getYMChats($position, $limit)
	{

		$dbh = new pldb;

		$sql = "SELECT ymchat_id, name, openID
				FROM pl_ymchat ORDER BY name
				DESC LIMIT :position, :limit ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
				
			while ($result = $sth -> fetch()) {

				$ymchats = new YMChat($result);
				$list[] = $ymchats;
			}
				
			$numbers = "SELECT ymchat_id FROM pl_ymchat";
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
	 * @method getYMChatById
	 * @param integer $id
	 * @return YMChat
	 */
	public static function getYMChatById($id)
	{
		$dbh = new Pldb;

		$sql = "SELECT ymchat_id, name, openID
				FROM pl_ymchat WHERE ymchat_id = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new YMChat($row);
	}

	/**
	 * Method nameExists
	 * to check an existing name
	 * in the record
	 * @param string $name
	 * @return boolean
	 */
	public static function nameExists($name)
	{
		$dbh = new Pldb;

		$sql = "SELECT COUNT(`ymchat_id`) FROM `pl_ymchat` WHERE `name`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $name);

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
	 * Method openIdExists
	 * checking an existing openID
	 * @param string $openId
	 * @return boolean
	 */
	public static function openIdExists($openId)
	{
		$dbh  = new Pldb;

		$sql = "SELECT COUNT(`ymchat_id`) FROM `pl_ymchat` WHERE `openID`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $openId);

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
	 * @param integer $ymId
	 * @return mixed
	 */
	public static function findById($ymId)
	{
		$dbh = new Pldb;

		$sql = "SELECT ymchat_id, name, openID
				FROM pl_ymchat WHERE ymchat_id = ?";

		$data = array($ymId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}
}