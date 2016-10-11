<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Postcat extends Plbase
 * Mapping table post image
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Postimg extends Plbase 
{

	/**
	 * image's filename
	 * @var assoc
	 */
	protected $filename = array();

	/**
	 * image's caption
	 * @var string
	*/
	protected $caption;

	/**
	 * image's slug
	 * @var string
	 */
	protected $slug;

	/**
	 * Inisialisasi post image
	 * dari object table pl_post_img
	 * @param string $input
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get Image's filename
	 * @return assoc
	 */
	public function getImage_Filename()
	{
		return $this->filename;
	}

	/**
	 * get Image's caption
	 * @return string
	 */
	public function getImage_Caption()
	{
		return $this->caption;
	}

	/**
	 * get Image's slug
	 * @return string
	 */
	public function getImage_Slug()
	{
		return $this->slug;
	}

	/**
	 * Method addImage
	 * insert new record
	 * @return string
	 */
	public function addImage()
	{
		$dbh = parent::hook();

		$sql = 'INSERT INTO pl_post_img(filename, caption, slug)VALUES(?, ?, ?)';

		$data = array($this->filename, $this->caption, $this->slug);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> rowCount();

		$imageId = $dbh -> lastId();

		return (array("hitung" => $row, "imageId" => $imageId));
	}

	/**
	 * Method updateImage
	 * update an existing image record
	 */
	public function updateImage()
	{
		$dbh = parent::hook();

		//apabila gambar diganti
		if ($this->getImage_Filename())
		{
				
			$sql = 'UPDATE pl_post_img SET filename = ?, caption = ?, slug = ? WHERE ID = ?';
				
			$data = array($this->filename, $this->caption, $this->slug, $this->ID);
				
		}
		else
		{
			$sql = 'UPDATE pl_post_img SET caption = ?, slug = ? WHERE ID = ?';

			$data = array($this->caption, $this->slug, $this->ID);

		}

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method deleteImage
	 * deleting an existing record
	 */
	public function deleteImage()
	{

		$dbh = parent::hook();

		$sql = 'DELETE FROM pl_post_img WHERE ID = ?';

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getImages
	 * retrieving all existing record
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:Postimg  number
	 */
	public static function getImages($position, $limit)
	{
		$dbh = parent::hook();

		$sql = 'SELECT ID, filename, caption, slug FROM pl_post_img ORDER BY ID LIMIT :position, :limit';

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(':position', $position, PDO::PARAM_INT);
		$sth -> bindValue(':limit', $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$images = array();
				
			while ($result = $sth -> fetch()) {

				$pictures = new Postimg($result);
				$images[] = $pictures;
			}
				
			$numbers = 'SELECT ID FROM pl_post_img';
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh = null;
				
			return (array("results" => $images, "totalRows" => $totalRows ));
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method getImage
	 * retrieving record
	 * based on their ID
	 * @param integer $id
	 * @return Postimg
	 */
	public static function getImage($id)
	{
		$dbh = parent::hook();

		$sql = 'SELECT ID, filename, caption, slug
				FROM pl_post_img WHERE ID = ?';

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch();

		if ($row) return new Postimg($row);

	}

	/**
	 * Method setPost_Images
	 * @return multitype:Postimg
	 */
	public static function setPost_Images()
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, filename, caption, slug FROM pl_post_img ORDER BY filename ";

		$sth = $dbh -> query($sql);

		$list = array();

		try {
				
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$images = new Postimg($result);
				$list[] = $images;
			}
				
			return ($list);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
	}

	/**
	 * Method setPostImg_Dropdown
	 * @param string $selected
	 * @return string
	 */
	public static function setPostImg_Dropdown($selected = '')
	{
		// set up first option for selection if none selected

		$option_selected = '';

		if ($selected) {
			$option_selected = ' selected="selected"';
		}

		//get categories
		$images= self::setPost_Images();

		$html  = array();

		$html[] = '<label for="image_id">Pilih Gambar</label>';
		$html[] = '<select class="form-control" name="image_id" id="image_id" >';

		foreach ($images as $img => $image)
		{
				
				
			if ((int) $selected == (int) $image -> getId()) {

				$option_selected = ' selected="selected"';
			}
				
			$html[] =  '<option value="' . $image -> getId() . '"' . $option_selected . '>' . $image -> getImage_Filename() . '</option>';

			// clear out the selected option flag
			$option_selected = '';
		}

		if ( empty($selected) OR (int)$image -> getId() == 0)
		{
			$html[] = '<option value="0" selected>-- Pilih Gambar --<option>';
		}

		$html[] = '</select>';

		return implode("\n", $html);

	}

	/**
	 * Method findById
	 * @param integer $imageId
	 * @return mixed
	 */
	public static function findById($imageId)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, filename, caption, slug
				FROM pl_post_img WHERE ID = ?";

		$data = array($imageId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}
}