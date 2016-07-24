<?php
namespace API\Application\Controllers;
use API\Application\Models\UserModel;
class UserController extends BaseController
{
	public function TestAction()
	{
			
	}
	public function BrowseAction()
	{
		$oUser = new UserModel();
		$oTable = $oUser->getTable();
		$columns = $oTable->getColumns();
		return $columns;
	}
	public function AddAction()
	{
		$oUser = new UserModel();
		$user = $oUser->getTable()->createRow();
		$user->username = $this->request()->get('username');
		$sPassword = $this->request()->get('password');
		$bHashSubmitPassword = true;
		if(!$password)
		{
			$password = substr(md5(time()),0,5);
			$bHashSubmitPassword = false;
		}
		list($hash, $password) = $oUser->getHash($password);
		$user->password = $password;
		$user->hash = $hash;
		$user->email =  $this->request()->get('email');
		if($user->isValid())
		{
			$user_id = $user->save();
			if($user_id)
			{
				$user->user_id = $user_id; 
			}
			return $user->toArray();
		}
		else
		{
			return array(
				'code' => HTTP_CODE_BAD_REQUEST,
				'message' => "Invalid input data: ". implode(',',$user->getErrors())
			);
		}
	}
	public function UpdateAction()
	{
		$oUser = new UserModel();
		$user_id = $this->request()->get('id');
		$email = $this->request()->get('email');
		$user = $oUser->getOne($user_id);
		if($user)
		{
			$user->email = $email; 
			$v = $user->update();
		}
		
	}
	public function DeleteAction()
	{
		$oUser = new UserModel();
		$user_id = $this->request()->get('id');
		$email = $this->request()->get('email');
		
		$user = $oUser->getOne($user_id);
		if($user)
		{
			$user->email = $email;
			$user->delete();
			
		}
	}
	public function InfoAction()
	{
		$oUser = new UserModel();
		$user_id = $this->request()->get('id');
		$user = $oUser->getOne($user_id);
		if($user)
		{
			return $user->toArray();
		}
		else
		{
			return array(
				"message" => "User not found",
				"code" => HTTP_CODE_NOT_FOUND
			);
		}
	}
	public function TestModelAction()
	{
		$oUser = \API\Application\Models\UserModel::factory('user','1.1','model');
		
	}
}