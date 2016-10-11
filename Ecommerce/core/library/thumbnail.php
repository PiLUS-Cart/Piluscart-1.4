<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Thumbnails
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Thumbnail 
{

	/**
	 * original resource
	 * @var string
	 */
	protected $_original;

	/**
	 * original resource width
	 * @var int
	 */
	protected $_originalwidth;

	/**
	 * original resource height
	 * @var int
	 */
	protected $_originalheight;

	/**
	 * thumbnail width
	 * @var int
	 */
	protected $_thumbwidth;

	/**
	 * thumbnail height
	 * @var int
	 */
	protected $_thumbheight;

	/**
	 * maxSize uploaded
	 * @var number
	 */
	protected $_maxSize = 120;

	/**
	 * status uploaded
	 * @var boolean
	 */
	protected $_canProcess = false;

	/**
	 * file image type
	 * @var unknown
	 */
	protected $_imageType;

	/**
	 * folder to keep image
	 * @var string
	 */
	protected $_destination;

	/**
	 * filename uploaded
	 * @var string
	 */
	protected $_name;

	/**
	 * filename with suffix thumb
	 * @var string
	 */
	protected $_suffix = '_thumb';

	/**
	 * message from uploading file image
	 * @var assoc
	 */
	protected $_messages = array ();

	/**
	 * Instantiate object properties automatically
	 * @param assoc $image
	*/
	public function __construct($image)
	{

		if (is_file ( $image ) && is_readable ( $image )) {
			$details = getimagesize ( $image );
		} else {
			$details = null;
			$this->_messages [] = "Cannot Open $image";
		}

		// jika getimagesize mengembalikan array, dan berupa image
		if (is_array ( $details )) {
			$this->_original = $image;
			$this->_originalwidth = $details [0];
			$this->_originalheight = $details [1];

			// Check MIME Type
			$this->checkType ( $details ['mime'] );
		} else {
			$this->_messages [] = "$image does not appear to be an image.";
		}

	}

	/**
	 * set destination folder
	 * for keeping a file image uploaded
	 * @param assoc $destination
	 */
	public function setDestination($destination)
	{

		if (is_dir ( $destination ) && is_writeable ( $destination )) {
			// mendapatkan karakter terakhir
			$last = substr ( $destination, - 1 );

			if ($last == '/' || $last == '\\') {
				$this->_destination = $destination;
			} else {
				$this->_destination = $destination . DIRECTORY_SEPARATOR;
			}
		} else {
			$this->_messages [] = "Cannot write to $destination.";
		}

	}

	/**
	 * Method setMaxSize
	 * @param integer $size
	 */
	public function setMaxSize($size)
	{

		if (is_numeric ( $size ) && $size > 0) {
			$this->_maxSize = abs ( $size );
		} else {
			$this->_messages [] = 'The Value for set setMaxSize() must be a positive number';
			$this->_canProcess = false;
		}

	}

	/**
	 * Method setSuffix
	 * @param string $suffix
	 */
	public function setSuffix($suffix)
	{

		if (preg_match ( '/^\w+$/', $suffix )) {
			if (strpos ( $suffix, '_' ) !== 0) {
				$this->_suffix = '_' . $suffix;
			} else {
				$this->_suffix = $suffix;
			}
		} else {
			$this->_suffix = '';
		}
	}

	/**
	 * create thumbnail image
	 */
	public function create()
	{

		if ($this->_canProcess && $this->_originalwidth != 0) {
			$this->measureSize ( $this->_originalwidth, $this->_originalheight );
			$this->getName ();
			$this->createThumbnail ();
		} elseif ($this->_originalwidth == 0) {
			$this->_messages [] = 'Cannot determine size of' . $this->_original;
		}

	}

	/**
	 * Method getAlert
	 * returning alert message
	 * @return assoc
	 */
	public function getAlert() {
		return $this->_messages;
	}

	/**
	 * Method checkType
	 * checking MIME type
	 * @param unknown $mime
	 */
	protected function checkType($mime) {
		$mimetypes = array (
				'image/jpeg',
				'image/png',
				'image/gif'
		);
		if (in_array ( $mime, $mimetypes )) {
			$this->_canProcess = true;
			// ekstrak karakter setelah 'image/
			$this->_imageType = substr ( $mime, 6 );
		}
	}

	/**
	 * Method measureSize
	 * @param integer $width
	 * @param integer $height
	 */
	protected function measureSize($width, $height) {
		if ($width <= $this->_maxSize && $height <= $this->_maxSize) {
			$ratio = 1;
		} elseif ($width > $height) {
			$ratio = $this->_maxSize / $width;
		} else {
			$ratio = $this->_maxSize / $height;
		}

		$this->_thumbwidth = round ( $width * $ratio );
		$this->_thumbheight = round ( $height * $ratio );
	}

	/**
	 * Method getName
	 * getting filename with extension
	 */
	protected function getName() {
		$extensions = array (
				'/\.jpg$/i',
				'/\.jpeg$/i',
				'/\.png$/i',
				'/\.gif$/i'
		);
		$this->_name = preg_replace ( $extensions, '', basename ( $this->_original ) );
	}

	/**
	 * Method createImageResource
	 * @return resource
	 */
	protected function createImageResource() {
		if ($this->_imageType == 'jpeg') {
			return imagecreatefromjpeg ( $this->_original );
		} elseif ($this->_imageType == 'png') {
			return imagecreatefrompng ( $this->_original );
		} elseif ($this->_imageType == 'gif') {
			return imagecreatefromgif ( $this->_original );
		}
	}

	/**
	 * Method createThumbnail
	 * creating image's thumbnail
	 */
	protected function createThumbnail() {
		$resource = $this->createImageResource ();
		$thumb = imagecreatetruecolor ( $this->_thumbwidth, $this->_thumbheight );
		imagecopyresampled ( $thumb, $resource, 0, 0, 0, 0, $this->_thumbwidth, $this->_thumbheight, $this->_originalwidth, $this->_originalheight );
		$newname = $this->_name . $this->_suffix;
		if ($this->_imageType == 'jpeg') {
			$newname .= '.jpg';
			$success = imagejpeg ( $thumb, $this->_destination . $newname, 100 );
		} elseif ($this->_imageType == 'png') {
			$newname .= '.png';
			$success = imagepng ( $thumb, $this->_destination . $newname );
		} elseif ($this->_imageType == 'gif') {
			$newname .= '.gif';
			$success = imagegif ( $thumb, $this->_destination . $newname );
		}
		if ($success) {
			$this->_messages [] = "$newname created successfully.";
		} else {
			$this->_messages [] = "Couldn't create a thumbnail for " . basename ( $this->_original );
		}

		imagedestroy ( $resource );
		imagedestroy ( $thumb );
	}
}