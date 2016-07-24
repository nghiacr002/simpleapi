<?php
namespace API\Application\Controllers;
class BaseController extends \API\Engine\Object
{
	protected $_sVersion = "default";
	
	public function notfoundAction()
	{
		
		return array(
			'code' => HTTP_CODE_NOT_FOUND,
			'message' => 'API NOT FOUND'
		);
	}
	public function versionAction()
	{
		return array(
			'version' => $this->_sVersion,
		);
	}
	public function request()
	{
		$request =  \API\Engine\Application::getInstance()->request;
		return ($request);
	}
}