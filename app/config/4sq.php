<?php

if (Kohana::$environment == Kohana::PRODUCTION)
{
  	return array(
		'client' => array(
			'id' => '3O2Z2ECV0LDXKV3A0SR2FCLIPPQRFDY4QOXNKAXWMGB0WSOA',
			'secret' => 'OGA5RRPQTYVKXMMK5YWFL5IYT5T13NVGHXHDMQEE30AMNHDD',
		),
		'v' => '20120521',
		'match' => array(
			"name" => true,
			"distance" => 100,
		)
	);
}
elseif (Kohana::$environment == Kohana::DEVELOPMENT)
{
  	return array(
		'client' => array(
			'id' => '3O2Z2ECV0LDXKV3A0SR2FCLIPPQRFDY4QOXNKAXWMGB0WSOA',
			'secret' => 'OGA5RRPQTYVKXMMK5YWFL5IYT5T13NVGHXHDMQEE30AMNHDD',
		),
		'v' => '20120521',
		'match' => array(
			"name" => true,
			"distance" => 100,
		)
	);
}
elseif (Kohana::$environment == Kohana::STAGING)
{
	return array(
		'client' => array(
			'id' => 'G4B4ZQ0C3QYJRJFPF3WH4TLKSDGXREGWMQCCJV3DNMUGZ0U3',
			'secret' => 'I51O0ICTYPH44S5FBGNDRJYHMPBAILTWNFBCOR0OBIMKAK5O',
		),
		'v' => '20120521',
		'match' => array(
			"name" => true,
			"distance" => 100,
		)
	);
}