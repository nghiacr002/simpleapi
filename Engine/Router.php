<?php
namespace API\Engine;
class Router
{
	private $_oInstance = null; 
	private $_aConfigs = array();
	public function __construct()
	{	
		$this->init();
	}
	public function init()
	{
		if(!$this->_oInstance)
		{
			$this->_oInstance = new \API\Library\AltoRouter();
			$this->fetchDefaultRouter();
		}
		return $this->_oInstance;
	}
	public function fetchDefaultRouter()
	{
		$sPath = API_PATH_SETTING . 'Router.php';
		include $sPath;
		$this->_aConfigs = $_ROUTER; 
		if(isset($_ROUTER['default']))
		{ 
			foreach($_ROUTER['default'] as $sName => $aRouter)
			{
				$method = isset($aRouter['method'])?$aRouter['method']:"GET";
				$route = isset($aRouter['route'])?$aRouter['route']:"/";
				$target = isset($aRouter['target'])?$aRouter['target']:null;
				$this->_oInstance->map($method, $route, $target,$sName);
			}
		}
	}
	public function getRouter($sName, $sVersion = "default")
	{
		return isset($this->_aConfigs[$sVersion][$sName])?$this->_aConfigs[$sVersion][$sName]: null; 
	}
	public function instance()
	{
		return $this->_oInstance; 
	}
	public function __call($name, $arguments = array())
    {
        if(method_exists($this->_oInstance, $name))
        {
        	$result =  call_user_func_array(array($this->_oInstance, $name), $arguments);
        	return $result;
        }
        return null;
    }
		
}