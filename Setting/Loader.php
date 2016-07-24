<?php
use API\Engine;
use API\Library;
use API\Controller;
use API\Model;
require 'Constant.php';
require 'Config.php';
spl_autoload_extensions(".php");
spl_autoload_register('system_autoloader');

function d($mInfo, $bVarDump = false)
{
    $bCliOrAjax = (PHP_SAPI == 'cli');
    (!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
    ($bVarDump ? var_dump($mInfo) : print_r($mInfo));
    (!$bCliOrAjax ? print '</pre>' : false);
}
function system_autoloader($class)
{
	$prefix = 'API\\';
    // base directory for the namespace prefix
    $base_dir = API_PATH;
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    // get the relative class name
    $relative_class = substr($class, $len);
    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = API_PATH . str_replace('\\', '/', $relative_class) . '.php';
	
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
}
function system_include_path($sPath)
{
	if(file_exists($sPath))
	{
		@set_include_path($sPath);
		@ini_set('include_path', $sPath);
		require_once $sPath;
		return true;
	}
	return false;
	
}
function system_load_version($sName = "", $sType = "controller", $sVersion = "default")
{
	$sPruralType = ucfirst($sType)."s"; 
	if($sVersion == "default")
	{
		$sPath = API_PATH. "Application" . API_DS; 
		$sNamespace = "\\API\\Application\\" .$sPruralType ."\\";
	}
	else 
	{
		$sPath = API_PATH. "Version". API_DS . "V".str_replace(".","",$sVersion) . API_DS;
		$sNamespace = "\\API\\Version\\". "V".str_replace(".","",$sVersion) ."\\". $sPruralType. "\\";
	}
	$sFileName = $sPath .$sPruralType. API_DS . ucfirst($sName).ucfirst($sType) .".php";
	
	if(file_exists($sFileName))
	{
		require_once $sFileName;
		$sClassName = ucfirst($sName) . ucfirst($sType);
		$sFullClassName = $sNamespace. $sClassName;
		$oClass = new $sFullClassName();
		return $oClass;  
	}elseif($sVersion !="default"){
		
		return system_load_version($sName,$sType,"default");
	}
	return false;
}
function system_display_result($mResult = array())
{
	$oResponse = new \API\Engine\Response();
	if(isset($mResult['code']))
	{
		$oResponse->setCode($mResult['code']);
		unset($mResult['code']);
	}
	else
	{
		$oResponse->setCode(HTTP_CODE_OK);
	}
	if(isset($mResult['message']))
	{
		$oResponse->setMessage($mResult['message']);
	}
	if(isset($mResult['content_type']))
	{
		$oResponse->setContentType($mResult['content_type']);
	}
	$oResponse->setParams($mResult); 
	$oResponse->display();
}
function array_to_xml(array $arr, SimpleXMLElement $xml)
{
	foreach ($arr as $k => $v) {
		is_array($v)
		? array_to_xml($v, $xml->addChild($k))
		: $xml->addChild($k, $v);
	}
	return $xml;
}