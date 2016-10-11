<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Post extends Plbase
 * Mapping table pl_post
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Post extends Plbase 
{

	/**
	 * post's image
	 * @var integer
	 */
	protected $post_image;

	/**
	 * post's category
	 * @var integer
	 */
	protected $post_cat;

	/**
	 * post' categoy name
	 * @var string
	 */
	protected $postCat_name;

	/**
	 * Image filename
	 * @var assoc
	 */
	protected $filename = array();

	/**
	 * post's author
	 * @var string
	*/
	protected $post_author;

	/**
	 * admin's username
	 * @var string
	 */
	protected $admin_login;

	/**
	 * date published
	 * @var string
	 */
	protected $post_date;

	/**
	 * post's title
	 * @var string
	 */
	protected $post_title;

	/**
	 * slug
	 * url friendly for post's title
	 * @var string
	 */
	protected $post_slug;

	/**
	 * post's content
	 * @var string
	 */
	protected $post_content;

	/**
	 * post's status
	 * @var string
	 */
	protected $post_status;

	/**
	 * post's type
	 * @var string
	 */
	protected $post_type;

	/**
	 * post's comments
	 * @var string
	 */
	protected $comment_status;
	
	/**
	 * post's tag
	 * @var string
	 */
	protected $post_tag;
	
	/**
	 * tag's name
	 * @var string
	 */
	protected $tag;

	/**
	 * Inisialisasi post dari
	 * objek tabel pl_post
	 * @param string $input - array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get post image's ID
	 * @return number
	 */
	public function getPost_Image()
	{
		return $this->post_image;
	}

	/**
	 * get post's category ID
	 * @return number
	 */
	public function getPost_Cat()
	{
		return $this->post_cat;
	}

	/**
	 * get post's category name
	 * @return string
	 */
	public function getPostCat_Name()
	{
		return  $this->postCat_name;
	}

	/**
	 * get post image's filename
	 * @return assoc
	 */
	public function getPostImg_Filename()
	{
		return $this -> filename;

	}

	/**
	 * get post author
	 * @return string
	 */
	public function getPost_Author()
	{
		return $this->post_author;
	}

	/**
	 * get author's username
	 * @return string
	 */
	public function getAuthor_Username()
	{
		return $this -> admin_login;
	}

	/**
	 * get date post submited
	 * @return string
	 */
	public function getPost_Date()
	{
		return $this->post_date;
	}

	/**
	 * get post title
	 * @return string
	 */
	public function getPost_Title()
	{
		return $this->post_title;
	}

	/**
	 * get post slug
	 * @return string
	 */
	public function getPost_Slug()
	{
		return $this->post_slug;
	}

	/**
	 * get post content
	 * @return string
	 */
	public function getPost_Content()
	{
		return $this->post_content;
	}

	/**
	 * get post status
	 * @return string
	 */
	public function getPost_Status()
	{
		return $this->post_status;
	}

	/**
	 * get post type
	 * @return string
	 */
	public function getPost_Type()
	{
		return $this->post_type;
	}

	/**
	 * get Comment
	 * @return string
	 */
	public function getComment_Status()
	{
		return $this->comment_status;
	}
	
	/***
	 * get post's tag
	 * @return string
	 */
	public function getPost_Tag()
	{
		return $this->post_tag;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}
	
	/**
	 * Insert a new record 
	 * 
	 * @method createPost
	 */
	public function createPost()
	{

		$dbh = parent::hook();

		if ( !empty($this->post_tag)) {
			
			$sql = 'INSERT INTO pl_post(post_image, post_cat, post_author,
				post_date, post_title, post_slug, post_content,
				post_status, comment_status, post_tag)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
			
			$data = array(
			
					$this->post_image, $this->post_cat, $this->post_author, $this->post_date,
					$this->post_title, $this->post_slug, $this->post_content,
					$this->post_status, $this->comment_status, $this->post_tag);
				
		} else {
			
			$sql = 'INSERT INTO pl_post(post_image, post_cat, post_author,
				post_date, post_title, post_slug, post_content,
				post_status, comment_status)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
				
			$data = array(
						
					$this->post_image, $this->post_cat, $this->post_author, $this->post_date,
					$this->post_title, $this->post_slug, $this->post_content,
					$this->post_status, $this->comment_status);
			
		}

		$sth = $dbh ->pstate($sql, $data);

		$post_id = $dbh -> lastId();
		
		return $post_id;
		
	}
	
	/**
	 * Insert a new record as page
	 * to pl_post
	 * 
	 * @method createPage
	 * @param string $post_type
	 */
	public function createPage()
	{

		$dbh = parent::hook();

		$sql = 'INSERT INTO pl_post(post_image, post_author,
				post_date, post_title, post_slug, post_content,
				post_status, post_type, comment_status)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

		$data = array(

				$this->post_image, $this->post_author, $this->post_date, $this->post_title,
				$this->post_slug, $this->post_content, $this->post_status, $this->post_type, $this->comment_status
		);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> rowCount();

		$page_id = $dbh -> lastId();

		return (array("hitung" => $row, "page_id" => $page_id));

	}

	/**
	 * Method updatePost
	 * update an existing record post
	 */
	public function updatePost()
	{
		$dbh = parent::hook();

		if ( !empty($this->post_tag)) {
			
			$sql = 'UPDATE pl_post SET post_image = ?, post_cat = ?, 
					post_title = ?, post_slug = ?, post_content = ?, 
					post_status = ?, comment_status = ?, post_tag = ?
				    WHERE ID = ?';
			
			$data = array(
			
					$this->post_image, $this->post_cat, $this->post_title,
					$this->post_slug, $this->post_content, $this->post_status,
					$this->comment_status, $this->post_tag, $this->ID);
				
		} else {
			
			$sql = 'UPDATE pl_post SET post_image = ?, post_cat = ?, 
					post_title = ?, post_slug = ?, post_content = ?, 
					post_status = ?, comment_status = ?
				    WHERE ID = ?';
			
			$data = array(
			
					$this->post_image, $this->post_cat, $this->post_title,
					$this->post_slug, $this->post_content, $this->post_status,
					$this->comment_status, $this->ID);
				
		}
		

		$sth = $dbh -> pstate($sql, $data);

	}


	/**
	 * Edit an existing page
	 * from table pl_post
	 * 
	 * @method updatePage
	 * @param string $post_type
	 */
	public function updatePage()
	{
		$dbh = parent::hook();

		$sql = 'UPDATE pl_post SET post_image = ?,
				post_title = ?, post_slug = ?,
				post_content = ?, post_status = ?,
				comment_status = ? WHERE ID = ? AND post_type = ?';

		$data = array($this->post_image, $this->post_title, $this->post_slug, $this->post_content, $this->post_status, $this->comment_status, $this->ID, $this->post_type);

		$sth = $dbh -> pstate($sql, $data);
	}


	/**
	 * Method deletePost
	 */
	public function deletePost()
	{
		$dbh = parent::hook();

		$sql = 'DELETE FROM pl_post WHERE ID = ? AND post_type="blog" ';

		$data =  array($this->ID);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * @method deletePage
	 * @param string $post_type
	 */
	public function deletePage($post_type)
	{
		$dbh = parent::hook();

		$sql = 'DELETE FROM pl_post WHERE ID = ? AND post_type = ? ';

		$data = array($this->ID, $post_type);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method postStatus_Dropdown
	 * @return string
	 */
	public function postStatus_Dropdown()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->post_status) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$posts = array('publish', 'draft');

		$html  = array();

		$html[] = '<label for="post">Status Posting</label>';
		$html[] = '<select class="form-control" name="post_status">';

		foreach ($posts as $p => $post) {

			if ($this->post_status == $post) {
				$option_selected = ' selected="selected"';
			}
			// set up the option line
			$html[]  =  '<option value="' . $post . '"' . $option_selected . '>' . $post . '</option>';
			// clear out the selected option flag
			$option_selected = '';
		}

		$html[] = '</select>';

		return implode("\n", $html);
	}

	/**
	 * Method commentStatus_Dropdown
	 * @return string
	 */
	public function commentStatus_Dropdown()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->comment_status) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$comments = array('open', 'close');

		$html  = array();

		$html[] = '<label for="post">Izinkan Komentar</label>';
		$html[] = '<select class="form-control" name="comment_status">';

		foreach ($comments as $c => $comment) {

			if ($this->comment_status == $comment) {
				$option_selected = ' selected="selected"';
			}
			// set up the option line
			$html[]  =  '<option value="' . $comment . '"' . $option_selected . '>' . $comment . '</option>';
			// clear out the selected option flag
			$option_selected = '';
		}

		$html[] = '</select>';

		return implode("\n", $html);
	}

	/**
	 * Method finderPage
	 * @param string $post_type
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:Post  number
	 */
	public static function findPages($post_type, $position, $limit)
	{
		$dbh = parent::hook();

		$sql = "SELECT pg.ID, pg.post_image, pg.post_author,
		pg.post_date, pg.post_title, pg.post_slug, pg.post_content,
		pg.post_status, pg.post_type, pg.comment_status,
		i.filename, i.caption, i.slug, a.admin_login
		FROM `pl_post` AS pg
		INNER JOIN `pl_post_img` AS i ON pg.post_image = i.ID
		INNER JOIN pl_admin AS a ON pg.post_author = a.ID
		WHERE pg.post_type = ?
		ORDER BY pg.ID
		LIMIT $position, $limit";


		$data = array($post_type);

		$sth = $dbh -> pstate($sql, $data);

		$pages = array();
		foreach ( $sth -> fetchAll() as $row)
		{
			$pages[] = new Post($row);
		}

		$numbers = "SELECT ID FROM pl_post WHERE post_type = '$post_type'";
		$sth = $dbh -> query($numbers);
		$totalRows = $sth -> rowCount();
		$dbh = null;

		return (array("results" => $pages, "totalRows" => $totalRows));

	}

	/**
	 * Method findPageById
	 * @param string $post_type
	 * @param integer $pageId
	 * @return Post
	 */
	public static function findPageById($pageId, $post_type)
	{
		$dbh = parent::hook();

		$sql = 'SELECT pg.ID, pg.post_image, pg.post_author,
				pg.post_date, pg.post_title, pg.post_slug, pg.post_content,
				pg.post_status, pg.post_type, pg.comment_status,
				img.filename, img.caption, img.slug
				FROM pl_post AS pg
				INNER JOIN pl_post_img AS img ON pg.post_image = img.ID
				WHERE pg.ID = ? AND pg.post_type = ? ';

		$data = array($pageId, $post_type);

		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();

		if ($row) return new Post($row);

	}

	/**
	 * @method seekById
	 * @param integer $pageId
	 * @param string $post_type
	 */
	public static function seekById($pageId, $type)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, post_image, post_author,
				post_date, post_title, post_slug, post_content,
				post_status, post_type, comment_status
				FROM pl_post WHERE  ID = ? AND post_type = ? ";

		$data = array($pageId, $type);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}

	/**
	 * get all post's record
	 * this method is used
	 * in back store
	 * 
	 * @method findPosts
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function findPosts($position, $limit)
	{
		$dbh = parent::hook();

		$sql = "SELECT p.ID, p.post_image, p.post_cat, p.post_author,
				p.post_date, p.post_title, p.post_slug, p.post_content,
				p.post_status, p.post_type, p.comment_status,
				pc.postCat_name, a.admin_login
				FROM `pl_post` AS p
				
				INNER JOIN pl_post_category AS pc ON  p.post_cat = pc.ID
				INNER JOIN pl_admin AS a ON p.post_author = a.ID
				WHERE p.post_type = 'blog'
				ORDER BY p.ID DESC
				LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
			$posts = array();
			foreach ( $sth -> fetchAll() as $row)
			{
				$posts[] = new Post($row);
					
			}
				
			$numbers = "SELECT ID FROM pl_post WHERE post_type = 'blog'";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
				
			return (array("results" => $posts, "totalRows" => $totalRows));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}

	}

	/**
	 * get all post's record
	 * this method is used
	 * in back store
	 *
	 * @method findPosts_ByStaff
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function findPosts_ByStaff($position, $limit)
	{
		$dbh = parent::hook();
	
		$sql = "SELECT p.ID, p.post_image, p.post_cat, p.post_author,
				p.post_date, p.post_title, p.post_slug, p.post_content,
				p.post_status, p.post_type, p.comment_status,
				pc.postCat_name, a.admin_login
				FROM `pl_post` AS p
				INNER JOIN pl_post_category AS pc ON  p.post_cat = pc.ID
				INNER JOIN pl_admin AS a ON p.post_author = a.ID
				WHERE p.post_author = '$_SESSION[adminID]' 
				AND p.post_type = 'blog'
				ORDER BY p.ID DESC
				LIMIT :position, :limit";
	
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);
	
		try {
	
			$sth -> execute();
			$posts = array();
			foreach ( $sth -> fetchAll() as $row)
			{
				$posts[] = new Post($row);
					
			}
	
			$numbers = "SELECT ID FROM pl_post WHERE post_author = '$_SESSION[adminID]' 
			            AND post_type = 'blog'";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
	
			return (array("results" => $posts, "totalRows" => $totalRows));
	
		} catch (PDOException $e) {
	
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
	
		}
	
	}
	
	/**
	 * @method findPostById
	 * @param integer $postId
	 * @param string $post_type
	 * @return Post
	 */
	public static function findPostById($postId)
	{
		$dbh = parent::hook();

		$sql = "SELECT p.ID, p.post_image, p.post_cat, p.post_author,
				p.post_date, p.post_title, p.post_slug,
				p.post_content, p.post_status, p.post_type,
				p.comment_status, p.post_tag, img.filename, 
				img.caption, img.slug, pc.postCat_name
				FROM pl_post AS p
				INNER JOIN pl_post_img AS img ON p.post_image = img.ID
				INNER JOIN pl_post_category AS pc ON p.post_cat = pc.ID
				WHERE p.ID = ? AND p.post_type = 'blog'";
			
		
		//$sql = "SELECT * FROM pl_post WHERE ID = ? AND post_type = 'blog'";

		$data = array( $postId);

		$sth = $dbh -> pstate($sql, $data);
		
		$row = $sth -> fetch();

		if ( $row ) return new Post($row);
	}

	/**
	 * @method findPostByStaff
	 * @param integer $postId
	 * @param string $authorId
	 * @return Post
	 */
	public static function findPostByStaff($postId, $authorId)
	{
		$dbh = parent::hook();
	
		$sql = "SELECT p.ID, p.post_image, p.post_cat, p.post_author,
				p.post_date, p.post_title, p.post_slug,
				p.post_content, p.post_status, p.post_type,
				p.comment_status, p.post_tag, img.filename, img.caption, img.slug,
				pc.postCat_name
				FROM pl_post AS p
				INNER JOIN pl_post_img AS img ON p.post_image = img.ID
				INNER JOIN pl_post_category AS pc ON p.post_cat = pc.ID
				WHERE p.ID = ? AND p.post_author = ? 
				AND p.post_type = 'blog'";
	
		$data = array( $postId, $authorId);
	
		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();
	
		if ( $row ) return new Post($row);
	}
	
}