<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas UploadImage
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class UploadImage extends Upload 
{
	/**
	 *
	 * @var string
	 */
	protected $_thumbDestination;

	/**
	 *
	 * @var string
	 */
	protected $_deleteOriginal;

	/**
	 *
	 * @var string
	 */
	protected $_suffix = '';

	/**
	 *
	 * @var array
	 */
	protected $_filenames = array ();

	/**
	 * Initialize object properties
	 * @param string $path
	 * @param string $deleteOrignal
	*/
	public function __construct($path, $deleteOrignal = false) {
		parent::__construct ( $path );
		$this->_thumbDestination = $path;
		$this->_deleteOriginal = $deleteOrignal;
	}

	/**
	 * Method setThumbDestination
	 * @param string $path
	 * @throws Exception
	 */
	public function setThumbDestination($path) {
		if (! is_dir ( $path ) || ! is_writable ( $path )) {
			throw new Exception ( "$path must be a valid, writable directory" );
		}

		$this->_thumbDestination = $path;
	}

	/**
	 * Method setThumbSuffix
	 * @param string $suffix
	 */
	public function setThumbSuffix($suffix) {
		if (preg_match ( '/\w+/', $suffix )) {

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
	 * Override Method getFilename
	 * (non-PHPdoc)
	 * @see Upload::getFilenames()
	 */
	public function getFilenames() {
		return $this->_filenames;
	}

	/**
	 * Method createThumb
	 * @param string $image
	 */
	protected function createThumb($image) {
		$thumb = new Thumbnail( $image );
		$thumb->setDestination ( $this->_thumbDestination );
		$thumb->setSuffix ( $this->_suffix );
		$thumb->create ();
		$messages = $thumb->getAlert ();
		$this->_messages = array_merge ( $this->_messages, $messages );
	}

	/**
	 * Override Method processFile
	 * (non-PHPdoc)
	 * @see Upload::processFile()
	 */
	protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite) {
		$no_problem = $this->checkError ( $filename, $error );
		if ($no_problem) {
			$size_Noproblem = $this->checkSize ( $filename, $size );
			$type_Noproblem = $this->checkType ( $filename, $type );

			if ($size_Noproblem && $type_Noproblem) {

				$name = $this->checkName ( $filename, $overwrite );
				$success = move_uploaded_file ( $tmp_name, $this->_destination . $name );

				if ($success) {
					$this->_filenames [] = $name;
					if (! $this->_deleteOriginal) {
						$message = "$filename uploaded successfully.";
						if ($this->_renamed) {

							$message .= " and renamed $name";
						}

						$this->_messages [] = $message;
					}

					// buat thumbanail dari image yang diupload
					$this->createThumb ( $this->_destination . $name );

					// Hapus image yg diupload jika diminta
					if ($this->_deleteOriginal) {

						unlink ( $this->_destination . $name );
					}
				} else {

					$this->_messages [] = "Could not upload $filename";
				}
			}
		}
	}
}