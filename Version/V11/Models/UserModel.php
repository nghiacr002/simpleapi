<?php
namespace API\Version\V11\Models;
use API\Version\V11\DbTables\User;
class UserModel extends \API\Application\Models\UserModel
{
	public function __construct()
	{
		$this->_oTable = new User();
	}
	public function getHref($aParams = array())
	{
		return "http://dac.com";
	}
}