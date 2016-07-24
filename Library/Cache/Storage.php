<?php
namespace API\Library\Cache;
interface Storage
{
	public function set($sCacheName, $mData, $iTimeToLive = 0);
	public function get($sCacheName);
	public function remove($sCacheName);
	public function clean($sType ="");
}