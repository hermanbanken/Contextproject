<?php defined('SYSPATH') or die('No direct access allowed.');

if (Kohana::$environment == Kohana::PRODUCTION)
{
  	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				'hostname'   => 'localhost',
				'database'   => 'cultuur',
				'username'   => 'cultuur',
				'password'   => '',
				'persistent' => TRUE,
			),
			'table_prefix' => 'live_',
			'charset'      => 'utf8',
			'caching'      => TRUE,
			'profiling'    => FALSE,
		),
	);
}
elseif (Kohana::$environment == Kohana::DEVELOPMENT)
{
  	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				'hostname'   => 'localhost',
				'database'   => 'cultuur',
				'username'   => 'cultuur',
				'password'   => '',
				'persistent' => TRUE,
			),
			'table_prefix' => 'dev_',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);
}
else
{
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				'hostname'   => ':hostname',
				'database'   => ':database',
				'username'   => ':username',
				'password'   => ':password',
				'persistent' => FALSE,
			),
			'table_prefix' => 'dev_',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);
}