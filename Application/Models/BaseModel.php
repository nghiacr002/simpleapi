<?php
namespace API\Application\Models;
use API\Engine\Database\Query;
class BaseModel extends \API\Engine\Object
{
	protected $_oTable;
	protected $_oCache;
	public function __construct()
	{
		if(!$this->_oTable)
		{
			$this->_oTable = new \API\Engine\Database\DbTable();
		}
	}

	public function cache()
	{
        
		if(!$this->_oCache)
		{
			$this->_oCache =(new \API\Engine\Cache())->getStorage();
		}
		return $this->_oCache;
	}
	public function getTable()
	{
		return $this->_oTable;
	}
	public function getOne($mValue, $mTableKey = null)
	{
		if(!$mTableKey){
			$mTableKey = $this->_oTable->getPrimaryKey();
		}
		$query = new Query("select");
		$query->select('*');
		$query->from($this->_oTable->getTableName());
		if(is_array($mTableKey))
		{
			foreach($mTableKey as $index =>$mKey)
			{
				$query->where($mKey,isset($mValue[$index]) ? $mValue[$index] : null);
			}
		}
		else
		{
			$query->where($mTableKey,$mValue);
		}
		
		$result = $this->_oTable->executeQuery($query);
		
		if(!isset($result[0]))
		{
			return null;
		}
		$row = new  \API\Engine\Database\DbRow($this->_oTable); 
		$row->setData($result[0]);
		return $row;
	}
}