<?php
namespace API\Engine;
class Cache 
{
	protected  $_oStorage;
	protected static $instance;
	public function __construct()
	{
		$this->init();
		self::$instance = $this;
	}
	public function init()
	{
		$this->_aConfigs = \API\Engine\Application::getInstance()->getConfig('cache');
		$sNamespace = "\\API\\Library\\Cache\\Storage\\";
		$sAdapter = ucfirst($this->_aConfigs['storage']);
		$sFullClass = $sNamespace . $sAdapter; 
		$this->_oStorage = new $sFullClass(); 
		return $this; 
	}
	public static function getInstance()
	{
		if(!self::$instance)
		{
			$tmp = new Cache();
			self::$instance = $tmp;
		}
		return self::$instance;
	}
	
	public function getStorage()
	{
		return $this->_oStorage;
	}
	
}