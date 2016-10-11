<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas PostComment
 * Mapping table pl_post_comment
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class PostComment 
{

	/**
	 * post's comment id
	 * @var integer
	 */
	protected $comment_id;

	/**
	 * post's ID
	 * @var integer
	 */
	protected $post_id;

	/**
	 * post's title
	 * @var string
	 */
	protected $post_title;

	/**
	 * commentator's fullname
	 * @var string
	 */
	protected $fullname;

	/**
	 * URL
	 * @var string
	 */
	protected $url;

	/**
	 * comment's content
	 * @var string
	 */
	protected $comment;

	/**
	 * comment's date created
	 * @var string
	 */
	protected $date_created;

	/**
	 * comment's time creatd
	 * @var string
	 */
	protected $time_created;

	/**
	 * comment's status
	 * @var string
	 */
	protected $actived;

	/**
	 * IP
	 * @var string
	 */
	protected $ip;


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
	 * get comment's id
	 * @return number
	 */
	public function getComment_Id()
	{
		return $this -> comment_id;
	}

	/**
	 * get post's id
	 * @return number
	 */
	public function getPost_Id()
	{
		return $this->post_id;
	}

	/**
	 * get post's title
	 * @return string
	 */
	public function getPost_Title()
	{
		return $this->post_title;
	}

	/**
	 * get fullname
	 * @return string
	 */
	public function getFullname()
	{
		return $this->fullname;
	}

	/**
	 * get url
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * get post's comment
	 * @return string
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * get comment's date created
	 * @return string
	 */
	public function getComment_dateCreated()
	{
		return $this->date_created;
	}

	/**
	 * get comment's time created
	 * @return string
	 */
	public function getComment_timeCreated()
	{
		return $this->time_created;
	}

	/**
	 * get comment's status
	 * @return string
	 */
	public function getComment_Status()
	{
		return $this->actived;
	}

	/**
	 * get ip
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * Method createComment
	 * insert record to table
	 * pl_post_comment
	 */
	public function createComment()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_post_comment(post_id,
				fullname, url, comment,
				date_created, time_created, ip)
				VALUES(?, ?, ?, ?, ?, ?, ?)";

		$data = array($this->post_id,
				$this->fullname,
				$this->url,
				$this->comment,
				$this->date_created,
				$this->time_created,
				$this->ip);

		$sth = $dbh -> pstate($sql, $data);

		$id_comment = $dbh -> lastId();
		
		if ( $id_comment ) {
			
			$intoReply = "INSERT INTO pl_comment_reply(comment_id, actived)VALUES('$id_comment', 'N')";
			
			$sth = $dbh -> query($intoReply);
			
		}
		
	}

	/**
	 * Method UpdateComment
	 * update an existing record from
	 * table pl_post_comment
	 */
	public function updateComment()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_post_comment SET fullname = ?, url = ?,
				comment = ?, actived = ?
				WHERE comment_id = ? AND post_id = ?";

		$data = array(
				$this -> fullname,
				$this->url,
				$this -> comment,
				$this->actived,
				$this->comment_id,
				$this->post_id
		);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method deleteComment
	 * delete record from
	 * table pl_post_comment
	 */
	public function deleteComment()
	{
		$dbh  = new Pldb;

		$sql = "DELETE FROM pl_post_comment WHERE comment_id = ?";

		$data = array($this->comment_id);

		$sth = $dbh -> pstate($sql, $data);
	}
	
	/**
	 * @method totalComment_ByPostId
	 * @param integer $postId
	 * @return numbers
	 */
	public function totalComment_ByPostId($postId)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT comment_id FROM pl_post_comment 
		       WHERE post_id = '$postId' AND actived='Y'";
		
		$sth = $dbh -> query($sql);
		
		return $sth -> rowCount();
		
	}
	
	/**
	 * Method getListComments
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:PostComment
	 */

	public static function getListComments($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT cm.comment_id, cm.post_id, cm.fullname, cm.url, 
				cm.comment, cm.date_created, cm.time_created, cm.actived,
				cm.ip, p.post_title
				FROM pl_post_comment AS cm INNER JOIN pl_post AS p
				ON cm.post_id = p.ID
				ORDER BY cm.comment_id
				DESC LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit,  PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
				
			while ($result = $sth -> fetch()) {

				$comments = new PostComment($result);
				$list[] = $comments;
			}
				
			$numbers = "SELECT comment_id FROM pl_post_comment";
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
	 * retrieve detail comment
	 * 
	 * @method getCommentById
	 * @param integer $commentId
	 * @return PostComment
	 */
	public static function getCommentById($commentId)
	{
		$dbh = new Pldb;

		$sql = "SELECT comment_id, post_id, fullname, url, 
				comment, date_created, time_created, 
				actived, ip FROM pl_post_comment 
				WHERE comment_id = :comment_id ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":comment_id", $commentId, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
			$row = $sth -> fetch();
				
			if ($row) return new PostComment($row);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * hitung jumlah komentar tulisan
	 * 
	 * @method countMessages
	 * @return number
	 */
	public static function countComments()
	{
		$dbh = new Pldb;

		$sql = "SELECT comment_id,
				post_id, fullname, url, comment,
				date_created, time_created, actived, ip
				FROM pl_post_comment";

		$sth = $dbh -> query($sql);

		return $sth -> rowCount();
	}

	/**
	 * @method commentNotifications
	 * @return PostComment[][]
	 */
	public static function commentNotifications()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT comment_id,
				post_id, fullname, url, comment,
				date_created, time_created, actived, ip
				FROM pl_post_comment ORDER BY time_created DESC LIMIT 5";
		
		$comments = array();
		
		try {
			
			$sth = $dbh -> query($sql);
			
			foreach ( $sth -> fetchAll() as $results )
			{
				$comments[] = new PostComment($results);
			}
			
			return (array("results" => $comments));
			
		} catch (PDOException $e) {
			
			LogError::newMessage($e);
			LogError::customErrorMessage();
		}
	}
	
	
	/**
	 * Method findById
	 * @param integer $commentId
	 * @return mixed
	 */
	public static function findById($commentId)
	{
		$dbh = new Pldb;

		$sql = "SELECT comment_id,
				post_id, fullname, url, comment,
				date_created, time_created, actived, ip
				FROM pl_post_comment 
				WHERE comment_id = ? ";

		$data = array($commentId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}

}