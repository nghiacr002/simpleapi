<?php
	$_CONF['db'] = array(
		'host' => 'localhost',
		'name' => 'api',
		'user' => 'root',
		'pwd' => '123456',
		'port' => 3306,
		'prefix' => 'api_',
		'adapter' => 'mysqli',
		'charset' => 'utf8'
	);
	$_CONF['api_versions'] = array(
		'default', '1.1',
	);
	$_CONF['system'] = array(
		'data_response' => 'JSON', 
		'format_time' => 'l jS \of F Y h:i:s A',
		'base_path' => '/api',
		'session_prefix' => 'simpleapi_'
	);
	$_CONF['api'] = array(
		'detect_version' => '/v(.*)/', // detect api version by URI in SEGMENT 0
		'authenticate' => 'basic'
	);
	$_CONF['cookie'] = array(
			'prefix' => 'simpleapi_',
			'expried' => 30,
			'path' => '/',
			'domain' =>'',
	);
	$_CONF['cache'] = array(
			'storage' => 'file',
			'path' => API_CACHE_PATH,
	);
	$_CONF['enviroment'] = 'development';
	$_CONF['security'] = array(
		'random' => 'abqwertyuioplkjhgfdsaxcvbnm876543210'
	);
?>