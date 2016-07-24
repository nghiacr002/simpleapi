<?php
namespace API\Library\Cache\Storage;
use API\Engine\Object;
class File extends Object implements \API\Library\Cache\Storage
{
	protected  $_sCachePath = "";
	public function __construct()
	{
		$this->_sCachePath = \API\Engine\Application::getInstance()->getConfig('cache','path');
	}
	protected function _getCacheId($sFileName)
	{
		return md5($sFileName);
	}
	public function get($sFileName)
	{
		$sRealFile = $this->_sCachePath.$this->_getCacheId($sFileName).'.php';
		
		if (file_exists($sRealFile))
		{
			require($sRealFile);
			if(isset($aCacheData))
			{
				if(isset($TTL) && $TTL > 0)
				{
					if($TTL > API_TIME)
					{
						return false;
					}
				}
				return $aCacheData;
			}
			else
			{
				return false;
			}
	
		}
		return false;
	}
	public function set($sFileName, $mContent = array(), $iTimeToLive = 0)
	{
		$sRealFile = $this->_sCachePath . $this->_getCacheId($sFileName).'.php';
		
		if($iTimeToLive > 0)
		{
			$iTime = API_TIME + $iTimeToLive;
		}
		else
		{
			$iTime = -1;
		}
		
		$sContent= "<?php \$aCacheData = ".var_export($mContent, true)."; \$TTL = ".$iTime.";\n?>";
		$oFile = @fopen($sRealFile,'w+');
		@fwrite($oFile, $sContent);
		@fclose($oFile);
		return true;
	
	}
	public function remove($sFileName, $sFolder = "")
	{
		$sRealFile = $this->_sCachePath.$this->_getCacheId($sFileName).'.php';
		if (file_exists($sRealFile))
		{
			@unlink($sRealFile);
		}
		return $this;
	}
	public function clean($sType ="")
	{
		$sFolder = $this->_sCachePath . $sType . API_DS;
		if(is_dir($sFolder))
		{
			$aFiles = (new \API\Engine\File())->scanFolder($sFolder);
			if(count($aFiles))
			{
				foreach($aFiles as $sFileName)
				{
					$this->remove($sFileName);
				}
				
			}
		}
	}
}