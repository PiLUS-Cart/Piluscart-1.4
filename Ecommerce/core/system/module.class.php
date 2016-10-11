<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Module
 * Mapping table pl_module
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Module 
{

	/**
	 * module's Id
	 * @var int
	 */
	protected $module_id;


	/**
	 * module's name
	 * @var string
	 */
	protected $module_name;

	/**
	 * module's link
	 * @var string
	 */
	protected $link;

	/**
	 * module's description
	 * @var string
	 */
	protected $description;

	/**
	 * module's privilege
	 * @var string
	 */
	protected $role_level;

	/**
	 * module's status
	 * @var string
	 */
	protected $actived;

	/**
	 * sort
	 * @var int
	 */
	protected $sort;


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
	 * get Module's Id
	 * @return integer
	 */
	public function getModule_Id()
	{
		return $this->module_id;
	}


	/**
	 * get Module's name
	 * @return string
	 */
	public function getModule_Name()
	{
		return $this->module_name;
	}

	/**
	 * get Module's link
	 * @return string
	 */
	public function getModule_Link()
	{
		return $this->link;
	}

	/**
	 * get Module's description
	 * @return string
	 */
	public function getModule_Description()
	{
		return $this->description;
	}

	/**
	 * get Module's role level
	 * @return string
	 */
	public function getModule_RoleLevel()
	{
		return $this->role_level;
	}

	/**
	 * get Module's status
	 * @return string
	 */
	public function getModule_Actived()
	{
		return $this->actived;
	}

	/**
	 * get Module sort
	 * @return number
	 */
	public function getModule_Sort()
	{
		return $this->sort;
	}

	/**
	 * Method createModule
	 * insert new record
	 * to table pl_module
	 */
	public function createModule()
	{
		$dbh = new Pldb;

		$query = "SELECT sort FROM pl_module ORDER BY sort DESC";

		$sth = $dbh -> query($query);
		$sort = $sth -> fetch(PDO::FETCH_ASSOC);
		$urutan = $sort['sort'] + 1;

		//input data modul
		$sql = "INSERT INTO
				pl_module( module_name, link,
				description, role_level,
				sort)
				VALUES(?, ?, ?, ?, ?)";

		$data = array(
				$this->module_name, $this->link, $this->description,
				$this->role_level, $urutan);

		$sth = $dbh -> pstate($sql, $data);

		$moduleId = $dbh -> lastId();

		$query = "SELECT module_id, link FROM pl_module WHERE module_id = '$moduleId' ";

		$result = $dbh -> query($query);

		$row = $result -> fetch();

		//update link module
		if ($row['link'] == '')
		{
			$updateLink = "UPDATE pl_module SET link = '#' WHERE module_id = ? ";
				
			$data_update = array($row['module_id']);
				
			$dbh -> pstate($updateLink, $data_update);
		}

	}

	/**
	 * @method updateModule
	 * to update an existing record
	 */
	public function updateModule()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_module SET
				module_name = ?, link = ?, description = ?,
				role_level = ?, sort = ?
				WHERE module_id = ? ";

		$data = array( $this->module_name, $this->link, $this->description,
				$this->role_level, $this->sort,
				$this->module_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * @method activateModul
	 */
	public function activateModul()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_module SET actived = 'Y' WHERE module_id = '$this->module_id'";

		$sth = $dbh -> query($sql);

		$module_id = $dbh -> lastInsertId();


	}

	/**
	 * @method deactivateModul
	 */
	public function deactivateModul()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_module SET actived = 'N' WHERE module_id = '$this->module_id'";

		$sth = $dbh -> query($sql);
	}

	/**
	 * Method to deleteModule
	 * to delete an existing record record
	 */
	public function deleteModule()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_module WHERE module_id = ?";

		$data = array($this->module_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	public function isModuleActived($module)
	{
		$dbh = new Pldb();
		
		$moduleExist = self::moduleExists($module);
		
		if ($moduleExist == true) {
			
			$sql = "SELECT actived FROM pl_module WHERE module_name = '$module' ";
			
			$sth = $dbh -> query($sql);
			
			return $sth -> fetchObject();
			
		
		} else {
			
			return false;
			
		}
		
	}
	
	
	/**
	 * Method getRole_LevelDropdown
	 * @return string
	 */
	public function getRole_DropDown()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->role_level) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$role_levels = array('public', 'private');

		$html  = array();

		$html[] = '<label for="role_level">Pilih Level Modul</label>';
		$html[] = '<select class="form-control" name="role_level">';

		foreach ($role_levels as $r => $role_level) {

			if ($this->role_level == $role_level) {
				$option_selected = ' selected="selected"';
			}
			// set up the option line
			$html[]  =  '<option value="' . $role_level . '"' . $option_selected . '>' . $role_level . '</option>';
			// clear out the selected option flag
			$option_selected = '';
		}

		$html[] = '</select>';
		return implode("\n", $html);
	}

	/**
	 * Method getModules
	 * to retrieve all records
	 * from module table
	 * with position and limit
	 * for using pagination
	 * @param int $position
	 * @param int $limit
	 * @return multitype:multitype:Module  mixed
	 */
	public static function getModules($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT module_id, module_name, link, description,
				role_level, actived, sort
				FROM pl_module ORDER BY sort LIMIT :position, :limit ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();

			while ($row = $sth -> fetch()) {
				$modules = new Module($row);
				$list[] = $modules;
			}

			$numbers = "SELECT module_id FROM pl_module";
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
	 * Method getModule
	 * to retrieve spesific record
	 * from column ID
	 * @param int $id
	 * @return Module
	 */
	public static function getModule($module_id)
	{
		$dbh = new Pldb;

		$sql = "SELECT module_id,
				module_name, link, description,
				role_level, actived, sort
				FROM pl_module WHERE module_id = ? ";

		$data = array($module_id);

		$sth = $dbh -> pstate($sql,$data);
		$row = $sth -> fetch(PDO::FETCH_ASSOC);

		if ($row) return new Module($row);

	}

	/**
	 * Method moduleExists
	 * to check an existing module
	 * in the record
	 * @param string $module_name
	 * @return boolean
	 */
	public static function moduleExists($module_name)
	{
		$dbh  = new Pldb;

		$sql = "SELECT COUNT(`module_id`) FROM `pl_module` WHERE `module_name`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $module_name);

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
	 * Method findById
	 * @param integer $moduleId
	 * @return mixed
	 */
	public static function findById($moduleId)
	{
		$dbh = new Pldb;

		$sql = "SELECT module_id, module_name, link, description, role_level,
				actived, sort FROM pl_module WHERE module_id = ? ";

		$data = array( $moduleId );

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}

	/**
	 * @method setModules
	 * @param unknown $roleLevel
	 * @return multitype:Module
	 */
	public static function setModules()
	{
		$dbh = new Pldb;

		$sql = "SELECT module_id, module_name, link, description,
				role_level, actived, sort
				FROM pl_module WHERE role_level = 'private' AND
				actived = 'Y' ORDER BY module_name";

		$sth = $dbh -> query($sql);
	  
		$modules = array();
	  
		try {

			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {
				 
				$modules[] = new Module($result);
				
			}

			return ($modules);

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}

	}

	/**
	 * @method setMenuModul
	 * @param string $roleLevel
	 */
	public static function setMenuModul()
	{
		
		$accessLevel = Admin::accessLevel();
		// set modules
		$modules = self::setModules();
			
		$html = array();
			
		foreach ( $modules as $m => $module )
		{
			$modulName = $module -> getModule_Name();
			$filename =  strtolower($modulName) . '.php';
			$modulePath = PL_SYSPATH . "cabin/module/$filename";
			$modulLink = $module -> getModule_Link();
			$modulLevel = $module -> getModule_RoleLevel();

			if ( $accessLevel != 'admin' && $accessLevel != 'editor' 
					&& $accessLevel != 'author' && $accessLevel != 'contributor') {
				
				if (is_readable($modulePath) && !empty($modulLink) && $modulLevel != 'public' ) {
				
					$html[] = '<li><a href="'.$modulLink.'">'.$modulName.'</a></li>';
						
				}
			}
			
		}
			
		return implode("\n", $html);
		
	}

}