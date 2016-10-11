<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas ReplyComment
 * Mapping table pl_comment_reply
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class ReplyComment 
{

	/**
	 * reply's Id
	 * @var integer
	 */
	protected $reply_id;

	/**
	 * comment's Id
	 * @var integer
	 */
	protected $comment_id;

	/**
	 * comment's content
	 * @var string
	 */
	protected $comment;
	
	/**
	 * commentator's name
	 * @var string
	 */
	protected $fullname;

	/**
	 * URL comment
	 * @var string
	 */
	protected $url;

	/**
	 * admin's Id
	 * @var integer
	 */
	protected $admin_id;

	/**
	 * admin's fullname
	 * @var unknown
	 */
	protected $admin_fullname;

	/**
	 * reply's content
	 * @var string
	 */
	protected $reply;

	/**
	 * reply's date created
	 * @var string
	 */
	protected $date_created;

	/**
	 * reply's status
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
	 * get reply's Id
	 * @return number
	 */
	public function getReply_Id()
	{
		return $this->reply_id;
	}

	/**
	 * get comment's Id
	 * @return number
	 */
	public function getComment_Id()
	{
		return $this->comment_id;
	}

	/**
	 * get commentator's fullname
	 * @return string
	 */
	public function getCommentator_Fullname()
	{
		return $this->fullname;
	}

	/**
	 * get comment's url
	 * @return string
	 */
	public function getComment_Url()
	{
		return $this->url;
	}

	/**
	 * get comment's content
	 * @return string
	 */
	public function getPost_Comment()
	{
		return $this->comment;
	}

	/**
	 * get admin's Id
	 * @return number
	 */
	public function getAdminId()
	{
		return $this->admin_id;
	}
	
	/**
	 * get admin's fullname
	 * @return unknown
	 */
	public function getAdminFullname()
	{
		return $this->admin_fullname;
		
	}
	
	/**
	 * get reply
	 * @return string
	 */
	public function getReply()
	{
		return $this->reply;
	}

	/**
	 * get reply's date created
	 * @return string
	 */
	public function getReply_dateCreated()
	{
		return $this->date_created;
	}

	/**
	 * get reply's status
	 * @return string
	 */
	public function getActived()
	{
		return $this->actived;
	}

	/**
	 * Method updateReply
	 * update an existing record
	 * from table pl_comment_reply
	 */
	public function updateReply()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_comment_reply SET admin_id = ?, reply = ?, 
				date_created = ?, actived = ?
				WHERE reply_id = ? AND comment_id = ?";

		$data = array( $this->admin_id, $this->reply, $this->date_created, $this->actived, $this->reply_id, $this->comment_id );

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * @method deleteReply
	 * delete record from
	 * table pl_comment_reply
	 */
	public function deleteReply()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_comment_reply WHERE reply_id = ? ";

		$data =  array($this->reply_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * 
	 * @param integer $commentId
	 */
	public function setReplyId($commentId)
	{
		global $sanitasi;
		
		$dbh = new Pldb;
		
		$sql = "SELECT reply_id, admin_id FROM pl_comment_reply WHERE comment_id = ?";
		
		$sanitzed_id = $sanitasi -> sanitasi($commentId, 'sql' );
		
		$data = array($sanitzed_id);
		
		$sth = $dbh -> pstate($sql, $data);
		
		return $sth -> fetchObject();
		
	}
	
	/**
	 * Method findReplies
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:ReplyComment  number
	 */
	public static function findReplies($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT r.reply_id, r.comment_id, r.admin_id, r.reply,
				r.date_crated, r.actived, c.url, c.post_comment,
				a.admin_fullname
				FROM pl_comment_reply AS r
				INNER JOIN pl_post_comment AS c ON r.comment_id = c.ID
				INNER JOIN pl_admin AS a ON r.admin_id = a.ID
				ORDER BY r.comment_id
				DESC LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
				
			$list = array();
				
			while ($result = $sth -> fetch()) {

				$replies =  new ReplyComment($result);
				$list[] = $replies;
			}
				
			$numbers = "SELECT reply_id FROM pl_comment_reply";
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
	 * retrieve record from 
	 * reply table based on their Id
	 * 
	 * @method findReply
	 * @param integer $reply_id
	 * @return ReplyComment
	 */
	public static function findReply($replyId)
	{
		global $sanitasi;
		
		$dbh = new Pldb;

		$sql = "SELECT r.reply_id, r.comment_id, r.admin_id, r.reply, r.date_created, 
				r.actived, c.fullname, c.comment 
				FROM pl_comment_reply AS r 
				INNER JOIN pl_post_comment AS c ON r.comment_id = c.comment_id
				WHERE r.reply_id = ? ";
		
		$sanitized = $sanitasi -> sanitasi($replyId, 'sql');
		
		$data = array($sanitized);
		
		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();
		
		if ($row) return new ReplyComment($row);
				
	}

	
	/**
	 * Method findById
	 * @param integer $commentId
	 * @return mixed
	 */
	public static function findById($replyId)
	{
		$dbh = new Pldb;
	
		$sql = "SELECT reply_id, comment_id, admin_id, 
				reply, date_created, actived 
				FROM pl_comment_reply 
				WHERE reply_id = ? ";
	
		$data = array($replyId);
	
		$sth = $dbh -> pstate($sql, $data);
	
		return $sth -> fetch();
	
	}
}