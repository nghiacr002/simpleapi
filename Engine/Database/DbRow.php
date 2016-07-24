<?php
namespace API\Engine\Database;
class DbRow extends \API\Engine\Object
{
	protected $_oTable;
	protected $_oValidator;
	protected $_aErrors;
	public function __construct($mTableName = "")
	{
		if(is_object($mTableName))
		{
			$this->_oTable = $mTableName;
		}
		else
		{
			$this->_oTable = new \API\Engine\Database\DbTable($mTableName);
		}
	}
	public function getTable()
	{
		return $this->_oTable;
	}
	public function setData($data)
	{
		$this->_aData = $data;
		return $this;
	}
	public function getErrors()
	{
		return $this->_aErrors;
	}
	public function setError($sError)
	{
		$this->_aErrors[] = $sError;
		return $this;
	}
	public function validator()
	{
		if(!$this->_oValidator)
		{
			$this->_oValidator = new \API\Engine\Validator($this->_aData);
		}
		return $this->_oValidator;
	}
	public function isValid()
	{
		$this->_aErrors = array();
		$aValidRules = $this->_oTable->getValidateRules();
		$this->validator()->rules($aValidRules);
		$bValidate = $this->_oValidator->validate(); 
		if($bValidate)
		{
			$bValidate = $this->_oTable->businessValidate($this);
		}
		if($bValidate)
		{
			return true;
		}
		foreach($this->_oValidator->errors() as $error)
		{
			$this->_aErrors[] = implode(',',$error);
		}
		return false;
	}
	public function save()
	{
		$query = new Query();
		$query->setCommand("insert");
		$query->setTableData($this->_oTable->getTableName(), $this->_aData);
		return $this->_oTable->executeQuery($query);
	}
	public function update()
	{
		$query = new Query();
		$query->setCommand("update");
		$query->setTableData($this->_oTable->getTableName(), $this->_aData);
		$this->_buildWherePrimary($query);
		return $this->_oTable->executeQuery($query);
	}
	public function delete()
	{
		$query = new Query();
		$query->setCommand("delete");
		$this->_buildWherePrimary($query);
		$query->from($this->_oTable->getTableName());
		return $this->_oTable->executeQuery($query);
	}
	protected function _buildWherePrimary($query)
	{
		$mPrimaryKey = $this->_oTable->getPrimaryKey();
		if(!is_array($mPrimaryKey))
		{
			$mPrimaryKey = array($mPrimaryKey);
		}
		foreach($mPrimaryKey as $sPrimaryKey)
		{
			$query->where($sPrimaryKey, $this->{$sPrimaryKey});
		}
	}
	public function toArray()
	{
		return $this->_aData;
	}
}