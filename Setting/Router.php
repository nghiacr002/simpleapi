<?php
$_ROUTER['default'] = array(
	'system_index' => array(
		'route' => '/',
		'controller' => 'system',
		'action' => 'index',
		'method' => 'GET',
		'params' => array(),
	),
	'system_info' => array(
		'route' => '/system/info', 
		'controller' => 'system',
		'action' => 'info', 
		'method' => 'GET', 
		'params' => array(),
		'auth' => true,
	),
	'system_version' => array(
		'route' => '/system/version',
		'controller' => 'system',
		'action' => 'version',
		'method' => 'GET',
		'params' => array(),
	),
	'user_browse' => array(
		'route' => '/user/browse',
		'controller' => 'user',
		'action' => 'browse',
		'method' => 'GET',
		'params' => array(),
	),
	'user_add' => array(
		'route' => '/user/add',
		'controller' => 'user',
		'action' => 'add',
		'method' => 'POST',
		'params' => array(),
		'auth' => true
	),
	'user_update' => array(
		'route' => '/user/[i:id]/update',
		'controller' => 'user',
		'action' => 'update',
		'method' => 'PUT',
		'params' => array(),
	),
	'user_delete' => array(
		'route' => '/user/[i:id]/delete',
		'controller' => 'user',
		'action' => 'delete',
		'method' => 'GET',
		'params' => array(),
	),
	'user_info' => array(
		'route' => '/user/[i:id]/info',
		'controller' => 'user',
		'action' => 'info',
		'method' => 'GET',
		'params' => array(),
	),
	'user_test' => array(
		'route' => '/user/testmodel',
		'controller' => 'user',
		'action' => 'testModel',
		'method' => 'GET',
		'params' => array(),
	),
	'system_test' => array(
		'route' => '/system/test',
		'controller' => 'system',
		'action' => 'test',
		'method' => 'GET',
		'params' => array(),
	)
);