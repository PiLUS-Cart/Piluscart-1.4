<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Menuchild
 * Mapping menu_child table
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Menuchild 
{

	/**
	 * Menu child's Id
	 * @var int
	 */
	protected $menu_child_id;

	/**
	 * Menu child's Label
	 * @var string
	 */
	protected $menu_child_label;

	/**
	 * Menu child's Link
	 * @var string
	 */
	protected $menu_child_link;

	/**
	 * Menu parent's Id
	 * @var int
	 */
	protected $menu_parent_id;

	/**
	 * Menu parent label
	 * @var string
	 */
	protected $menu_label;
	/**
	 * Menu grand's child
	 * @var int
	 */
	protected $menu_grand_child;

	/**
	 * Menu child's role
	 * @var unknown
	 */
	protected $menu_child_role;


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
	 * get Menu child's Id
	 * @return number
	 */
	public function getMenu_Child_Id()
	{
		return $this->menu_child_id;
	}

	/**
	 * get Menu Child's Label
	 * @return string
	 */
	public function getMenu_Child_Label()
	{
		return $this->menu_child_label;
	}

	/**
	 * get Menu Child's Link
	 * @return string
	 */
	public function getMenu_Child_Link()
	{
		return $this -> menu_child_link;
	}

	/**
	 * get Menu parent's Id
	 * @return number
	 */
	public function getMenu_Parent_Id()
	{
		return $this->menu_parent_id;
	}

	/**
	 * get Menu grand's child
	 * @return number
	 */
	public function getMenu_Grand_Child()
	{
		return $this->menu_grand_child;
	}

	/**
	 * get Menu Child's privilege
	 * @return string
	 */
	public function getMenu_Child_Role()
	{
		return $this -> menu_child_role;
	}

	/**
	 * get Menu Parent Label
	 * @return string
	 */
	public function getMenu_Parent()
	{
		return $this->menu_label;
	}

	/**
	 * set Menu childs's roles
	 * @return string
	 */
	public function setMenuChild_Role()
	{
		// set up first option for selection if none selected
		$option_selected = '';

		if (!$this->menu_role) {
			$option_selected = ' selected="selected"';
		}

		// list position in array
		$role_levels = array('public', 'private');

		$html  = array();

		$html[] = '<label for="role_level">Pilih Level Sub Menu</label>';
		$html[] = '<select class="form-control" name="child_role">';

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
	 * Method createMenuChild
	 * insert new reocrd
	 */
	public function createMenuChild()
	{
		$dbh = new Pldb;

		$sql = "INSERT INTO pl_menu_child
				( menu_child_label, menu_child_link, menu_parent_id,
				menu_grand_child, menu_child_role )
				VALUES(?, ?, ?, ?, ?)";

		$data = array($this->menu_child_label, $this->menu_child_link,
				$this->menu_parent_id, $this->menu_grand_child, $this->menu_child_role
		);

		$sth = $dbh -> pstate($sql, $data);

		$child_id = $dbh -> lastId();


		$query = "SELECT menu_child_id, menu_child_link FROM pl_menu_child WHERE menu_child_id = '$child_id'";

		$result = $dbh -> query($query);

		$row = $result -> fetch();

		//update link
		if ($row['menu_child_link'] == '')
		{
			$updateLink = "UPDATE pl_menu_child SET menu_child_link = '#' WHERE menu_child_id = ? ";
				
			$data_update = array($row['menu_child_id']);
				
			$dbh -> pstate($updateLink, $data_update);
		}



	}

	/**
	 * Method updateMenuChild
	 * update an existing record
	 */
	public function updateMenuChild()
	{
		$dbh = new Pldb;

		$sql = "UPDATE pl_menu_child SET menu_child_label = ?,
				menu_child_link = ?,
				menu_parent_id = ?, menu_grand_child = ?,
				menu_child_role = ?
				WHERE menu_child_id = ?";

		$data = array($this->menu_child_label, $this->menu_child_link,
				$this->menu_parent_id,
				$this->menu_grand_child,
				$this->menu_child_role,
				$this->menu_child_id
		);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method deleteMenuChild
	 * to delete an existing record
	 */
	public function deleteMenuChild()
	{
		$dbh = new Pldb;

		$sql = "DELETE FROM pl_menu_child WHERE menu_child_id = ?";

		$data = array($this->menu_child_id);

		$sth = $dbh -> pstate($sql, $data);
	}

	/**
	 * Method fetchMenuChild
	 * this method is used
	 * in Module navigation
	 * with action newMenuChild
	 * fetching menu child based
	 * on their id
	 * @param integer $child_id
	 * @return mixed
	 */
	public function fetchMenuChild($child_id)
	{
		$dbh = new Pldb;

		$sql = "SELECT menu_child_id, menu_child_label, menu_child_link,
				menu_parent_id, menu_grand_child, menu_child_role
				FROM pl_menu_child WHERE menu_child_id = ?";

		$sth = $dbh -> prepare( $sql );

		$sth -> bindValue(1, $child_id);

		try {
				
			$sth -> execute();
				
			return $sth -> fetch();
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}


	}
	/**
	 * Method getMenuChild
	 * retrieve all record
	 * from menu childs table
	 * @param integer $position
	 * @param integer $limit
	 * @return multitype:multitype:Menuchild  mixed
	 */
	public static function getMenuChilds($position, $limit)
	{
		$dbh = new Pldb;

		$sql = 'SELECT mc.menu_child_id,
				mc.menu_child_label,
				mc.menu_child_link,
				mc.menu_parent_id,
				mc.menu_grand_child,
				mc.menu_child_role,
				mp.menu_label
				FROM pl_menu_child mc
				INNER JOIN pl_menu AS mp ON mc.menu_parent_id = mp.menu_id
				ORDER BY mc.menu_child_id LIMIT :position, :limit';

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":position", $position, PDO::PARAM_INT);
		$sth -> bindValue(":limit", $limit, PDO::PARAM_INT);

		try {
				
			$sth -> execute();
			$menuchilds = array();
				
			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$menuchilds[] = new Menuchild($result);

			}
				
			$numbers = "SELECT menu_child_id FROM pl_menu_child ";
			$sth = $dbh -> query($numbers);
			$totalRows = $sth -> rowCount();
			$dbh  = null;
			return (array("results" => $menuchilds, "totalRows" => $totalRows ));
				
				
		} catch (PDOException $e) {
				
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');

		}

	}

	/**
	 * Method setMenuChilds
	 * @return multitype:Menu
	 */
	public static function setMenuChilds()
	{
		$childmenus = array();
		 
		$dbh  = new Pldb;

		$sql = "SELECT menu_child_id,
				menu_child_label,
				menu_child_link,
				menu_parent_id,
				menu_grand_child,
				menu_child_role
				FROM pl_menu_child
				ORDER BY menu_child_label";

		$sth = $dbh -> query($sql);

		try {

			while ($result = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$childmenus[] = new Menuchild($result);
			}

			return ($childmenus);

		} catch (PDOException $e) {
			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method getMenuChild
	 * @param integer $id
	 * @return Menuchild
	 */
	public static function getMenuChild($child_id)
	{
		$dbh  = new Pldb;

		$sql = "SELECT mc.menu_child_id, mc.menu_child_label,
				mc.menu_child_link, mc.menu_parent_id, mc.menu_grand_child,
				mc.menu_child_role, mp.menu_id, mp.menu_label, mp.menu_link,
				mp.menu_order, mp.menu_role
				FROM pl_menu_child mc
				INNER JOIN pl_menu AS mp ON mc.menu_parent_id = mp.menu_id
				WHERE mc.menu_child_id = ?";

		$data = array($child_id);

		$sth = $dbh -> pstate($sql, $data);

		$row = $sth -> fetch(PDO::FETCH_ASSOC);

		if ($row) return new Menuchild($row);

	}

	/**
	 * Method setMenuChild
	 * @param unknown $child_id
	 * @return Menu
	 */
	public static function setMenuChild($child_id)
	{

		$dbh = new Pldb;

		$sql = "SELECT menu_child_id, menu_child_label,
				menu_child_link, menu_parent_id,
				menu_grand_child, menu_child_role
				FROM pl_menu_child WHERE menu_child_id = :child_id ";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(":child_id", $child_id, PDO::PARAM_INT);

		try {
			$sth -> execute();

			while ($results = $sth -> fetch(PDO::FETCH_ASSOC)) {

				$menuChild = new Menuchild($results);
			}

			return ($menuChild);

		} catch (PDOException $e) {

			$dbh = null;
			die('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $e -> getMessage() . '</div>');
		}
	}

	/**
	 * Method menuChildExists
	 * checking an existing menu child
	 * @param string $menu_child
	 * @return boolean
	 */
	public static function menuChildExists($menu_child)
	{
		$dbh = new Pldb;

		$sql = "SELECT COUNT(`menu_child_id`) FROM `pl_menu_child` WHERE `menu_child_label`= ?";

		$sth = $dbh -> prepare($sql);
		$sth -> bindValue(1, $menu_child);

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
	 * Method setChild_Dropdown
	 * @param string $selected
	 * @return string
	 */
	public static function setChild_Dropdown($selected = '')
	{
		$option_selected = '';

		if (!$selected)
		{
			$option_selected = ' selected="selected"';
		}

		//get child menus
		$child_menus = self::setMenuChilds();

		$html = array();

		$html[] = '<label for="role_level">Sub Menu</label>';
		$html[] = '<select class="form-control" name="menu_child">';
		$html[] = '<option value=0 selected>--Pilih Sub Menu--</option>';

		foreach ($child_menus as $child_menu)
		{
			if ((int) $selected == (int) $child_menu -> getMenu_Child_Id())
			{
				$option_selected = ' selected="selected"';

			}
				
			$html[] = '<option value="'. $child_menu -> getMenu_Child_Id().'"'.$option_selected.'>' . $child_menu -> getMenu_Child_Label() . '</option>';

			//clear out the selected option flag
			$option_selected = '';
		}

		$html[] = '</select>';

		return implode("\n", $html);
	}

	/**
	 * Method findById
	 * @param integer$menuChild_id
	 * @return mixed
	 */
	public static function findById($child_id)
	{
		$dbh = new Pldb;

		$sql = "SELECT menu_child_id, menu_child_label, menu_child_link,
				menu_parent_id, menu_grand_child, menu_child_role
				FROM pl_menu_child WHERE menu_child_id = ? ";

		$data = array($child_id);

		$sth = $dbh -> pstate($sql, $data);

		return $sth -> fetch();

	}
}