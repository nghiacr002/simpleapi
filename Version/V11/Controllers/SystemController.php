<?php
namespace API\Version\V11\Controllers;
class SystemController extends \API\Version\V10\Controllers\SystemController
{
	public function __construct()
	{
		$this->_sVersion = '1.1';
	}
	
}