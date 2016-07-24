<?php
namespace API\Application\Models;
use API\Application\DbTables\User;
use API\Engine\Application;
class UserModel extends BaseModel
{
	public function __construct()
	{
		$this->_oTable = new User();
		parent::__construct();
	}
	public function getOne($mValue,$mTableKey = null)
	{
		$sCacheName = $this->_oTable->getTableName();
		if(!$mTableKey)
		{
			$mTableKey = $this->_oTable->getPrimaryKey();
		}
		$sCacheName = $sCacheName.serialize($mValue).serialize($mTableKey);
		if($user = $this->cache()->get($sCacheName))
		{
			return $user;
		}
		$user = parent::getOne($mValue,$mTableKey);
		$this->cache()->set($sCacheName,$user,100);
		return $user;
	}
	public function login($sUserName, $sPassword)
	{
		
	}
	public function getHash($sPassword)
	{
		$sRandom = Application::getInstance()->getConfig('security','random');
		$sSalt = md5(uniqid($sRandom, true));
		$sSalt = substr($sSalt,0,5);
		return array($sSalt,sha1($sRandom.$sPassword.$sSalt));
	}
}