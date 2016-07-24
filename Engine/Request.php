<?php
namespace API\Engine;
class Request
{
	private $_aRequestHeaders = array();
	private $_aSegments = array();
	private $_aParams = array();
	public function __construct()
	{
		$this->_aRequestHeaders = $this->getHeaders();
		$this->_aSegments = $this->getSegments();
		$this->_getParams();
	}
	public function get($mKey)
	{
		return isset($this->_aParams[$mKey])?$this->_aParams[$mKey]:null;
	}
	public function setParam($mKey, $mValue)
	{
		$this->_aParams[$mKey] = $mValue;
		return $this;
	}
	public function setParams($aParams)
	{
		$this->_aParams = array_merge($this->_aParams,$aParams);
		return $this;
	}
	public function getParams()
	{
		return $this->_aParams;
	}
	public function seg($index)
	{
		return isset($this->_aSegments[$index])?$this->_aSegments[$index]:null;
	}
	public function getSegments()
	{
		$aSegments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
		return $aSegments;
	}
	public function getHeaders()
	{
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', (strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
	
	public function getHeaderVersion()
	{
		$sVersion = isset($this->_aRequestHeaders['version'])?$this->_aRequestHeaders['version']:"";
		return $sVersion;
	}
	protected function _getParams()
	{
		$this->_aParams = array_merge($_GET, $_POST, $_FILES);
		
		$sContent = file_get_contents("php://input");
		$aContentJSON = json_decode($sContent,true);
		if($sContent && $aContentJSON)
		{
			$this->_aParams = array_merge($this->_aParams,$aContentJSON);
		}
		return $this->_aParams;
	}
}