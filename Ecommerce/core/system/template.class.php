<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Template extends Pbase
 * Mapping template table
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Template extends Plbase 
{

	/**
	 * template's name
	 * @var string
	 */
	protected $template_name;

	/**
	 * short description
	 * @var string
	 */
	protected $short_desc;

	/**
	 * Template designed by ...
	 * @var string
	 */
	protected $designed_by;

	/**
	 * folder to keep template
	 * @var string
	 */
	protected $folder;

	/**
	 * template's status
	 * @var string
	 */
	protected $actived;


	/**
	 * Inisialisasi object template dengan
	 * data tabel template dari database
	 * @param array
	 */
	public function __construct($input = false)
	{
		parent::__construct($input);

	}

	/**
	 * get template's name
	 * @return string
	 */
	public function getTemplate_Name()
	{
		return $this->template_name;
	}

	/**
	 * get template's description
	 * @return string
	 */
	public function getTemplate_Desc()
	{
		return $this->short_desc;
	}

	/**
	 * get template's designer
	 * @return string
	 */
	public function getTemplate_Designer()
	{
		return $this->designed_by;
	}

	/**
	 * get template's folder
	 * @return string
	 */
	public function getTemplate_Folder()
	{
		return $this->folder;
	}

	/**
	 * get template's status
	 * @return string
	 */
	public function getTemplate_Status()
	{
		return $this->actived;
	}

	/**
	 * Method insertTemplate
	 * insert a new record
	 * on pl_template table
	 */
	public function insertTemplate()
	{
		$dbh = parent::hook();

		$sql = "INSERT INTO pl_template(template_name, short_desc, designed_by, folder)
				VALUES(?, ?, ?, ?) ";

		$data = array($this->template_name, $this->short_desc, $this->designed_by, $this->folder);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method updateTemplate
	 * updating an existing
	 * record from pl_template table
	 */
	public function updateTemplate()
	{
		$dbh = parent::hook();

		$sql = "UPDATE pl_template SET template_name = ?,
				short_desc = ?, designed_by = ?,
				folder = ? WHERE ID = ?";

		$data = array($this->template_name, $this->short_desc, $this->designed_by, $this->folder, $this->ID );

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method activateTheme
	 * to activate template
	 * @param integer $id
	 */
	public function activateTheme()
	{
		$dbh = parent::hook();

		$sql = "UPDATE pl_template SET actived = 'Y' WHERE ID = '$this->ID'";

		$sql2 = "UPDATE pl_template SET actived = 'N' WHERE ID != '$this->ID'";
			
		$sth = $dbh -> query($sql);
		$sth2 = $dbh -> query($sql2);
	}

	/**
	 * Method deleteTheme
	 * deleting an existing record
	 * from pl_template
	 */
	public  function deleteTheme()
	{
		$dbh = parent::hook();

		$sql = "DELETE FROM pl_template WHERE ID = ?";

		$data = array($this->ID);

		$sth = $dbh -> pstate($sql, $data);
	}


	/**
	 * Method getTemplates
	 * retrieve all record
	 * from pl_template table
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:Template  number
	 */
	public static function getTemplates($position, $limit)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, template_name, short_desc, designed_by,
				folder, actived
				FROM pl_template ORDER BY ID DESC LIMIT :position, :limit";

		$sth = $dbh  -> prepare( $sql );
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
				
			foreach ( $sth -> fetchAll() as $row)
			{
				$themes = new Template($row);
				$list[] = $themes;
			}
				
			$numbers = "SELECT ID FROM pl_template";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh  = null;
				
			return (array("results" => $list,  "totalRows" => $totalRows));
				
		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method getTemplate
	 * @param integer $id
	 * @return Template
	 */
	public static function getTemplate($id)
	{
		$dbh = parent::hook();

		$sql = "SELECT ID, template_name,
				short_desc, designed_by,
				folder, actived
				FROM pl_template WHERE ID = ?";

		$data = array($id);

		$sth = $dbh -> pstate($sql, $data);
		$row = $sth -> fetch(PDO::FETCH_ASSOC);

		if ($row) return new Template($row);
	}

	/**
	 * Method themeExists
	 * checking an avaibilitiy theme
	 * @param integer $theme
	 * @return boolean
	 */
	public static function themeExists($theme)
	{
		$dbh = parent::hook();

		$sql = "SELECT COUNT(`ID`) FROM `pl_template` WHERE `template_name` = ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $theme);

		try {

			$sth -> execute();
			$rows = $sth -> fetchColumn();

			if ($rows == 1)
			{
				return true;
			}
			else {
				return false;
			}
		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method LoadTheme
	 * @return Template
	 */
	public static function loadTheme()
	{
		$dbh = parent::hook();

		$sql = "SELECT  ID, template_name, short_desc,
				designed_by, folder, actived
				FROM pl_template WHERE actived = 'Y'";

		$sth = $dbh -> pstate($sql);

		$row = $sth -> fetch();

		if ($row) return new Template($row);
	}

	/**
	 * Method findById
	 * @param integer $themeId
	 * @return Testimoni
	 */
	public static function findById($themeId)
	{
		$dbh = new Pldb;

		$sql = "SELECT ID, template_name, short_desc, designed_by, folder, actived
				FROM pl_template WHERE ID = ? ";

		$data = array($themeId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}

}