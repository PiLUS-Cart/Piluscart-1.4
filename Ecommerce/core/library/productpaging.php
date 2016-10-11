<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas ProductPaging
 * product page pagination
 * used in administrator page
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class ProductPaging 
{

	/**
	 * Method untuk mengecek posisi data
	 * dan halaman/order
	 * @param int $limit
	 * @return number
	 */
	public function getPosition($limit)
	{
		if (empty($_GET['order']))
		{
			$position = 0;
			$_GET['order'] = 1;
		}
		else {
			$position = ($_GET['order']-1) * $limit;
		}

		return $position;
	}

	/**
	 *
	 * Method untuk menghitung total
	 * halaman/order
	 * @param int $totalData
	 * @param int $limit
	 * @return number
	 */
	public function totalPage($totalData, $limit)
	{
		$totalPage = ceil($totalData/$limit);

		return $totalPage;
	}


	// method untuk link halaman 1,2,3 (back end website)
	public function navPage($activePage, $catId, $totalPage)
	{
		$page_link = '';

		// Link ke halaman pertama (Awal) dan sebelumnya (prev)
		if ($activePage > 1)
		{
			$Sebelumnya= $activePage-1;
			$page_link .= "<span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=".abs((int)1)."><< Awal</a></span>
			<span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=".abs((int)$prev).">< Prev</a></span> ";

		}

		else {

			$page_link .= "<span class=disabled><< Awal  < Sebelumnya </span>";
		}


		//Page link number 1, 2, 3, ...
		$number = ($activePage > 3 ? " ... " : " ");

		for ($i=$activePage-2; $i<$activePage; $i++)
		{
			if ( $i < 1 )
				continue;
			$number .= "<span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=". abs((int)$i).">$i</a></span> ";
		}

		//active page
		$number .= " <span class=current> $activePage </span>  ";

		for ($i=$activePage+1; $i<($activePage + 3); $i++)
		{
			if ( $i > $totalPage )
				break;

			$number .= "<span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=".abs((int)$i).">$i</a></span>  ";
		}

		$number .= ($activePage+2< $totalPage ? " ... <span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=".abs((int)$totalPage).">$totalPage</a> </span>  " : " ");

		$page_link .= "$number";

		//Link ke halaman berikutnya (Berikutnya) dan halaman terakhir(Terakhir)
		if ($activePage < $totalPage)
		{
			$Berikutnya = $activePage + 1;

			$page_link .= " <span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=$Berikutnya>Berikutnya ></a></span>
			<span class=disabled><a href=$_SERVER[PHP_SELF]?module=$_GET[module]&action=listProducts&catId=$catId&order=$totalPage>Terakhir >></a></span> ";
		}

		else {

			$page_link .= " <span class=disabled>Berikutnya >  Terakhir >></span>";
		}

		return $page_link;
	}

}