<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Statistics
 * Mapping table pl_statistics
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Statistics 
{

	/**
	 * hits's Id
	 * @var integer
	 */
	protected $statistic_id;

	/**
	 * ip's visitor
	 * @var string
	 */
	protected $ip;

	/**
	 * browser's  visitor
	 * @var string
	 */
	protected $browser;

	/**
	 * date visit
	 * @var integer
	 */
	protected $date_visit;

	/**
	 * hits counter
	 * @var integer
	 */
	protected $hits;

	/**
	 * who is online
	 * @var integer
	 */
	protected $online;

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
	 * get hit's Id
	 * @return number
	 */
	public function getHitsId()
	{
		return $this->statistic_id;
	}

	/**
	 * get IP
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * get browser
	 * @return string
	 */
	public function getBrowser()
	{
		return $this->browser;
	}

	/**
	 * get When user's visit
	 * @return number
	 */
	public function getDate_Visit()
	{
		return $this->date_visit;
	}

	/**
	 * get Hits
	 * @return number
	 */
	public function getHits()
	{
		return $this->hits;
	}

	/**
	 * get Who is online
	 * @return number
	 */
	public function getWhoIsOnline()
	{
		return $this->online;
	}

	/**
	 * Method createCounter
	 * @param string $ip
	 * @param string $date
	 * @param string $time
	 * @param string $online
	 */
	public function createCounter($ip, $browser, $date, $time, $online)
	{
		$dbh = new Pldb;

		$checkIp = self::checkUserIp($ip, $date);

		if ( $checkIp == false)
		{
			$sql = "INSERT INTO pl_statistics(ip, browser, date_visit, time_visit, hits, online)VALUES(?, ?, ?, ?, ?, ?)";
			$data = array($ip, $browser, $date, $time, 1, $online);
		}
		else
		{
			$sql = "UPDATE pl_statistics SET hits = hits+1, online = ? WHERE ip = ? AND date_visit = ? ";
			$data = array($online, $ip, $date);
		}

		$sth = $dbh ->pstate($sql, $data);

	}

	/**
	 * @method setVisitorToday
	 * @param unknown $date_visit
	 * @return multitype:number
	 */
	public function setVisitorToday($date_visit)
	{
		
		$dbh = new Pldb;

		$sql = "SELECT statistic_id,
		ip, browser, date_visit,
		time_visit, hits, online
		FROM pl_statistics 
		WHERE date_visit = '$date_visit'GROUP BY ip ";
		$sth = $dbh -> query($sql);

		return $sth -> rowCount();

	}

	/**
	 * Method setTotalVisitor
	 * @return PDOStatement
	 */
	public function setTotalVisitor()
	{
		$dbh = new Pldb;

		$sql = "SELECT COUNT(hits) FROM pl_statistics";

		$sth = $dbh -> query($sql);

		return $sth -> fetchColumn();
	}

	/**
	 * Method setHits
	 * @param unknown $date_visit
	 */
	public function setHitsToday($date_visit)
	{
		$dbh = new Pldb;

		$sql= "SELECT SUM(hits) AS hitstoday FROM pl_statistics WHERE date_visit = '$date_visit' 
		       GROUP BY date_visit ";

		$sth = $dbh -> query($sql);

		return $sth -> fetch();

	}

	/**
	 * @method setTotalHits
	 * @return string
	 */
	public function setTotalHits()
	{
		$dbh = new Pldb;

		$sql = "SELECT SUM(hits) FROM pl_statistics";

		$sth = $dbh -> query($sql);

		return $sth -> fetchColumn();
	}

	/**
	 * @method checkUserIp
	 * @param integer $ip
	 * @param integer $date
	 * @return boolean
	 */
	public static function checkUserIp($ip, $date)
	{
		$dbh = new Pldb;

		$sql = "SELECT * FROM pl_statistics WHERE ip = :ip AND date_visit = :date_visit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":ip", $ip);
		$sth -> bindValue(":date_visit", $date);

		try {
			$sth -> execute();
			$numRows = $sth -> rowCount();
				
			if ($numRows == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}
}