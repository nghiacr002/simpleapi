<?php
	@error_reporting(E_ALL);
	@ini_set('display_errors', 1);
	
	if (version_compare(PHP_VERSION, '5.3.0', '<')) {
		throw new Exception('The Facebook SDK requires PHP version 5.3 or higher.');
	}
	define('SIMPLE_API',true);
	define('API_DS',DIRECTORY_SEPARATOR);
	define('API_PATH',dirname(__FILE__). API_DS);
	define('API_PATH_SETTING',API_PATH . 'Setting'. API_DS);
	define('API_PATH_LIB',API_PATH . 'library'. API_DS);
	
	try
	{
		if(!session_id())
		{
			session_start();	
		}
		require_once API_PATH_SETTING . 'Loader.php';
		$mainApp = new API\Engine\Application($_CONF);
		$mainApp->execute();
	}
	catch(Exception $ex)
	{
		$mDisplay = array(
			'code' => $ex->getCode(),
			'message' => $ex->getMessage(),
		);
		system_display_result($mDisplay);
	}
	
?>