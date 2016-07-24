<?php
namespace API\Engine;
class Utils
{
	public static function format_date($iUnixTime, $sFormatTime = null)
	{
		if(!$sFormatTime)
		{
			$sFormatTime = \API\Engine\Application::getInstance()->getConfig('system','format_time');
		}
		return date($sFormatTime,$iUnixTime);
	}
}