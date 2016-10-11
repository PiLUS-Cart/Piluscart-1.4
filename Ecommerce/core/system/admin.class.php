<?php  if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Admin extends Plbase
 * Mapping table pl_admin
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.3
 * 
 */


class Admin extends Plbase 
{

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
	 * admin's username
	 * @var string
	 */
	protected $admin_email;

	/**
	 * admin's password
	 * @var string
	 */
	protected $admin_pass;

	/**
	 * admin's date registered
	 * @var integer
	 */
	protected $admin_registered;

	/**
	 * admin's activation key
	 * @var string
	 */
	protected $admin_activation_key;

	/**
	 * admin's reset password key
	 * @var string
	 */
	protected $admin_reset_key;

	/**
	 * admin's reset status
	 * @var string
	 */
	protected $admin_resetComplete;

	/**
	 * admin's privelege
	 * @var string
	 */
	protected $admin_level;

	/**
	 * admin's session
	 * @var string
	 */
	protected $admin_session;

	/**
	 * admin's url
	 * @var string
	 */
	protected $admin_url;

	/**
	 * admin meta's id
	 * @var integer
	 */
	protected $admeta_id;

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
	 * Inisialisasi objek user 
	 * @param array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get admin's username
	 * @return string
	 */
	public function getAdmin_Username()
	{
		return $this->admin_login;
	}

	/**
	 * get admin's fullname
	 * @return string
	 */
	public function getAdmin_Fullname()
	{
		return $this->admin_fullname;
	}

	/**
	 * get admin's username
	 * @return string
	 */
	public function getAdmin_Email()
	{
		return $this->admin_email;
	}

	/**
	 * get admin' date registered
	 * @return number
	 */
	public function getAdmin_Registered()
	{
		return $this->admin_registered;
	}

	/**
	 * get admin's activation key
	 * @return string
	 */
	public function getAdmin_Activation_Key()
	{
		return $this->admin_activation_key;
	}

	/**
	 * get admin's reset key
	 * @return string
	 */
	public function getAdmin_Reset_Key()
	{
		return $this->admin_reset_key;
	}

	/**
	 * get admin's status reset password
	 * @return string
	 */
	public function getAdmin_resetComplete()
	{
		return $this->admin_resetComplete;
	}

	/**
	 * get admin's level
	 * @return string
	 */
	public function getAdmin_Level()
	{
		return $this->admin_level;
	}

	/**
	 * get admin's web/blog address
	 * @return string
	 */
	public function getAdmin_Url()
	{
		return $this->admin_url;
	}

	/**
	 * get admin's session_key
	 * @return string
	 */
	public function getSession_Key()
	{
		return $this->admin_session;
	}

	/**
	 * get admin meta's Id
	 * @return number
	 */
	public function getAdmeta_Id()
	{
		return $this->admeta_id;
	}

	/**
	 * get admin meta's address
	 * @return string
	 */
	public function getAdmeta_Address()
	{
		return $this->admeta_address;
	}

	/**
	 * get admin meta's gender
	 * @return string
	 */
	public function getAdmeta_Gender()
	{
		return $this->admeta_gender;
	}

	/**
	 * get admin meta's borndate
	 * @return string
	 */
	public function getAdmeta_Borndate()
	{
		return $this->admeta_borndate;
	}

	/**
	 * get admin meta's phone
	 * @return number
	 */
	public function getAdmeta_Phone()
	{
		return $this->admeta_phone;
	}

	/**
	 * get admin meta's bio
	 * @return string
	 */
	public function getAdmeta_Bio()
	{
		return $this->admeta_bio;
	}

	/**
	 * get admin meta's profil pict
	 * @return string
	 */
	public function getAdmeta_Avatar()
	{
		return $this->admeta_avatar;
	}

	/**
	 * @method getLevel_dropDown
	 * @return string
	 */
	public function getLevel_dropDown()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->admin_level) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$levels = array('superadmin', 'admin', 'editor', 'author', 'contributor');

		$html  = array();

		$html[] = '<label for="level">Pilih level staff</label>';
		$html[] = '<select class="form-control" name="admin_level">';

		foreach ($levels as $l => $level) {

			if ($this->admin_level == $level) {
				$option_selected = ' selected="selected"';
			}
			// set up the option line
			$html[]  =  '<option value="' . $level . '"' . $option_selected . '>' . $level . '</option>';
			// clear out the selected option flag
			$option_selected = '';
		}
		
		if ( empty($this->admin_level) OR $level == '')
		{
			$html[] = '<option value="0" selected>-- Pilih Level Staff --<option>';
		}
		
		$html[] = '</select>';

		
		return implode("\n", $html);

	}

	/**
	 * insert a new record
	 * to pl_admin table
	 * 
	 * @method createAdmin
	 */
	public function createAdmin()
	{
		$dbh = parent::hook();

		$sql = "INSERT INTO pl_admin(admin_login, admin_fullname, admin_email,
				admin_pass, admin_registered, admin_activation_key,
				admin_level, admin_session, admin_url)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$shield_pass = self::create_hash($this->admin_pass);

		$data = array($this->admin_login, $this->admin_fullname, $this->admin_email,
				$shield_pass, $this->admin_registered, $this->admin_activation_key,
				$this->admin_level, $this->admin_session, $this->admin_url);

		$sth = $dbh -> pstate($sql, $data);

		$admin_id = $dbh -> lastId();

		if (isset($admin_id)) {
			
			//get all record from last entry
			$getDataAdmin = "SELECT ID, admin_login, admin_fullname, admin_email,
					        admin_pass, admin_registered, admin_activation_key,
					        admin_level, admin_session, admin_url 
					        FROM pl_admin WHERE ID = ?";
			
			$data_selected =  array($admin_id);
				
			$sth = $dbh -> pstate($getDataAdmin, $data_selected);
				
			foreach ( $sth -> fetchAll() as $row) {
					
				//insert record to pl_adminmeta table
				$admin_meta = "INSERT INTO pl_adminmeta(admin_id)VALUES(?)";
				$data_meta = array($row['ID']);
				$dbh -> pstate($admin_meta, $data_meta);
				$dbh = null;
					
			}
			
		}

	}


	/**
	 * this method is used
	 * with privilege as superadmin
	 * to update an existing record
	 * from pl_admin table
	 * 
	 * @method updateAdmin
	 */
	public function updateAdmin()
	{

		$dbh = parent::hook();

		if (empty($this->admin_pass)) {

			$sql = "UPDATE pl_admin SET admin_fullname = :admin_fullname,
					admin_email = :admin_email,
					admin_level = :admin_level,
					admin_url = :admin_url
					WHERE admin_session = :admin_session";

			$sth = $dbh -> prepare( $sql );
			$sth -> bindValue(":admin_fullname", $this->admin_fullname, PDO::PARAM_STR);
			$sth -> bindValue(":admin_email", $this->admin_email, PDO::PARAM_STR);
			$sth -> bindValue(":admin_level", $this->admin_level, PDO::PARAM_STR);
			$sth -> bindValue(":admin_url", $this->admin_url, PDO::PARAM_STR);
			$sth -> bindValue(":admin_session", $this->admin_session, PDO::PARAM_STR);

		} else {
				
			$hash_password = self::create_hash($this->admin_pass);

			$sql = "UPDATE pl_admin SET admin_fullname = :admin_fullname,
					admin_email = :admin_email,
					admin_pass = :admin_pass,
					admin_level = :admin_level,
					admin_url = :admin_url
					WHERE admin_session = :admin_session";

			$sth = $dbh -> prepare( $sql );
			
			$sth -> bindValue(":admin_fullname", $this->admin_fullname, PDO::PARAM_STR);
			$sth -> bindValue(":admin_email", $this->admin_email, PDO::PARAM_STR);
			$sth -> bindValue(":admin_pass", $hash_password, PDO::PARAM_STR);
			$sth -> bindValue(":admin_level", $this->admin_level, PDO::PARAM_STR);
			$sth -> bindValue(":admin_url", $this->admin_url, PDO::PARAM_STR);
			$sth -> bindValue(":admin_session", $this->admin_session, PDO::PARAM_STR);
				
		}

		try {
				
			$sth -> execute();
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		
		}

	}


	/**
	 * update an existing record
	 * from pl_adminmeta table
	 * based on their ID
	 * 
	 * @method updateUserMeta
	 */
	public function updateUserMeta()
	{
		$dbh = parent::hook();

		if ($this -> getAdmeta_Avatar()) {
			
			$sql = 'UPDATE pl_adminmeta SET admeta_address = ?,
					admeta_gender = ?,
					admeta_borndate = ?,
					admeta_phone = ?, admeta_bio = ?, admeta_avatar = ?
					WHERE admeta_id = ?';
				
			$data = array($this->admeta_address, $this->admeta_gender, $this->admeta_borndate, $this->admeta_phone, $this->admeta_bio, $this->admeta_avatar, $this->admeta_id);
				
		} else {
			
			$sql = 'UPDATE pl_adminmeta SET admeta_address = ?,
					admeta_gender = ?, admeta_borndate = ?,
					admeta_phone = ?, admeta_bio = ?
					WHERE admeta_id = ?';

			$data = array($this->admeta_address, $this->admeta_gender, $this->admeta_borndate, $this->admeta_phone, $this->admeta_bio, $this->admeta_id);

		}

		$sth = $dbh -> pstate($sql, $data);
		
	}


	/**
	 * to delete an existing record
	 * 
	 * @method deleteAdmin
	 */
	public function deleteAdmin()
	{
		//apakah objeck admin yang akan dihapus memiliki ID ?
		if ( is_null($this->ID)) trigger_error( "Admin::deleteAdmin: Attempt to delete an Admin object that does not have it's ID property Sset.", E_USER_ERROR );

		//hapus admin
		$dbh = parent::hook();

		$sql = "DELETE FROM pl_admin WHERE ID = ?";

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);

		//hapus admin meta
		$hapusMeta = "DELETE FROM pl_adminmeta WHERE admin_id = ?";

		$admin_id = array($this -> ID);

		$stmt = $dbh -> pstate($hapusMeta, $admin_id);

		$dbh = null;
		
	}

	/**
	 * Method activateAdmin
	 * to activate admin
	 * @param string $adminKey
	 */
	public function activateAdmin($adminKey)
	{
		$dbh = parent::hook();

		$tgl_sekarang = date("Ymd");

		try {

			$cek_adminKey = self::checkActivationKey($adminKey);

			if ($cek_adminKey === false) {
				
				directPage();
				
			} else {
				
				$sql = "UPDATE pl_admin SET admin_activation_key = 'Yes', admin_registered = ?
						WHERE admin_activation_key = ? ";

				$data = array($tgl_sekarang, $adminKey);

				$sth = $dbh -> pstate($sql, $data);

				if ($row = $sth -> rowCount() == 1) {
					
					//redirect to login page
					header('Location: ' . PL_CABIN . 'login.php?status=aktif');
					exit();
				}

			}
			
		} catch (PDOException $e) {
			
			LogError::newMessage($e);
			LogError::customErrorMessage();
		}
		
	}

	/**
	 * @method login
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function login($username,$password)
	{

		$hashed = $this->get_admin_hash($username);

		$_SESSION['loggedIn'] = false;

		if($this->verify_hash($password,$hashed) == 1) {
			
			$_SESSION['loggedIn'] = true;
			
			return true;
			
		}

	}

	/**
	 * @method logout
	 */
	public function logout()
	{
		
		if (!isset($_SESSION['adminID'])) {
			
			directPage();
			
		} else {
			
			$_SESSION = array();
			
			session_destroy();
			
			setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
			
			//Redirect to Login Page
			$logIn_Page = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
			
			header('Location:' . $logIn_Page);
			
		} 
			
	}

	/**
	 * to recover password
	 * 
	 * @method recoverPass
	 * @param string $token
	 * @param string $admin_pass
	 */
	public function recoverPass($password, $token)
	{

		$dbh = parent::hook();

		$sql = "UPDATE pl_admin SET admin_pass = ?,
				admin_resetComplete = 'Yes' WHERE admin_reset_key = ?";

		$hash_password = self::create_hash($password);

		$data_recover = array($hash_password, $token);

		$sth = $dbh ->pstate($sql, $data_recover);

		if ($row = $sth -> rowCount() == 1) {
			// redirect to login page
			$logIn_Page = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
				
			header('Location: ' . $logIn_Page . 'login.php?status=ganti');
		}

	}

	/**
	 * to update session record
	 * 
	 * @method updateAdminSession
	 * @param string $userLogin
	 * @param string $sessionKey
	 * @param string $sessionId
	 */
	public function updateAdminSession($sessionKey, $userName)
	{
		$dbh = parent::hook();

		$sql = "UPDATE pl_admin SET admin_session = ? WHERE admin_login = ?";

		$generateKey = $this->createSessionKey($sessionKey);

		$data = array($generateKey, $userName);

		$sth = $dbh -> pstate($sql, $data);

		if ( self::isLoggedIn()) {
			
			$dataUser = $this->fetchAdminData($userName);
				
			$_SESSION['adminID']      = $dataUser['ID'];
			$_SESSION['adminLogin']   = $dataUser['admin_login'];
			$_SESSION['adminName']    = $dataUser['admin_fullname'];
			$_SESSION['adminEmail']   = $dataUser['admin_email'];
			$_SESSION['adminSession'] = $dataUser['admin_session'];
			$_SESSION['adminLevel']   = $dataUser['admin_level'];
			$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
			
			header('Location: '. PL_CABIN . 'index.php?module=dashboard');
				
		}
				
	}

	/**
	 * verifying password
	 * 
	 * @method verify_hash
	 * @param string $password
	 * @param string $hash
	 * @return boolean
	 */
	private function verify_hash($password,$hash)
	{
		return $hash == crypt($password, $hash);
	}

	/**
	 * Method get_admin_hash
	 * @param string $username
	 * @return mixed
	 */
	private function get_admin_hash($username)
	{

		$dbh = parent::hook();

		$sql = "SELECT admin_pass FROM pl_admin WHERE admin_login = :admin_login";

		try {

				
			$sth = $dbh->prepare($sql);
			$sth->execute(array(':admin_login' => $username));

			$row = $sth->fetch();
			return $row['admin_pass'];

		} catch(PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}
		
	}

	/**
	 * retrieve spesific
	 * admin record for creating session
	 * in method login
	 *
	 * @method fetchAdminData
	 * @param string $username
	 * @return mixed
	 */
	private function fetchAdminData($username)
	{
		$dbh  = parent::hook();
	
		$sql = "SELECT ID, admin_login,
				admin_fullname,
				admin_email, admin_pass,
				admin_level, admin_session
				FROM pl_admin WHERE admin_login = ?";
	
		$sth = $dbh  -> prepare($sql);
	
		$sth -> bindValue(1, $username);
	
		try {
	
			$sth -> execute();
	
			return $sth -> fetch();
	
		} catch (PDOException $e) {
	
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
	
		}
	
	}
	
	/**
	 * checking sesi login
	 * 
	 * @method isLoggedIn
	 * @return boolean
	 */
	public static function isLoggedIn()
	{
		if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
			
			return true;
			
		}
		
	}

	/**
	 * Method accessLevel
	 * checking an existing
	 * session for admin level
	 * @return array|boolean
	 */
	public static function accessLevel()
	{
		if (isset($_SESSION['adminLevel'])) {
			
			return $_SESSION['adminLevel'];
			
		} else {
			
			return false;
			
		}
		
	}

	/**
	 * Method usernameExists
	 * to check existing username
	 * @param string $admin_login
	 * @return boolean|mixed
	 */
	public static function usernameExists($admin_login)
	{
		$dbh = parent::hook();

		$sql = "SELECT COUNT(`ID`) FROM `pl_admin` WHERE `admin_login`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $admin_login);

		try {

			$sth -> execute();
			$rows = $sth -> fetchColumn();

			if ($rows == 1) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}
		
	}

	/**
	 * Method emailExists
	 * to check existing email
	 * 
	 * @param string $email
	 * @return boolean
	 */
	public static function emailExists($email)
	{
		$dbh = parent::hook();

		$sql = "SELECT COUNT(`ID`) FROM `pl_admin` WHERE `admin_email`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $email);

		try {
			$sth -> execute();
			$rows = $sth -> fetchColumn();
				
			if ($rows == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method checkActivationKey
	 * checking activation key
	 * @param string $adminKey
	 * @return boolean
	 */
	public static function checkActivationKey($adminKey)
	{
		$dbh = parent::hook();

		$sql = "SELECT COUNT('ID') FROM pl_admin WHERE admin_activation_key = ?";
		$sth = $dbh -> prepare( $sql );
		$sth -> bindValue(1, $adminKey);

		try {
			$sth -> execute();
			$row = $sth -> fetchColumn();
			if ($row == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method cekStatusToken
	 * mengecek status aktivasi akun admin
	 * @param string $value
	 * @return boolean
	 */
	public static function cekStatusToken($value)
	{
		$dbh = parent::hook();

		$sql = "SELECT admin_activation_key FROM pl_admin
				WHERE admin_login = :admin_login ";
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":admin_login", $value, PDO::PARAM_STR);

		try {

			$sth -> execute();
			$status = 'Yes';

			while ($row = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$administrator = $row['admin_activation_key'];
			}

			if ($administrator != $status)
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


	/**
	 * Method fetchUserMeta
	 * retrieving an existing
	 * record from pl_adminmeta
	 * based on their ID and session
	 * this record is used for editing
	 * user's info
	 * @param integer $admin_id
	 * @param string $admin_session
	 * @return Admin
	 */
	public static function fetchUserMeta($admin_id, $admin_session)
	{
		$dbh = parent::hook();

		$sql = 'SELECT
				adm.admeta_id, adm.admin_id,
				adm.admeta_address, adm.admeta_gender,
				adm.admeta_borndate, adm.admeta_phone,
				adm.admeta_bio, adm.admeta_avatar,
				a.ID, a.admin_login, a.admin_fullname, a.admin_email,
				a.admin_pass,
				a.admin_registered,a.admin_activation_key,
				a.admin_level, a.admin_session, a.admin_url
				FROM pl_adminmeta AS adm
				INNER JOIN pl_admin AS a ON adm.admin_id = a.ID
				WHERE adm.admin_id = ? AND a.admin_session = ?';

		$data = array($admin_id, $admin_session);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Admin($row);

	}

	/**
	 * @method findById
	 * @param integer $id
	 * @param integer $sessionId
	 * @return object field from pl_admin table
	 */
	public static function findById($id, $sessionId)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, admin_login, admin_fullname, admin_email,
				admin_pass, admin_registered, admin_activation_key,
				admin_reset_key, admin_resetComplete,
				admin_level, admin_session, admin_url
				FROM pl_admin WHERE ID = ? AND admin_session = ?";

		$data = array($id,  $sessionId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetchObject();

	}

	/**
	 * @method findMetaById
	 * @param unknown $id
	 */
	public static function findMetaById($id, $userName)
	{
		$dbh = parent::hook();
		
		$sql = "SELECT adm.admeta_id, adm.admin_id, 
				adm.admeta_address, adm.admeta_gender,
				adm.admeta_borndate, adm.admeta_phone, 
				adm.admeta_bio, adm.admeta_avatar,
				a.admin_login, a.admin_level
				FROM pl_adminmeta AS adm
				INNER JOIN pl_admin AS a ON adm.admin_id = a.ID
				WHERE adm.admin_id = ? AND a.admin_login = ?";
		
		$data = array($id, $userName);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> fetchObject();
	}
	
	/**
	 * @method getListUsers
	 * retrieve all recordss
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:Admin  mixed
	 */
	public static function getListUsers($position, $limit)
	{
		$dbh = parent::hook();
			
		$sql = "SELECT ID, admin_login, admin_fullname, admin_email, admin_pass, admin_registered,
				admin_activation_key, admin_reset_key, admin_resetComplete, admin_level, admin_session,
				admin_url FROM pl_admin ORDER BY admin_login LIMIT :position, :limit";
			
		$sth = $dbh -> prepare($sql);
		$sth -> bindParam(":position", $position, PDO::PARAM_INT);
		$sth -> bindParam(":limit", $limit, PDO::PARAM_INT);

		$list = array();

		try {
				
			$sth -> execute();
			
			foreach ( $sth -> fetchAll() as $row)
			{
				$list[] = new Admin($row);
			}
			
			$numbers = 'SELECT ID FROM pl_admin';
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
	 * to retrieve a spesific record
	 * based on their session
	 * this method is used in editing
	 * user's info by admin
	 * 
	 * @method getUserBySession
	 * @param int $id
	 * @return mixed
	 */
	public static function getUserBySession($sessionId)
	{
		$dbh = parent::hook();
			
		$sql = "SELECT ID, admin_login, admin_fullname,
				admin_email, admin_pass, admin_registered,
				admin_activation_key, admin_reset_key,
				admin_resetComplete, admin_level, admin_session,
				admin_url FROM pl_admin WHERE admin_session = ?";

		$data = array($sessionId);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Admin($row);
			
	}
	
	/**
	 * to retrieve a spesific record
	 * based on their ID
	 *
	 * @method getUserById
	 * @param int $id
	 * @return mixed
	 */
	public static function getUserById($id)
	{
		$dbh = parent::hook();
			
		$sql = "SELECT ID, admin_login, admin_fullname,
				admin_email, admin_pass, admin_registered,
				admin_activation_key, admin_reset_key,
				admin_resetComplete, admin_level, admin_session,
				admin_url FROM pl_admin WHERE ID = ?";
		
		$data = array($id);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();
		
		if ($row) return new Admin($row);
		
	}

	/**
	 * Method create_hash
	 * hashing password
	 * @param string $value
	 * @return string
	 */
	private static function create_hash($value)
	{
		return $hash = crypt($value, '$2a$12$'.substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22));
	}

	/**
	 *
	 * Method to create session key
	 * @param string $token
	 * @return string
	 */
	protected static function createSessionKey($token)
	{
		//create token
		$salt = 'cTtd*7xMCY-MGHfDagnuC6[+yez/DauJUmHTS).t,b,T6_m@TO^WpkFBbm,L<%C';
		$token = sha1(mt_rand(10000, 99999) . time(). $salt);

		return $token;

	}
	
}