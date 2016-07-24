<?php
namespace API\Engine;
class Authenticate 
{
	protected  $_oMethod;
	protected static $instance;
	public function __construct()
	{
		$this->init();
		self::$instance = $this;
	}
	public function init()
	{
		$sMethod = \API\Engine\Application::getInstance()->getConfig('api','authenticate');
		$sNamespace = "\\API\\Library\\Authenticate\\Method\\";
		$sMethodName = ucfirst($sMethod);
		$sFullClass = $sNamespace . $sMethodName; 
		$this->_oMethod = new $sFullClass(); 
		return $this; 
	}
	public static function getInstance()
	{
		if(!self::$instance)
		{
			$tmp = new Authenticate();
			self::$instance = $tmp;
		}
		return self::$instance;
	}
	public function Auth()
	{
		
	}
	public function getMethod()
	{
		return $this->_oMethod;
	}
	public static function getToken()
	{
		return uniqid(time()).md5(time());
	}
}