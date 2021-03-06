<?php
namespace API\Engine;
class Database 
{
	protected  $_oAdapter; 
	protected $_aConfigs;
	protected static $instance;
	public function __construct()
	{
		$this->init();
		self::$instance = $this;
	}
	public function init()
	{
		$this->_aConfigs = \API\Engine\Application::getInstance()->getConfig('db');
		$sNamespace = "\\API\\Library\\Database\\Adapter\\";
		$sAdapter = ucfirst($this->_aConfigs['adapter']);
		$sFullClass = $sNamespace . $sAdapter; 
		$this->_oAdapter = new $sFullClass; 
		if($this->_oAdapter)
		{
			$this->_oAdapter->connect($this->_aConfigs);
		}
		return $this; 
	}
	public static function getInstance()
	{
		if(!self::$instance)
		{
			$db = new Database();
			self::$instance = $db;
		}
		return self::$instance;
	}
	public function getInfo()
	{
		return $this->_oAdapter->getDriverInfo();
	}
	public function getAdapter()
	{
		return $this->_oAdapter;
	}
}