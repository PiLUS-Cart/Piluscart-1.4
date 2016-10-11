<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas Uploader
 * Uploading files
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class Uploader 
{
	/**
	 * Uploaded's filename
	 * @var assoc
	 */
	private $_filename;

	/**
	 * Uploaded's fileLocation
	 * @var assoc
	 */
	private $_fileLocation;

	/**
	 * Uploaded file's destination
	 * @var string
	 */
	private $_pathdestination;

	/**
	 * Uploaded file's error message
	 * @var string
	 */
	private $_errorMessage;

	/**
	 * Uploaded file's error code
	 * @var assoc
	 */
	private $_errorCode;

	/**
	 * Instantiate automatically
	 * object properties
	 * @param assoc $key
	 */
	public function __construct( $key )
	{
		$this->_filename = $_FILES[$key]['name'];
		$this->_fileLocation = $_FILES[$key]['tmp_name'];
		$this->_errorCode = ($_FILES[$key]['error']);
	}

	/**
	 * folder to keep file uploaded
	 * @param string $folder
	 */
	public function saveIn( $folder )
	{
		$this->_pathdestination = $folder;
	}

	/**
	 * Moving file - save file uploaded
	 * @throws Exception
	 */
	public function save()
	{
		if ( $this->readyToUpload() )
		{

			move_uploaded_file($this->_fileLocation, "$this->_pathdestination/$this->_filename");
				
		}
		else
		{
			$exception = new Exception( $this->_errorMessage );
			throw $exception;
		}
	}

	/**
	 * Checking file uploaded
	 * @return boolean
	 */
	private function readyToUpload()
	{
		$folderIsWriteAble = is_writable( $this->_pathdestination );
		$tempName = is_uploaded_file($this->_fileLocation);
		if ( $folderIsWriteAble === false OR $tempName === false )
		{
			$this->_errorMessage = "Error: destination folder is ";
			$this->_errorMessage .= "not writable or there is no file uploaded";
			$canUpload = false;
		}
		else if ( $this->_errorCode === 1)
		{
			$maxSize = ini_get( 'upload_max_filesize' );
			$this->_errorMessage = " Error: File is too big ";
			$this->_errorMessage .= " Max file size is $maxSize";
			$canUpload = false;
				
		}
		else if ( $this->_errorCode > 1)
		{
			$this->_errorMessage = "Something went wrong!";
			$this->_errorMessage .= "Error code: $this->_errorCode ";
		}
		else
		{
			$canUpload = true;
		}

		return $canUpload;
	}

}