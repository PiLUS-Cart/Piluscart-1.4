<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Tag
 * Mapping table pl_post_tag
 *
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Tag 
{
	
	/**
	 * tag's id
	 * @var integer
	 */
	protected $tag_id;
	
	/**
	 * tag's name
	 * @var string
	 */
	protected $tag;
	
	/**
	 * tag's slug
	 * @var string
	 */
	protected $slug;
	
	/**
	 * count tag
	 * @var integer
	 */
	protected $count_tag;
	
	/**
	 * Initialize object properties
	 * @param string $input
	 */
	public function __construct($input = false)
	{
		if (is_array($input)) {
			foreach ($input as $key => $val) {
	
				$this->$key = $val;
			}
		}
	}
	
	/**
	 * @method getTagId
	 * @return number
	 */
	public function getTagId()
	{
		return $this->tag_id;
	}
	
	/**
	 * @method getTagName()
	 * @return string
	 */
	public function getTagName()
	{
		return $this->tag;
	}
	
	/**
	 * @method getTagSlug
	 * @return string
	 */
	public function getTagSlug() 
	{
		return $this->slug;
	}
	
	/**
	 * @method getTagCounted
	 * @return number
	 */
	public function getTagCounted()
	{
		return $this->count_tag;
	}
	
	/**
	 * Insert a new record
	 * into pl_post_tag
	 * 
	 * @method createTag
	 */
	public function createTag()
	{
		$dbh = new Pldb;
		
		$sql = "INSERT INTO pl_post_tag(tag, slug)VALUES(?, ?)";
		
		$data = array( $this->tag, $this->slug);
		
		$sth = $dbh -> pstate($sql, $data);
		
	}
	
	/**
	 * Update an existing
	 * record from table pl_post_tag
	 * 
	 * @method updateTag
	 */
	public function updateTag()
	{
		$dbh = new Pldb;
		
		$sql = "UPDATE pl_post_tag SET tag = ?, slug = ? 
				WHERE tag_id = ?";
		
		$data = array( $this -> tag, $this->slug, $this->tag_id);
		
		$sth = $dbh -> pstate($sql, $data);
	}
	
	/**
	 * @method updateTagCounted
	 * @param string $tag
	 */
	public function updateTagCounted($tag)
	{
		$dbh = new pldb;
	
		$jumlah = count($tag);
		for ($i=0; $i<$jumlah; $i++)
		{
			$sth = $dbh -> query("UPDATE pl_post_tag 
					SET count_tag=count_tag+1 
					WHERE slug = '$tag[$i]'");
				
		}
		
	}
	
	/**
	 * Delete an existing record
	 * from table pl_post_tag
	 * 
	 * @method deleteTag
	 */
	public function deleteTag()
	{
		$dbh = new Pldb;
		
		$sql = "DELETE FROM pl_post_tag WHERE tag_id = ?";
		
		$data = array( $this->tag_id );
		
		$sth = $dbh -> pstate($sql, $data);
	}
	
	/**
	 * @method getCheckBoxes
	 * @param string $key
	 * @param string $Label
	 * @param string $value
	 * @return string
	 */
	public function getCheckBoxes($key, $Label, $value='')
	{
		$dbh = new Pldb;
	
		$html = array();
	
		$html[] = '<label>*Label</label>';
	
		$sql = "SELECT tag_id, tag, slug, count_tag 
				FROM pl_post_tag ORDER BY tag";
	
		try {
				
			$sth = $dbh -> query($sql);
	
			$pecah_value = explode(',', $value);
	
			while ( $result = $sth -> fetch())
			{
					
				$checked = (array_search($result[$key], $pecah_value) === false) ? '' : 'checked';
					
				$html[] = '<div class="checkbox">';
				$html[] = '<label>';
				$html[] = "<input type=checkbox name='".$key."[]' value='$result[$key]' $checked>$result[$Label] ";
				$html[] = '</label>';
				$html[] = '</div>';
					
			}
	
	
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
	
		return implode("\n", $html);
	}
	
	/**
	 * method ini dipanggil
	 * di method setCheckBoxes
	 * 
	 * @method setTags
	 * @return Tag[]
	 */
	public static function setTags()
	{
		$dbh = new Pldb;
		
		$sql = "SELECT tag_id, tag, slug, count_tag
				FROM pl_post_tag ORDER BY tag";
		
		$tags = array();
		
		try {
			
			$sth = $dbh -> query($sql);
			
			while ($results = $sth -> fetch(PDO::FETCH_ASSOC)) {
				
				$tags[]  = new Tag($results);
				
			}
			
			return ($tags);
			
		} catch (PDOException $e) {
			
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
				
		}
	}

	/**
	 * @method setCheckBoxes
	 * @param string $checked
	 */
    public static function setCheckBoxes($checked = '')
	{
    	
		$checkbox_checked = '';
		
		if ( $checked )
		{
		  $checkbox_checked = "checked='checked'";
		}
		
		// get tags
		$tags = self::setTags();
		
		$html = array();
		
		$html[] = '<label>Label</label>';
		
		
		foreach ( $tags as $t => $tag)
		{
			if (isset($checked))
			{
				if ( in_array($tag -> getTagSlug(), $checked))
				{
					$checkbox_checked="checked='checked'";
				}
				else 
				{
					$checkbox_checked = null;
				}
			}
			
			$html[] = '<div class="checkbox">';
			$html[] = '<label>';
			$html[] = '<input type="checkbox" name="slug[]" value="'.$tag -> getTagSlug().'" '.$checked.'>'.$tag -> getTagName();	
			$html[] = '</label>';
			$html[] = '</div>';
			
			// clear out checkbox checked
			$checkbox_checked = '';
		}
		
		return implode("\n", $html);
		
	}
	
	
	/**
	 * retrieve all record
	 * from table pl_post_tag
	 * 
	 * @method getTags
	 * @param integer $position
	 * @param integer $limit
	 * @return Tag[][]|number[]
	 */
	public static function getTags($position, $limit)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT tag_id, tag, slug, count_tag
				FROM pl_post_tag ORDER BY tag_id 
				DESC LIMIT :position, :limit";
		
		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);
		
		try {
			
			$sth -> execute();
			$list =  array();
			
			foreach ( $sth -> fetchAll() as $row )
			{
				$tags = new Tag($row);
				$list[] = $tags;
			}
			
			$numbers = "SELECT tag_id FROM pl_post_tag";
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
	 * retrieve record 
	 * from pl_post_tag
	 * based on their Id
	 * 
	 * @param integer $tagId
	 * @return Tag
	 */
	public static function getTag($tagId)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT tag_id, tag, slug, count_tag
				FROM pl_post_tag WHERE tag_id = ?";
		
		$cleanId = abs((int)$tagId);
		
		$data = array($cleanId);
		
		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch();
		
		if ( $row ) return new Tag($row);
	}
	
	/**
	 * @method findById
	 * @param integer $tagId
	 * @return associative array
	 */
	public static function findById($tagId)
	{
		$dbh = new Pldb;
		
		$sql = "SELECT tag_id, tag, slug, count_tag
				FROM pl_post_tag WHERE tag_id = ?";
		
		$data = array($tagId);
		
		$sth = $dbh -> pstate($sql,$data);
		
		return $sth -> fetch();
	}
	
}