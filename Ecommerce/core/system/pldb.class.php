<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!"); 
/**
 * Kelas Pldb - Pilus database
 * untuk koneksi database dilengkapi
 * fitur pencarian produk dan tulisan
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Pldb extends PDO 
{

	/**
	 * SQL
	 * @var string
	 */
	public $sql;

	/**
	 * Error
	 * @var string
	 */
	public $error;

	/**
	 * result
	 * @var string
	 */
	public $result;

	/**
	 * bind parameter
	 * @var unknown
	 */
	public $bind;


	/**
	 * hold the statement
	 * @var string $sth
	 */


	function __construct()
	{

		try {
			
			parent::__construct(PL_DBTYPE .':host='. PL_DBHOST .';dbname='. PL_DBNAME,PL_DBUSER,PL_DBPASS);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e){
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
		}

	}

	/**
	 * Query For Prepared Statement
	 * using positional placeholder
	 * or question mark
	 * @param string $sql
	 */
	public function pstate($sql, $data = NULL )
	{
		$this->sql = $this->prepare($sql);

		try {
				
			$this->sql->execute($data);

		} catch (PDOException $e) {
				
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
		}

		return $this->sql;
		
	}

	/**
	 * @method tableExists
	 * checking an avaibility table
	 * @param unknown $table
	 * @return boolean
	 */
	public function tableExists($table)
	{

		$sql = "SHOW TABLES LIKE '" . $table . "'";

		$this->sql = $this->query($sql);

		return $this->sql->rowCount();
	}

	/**
	 * Method lastId
	 * return the last
	 * inserted Id as a string
	 * @return string
	 */
	public function lastId()
	{
		return $this -> lastInsertId();
	}

	/**
	 *
	 * @param string $bind
	 * @return associative
	 */
	public function cleanup($bind)
	{
		return $bind;
	}

	/**
	 * Query For Prepared Statement
	 * using bind parameter
	 * or question mark
	 * @param string $sql
	 */
	public function pstateBind($sql, $bind = "")
	{
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = '';

		try {
				
			$sth = $this->prepare($this->sql);
				
			if ( $sth -> execute($this->bind) !== false )
			{

				if (preg_match("/^(" . implode("|", array ("select", "describe", "pragma")) . ") /i", $this->sql))
				{
						
					return $sth->fetchAll(PDO::FETCH_ASSOC);
				}
				elseif (preg_match("/^(" . implode("|", array ("delete", "insert", "update")) . ") /i", $this->sql))
				{
						
					return $sth->rowCount();
				}

			}
				
		} catch (PDOException $e) {
				
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
				
		}

		return false;

	}

	/**
	 * Query using bind parameter
	 * or without it
	 * @param string $sql
	 */
	public function plQuery($sql, $bind = false)
	{
		$this->error = '';

		try {
				
			if($bind !== false)
			{
				return $this->pstateBind($sql, $bind);
			}
			else
			{
				$this->result = $this->query($sql);
				return $this->result;
			}
				
		} catch (PDOException $e) {
				
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
		}

		return false;
	}

	/**
	 * Method pencarian produk
	 * 
	 * @method searchProduct
	 * @param string $data
	 * @return multitype:number <boolean, string, multitype:>
	 */
	public function searchProduct($data)
	{
		$bind = array(":keyword" => "%$data%");

		$sql = 'SELECT ID, product_catId, product_name, slug,
				description, price, stock, weight, date_submited,
				bought, discount, image
				FROM pl_product
				WHERE description LIKE :keyword
				OR product_name LIKE :keyword';

		$hasil_pencarian = $this->plQuery($sql, $bind); // hasil pencarian

		// hitung jumlah produk yang ditemukan dengan kata kunci :keyword
		$sth = $this->prepare($sql);
		$keyword = '%'.$data.'%';
		$sth -> bindValue(':keyword', $keyword, PDO::PARAM_STR);
		$sth -> execute();
		$totalRows = $sth -> rowCount();

		return (array("results" => $hasil_pencarian, "totalRows" => $totalRows));
	}

	/**
	 * pencarian artikel
	 * 
	 * @method searchArticle
	 * @param string $data
	 * @return number[]|boolean[]|string[]
	 */
	public function searchArticle($data)
	{
		$bind = array(":keyword" => "%$data%");
		
		$sql = 'SELECT ID, post_title, post_image, 
				post_cat, post_author, post_date,
				post_title, post_slug, post_content,
				post_status, post_type, comment_status
				FROM pl_post
				WHERE post_title LIKE :keyword OR post_content LIKE :keyword';
		
		$hasil_pencarian = $this->plQuery($sql, $bind); // hasil pencarian
		
		// hitung jumlah artikel yang ditemukan denga kata kunci :keyword 
		$sth = $this->prepare($sql);
		$keyword = '%'.$data.'%';
		$sth -> bindValue(':keyword', $keyword, PDO::PARAM_STR);
		$sth -> execute();
		$totalRows = $sth -> rowCount(); 
		
		return (array("results" => $hasil_pencarian, "totalRows" => $totalRows));
		
	}

}