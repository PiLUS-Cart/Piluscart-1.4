<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Postcat extends Plbase
 * Mapping table post category
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Postcat extends Plbase 
{

	/**
	 * post's category
	 * @var string
	 */
	protected $postCat_name;

	/**
	 * post's slug
	 * @var string
	 */
	protected $slug;

	/**
	 * post category's description
	 * @var string
	 */
	protected $description;

	/**
	 * post category's actived
	 * @var string
	 */
	protected $actived;


	/**
	 * Inisialisasi post category dari
	 * object table pl_post_category
	 * @param string $input - array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get post category's name
	 * @return string
	 */
	public function getPostcat_Name()
	{
		return $this->postCat_name;
	}

	/**
	 * get post category's slug
	 * @return string
	 */
	public function getPostcat_Slug()
	{
		return $this->slug;
	}

	/**
	 * get post category's description
	 * @return string
	 */
	public function getPostcat_Desc()
	{
		return $this->description;
	}

	/**
	 * get post category's status
	 * @return string
	 */
	public function getPostcat_Status()
	{
		return $this->actived;
	}

	/**
	 * Method createPostcat
	 * insert a new record
	 * into post category table
	 */
	public function createPostcat()
	{
		$dbh = parent::hook();

		$sql = "INSERT INTO pl_post_category(postCat_name, slug, description, actived)
				VALUES(?, ?, ?, ?)";

		$data = array($this->postCat_name, $this->slug, $this->description, $this->actived);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method updatePostcat
	 * update an existing record
	 * into post category table
	 */
	public function updatePostcat()
	{
		$dbh  = parent::hook();

		$sql  = "UPDATE pl_post_category SET postCat_name = ?, 
				 slug = ?, description = ?, actived  = ?
				 WHERE ID = ?";

		$data = array($this->postCat_name, $this->slug, $this->description, $this->actived, $this->ID);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method deletePostcat
	 * deleting an existing record
	 * from post category table
	 */
	public function deletePostcat()
	{
		$dbh = parent::hook();

		$sql = "DELETE FROM pl_post_category WHERE ID = ?";

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getPost_Categories
	 * retrieve all record from
	 * post category table
	 * @param integer $position
	 * @param integer $limit
	 */
	public static function getPost_Categories($position, $limit)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, postCat_name, slug, description, actived
				FROM pl_post_category ORDER BY postCat_name
				LIMIT :position, :limit";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
			while ($result = $sth -> fetch()) {

				$postcats = new Postcat($result);
				$list[] = $postcats;
			}

			$numbers = "SELECT ID FROM pl_post_category";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;

			return (array("results" => $list, "totalRows" => $totalRows ));

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');

		}

	}


	/**
	 * Method getPost_Category
	 * @param integer $id
	 * @return Postcat
	 */
	public static function getPost_Category($id)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, postCat_name, slug, description, actived
				FROM pl_post_category WHERE ID = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch(PDO::FETCH_ASSOC);

		if ($row) return new Postcat($row);

	}

	/**
	 * this method is used
	 * for post categories dropdown
	 * 
	 * @method setPost_Categories
	 * @return multitype:Postcat
	 */
	public static function setPost_Categories()
	{

		$dbh = parent::hook();

		$sql = 'SELECT ID, postCat_name, slug, description, actived
				FROM pl_post_category WHERE actived="Y" 
				ORDER BY postCat_name';

		$list = array();
		
		try {

			$sth = $dbh -> query($sql);
			
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$postCategories = new Postcat($result);
				$list[] = $postCategories;
					
			}

			return ($list);

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');

		}

	}

	/**
	 * @method setPostcat_Dropdown
	 * @param string $selected
	 */
	public static function setPostcat_Dropdown($selected = '')
	{

		$option_selected = '';

		if ($selected) {

			$option_selected = 'selected="selected"';
		}

		// get post category
		$postCats = self::setPost_Categories();

		$html  = array();

		$html[] = '<label>*Pilih Kategori</label>';
		$html[] = '<select class="form-control" name="catID"  >';

		foreach ( $postCats as $pc => $postCat)
		{
			if ((int)$selected  == (int)$postCat-> getId())
			{
				$option_selected='selected="selected"';
			}

			$html[] =  '<option value="' . $postCat-> getId() . '"' . $option_selected . '>' . $postCat-> getPostcat_Name() . '</option>';

			// clear out the selected option flag
			$option_selected = '';
		}

		if ( empty($selected) OR (int)$postCat -> getId() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Kategori --<option>';
		}

		$html[] = '</select>';

		return implode("\n", $html);

	}


	/**
	 * Method findById
	 * @param integer $postCat_Id
	 * @return mixed
	 */
	public static function findById($postCat_Id)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, postCat_name, slug, description, actived
				FROM pl_post_category WHERE ID = ?";

		$data = array($postCat_Id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}

}