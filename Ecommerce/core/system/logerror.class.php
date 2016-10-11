<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * Kelas LogError
 * untuk merekam error pada cms pilus
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 *
 */

class LogError 
{

	/**
	 *
	 * @var string
	 */
	private static $_printError = false;

	/**
	 * @method customErrorMessage
	 */
	public static function customErrorMessage()
	{


		echo '<div id="page-wrapper">
				<div class="row">
				<div class="col-lg-12">
				<h1 class="page-header">
				Kesalahan!
				</h1>
				</div>
				<!-- /.col-lg-12 -->
				</div>
				<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p>Terjadi Kesalahan, Segera periksa kesalahan pada error log dan laporkan ke email: webdev@kartatopia.com </p>
				</div>
				</div>';

		exit();

	}

	/**
	 * exception handler
	 * 
	 * @method exceptionHandler
	 * @param string $e
	 */
	public static function exceptionHandler($e)
	{
		self::newMessage($e);
		self::customErrorMessage();
	}

	/**
	 *
	 * errorHandler
	 * 
	 * @method errorHandler
	 * @param integer $nomor
	 * @param string $pesan
	 * @param string $berkas
	 * @param string $baris
	 * @return number
	 */
	public static function errorHandler( $nomor, $pesan, $berkas, $baris )
	{
		$psn = "$pesan di $berkas pada baris $baris";

		if ( ($nomor !== E_NOTICE) && ($nomor < 2048))
		{
			self::errorMessage($psn);
			self::customErrorMessage();
				
		}

		return 0;
	}

	/**
	 * @static method newMessage
	 * @param Exception $exception
	 * @param string $_printError
	 * @param string $clear
	 * @param string $error_file
	 */
	public static function newMessage(Exception $exception, $_printError = false, $clear = false, $error_file = 'logerror.html')
	{

		$message = $exception->getMessage();
		$code = $exception->getCode();
		$file = $exception->getFile();
		$line = $exception->getLine();
		$trace = $exception->getTraceAsString();
		$date = date('M d, Y G:iA');
			
		$log_message = "<h3>Exception information:</h3>\n
		<p><strong>Date:</strong> {$date}</p>\n
		<p><strong>Message:</strong> {$message}</p>\n
		<p><strong>Code:</strong> {$code}</p>\n
		<p><strong>File:</strong> {$file}</p>\n
		<p><strong>Line:</strong> {$line}</p>\n
		<h3>Stack trace:</h3>\n
		<pre>{$trace}</pre>\n
		<hr />\n";
			
		if( is_file($error_file) === false ) {
			file_put_contents($error_file, '');
		}
			
		if( $clear ) {
			$content = '';
		} else {
			$content = file_get_contents($error_file);
		}
			
		file_put_contents($error_file, $log_message . $content);

		if($_printError == true){
			echo $log_message;

		 exit();
		 
		}
	}

	/**
	 * @static method errorMessage
	 * @param string $error
	 * @param string $_printError
	 * @param string $error_file
	 */
	public static function errorMessage($error, $_printError = false, $error_file = 'errorlog.html')
	{

		$date = date('M d, Y G:iA');
		$log_message = "<p>Error on $date - $error</p>";
			
		if( is_file($error_file) === false ) {
			file_put_contents($error_file, '');
		}
			
		$content = file_get_contents($error_file);
		file_put_contents($error_file, $log_message . $content);

		if($_printError == true){
			echo $log_message;
			
			exit();
			
		}
		
	}
	
}