<?php
namespace API\Library\Database;
interface Adapter
{
	public function connect($configs = array());
	public function disconnect();
	public function execute($query, $bind_params = array());
	public function hasError();
	public function getDriverInfo();
	public function getErrors();
	public function escape($query);
	/*public function delete($conds = array());
	public function update($updates, $conds = array());
	public function replace(); 
	public function query($query);*/
	
}