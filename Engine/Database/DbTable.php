<?php
namespace API\Engine\Database;
class DbTable extends \API\Engine\Object
{
	protected $_sTableName; 
	protected $_mPrimaryKey;
	protected $_oQuery; 
	protected $_aValidateRules;
	
	public function __construct($sTableName = "", $mPrimaryKey = null )
	{
		if(empty($sTableName))
		{
			$sTableName = $this->_sTableName;
		}
		$this->_sTableName = self::getFullTableName($sTableName);
		if($mPrimaryKey)
		{
			$this->_mPrimaryKey = $mPrimaryKey;
		}
	}
	
	public function businessValidate(\API\Engine\Database\DbRow $mData = null)
	{
		return true;
	}
	public function getValidateRules()
	{
		return $this->_aValidateRules;
	}
	public function getPrimaryKey()
	{
		return $this->_mPrimaryKey;
	}
	public function setPrimaryKey($mKey)
	{
		$this->_mPrimaryKey = $mKey;
		return $this;
	}
	public function query()
	{
		if(!$this->_oQuery)
		{
			$this->_oQuery = new \API\Engine\Database\Query();
		}
		return $this->_oQuery;
	}
	public function createRow($data = array())
	{
		$mRow =  new \API\Engine\Database\DbRow($this);
		$mRow->setData($data);
		return $mRow;
	}
	public function getColumns()
	{
		$adapter = $this->getDatabase()->getAdapter(); 
		$results = $adapter->execute("SHOW COLUMNS FROM ".$this->_sTableName);
		
		$columns = array();
		foreach($results as $result)
		{
			$field = $result['Field'];
			unset( $result['Field']);
			$columns[$field] = $result;
		}
		return $columns;
	}
	public function executeQuery(\API\Engine\Database\Query $query)
	{
		list($sSql, $aBindParams) = $query->build();
		return $this->getAdapter()->execute($sSql, $aBindParams);
	}
	public function getTableName()
	{
		return $this->_sTableName;
	}
	public static function getFullTableName($sName)
	{
		$sPrefix = \API\Engine\Application::getInstance()->getConfig('db','prefix');
		return $sPrefix. $sName;
	}
	public function getDatabase()
	{
		return \API\Engine\Database::getInstance();
	}
	public function getAdapter()
	{
		return  \API\Engine\Database::getInstance()->getAdapter();
	}
}