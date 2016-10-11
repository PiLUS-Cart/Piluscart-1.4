<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Notifikasi
 * untuk merekam notifikasi
 * dari aktifitas inbox, order, member baru
 * dan mengirimkan email pemberitahuan
 * ke pemilik toko online
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Notification 
{

	/**
	 * notify's ID
	 * @var
	 */
	protected $notify_id;

	/**
	 * sender's name
	 * @var string
	 */
	protected $sender;
	
	/**
	 * notify's Title
	 * @var string
	 */
	protected $notify_title;

	/**
	 * date submission
	 * @var date_submited
	 */
	protected $date_submited;

	/**
	 * time submission
	 * @var string
	 */
	protected $time_submited;

	/**
	 * notify's content
	 * @var string
	 */
	protected $content;

	/**
	 * notify's status
	 * @var integer
	 */
	protected $status;


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
	 * get notication's ID
	 * @return integer
	 */
	public function getNotifyId()
	{
		return $this->notify_id;
	}

	/**
	 * get notificaton's title
	 * @return string
	 */
	public function getNotifyTitle()
	{
		return $this->notify_title;
	}

	/**
	 * get notification's date submited
	 * @return date_submited
	 */
	public function getDateSubmited()
	{
		return $this->date_submited;
	}

	/**
	 * get notification's time submited
	 * @return string
	 */
	public function getTimeSubmited()
	{
		return $this->time_submited;
	}

	/**
	 * get notification's content
	 * @return string
	 */
	public function getNotifyContent()
	{
		return $this->content;
	}

	/**
	 * get notification's status
	 * @return number
	 */
	public function getNotifyStatus()
	{
		return $this->status;
	}

	/**
	 * @method generalNotification
	 * insert a new record for
	 * general notification
	 * new messages
	 * to table pl_notification
	 */
	public function generalNotification()
	{
		$dbh = new Pldb;

		global $option;

		$sql = "INSERT INTO pl_notification(notify_title, date_submited, time_submited, content)
				VALUES(?, ?, ?, ?)";

		$data = array($this->notify_title, $this->date_submited, $this->time_submited, $this->content);

		$sth = $dbh -> pstate($sql, $data);
			
		$jml_notifikasi = $sth -> rowCount();

		$notify_id = $dbh -> lastId();

		if (isset($notify_id) && $jml_notifikasi > 0) {
			$data_option = $option -> getOptions();
				
			$meta_options = $data_option['results'];
				
			foreach ( $meta_options as $meta_option )
			{
				$ownerEmail = $meta_option -> getOwnerEmail();
				$shop_name = $meta_option -> getSite_Name();
			}
			
			$dari = htmlentities($shop_name);
			$kepada = safeEmail($ownerEmail);
			$subyek = "Cek pesan baru dari pengunjung toko online anda";
			$pesan = "<html>
					  <body>
					  <p>pemberitahuan ini dikirim karena pengunjung toko online anda mengirim pesan via form kontak.<br />
					  Silahkan cek pesan tersebut dengan login melalui tautan berikut ini, kemudian pada dashboard atau halaman utama pilihlan baca pesan.<br />
					  <a href=".PL_DIR."cabin/login.php>Cek Pesan</a><br /><br />
					  Terima kasih,</p><br />
					  <b>Tim Pengembang Pilus Open Source E-commerce Software</b>
					  </body>
					</html>";
				
			$kirim_pesan = new Mailer();
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($dari);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);
				
			// kirim email pemberitahuan ke pemilik toko online
			$kirim_pesan -> send();
			
		}

	}

	/**
	 * @method orderNotification
	 */
	public function orderNotification()
	{
		$dbh = new Pldb;

		global $option;

		$sql = "INSERT INTO pl_notification(notify_title, date_submited, time_submited, content)
				VALUES(?, ?, ?, ?)";

		$data = array($this->notify_title, $this->date_submited, $this->time_submited, $this->content);

		$sth = $dbh -> pstate($sql, $data);
			
		$jml_notifikasi = $sth -> rowCount();

		$notify_id = $dbh -> lastId();

		if (isset($notify_id) && $jml_notifikasi > 0) {
			
			$data_option = $option -> getOptions();

			$meta_options = $data_option['results'];

			foreach ( $meta_options as $meta_option )
			{
				$ownerEmail = $meta_option -> getOwnerEmail();
				$shop_name = $meta_option -> getSite_Name();
			}

			$dari = htmlentities($shop_name);
			$kepada = safeEmail($ownerEmail);
			$subyek = "Order Baru, Segera cek detail order";
			$pesan = "<html>
					<body>
					Pemberitahuan ini dikirim karena anda mendapatkan Order Baru melalui toko online anda.<br />
					Silahkan cek detail order baru tersebut dengan login melalui tautan berikut ini: <br /> 
					<a href=".PL_DIR."cabin/login.php>Cek Order</a><br /><br />
					kemudian pilihlah <b>menu Order</b> pada menu bagian kiri halaman administrator web.<br />
					Terima kasih,<br />
					<b>Tim Pengembang Pilus Open Source E-commerce Software</b>
					</body>
					</html>";

			$kirim_pesan = new Mailer();
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($dari);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);

			//kirim email pemberitahuan ke pemilik toko online
			$kirim_pesan -> send();
			
		}
	}
	
	/**
	 * @method regMember_Notification
	 */
	public function regMember_Notification()
	{
		$dbh = new Pldb;
		
		global $option;
		
		$sql = "INSERT INTO pl_notification(notify_title, date_submited, time_submited, content)
				VALUES(?, ?, ?, ?)";
		
		$data = array($this->notify_title, $this->date_submited, $this->time_submited, $this->content);
		
		$sth = $dbh -> pstate($sql, $data);
			
		$jml_notifikasi = $sth -> rowCount();
		
		$notify_id = $dbh -> lastId();
		
		if (isset($notify_id) && $jml_notifikasi > 0) {
			
			$data_option = $option -> getOptions();
		
			$meta_options = $data_option['results'];
		
			foreach ( $meta_options as $meta_option )
			{
				$ownerEmail = $meta_option -> getOwnerEmail();
				$shop_name = $meta_option -> getSite_Name();
			}
		
			$dari = htmlentities($shop_name);
			$kepada = safeEmail($ownerEmail);
			$subyek = "Member Baru--Segera cek member baru anda!";
			$pesan = "<html>
					<body>
					Pemberitahuan ini dikirim karena seseorang mendaftar menjadi member baru.<br />
					Silahkan cek siapa member baru tersebut dengan login melalui tautan berikut ini: <br />
					<a href=".PL_DIR."cabin/login.php>Cek Member</a><br /><br />
					kemudian pilihlah <b>menu pengguna</b> --> kustomer, pada menu bagian kiri halaman administrator web.<br />
					Terima kasih,<br />
					<b>Tim Pengembang Pilus Open Source E-commerce Software</b>
					</body>
					</html>";
		
			$kirim_pesan = new Mailer();
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($dari);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);
		
			//kirim email pemberitahuan ke pemilik toko online
			$kirim_pesan -> send();
		}
		
	}
	
	/**
	 * @method testimonyNotification
	 */
	public function testimonyNotification()
	{
		$dbh = new Pldb;
		
		global $option;
		
		$sql = "INSERT INTO pl_notification(notify_title, date_submited, time_submited, content)
				VALUES(?, ?, ?, ?)";
		
		$data = array($this->notify_title, $this->date_submited, $this->time_submited, $this->content);
		
		$sth = $dbh -> pstate($sql, $data);
			
		$jml_notifikasi = $sth -> rowCount();
		
		$notify_id = $dbh -> lastId();
		
		if (isset($notify_id) && $jml_notifikasi > 0) {
			
			$data_option = $option -> getOptions();
		
			$meta_options = $data_option['results'];
		
			foreach ( $meta_options as $meta_option )
			{
				$ownerEmail = $meta_option -> getOwnerEmail();
				$shop_name = $meta_option -> getSite_Name();
			}
		
			$dari = htmlentities($shop_name);
			$kepada = safeEmail($ownerEmail);
			$subyek = "Testimoni Member";
			$pesan = "<html>
					<body>
					Pemberitahuan ini dikirim karena member toko online anda mengirimkan testimoni.<br />
					Silahkan cek testimoni dari member anda melalui tautan berikut ini: <br />
					<a href=".PL_DIR."cabin/login.php>Cek Testimoni</a><br /><br />
					kemudian pilihlah <b> menu modul --> Testimoni </b> , pada menu bagian kiri halaman administrator web.<br />
					Terima kasih,<br />
					<b>Tim Pengembang Pilus Open Source E-commerce Software</b>
					</body>
					</html>";
		
			$kirim_pesan = new Mailer();
			$kirim_pesan -> setSendText(false);
			$kirim_pesan -> setSendTo($kepada);
			$kirim_pesan -> setFrom($dari);
			$kirim_pesan -> setSubject($subyek);
			$kirim_pesan -> setHTMLBody($pesan);
		
			//kirim email pemberitahuan ke pemilik toko online
			$kirim_pesan -> send();
		}
	}
	
	/**
	 * @method commentNotification
	 */
	public function commentNotification()
	{
		$dbh = new Pldb;
	
		global $option;
	
		$sql = "INSERT INTO pl_notification(notify_title, date_submited, time_submited, content)
				VALUES(?, ?, ?, ?)";
	
		$data = array($this->notify_title, $this->date_submited, $this->time_submited, $this->content);
	
		$sth = $dbh -> pstate($sql, $data);
			
		$jml_notifikasi = $sth -> rowCount();
	
		$notify_id = $dbh -> lastId();

	}
	
	/**
	 * Menghitung jumlah notifikasi
	 * 
	 * @method countNotification
	 * @return number
	 */
	public static function countNotification()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT notify_id, notify_title, date_submited, time_submited, 
				content, status FROM pl_notification WHERE status='0' ";
		
		$sth = $dbh -> query($sql);
		
		return $sth -> rowCount();
		
	}
	
	/**
	 * @method updateStatus_Notification
	 */
	public function updateStatus_Notification()
	{
		$dbh = new Pldb;
		
		$sql = "UPDATE pl_notification SET status = ? WHERE notify_id = ?";
		
		$data = array( $this -> status, $this -> notify_id);
		
		$sth = $dbh -> pstate($sql, $data);
		
	}
	
	/**
	 * @method deleteNotification
	 */
	public function deleteNotification()
	{
		$dbh = new Pldb;
		
		$sql = "DELETE FROM pl_notification WHERE notify_id = ?";
		
		$data = array($this->notify_id);
		
		$sth = $dbh -> pstate($sql, $data);
	}
	
	/**
	 * @method getNotifications
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function getNotifications($position, $limit)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT notify_id, notify_title, date_submited, time_submited, 
				content, status FROM pl_notification 
				ORDER BY status DESC LIMIT :position, :limit";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);
		
		try {
			
			$sth -> execute();
			$postComments = array();
			
			foreach ( $sth -> fetchAll() as $results )
			{
				$postComments[] = new Notification($results);
				
			}
			
			$numbers = "SELECT notify_id FROM pl_notification";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
			return (array("results" => $postComments, "totalRows" => $totalRows));
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
		
	}
	
	/**
	 * @method getNotification
	 * @param integer $id
	 */
	public static function getNotification($id) {
		
		$dbh = new Pldb;
		
		$sql = "SELECT notify_id, notify_title, date_submited, time_submited,
				content, status FROM pl_notification
				WHERE notify_id = :notify_id";
		
		$sth = $dbh -> prepare($sql);
		
		$sth -> bindValue(":notify_id", $id, PDO::PARAM_INT);
		
		try {
			
			$sth -> execute();
			$row = $sth -> fetch();
			
			if ($row) return new Notification($row);
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}
	
	/**
	 * @method findById
	 * @param integer $notifyId
	 */
	public static function findById($notifyId)
	{
		
		$dbh = new Pldb;
		
		$sql = "SELECT notify_id, notify_title, date_submited, time_submited, 
				content, status FROM pl_notification WHERE notify_id = ?";
		
		$data = array($notifyId);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> fetch();
	}
}