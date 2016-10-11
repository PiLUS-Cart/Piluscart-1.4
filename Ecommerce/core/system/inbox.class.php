<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Inbox
 * Mapping table pl_inbox
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Inbox 
{

	/**
	 * inbox_id
	 * @var integer
	 */
	protected $inbox_id;

	/**
	 * e-mail
	 * @var string
	 */
	protected $email;

	/**
	 * subject
	 * @var string
	 */
	protected $subject;

	/**
	 * messages
	 * @var string
	 */
	protected $messages;

	/**
	 * message's date sent
	 * @var string
	 */
	protected $date_sent;
	
	/**
	 * message's time sent
	 * @var string
	 */
	protected $time_sent;


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
	 * get Inbox_id
	 * @return number
	 */
	public function getInbox_Id()
	{
		return $this->inbox_id;
	}

	/**
	 * get sender
	 * @return string
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * get email
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * get subject
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * get messages
	 * @return string
	 */
	public function getMessage()
	{
		return $this->messages;
	}

	/**
	 * Method getDate_Sent
	 * @return
	 */
	public function getDate_Sent()
	{
		return $this->date_sent;
	}

	public function getTime_Sent()
	{
		return $this->time_sent;
	}
	
	/**
	 * @method sentMessage
	 * insert a new record
	 * to table pl_inbox
	 */
	public function sentMessage()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_inbox(sender, email, subject, messages, date_sent, time_sent)
				VALUES(?, ?, ?, ?, ?, ?)";

		$data = array($this->sender, $this->email, $this->subject, $this->messages, $this->date_sent, $this->time_sent);

		$sth = $dbh -> pstate($sql, $data);

		return $dbh -> lastId();

	}

	/**
	 * Method readMessage
	 * retrieve message record
	 * from pl_inbox table
	 */
	public function readMessage($id)
	{
		$dbh = new Pldb;

		$sql = "SELECT inbox_id, sender, email, subject,
				messages, date_sent, time_sent
				FROM pl_inbox WHERE inbox_id = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Inbox($row);
	}

	/**
	 * Method replyMessage
	 * @param integer $id
	 */
	public function replyMessage($to, $subject, $message, $from)
	{
		
		$kepada = safeEmail($to);
	
		$balas_pesan = new Mailer();
		$balas_pesan -> setSendText(false);
		$balas_pesan -> setSendTo($kepada);
		$balas_pesan -> setFrom($from);
		$balas_pesan -> setSubject($subject);
		$balas_pesan -> setHTMLBody( $message );

		$balas_pesan -> send();
		
	}

	/**
	 * Method deleteMessage
	 * deleting an existing record
	 */
	public function deleteMessage()
	{
		$dbh  = new Pldb;

		$sql  = "DELETE FROM  pl_inbox  WHERE inbox_id = ?";

		$data = array($this->inbox_id);

		$sth  = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getMessages
	 * retrieve all record from
	 * pl_inbox table
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:Template  number
	 */
	public static function getMessages($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT inbox_id, sender, email, subject, messages, 
				date_sent, time_sent
				FROM pl_inbox ORDER BY sender LIMIT :position, :limit ";

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			
			$sth -> execute();
			$list = array();
			
			while ($row = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$message = new Inbox($row);
				$list[] = $message;
			}
				
			$numbers = "SELECT inbox_id FROM pl_inbox";
			$sth = $dbh -> query( $numbers );
			$totalRows = $sth -> rowCount();
			$dbh = null;
				
			return (array("results" => $list, "totalRows" => $totalRows ));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}

	}

	/**
	 * @method countMessage
	 * hitung jumlah pesan
	 * @return number
	 */
	public static function countMessage()
	{
		$dbh = new Pldb;

		$sql = "SELECT inbox_id, sender, email, subject, messages, 
				date_sent, time_sent
				FROM pl_inbox";

		$sth = $dbh -> query($sql);

		return $sth -> rowCount();
	}

	/**
	 * method ini berperan
	 * menyampaikan notifikasi
	 * pesan masuk
	 * di halaman administrator
	 * 
	 * @method messageNotification
	 * @return Inbox[][]
	 */
	public static function messageNotifications()
	{
		$dbh = new Pldb;
		
        $sql = "SELECT inbox_id, sender, email, subject, messages, 
        		date_sent, time_sent
				FROM pl_inbox ORDER BY time_sent DESC LIMIT 5";
        
        $messages = array();
         
        try {
        	
          $sth = $dbh -> query($sql);
        	
        	foreach ( $sth -> fetchAll() as $results )
        	{
        		$messages[] = new Inbox($results);
        	}
      
        	return (array("results" => $messages));
        	
        } catch (PDOException $e) {
        	
        	LogError::newMessage($e);
        	LogError::customErrorMessage();
        		
        }
        
	}

	/**
	 * @method findById
	 * @param integer $message_id
	 * @return mixed
	 */
	public static function findById($message_id)
	{
		
		$dbh = new Pldb;

		$sql = "SELECT inbox_id, sender, email, subject, 
				messages, date_sent, time_sent
				FROM pl_inbox WHERE inbox_id = ?";

		$data = array($message_id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
		
	}

}