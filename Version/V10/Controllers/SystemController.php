<?php
namespace API\Version\V10\Controllers;
class SystemController extends \API\Application\Controllers\SystemController
{
	public function __construct()
	{
		$this->_sVersion = "1.0";
	}
	
}