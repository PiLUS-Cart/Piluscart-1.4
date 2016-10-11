<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Customer extends Plbase
 * Mapping table pl_customers
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Customer extends Plbase 
{

	/**
	 * customer's fullname
	 * @var string
	 */
	protected $fullname;

	/**
	 * customer's email
	 * @var string
	 */
	protected $email;

	/**
	 * customer's password
	 * @var string
	 */
	protected $password;

	/**
	 * customer's address
	 * @var string
	 */
	protected $address;

	/**
	 * customer's phone
	 * @var string
	 */
	protected $phone;

	/**
	 * city's id
	 * @var string
	 */
	protected $district_id;

	/**
	 * city's name
	 * @var string
	 */
	protected $district_name;

	/**
	 * shipping's Id
	 * @var integer
	 */
	protected $shipping_id;

	/**
	 * shipping's name
	 * @var string
	 */
	protected $shipping_name;

	/**
	 * customer type
	 * @var boolean
	 */
	protected $customer_type;
	
	/**
	 * customer's reset key
	 * @var string
	 */
	protected $customer_resetKey;
	
	/**
	 * reset status
	 * @var string
	 */
	protected $customer_resetComplete;
	
	/**
	 * customer's session
	 * @var string
	 */
	protected $customer_session;
	
	/**
	 * date customer registered
	 * @var string
	 */
	protected $date_registered;
	
	/**
	 * time customer registered
	 * @var string
	 */
	protected $time_registered;
	
	/**
	 * Inisialisasi object dari kelas customer
	 * dengan tabel pl_customers
	 * @param array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get customer's fullname
	 * @return string
	 */
	public function getCustomerFullname()
	{
		return $this->fullname;
	}

	/**
	 * get customer's e-mail
	 * @return string
	 */
	public function getCustomerEmail()
	{
		return $this->email;
	}

	/**
	 * get customer's address
	 * @return string
	 */
	public function getCustomerAddress()
	{
		return $this->address;
	}

	/**
	 * get customer's phone
	 * @return string
	 */
	public function getCustomerPhone()
	{
		return $this->phone;
	}

	/**
	 * get city's Id
	 * @return string
	 */
	public function getDistrictId()
	{
		return $this->district_id;
	}

	/**
	 * get city's name
	 * @return string
	 */
	public function getDistrictName()
	{
		return $this->district_name;
	}

	/**
	 * get shipping's Id
	 * @return number
	 */
	public function getShippingId()
	{
		return $this->shipping_id;
	}

	/**
	 * get shipping's name
	 * @return string
	 */
	public function getShippingName()
	{
		return $this->shipping_name;
	}
	
	/**
	 * get customer's type
	 * @return boolean
	 */
	public function getCustomerType()
	{
		return $this->customer_type;
	}
	
	/**
	 * get customer's reset key
	 * @return string
	 */
	public function getCustomer_resetKey()
	{
		return $this->customer_resetKey;
	}
	
	/**
	 * @method getCustomer_resetComplete
	 * @return string
	 */
	public function getCustomer_resetComplete()
	{
		return $this->customer_resetComplete;
	}
	
	/**
	 * @method getCustomer_Session
	 * @return string
	 */
	public function getCustomer_Session()
	{
		return $this->customer_session;
	}
	
	/**
	 * @method getCustomer_DateRegistered
	 * @return string
	 */
	public function getCustomer_DateRegistered()
	{
		return $this->date_registered;
	}
	
	/**
	 * @method getCustomer_TimeRegistered
	 * @return string
	 */
	public function getCustomer_TimeRegistered()
	{
		return $this->time_registered;
	}
	
	/**
	 * insert a new record
	 * to table pl_customers
	 * 
	 * @method addCustomer
	 * @return string
	 */
	public function addCustomer()
	{
		
		$dbh = parent::hook();

		$sql = "INSERT INTO pl_customers( fullname, email, password, address, phone,
				district_id, shipping_id, customer_type, customer_session,
				date_registered, time_registered)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		
		$customerSesi = generateSessionKey($this->customer_session);
		
		$data = array($this->fullname, $this->email, $this->password,
				$this->address, $this->phone, $this->district_id, $this->shipping_id,  
				$this->customer_type, $customerSesi, $this->date_registered,
				$this->time_registered);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$id_customer = $dbh -> lastId();
		
		if (isset($id_customer)) {
			
			$stmt = $dbh -> query("UPDATE pl_customers 
					SET password = '".shieldPass($this->password, $id_customer)."' 
					WHERE ID = '$id_customer'");
			
			return $id_customer;
		}
		
	}
	
	/**
	 * @method updateCustomer
	 */
	public function updateCustomer()
	{
		$dbh = parent::hook();
		
		try {
			
			if ( empty($this->password)) {
				
				$sql = "UPDATE pl_customers SET fullname = ?, email = ?,
					address = ?, phone = ?, district_id = ?,
					shipping_id = ?, customer_type = ?
					WHERE ID = ?";
					
				$data = array(
						$this->fullname, $this->email, $this->address, $this->phone, $this->district_id,
						$this->shipping_id, $this->customer_type, $this->ID);
					
			} else {
				
				$hash_password = shieldPass($this->password, $this->ID);
				
				$sql = "UPDATE pl_customers SET fullname = ?, email = ?, password = ?,
					address = ?, phone = ?, district_id = ?, shipping_id = ?,
					customer_type = ? WHERE ID = ?";
					
				$data = array(
							
						$this->fullname, $this->email, $hash_password, $this->address,
						$this->phone, $this->district_id, $this->shipping_id, $this->customer_type,
						$this->ID );
					
			}
			
			$sth = $dbh -> pstate($sql, $data);
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}
					
	}
	
	/**
	 * this method used to update record 
	 * in pl_customers table based on their Id
	 * when customer's data 
	 * such as phone and address are null
	 * 
	 * @method updateMemberById
	 * 
	 */
	public function updateMemberById()
	{
		$dbh = parent::hook();
	
		$sql = "UPDATE pl_customers SET fullname = ?, email = ?,
				address = ?, phone = ?, district_id = ?,
				shipping_id = ? WHERE ID = ?";
			
		$data = array(
				$this->fullname, $this->email, $this->address, 
				$this->phone, $this->district_id,
				$this->shipping_id, $this->ID);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> rowCount() == 1;
	}
	
	/**
	 * @method updatePasswordById
	 */
	public function updatePasswordById()
	{
		
		$dbh = parent::hook();
		
		$hash_password = shieldPass($this->password, $this->ID);
		
		$sql = "UPDATE pl_customers SET password = ? WHERE ID = ?";
		
		$data = array( $hash_password, $this->ID);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> rowCount() == 1;
	}
	
	/**
	 * @method deleteCustomer
	 */
	public function deleteCustomer()
	{
		//apakah objeck admin yang akan dihapus memiliki ID ?
		if ( is_null($this->ID)) trigger_error( "Admin::deleteAdmin: Attempt to delete an Admin object that does not have it's ID property Sset.", E_USER_ERROR );
		
		//hapus admin
		$dbh = parent::hook();
		
		$sql = "DELETE FROM pl_customers WHERE ID = ?";
		
		$data = array($this->ID);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$dbh = null;
	}
	
	/**
	 * Registrasi Member
	 * Insert a new record 
	 * to pl_customers table 
	 * as Member
	 * 
	 * @method registerMember
	 * @return string
	 */
	public function registerMember()
	{
		global $option;
		
		$dbh = parent::hook();
		
		$sql = "INSERT INTO pl_customers( fullname, email,
				password, customer_type, customer_session, 
				date_registered, time_registered)VALUES(?, ?, ?, ?, ?, ?, ?)";
		
		$customerSesi = generateSessionKey($this->customer_session);
		
		$data = array($this->fullname, $this->email, 
				$this->password, $this->customer_type, 
				$customerSesi, $this->date_registered, 
				$this->time_registered);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$id_member = $dbh -> lastId();
		
		if (isset($id_member)) {
			
			$stmt = $dbh -> query("UPDATE pl_customers SET password = '".shieldPass($this->password, $id_member)."' WHERE ID = '$id_member' ");
			
			$dataMember = self::fetchMemberData($this->email);
			
			$ID = $dataMember['ID'];
			$nama_member = $dataMember['fullname'];
			$email_member = $dataMember['email'];
			$alamat_member = $dataMember['address'];
			
			//Mengambil data pemilik toko
			$metaowner = '';
				
			$data_owner = $option -> getOptions();
				
			$metaowner = $data_owner['results'];
				
			foreach ( $metaowner as $owner )
			{
				$owner_email = $owner -> getOwnerEmail();
				$no_rekening = $owner -> getNoRekening();
				$nomor_telp = $owner -> getNoTelpon();
				$pinBB = $owner -> getPinBB();
				$namaToko = $owner -> getSite_Name();
			}
				
			$pesan = "<html><body>
			<p>Terima kasih telah melakukan pendaftaran Member di toko online $namaToko<br /><br />
			Data Pendaftaran Member anda adalah sebagai berikut: <br /><br />
			ID Member: $ID<br />
			Nama: $nama_member<br />
			Email: $email_member <br />
			Password: $this->password <br /><hr />
				
			<b>Simpanlah data pendaftaran member ini.</b><br /><br />
			<b>Hormat kami,</b><br /><br />
			<b>$namaToko</b></p><br />
			</body>
			</html>";
				
			$subjek = "Pendaftaran Member di Toko $namaToko";
				
			$toMember = safeEmail($email_member);
				
			// Kirim Email dalam format HTML -- ke member
			$kirim_email = new Mailer();
			$kirim_email -> setSendText(false);
			$kirim_email -> setSendTo($toMember);
			$kirim_email -> setFrom($namaToko);
			$kirim_email -> setSubject($subjek);
			$kirim_email -> setHTMLBody($pesan);
			
			if ( $kirim_email -> send())
			{
				
				$message_to_owner = "Hai, Admin! anda mendapatkan member baru : <br>
				ID Member: $ID<br />
				Nama: $nama_member <br />
				Email: $email_member <br /
				Alamat: $alamat_member <br /><hr />";
					
				$data_notifikasi = array(
							
						'notify_title' => "newMember",
						'date_submited' => date("Y-m-d"),
						'time_submited' => date("H:i:s"),
						'content' => preventInject($message_to_owner));
					
				pushNotification($data_notifikasi);
				
			}
		}
		
	}
	
	/**
	 * A method to validate 
	 * an email and password
	 * 
	 * @method validateCustomer
	 * @param string $email
	 * @param string $password
	 */
	public function validateCustomer()
	{
		$dbh = parent::hook();

		// get customer's ID
		$id_customer = self::getCustomerId_ByEmail($this->email);
		
		$hash_password = shieldPass($this->password, $id_customer -> ID);
		
		$sql = "SELECT ID, fullname,  email, password, address, 
				phone, district_id, shipping_id, 
				customer_type, customer_session, 
				date_registered, time_registered
				FROM pl_customers WHERE email = :email AND password = :password
				AND customer_type = 'member' ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":email", $this->email, PDO::PARAM_STR);
		$sth -> bindValue(":password", $hash_password, PDO::PARAM_STR);
		
		try {
			
			$sth -> execute();
			
			$row = $sth -> fetch();
			
			if ($row) return new Customer($row);
		
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
		
	}
	
	/**
	 * @method updateMemberSession
	 * @param string $sessionKey
	 * @param string $email
	 */
	public function updateMemberSession($sessionKey, $email)
	{
		$dbh = parent::hook();
		
		$sql = "UPDATE pl_customers SET customer_session = ? WHERE email = ? ";
		
		$generateKey = generateSessionKey($sessionKey);
		
		$data = array($generateKey, $email);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$data_kustomer = $this->fetchMemberData($email);
		
		if ( isset($_SESSION['memberLoggedIn']) && $_SESSION['memberLoggedIn'] == true)
		{
			$_SESSION['member_id'] = $data_kustomer['ID'];
			$_SESSION['member_fullname'] = $data_kustomer['fullname'];
			$_SESSION['member_email'] = $data_kustomer['email'];
			$_SESSION['member_type'] = $data_kustomer['customer_type'];
			$_SESSION['member_session'] = $data_kustomer['customer_session'];
			
			header('Location: '. PL_DIR );
			
			exit();
			
		}
		
	}
	
	/**
	 * @method signOutMember
	 */
	public function signOutMember()
	{
		
		if (self::isMemberLoggedIn())
		{
	
			unset($_SESSION['member_id']);
			unset($_SESSION['member_fullname']);
			unset($_SESSION['member_email']);
			unset($_SESSION['member_pass']);
			unset($_SESSION['member_type']);
			unset($_SESSION['member_session']);
				
		}
	
	}
	
	/**
	 * @method recoverPassword
	 * @param string $password
	 * @param string $token
	 */
	public function recoverPassword($email, $password, $token)
	{
		$dbh = parent::hook();
		
		$id_customer = self::getCustomerId_ByEmail($email);
		
		$sql = "UPDATE pl_customers SET password = ?,
				customer_resetComplete = 'Yes' WHERE customer_resetKey = ?";
		
		$hash_password = shieldPass($password, $id_customer -> ID);
		
		$data = array($hash_password, $token);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> rowCount() == 1;
		
	}

	/**
	 * @method emailExists
	 * cek keberadaan email
	 * @param string $email
	 * @return boolean
	 */
	public  function emailExists($email)
	{
		$dbh = parent::hook();

		$sql = "SELECT `email` FROM pl_customers 
				WHERE `email` = ? AND customer_type='member' ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $email);

		try {
			
			$sth -> execute();
			
			if ($sth -> rowCount() > 0 ) {
				
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
	 * @method fetchMemberData
	 * @param string $email
	 * @return Customer
	 */
	private function fetchMemberData($email)
	{
		$dbh = parent::hook();
	
		$sql = "SELECT ID, fullname, email, password,
				address, phone, district_id, shipping_id,
				customer_type, customer_session 
				FROM pl_customers WHERE email = ? AND customer_type = 'member'";
	
		$data = array($email);
	
		$sth = $dbh -> pstate($sql, $data);
	
		return $sth -> fetch();
	
	}
	
	/**
	 * checking customer sesi
	 * 
	 * @method isMemberLoggedIn
	 * @return boolean
	 */
	public static function isMemberLoggedIn()
	{
	    if (isset($_SESSION['memberLoggedIn']) 
	        && $_SESSION['memberLoggedIn'] == true)
	    {
	    	return true;
	    }
	    
	}
	
	/**
	 * this method is used 
	 * for member transaction
	 * in class order
	 * 
	 * @method getCustomer
	 * @param string $email
	 * @param string $password
	 * @return customer
	 */
	public static function getCustomer($email, $password)
	{
		$dbh = parent::hook();

		$id_customer = self::getCustomerId_ByEmail($email);
		
		$sql = "SELECT ID, fullname, email, password, address, phone, 
				district_id, shipping_id, customer_type, 
				customer_session, date_registered, time_registered
				FROM pl_customers WHERE email = ? AND password = ? 
				AND customer_type = 'member' ";

		$valid_pass = shieldPass($password, $id_customer -> ID);

		$data = array($email, $valid_pass);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetchObject();
	}
	
	/**
	 * Retrieve All records
	 * from pl_customers table
	 * 
	 * @method getListCustomers
	 * @param integer $position
	 * @param integer $limit
	 * @return Customer[][]|number[]
	 */
	public static function getListCustomers($position, $limit)
	{
		$dbh = parent::hook();
		
		$sql = "SELECT ID, fullname, email, password, address, phone,
				district_id, shipping_id, customer_type, customer_resetKey,
				customer_resetComplete, customer_session, 
				date_registered, time_registered
				FROM pl_customers ORDER BY fullname LIMIT :position, :limit";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);
		
		$customers = array();
		
		try {
			
			$sth -> execute();
			
			foreach ( $sth ->fetchAll() as $results )
			{
				$customers[] = new Customer($results);
			}
			
			$numbers = "SELECT ID FROM pl_customers";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			
			$dbh = null;
			
			return (array("results" => $customers, "totalRows" => $totalRows));
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
			
		}
	}
	
	/**
	 * @method getCustomerBySession
	 * @param string $sessionId
	 * @return Customer
	 */
	public static function getCustomerBySession($sessionId)
	{
		$dbh = parent::hook();
		
		$sql = "SELECT ID, fullname, email, password, address, 
				phone, district_id, shipping_id, 
				customer_type, customer_resetKey, 
				customer_resetComplete, customer_session, 
				date_registered, time_registered
				FROM pl_customers 
				WHERE customer_session = ?";
		
		$data = array( $sessionId );
		
		$sth = $dbh -> pstate( $sql, $data );
		
		$row = $sth -> fetch();
		
		if ( $row ) return new Customer($row);
		
	}
	
	/**
	 * @method getCustomerById
	 * @param integer $customerId
	 * @return Customer
	 */
	public static function getCustomerById($customerId)
	{
		
		$dbh = parent::hook();
		
		$sql = "SELECT ID, fullname, email, password, 
				address, phone, district_id, shipping_id, 
				customer_type, customer_resetKey, 
				customer_resetComplete, customer_session, 
				date_registered, time_registered
				FROM pl_customers 
				WHERE ID = ?";
		
		$data = array($customerId);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();
		
		if ( $row ) return new Customer($row);
	}
	
	/**
	 * @method getCustomerId_ByEmail
	 * @param string $email
	 * @return integer ID
	 */
	public static function getCustomerId_ByEmail($email)
	{
		$dbh = parent::hook();
		
		$sql = "SELECT ID FROM pl_customers WHERE email = ?";
		
		$data = array($email);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> fetchObject();
	}
	
	/**
	 * @method countMembers
	 * @return number
	 */
	public static function countMembers()
	{
		$dbh = parent::hook();
	
		$sql = "SELECT ID, fullname, email, password, address,
				phone, district_id, shipping_id,
				customer_type, customer_resetKey,
				customer_resetComplete, customer_session,
				date_registered, time_registered
				FROM pl_customers WHERE customer_type = 'member' ";
	
		$sth = $dbh -> query($sql);
	
		return $sth -> rowCount();
	
	}
	
	/**
	 * @method memberNotifications
	 * @return Customer[][]
	 */
	public static function memberNotifications()
	{
		$dbh = parent::hook();
	
		$sql = "SELECT ID, fullname, email, password, address,
				phone, district_id, shipping_id,
				customer_type, customer_resetKey,
				customer_resetComplete, customer_session, date_registered,
				time_registered FROM pl_customers
				WHERE customer_type = 'member'
				ORDER BY time_registered DESC LIMIT 5";
	
		$members = array();
	
		try {
				
			$sth = $dbh -> query($sql);
				
			foreach ( $sth -> fetchAll() as $results)
			{
				$members[] = new Customer($results);
	
			}
				
			return (array("results" => $members));
				
		} catch (PDOException $e) {
				
			LogError::newMessage($e);
			LogError::customErrorMessage();
				
		}
	}
	
	/**
	 * @method findById
	 * @param integer $customerId
	 * @param string $sessionId
	 */
	public static function findById($customerId, $sessionId)
	{
		$dbh = parent::hook();
		
		$sql = "SELECT ID, fullname, email, password, address, 
				phone, district_id, shipping_id, customer_type, 
				customer_resetKey, customer_resetComplete, 
				customer_session, date_registered, time_registered 
				FROM pl_customers WHERE ID = ? AND customer_session = ? ";
		
		$data = array( $customerId, $sessionId);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> fetchObject();
		
	}
	
}