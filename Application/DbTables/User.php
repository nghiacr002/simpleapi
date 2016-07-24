<?php
namespace API\Application\DbTables;
use API\Application\Models\UserModel;
class User extends \API\Engine\Database\DbTable
{
	protected $_sTableName = "user";
	protected $_mPrimaryKey = "user_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('username','required'),
			array('email','email')
		),
		'integer' => 'level',
	);
	public function businessValidate(\API\Engine\Database\DbRow $user = null)
	{
		$oUserModel = new UserModel();
		$user_existed = $oUserModel->getOne($user->email,'email');
		if($user_existed && $user_existed->user_id)
		{
			$user->setError("User has already existed");
			return false;
		}
		return true;
	}
	
}