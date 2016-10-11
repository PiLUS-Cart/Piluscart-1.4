<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Testimoni
 * Mapping testimoni table
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Testimoni 
{

	/**
	 * testimoni's ID
	 * @var integer
	 */
	protected $testimoni_id;

	/**
	 * customer's ID
	 * @var integer
	 */
	protected $customer_id;

	/**
	 * customer's fullname;
	 * @var string
	 */
	protected $fullname;

	/**
	 * customer's email
	 * @var string
	 */
	protected $email;

	/**
	 * Testimony
	 * @var string
	 */
	protected $testimoni;

	/**
	 * submission date
	 * @var string
	 */
	protected $submission_date;

	/**
	 * testimoni'status
	 * @var string
	 */
	protected $actived;


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
	 * get testimoni's ID
	 * @return number
	 */
	public function getTestimoni_Id()
	{
		return $this -> testimoni_id;
	}

	/**
	 * get Customer's ID
	 * @return number
	 */
	public function getCustomer_Id()
	{
		return $this -> customer_id;
	}

	/**
	 * get customer's fullname
	 * @return string
	 */
	public function getCustomer_Fullname()
	{
		return $this -> fullname;
	}

	/**
	 * get customer's email
	 * @return string
	 */
	public function getCustomer_Email()
	{
		return $this -> email;
	}

	/**
	 * get testimoni's content
	 * @return string
	 */
	public function getTestimoni_Content()
	{
		return $this -> testimoni;
	}

	/**
	 * get testiimoni's
	 * submission date
	 * @return string
	 */
	public function getSubmission_Date()
	{
		return $this -> submission_date;
	}

	/**
	 * get testimoni's status
	 * @return string
	 */
	public function getTestimoni_Status()
	{
		return $this -> actived;
	}

	/**
	 * Method createTestimoni
	 * Insert new record to
	 * table pl_testimoni
	 */
	public function createTestimoni()
	{
		$dbh = new Pldb;

		$sql = 'INSERT INTO pl_testimoni(customer_id, testimoni, submission_date)VALUES(?, ?, ?)';

		$data = array($this->customer_id, $this->testimoni, $this->submission_date);

		$sth = $dbh -> pstate($sql,$data);
		
		return $dbh -> lastId();

	}

	/**
	 * Method updateTestimoni
	 * Update an existing record
	 * from table pl_testimoni
	 */
	public function updateTestimoni()
	{
		$dbh = new Pldb;

		$sql = 'UPDATE pl_testimoni SET testimoni = ?, actived = ? WHERE testimoni_id = ? AND customer_id = ? ';

		$data = array($this->testimoni, $this->testimoni_id, $this->customer_id);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method deleteTestimoni
	 * deleting an existing record
	 */
	public function deleteTestimoni()
	{
		$dbh = new Pldb;

		$sql = 'DELETE FROM pl_testimoni WHERE customer_id = ?';

		$data = array($this->customer_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getTestimonies
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:Testimoni  number
	 */
	public static function getTestimonies($position, $limit)
	{
		$dbh = new Pldb;

		$sql = 'SELECT t.testimoni_id, t.customer_id, t.testimoni, t.submission_date, t.actived,
				c.fullname, c.email, c.address
				FROM pl_testimoni AS t
				INNER JOIN pl_customers AS c
				ON t.customer_id = c.ID
				ORDER BY t.testimoni_id LIMIT :position, :limit';

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(':position', $position, PDO::PARAM_INT);
		$sth -> bindValue(':limit', $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$testimonies = array();
				
			foreach ( $sth -> fetchAll() as $row)
			{
				$testimonies[] = new Testimoni($row);
			}
				
			$numbers = "SELECT testimoni_id FROM pl_testimoni ";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
			return (array("results" => $testimonies, "totalRows" => $totalRows));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method getTestimoni
	 * @param integer $id
	 * @return Testimoni
	 */
	public static function getTestimoni($id)
	{
		$dbh = new Pldb;

		$sql = 'SELECT t.testimoni_id, t.customer_id, t.testimoni, t.submission_date, t.actived,
				c.fullname, c.email, c.address
				FROM pl_testimoni AS t
				INNER JOIN pl_customers AS c
				ON t.customer_id = c.ID
				WHERE t.testimoni_id = ?';

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();

		if ($row) return new Testimoni($row);
	}
	
	/**
	 * Method findById
	 * @param integer $testimoni_id
	 * @return mixed
	 */
	public static function findById($testimoni_id)
	{
		$dbh = new Pldb;

		$sql = "SELECT testimoni_id, customer_id,
				testimoni_id, submission_date, actived
				FROM pl_testimoni WHERE testimoni_id = ? ";

		$data = array($testimoni_id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}

}