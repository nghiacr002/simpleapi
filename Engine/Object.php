<?php
namespace API\Engine;
class Object
{
	protected $_aData;
	protected $app; 
	public function __construct()
	{
		$this->app = \API\Engine\Application::getInstance();
	}
	public function __set($sName, $mValue)
	{
		$this->_aData[$sName] = $mValue;
		return $this;
	}
	public function app()
	{
		return $this->app;
	}
	public function __get($sName)
	{
		if (array_key_exists($sName, $this->_aData))
		{
			return $this->_aData[$sName];
		}
		return null;
	}
	public function __call($sName, $arguments = array())
	{
		if(method_exists($this, $sName))
		{
			call_user_func_array(array($this,$sName), $arguments);
		}
		return null;
	}
	public static function __set_state($array)
	{
		$obj = new static();
		foreach($array as $key => $value)
		{
			$obj->{$key} = $value;
		}
		return $obj;
	}
	public function getProps()
	{
		return $this->_aData;
	}
	public static function factory($sName, $sVersion = "default", $sType = "controller")
	{
		return system_load_version($sName, $sType, $sVersion);
	}
}