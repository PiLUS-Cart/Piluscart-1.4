<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Plbase
 * Generalize common properties and function
 * to connect to database
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 * 
 */

class Plbase
{
	/**
	 *
	 * @var ID
	 */
	protected $ID;

	/**
	 * Initialize object input
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
	 * @method hook
	 * koneksi ke database
	 * @return Pldb
	 */
	protected static function hook()
	{
		$db = new Pldb;
		return $db;
	}

	/**
	 * Return ID
	 * @return ID
	 */
	public function getId()
	{
		return $this->ID;
	}


}