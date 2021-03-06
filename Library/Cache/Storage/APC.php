<?php
namespace API\Library\Cache\Storage;
class APC implements \API\Library\Storage
{
	public function set($sCacheName, $mData, $iTimeToLive = 0)
	{
		apc_add($sCacheName, $mData,$iTimeToLive);
		return $this;
	}
	public function get($sCacheName)
	{
		return apc_fetch($sCacheName);
	}
	public function remove($sCacheName)
	{
		apc_delete($sCahename);
	}
	public function clean($sType ="")
	{
		apc_clear_cache($sType);
	}
}