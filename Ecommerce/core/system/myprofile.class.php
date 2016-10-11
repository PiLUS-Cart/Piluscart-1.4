<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas MyProfile
 * Mapping table pl_adminmeta
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class MyProfile 
{

	/**
	 * admin meta's id
	 * @var integer
	 */
	protected $admeta_id;

	/**
	 * admin's ID
	 * @var integer
	 */
	protected $admin_id;

	/**
	 * admin's username
	 * @var string
	 */
	protected $admin_login;

	/**
	 * admin's fullname
	 * @var string
	 */
	protected $admin_fullname;

	/**
	 * admin's email
	 * @var string
	 */
	protected $admin_email;

	/**
	 * admin's password
	 * @var string
	 */
	protected $admin_pass;

	/**
	 * admin's level
	 * @var string
	 */
	protected $admin_level;
	
	/**
	 * admin's session
	 * @var string
	 */
	protected $admin_session;
	
	/**
	 * admin's  url
	 * @var string
	 */
	protected $admin_url;

	/**
	 * admin meta's address
	 * @var string
	 */
	protected $admeta_address;

	/**
	 * admin meta's gender
	 * @var string
	 */
	protected $admeta_gender;

	/**
	 * admin meta's borndate
	 * @var string
	 */
	protected $admeta_borndate;

	/**
	 * admin meta's phone
	 * @var string
	 */
	protected $admeta_phone;

	/**
	 * admin meta's bio
	 * @var string
	 */
	protected $admeta_bio;

	/**
	 * admin meta's avatar
	 * @var string
	 */
	protected $admeta_avatar;


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
	 * @method getAdmeta_Id
	 * @return number
	 */
	public function getAdmeta_Id()
	{
		return $this->admeta_id;
	}

	/**
	 * Method getAdmin_Id
	 * get admin's ID
	 * @return number
	 */
	public function getAdmin_Id()
	{
		return $this -> admin_id;
	}

	/**
	 * Method getUsername
	 * get username
	 * @return string
	 */
	public function getUsername()
	{
		return $this->admin_login;
	}

	/**
	 * @method getFullname
	 * @return string
	 */
	public function getFullname()
	{
		return $this->admin_fullname;
	}
	
	/**
	 * @method getEmail
	 * @return string
	 */
	public function getEmail()
	{
		return $this -> admin_email;
	}
	
	/**
	 * @method getLevel
	 * @return string
	 */
	public function getLevel()
	{
		return $this->admin_level;
	}

	/**
	 * Method getAdmeta_Address
	 * get admeta_address
	 * @return string
	 */
	public function getAdmeta_Address()
	{
		return $this->admeta_address;
	}

	/**
	 * Method getAdmeta_Gender
	 * get admeta_gender
	 * @return string
	 */
	public function getAdmeta_Gender()
	{
		return $this->admeta_gender;
	}

	/**
	 * Method getAdmeta_Borndate
	 * get admeta_borndate
	 * @return string
	 */
	public function getAdmeta_Borndate()
	{
		return $this->admeta_borndate;
	}

	/**
	 * Method getAdmeta_Phone
	 * get admeta_phone
	 * @return string
	 */
	public function getAdmeta_Phone()
	{
		return $this->admeta_phone;
	}

	/**
	 * Method getAdmeta_Bio
	 * get admin meta's bio
	 * @return string
	 */
	public function getAdmeta_Bio()
	{
		return $this->admeta_bio;
	}

	/**
	 * Method getAdmeta_Avatar
	 * get admin meta's avatar
	 * @return string
	 */
	public function getAdmeta_Avatar()
	{
		return $this->admeta_avatar;
	}

	/**
	 * Method updateMyProfile
	 */
	public function updateMyMetaProfile()
	{
		$dbh = new Pldb;

		if ($this -> getAdmeta_Avatar())
		{
			$sql = 'UPDATE pl_adminmeta SET admeta_address = ?,
					admeta_gender = ?,
					admeta_borndate = ?,
					admeta_phone = ?, admeta_bio = ?, admeta_avatar = ?
					WHERE admeta_id = ?';

			$data = array($this->admeta_address, $this->admeta_gender, $this->admeta_borndate, $this->admeta_phone, $this->admeta_bio, $this->admeta_avatar, $this->admeta_id);

		}
		else
		{
			$sql = 'UPDATE pl_adminmeta SET admeta_address = ?,
					admeta_gender = ?, admeta_borndate = ?,
					admeta_phone = ?, admeta_bio = ?
					WHERE admeta_id = ?';

			$data = array($this->admeta_address, $this->admeta_gender, $this->admeta_borndate, $this->admeta_phone, $this->admeta_bio, $this->admeta_id);

		}

		$sth = $dbh -> pstate($sql, $data);


	}

	/**
	 * @method updateMyProfile
	 */
	public function updateMyProfile()
	{
		$dbh = new Pldb;

		$admin = new Admin();

		if ( empty($_POST['admin_pass']))
		{
			$sql = "UPDATE pl_admin SET admin_fullname = ?,
					admin_email = ?,
					admin_url = ?
					WHERE admin_session = ?";
				
			$data = array(
					$_POST['admin_fullname'],
					$_POST['admin_email'],
					$_POST['admin_url'],
					$_POST['sesi_id']
			);
		}
		else
		{
			$hash_password = $admin -> create_hash($_POST['admin_pass']);
				
			$sql = "UPDATE pl_admin SET admin_fullname = ?,
					admin_email = ?,
					admin_pass = ?,
					admin_url = ?
					WHERE admin_session = ?";
				
			$data = array(
					$_POST['admin_fullname'],
					$_POST['admin_email'],
					$hash_password,
					$_POST['admin_url'],
					$_POST['sesi_id']
			);
		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * retrieve phone record
	 * from table pl_adminmeta
	 *
	 * @method findPhone
	 * @param integer $id
	 */
	public function findPhone($id)
	{
		$dbh =  new Pldb;
	
		$sql = "SELECT admeta_id, admeta_phone 
				FROM pl_adminmeta WHERE admin_id = ? ";
	
		$data =  array($id);
	
		$sth = $dbh -> pstate($sql, $data);
	
		return $sth -> fetchObject();
	}

	/**
	 * Method getBiodata
	 * retrieve data record
	 * from pl_admin
	 * @param string $userName
	 * @return mixed
	 */
	public static function getBiodata($userName)
	{
		$dbh = new Pldb;

		$sql = "SELECT ID, admin_login,
				admin_fullname, admin_email,
				admin_pass, admin_registered,
				admin_activation_key, admin_reset_key,
				admin_resetComplete,
				admin_level,
				admin_session,
				admin_url 
				FROM pl_admin WHERE admin_login = ?";

		$data = array($userName);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
			
	}


	/**
	 * @method getMyProfile
	 * @param integer $admeta_id
	 * @param integer $admin_id
	 * @return mixed
	 */
	public static function getMyProfile($admin_id, $sessionId, $adminLogin)
	{
		$dbh = new Pldb;

		$sql = "SELECT adm.admeta_id, adm.admin_id, adm.admeta_address,
				adm.admeta_gender, adm.admeta_borndate,
				adm.admeta_phone, adm.admeta_bio, admeta_avatar,
				a.ID, a.admin_login, a.admin_session, a.admin_level 
				FROM pl_adminmeta AS adm
				INNER JOIN pl_admin AS a ON adm.admin_id = a.ID
				WHERE adm.admin_id = ? AND a.admin_session = ? 
				AND a.admin_login = ?";

		$data = array($admin_id, $sessionId, $adminLogin);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new MyProfile($row);

	}
	
}