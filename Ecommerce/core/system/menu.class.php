<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Menu
 * Mapping table pl_menu
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Menu 
{

	/**
	 * Menu's ID
	 * @var integer
	 */
	protected $menu_id;

	/**
	 * Menu's Label
	 * @var string
	 */
	protected $menu_label;

	/**
	 * Menu;s Link
	 * @var string
	 */
	protected $menu_link;

	/**
	 * Menu's order
	 * @var integer
	 */
	protected $menu_order;

	/**
	 * Menu's Role
	 * @var string
	 */
	protected $menu_role;


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
	 * get Menu's Id
	 * @return number
	 */
	public function getMenu_Id()
	{
		return $this->menu_id;
	}

	/**
	 * get Menu's Label
	 * @return string
	 */
	public function getMenu_Label()
	{
		return $this-> menu_label;
	}

	/**
	 * get Menu's Link
	 * @return string
	 */
	public function getMenu_Link()
	{
		return $this -> menu_link;
	}

	/**
	 * get Menu's order
	 * urutan menu
	 * @return number
	 */
	public function getMenu_Order()
	{
		return $this->menu_order;
	}

	/**
	 * get Menu's privilege
	 * @return string
	 */
	public function getMenu_Role()
	{
		return $this->menu_role;
	}

	/**
	 * Method setMenu_RoleDropdown
	 * @return string
	 */
	public function setMenu_RoleDropdown()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->menu_role) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$role_levels = array('public', 'private');

		$html  = array();

		$html[] = '<label for="role_level">Pilih Level Menu</label>';
		$html[] = '<select class="form-control" name="menu_role">';

		foreach ($role_levels as $r => $role_level) {

			if ($this->menu_role == $role_level) {
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
	 * Method createMenu
	 * insert new record
	 */
	public function createMenu()
	{
		$dbh = new Pldb;

		//get number of sort
		$query = "SELECT menu_order FROM pl_menu ORDER BY menu_order DESC";

		$sth = $dbh -> query($query);
		$sort = $sth -> fetch(PDO::FETCH_ASSOC);
		$urutan = $sort['menu_order'] + 1;

		//input data menu

		$sql = "INSERT INTO pl_menu(menu_label, menu_link, menu_order, menu_role)
				VALUES(?, ?, ?, ?)";


		$data = array($this->menu_label, $this->menu_link, $urutan, $this->menu_role );

		$sth = $dbh -> pstate($sql, $data);

		$menuId = $dbh -> lastId();

		$query = "SELECT menu_id, menu_link FROM pl_menu WHERE menu_id = '$menuId' ";

		$result = $dbh -> query($query);

		$row = $result -> fetch();

		//update link menu

		if ($row['menu_link'] == '')
		{
			$update_menuLink = "UPDATE pl_menu SET menu_link = '#' WHERE menu_id = ? ";
	   
			$data_update = array($row['menu_id']);
	   
			$dbh -> pstate($update_menuLink, $data_update);
		}

	}

	/**
	 * Method updateMenu
	 * update an existing record
	 */
	public function updateMenu()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_menu SET menu_label = ?,
				menu_link = ?, menu_order = ?, menu_role = ?
				WHERE menu_id = ?";

		$data = array($this->menu_label, $this->menu_link, $this -> menu_order,
				$this->menu_role, $this->menu_id);

		$sth = $dbh -> pstate($sql, $data);

	}

	/**
	 * Method deleteMenu
	 * delete an existing record
	 */
	public function deleteMenu()
	{

		$dbh = new Pldb;

		$sanitize = new Sanitize();

		$sql = "DELETE FROM pl_menu WHERE menu_id = ?";

		$cleanId = $sanitize -> sanitasi($this->menu_id, 'sql');

		$data = array($cleanId);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method getMenus
	 * retrieve all menus record
	 * @param int $position
	 * @param int $limit
	 */
	public static function getMenus($position, $limit)
	{
		$dbh = new Pldb;

		$sql = "SELECT menu_id, menu_label, menu_link, menu_order, menu_role
				FROM pl_menu ORDER BY menu_order LIMIT :position, :limit ";

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
			$sth -> execute();
			$list = array();
			while ($row = $sth -> fetch()) {
					
				$menus  = new Menu($row);
				$list[] = $menus;
			}
				
			$numbers = "SELECT menu_id FROM pl_menu";
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
	 * Method setMenus
	 * this method is used
	 * for method setMenu_DropDown
	 * @return multitype:Menu
	 */
	public static function setMenus()
	{

		$dbh  = new pldb;

		$sql = "SELECT menu_id, menu_label, menu_link, menu_order, menu_role
				FROM pl_menu ORDER BY menu_label";

		$sth = $dbh -> query($sql);

		$menus = array();

		try {

			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$menus[] = new Menu($result);
			}

			return ($menus);

		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method getMenu($id)
	 * retrieve spesific records
	 * based on their ID
	 * @param integer $menu_id
	 * @return Menu
	 */
	public static function getMenu($menu_id)
	{
		$dbh = new Pldb;

		$sql = "SELECT menu_id, menu_label,
				menu_link, menu_order, menu_role
				FROM pl_menu WHERE menu_id = ? ";

		$data = array($menu_id);
	  
		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch(PDO::FETCH_ASSOC);

		if ($row) return new Menu($row);
	}

	/**
	 * Method setMenu
	 * @param integer $menuId
	 * @return Menu
	 */
	public static function setMenu($menuId)
	{

		$dbh  = new Pldb;

		$sql = "SELECT menu_id, menu_label,
				menu_link, menu_order, menu_role
				FROM pl_menu WHERE menu_id = :menu_id ";

		$sth = $dbh -> prepare($sql);

		$sth -> bindValue(":menu_id", $menuId, PDO::PARAM_INT);

		try {
			$sth -> execute();
				
			while ($results = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$menu = new Menu($results);
			}
				
			return ($menu);
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method menuExists
	 * checking an existing menu
	 * @param string $menu_name
	 * @return boolean
	 */
	public static function menuExists($menu_name)
	{
		$dbh = new Pldb;

		$sql = "SELECT COUNT(`menu_id`) FROM `pl_menu` WHERE `menu_label`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $menu_name);

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
	 * Method setMenu_Dropdown
	 * @param integer $selected
	 * @return string
	 */
	public static function setMenu_DropDown($selected = '')
	{
		$option_selected = '';

		if (!$selected)
		{
			$option_selected = ' selected="selected"';
		}

		//get the menus
		$menus = self::setMenus();

		$html = array();

		$html[] = '<label for="role_level">Menu Utama</label>';
		$html[] = '<select class="form-control" name="menu_parent">';
		$html[] = '<option value=0 selected>--Pilih Menu Utama--</option>';

		foreach ($menus as $menu) {
				
			if ((int) $selected == (int) $menu -> getMenu_Id())
			{
				$option_selected = ' selected="selected"';

			}
				
			$html[] = '<option value="'. $menu -> getMenu_Id().'"'.$option_selected.'>' . $menu -> getMenu_Label() . '</option>';
				
			//clear out the selected option flag
			$option_selected = '';
		}

		$html[] = '</select>';

		return implode("\n", $html);
	}

	/**
	 * Method findById
	 * @param integer $menuId
	 * @return mixed
	 */
	public static function findById($menuId)
	{
		$dbh = new Pldb;

		$sql = "SELECT menu_id, menu_label, menu_link, menu_order, menu_role
				FROM pl_menu WHERE menu_id = ? ";

		$data = array($menuId);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();
	}
}