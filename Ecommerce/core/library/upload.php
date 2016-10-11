<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Uploads Image
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Upload 
{
	/**
	 *  file uploaded
	 * @var array
	 */
	protected $_uploaded = array ();
	/**
	 * destination uploaded's file
	 * @var string
	*/
	protected $_destination;
	/**
	 * Max file size
	 * @var integer
	 */
	protected $_max = 51200;
	/**
	 * Message error
	 * @var string
	 */
	protected $_messages = array ();

	/**
	 * permitted file extension
	 * @var string
	*/
	protected $_permitted = array (
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png'
	);

	/**
	 * Message renamed
	 * @var string
	*/
	protected $_renamed = false;

	/**
	 * Filename
	 * @var array
	 */
	protected $_filenames = array ();

	/**
	 * Initialize object properties
	 * @param string $path
	 * @throws Exception
	*/
	public function __construct($path) {
		if (! is_dir ( $path ) || ! is_writable ( $path )) {
			throw new Exception ( "$path must be a valid, writable directory." );
		}
		$this->_destination = $path;
		$this->_uploaded = $_FILES;
	}

	/**
	 * Method move
	 * @param string $overwrite
	 */
	public function move($overwrite = false) {
		$field = current ( $this->_uploaded );
		if (is_array ( $field ['name'] )) {
			foreach ( $field ['name'] as $number => $filename ) {

				// process multiple upload
				$this->_renamed = false;
				$this->processFile ( $filename, $field ['error'] [$number], $field ['size'] [$number], $field ['type'] [$number], $field ['tmp_name'] [$number], $overwrite );
			}
		} else {
			$this->processFile ( $field ['name'], $field ['error'], $field ['size'], $field ['type'], $field ['tmp_name'], $overwrite );
		}
	}

	/**
	 * Method getAlert
	 * @return string
	 */
	public function getAlert() {
		return $this->_messages;
	}

	/**
	 * Method getMaxSize
	 * @return string
	 */
	public function getMaxSize() {
		return number_format ( $this->_max / 1024, 1 ) . 'kB';
	}

	/**
	 * Method setMaxSize
	 * @param string $num
	 * @throws Exception
	 */
	public function setMaxSize($num) {
		if (! is_numeric ( $num )) {
			throw new Exception ( "Maximum size must be a number" );
		}

		$this->_max = ( int ) $num;
	}

	/**
	 * Method addPermittedTypes
	 * @param string $types
	 */
	public function addPermittedTypes($types) {
		$types = ( array ) $types;
		$this->isValidMime ( $types );
		$this->_permitted = array_merge ( $this->_permitted, $types );
	}

	/**
	 * Method setPermittedTypes
	 * @param string $types
	 */
	public function setPermittedTypes($types) {
		$types = ( array ) $types;
		$this->isValidMime ( $types );
		$this->_permitted = $types;
	}

	/**
	 * Method getFilenames
	 * @return multitype:
	 */
	public function getFilenames() {
		return $this->_filenames;
	}

	/**
	 * Method processFile
	 * @param string $filename
	 * @param string $error
	 * @param string $size
	 * @param string $type
	 * @param string $tmp_name
	 * @param string $overwrite
	 */
	protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite) {
		$no_problemo = $this->checkError ( $filename, $error );

		if ($no_problemo) {

			$size_noProblem = $this->checkSize ( $filename, $size );
			$type_noProblem = $this->checkType ( $filename, $type );

			if ($size_noProblem && $type_noProblem) {
				$name = $this->checkName ( $filename, $overwrite );
				$success = move_uploaded_file ( $tmp_name, $this->_destination . $name );

				if ($success) {
					// tambahkan filename yang diperbaiki ke dalam array filename
					$this->_filenames [] = $name;
					$message = "$filename uploaded successfully.";
					if ($this->_renamed) {
						$message .= " and renamed $name";
					}
					$this->_messages [] = $message;
				} else {
					$this->_messages [] = "Could not upload $filename";
				}
			}
		}
	}

	/**
	 * Method checkError
	 * @param string $filename
	 * @param string $error
	 * @return boolean
	 */
	protected function checkError($filename, $error) {
		switch ($error) {
			case 0 :
				return true;
			case 1 :
			case 2 :
				$this->_messages [] = "$filename exceeds maximum size:" . $this->getMaxSize ();
				return true;
			case 3 :
				$this->_messages [] = "Error uploading $filename. Please try again.";
				return false;
			case 4 :
				$this->_messages [] = "There is no file selected.";
				return false;
					
			default :
				$this->_messages [] = "System error uploading $filename. Check your system.";
				return false;
		}
	}

	/**
	 * Method checkSize
	 * @param string $filename
	 * @param string $size
	 * @return boolean
	 */
	protected function checkSize($filename, $size) {
		if ($size == 0) {
			return false;
		} elseif ($size > $this->_max) {
			$this->_messages [] = "$filename exceeds maximum size:  " . $this->getMaxSize ();
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Method checkType
	 * @param string $filename
	 * @param string $type
	 * @return boolean
	 */
	protected function checkType($filename, $type) {
		if (empty ( $type )) {
			return false;
		} elseif (! in_array ( $type, $this->_permitted )) {
			$this->_messages [] = "$filename is not a permitted type of file.";
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Method isValidMime
	 * @param array $types
	 * @throws Exception
	 */
	protected function isValidMime($types) {
		$alsoValid = array (
				'image/tiff',
				'application/pdf',
				'text/plain',
				'text/rtf'
		);
		$valid = array_merge ( $this->_permitted, $alsoValid );
		foreach ( $types as $type ) {
			if (! in_array ( $type, $valid )) {
				throw new Exception ( "$type is not a permitted MIME type." );
			}
		}
	}

	/**
	 * Method checkName
	 * @param string $name
	 * @param unknown $overwrite
	 * @return Ambigous <string, mixed>
	 */
	protected function checkName($name, $overwrite) {
		$nospaces = str_replace ( ' ', '_', $name );
		if ($nospaces != $name) {
			$this->_renamed = true;
		}

		if (! $overwrite) {
			$existing = scandir($this->_destination);
			if (in_array ( $nospaces, $existing )) {
				$dot = strrpos ( $nospaces, '.' );
				if ($dot) {
					$base = substr($nospaces, 0, $dot);
					$extension = substr ( $nospaces, $dot );
				} else {
					$base = $nospaces;
					$existing = '';
				}

				$i = 1;

				do {
					$nospaces = $base . '_' . $i ++ . $extension;
				} while ( in_array ( $nospaces, $existing ) );

				$this->_renamed = true;
			}
		}

		return $nospaces;
	}
}