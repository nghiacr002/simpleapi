<?php
namespace API\Application\Controllers;
class SystemController extends BaseController
{
	public function InfoAction()
	{
		$oUtils = new \API\Engine\Utils();
		$app = \API\Engine\Application::getInstance();
		return array(
			'name' => $app->name,
			'version' => $app->version,
			'time_zone' => date_default_timezone_get(),
			'current_time_unix' => API_TIME,
			'current_time_view' => $oUtils->format_date(API_TIME),
		);
	}
	public function TestAction()
	{
		$queryMain = new \API\Engine\Database\Query("select");
		$querySub = new \API\Engine\Database\Query("select");
		$querySub->select('user_id');
		$querySub->where('email',array('lorem@abc.com','abc.com'),'IN');
		$querySub->from('api_user',"a1");
		
		$queryMain->where('a2.user_id',$querySub,"IN");
		$queryMain->from("api_user","a2");
		$queryMain->join("api_user", "a1.user_id = a2.user_id","a2");
		//$sql = "select * from api_user where user_id in (select user_id from api_user where user_id = ?)";
		$db = \API\Engine\Application::getInstance()->db;
		echo $queryMain->getRawSql();
		$result = $db->getAdapter()->execute($queryMain);
		
		d($result);die();
	}
}