<?php
	return array(
      'native' => array(
	        'name' => 'session_name',
	        'lifetime' => 43200,
	        'encrypted' => FALSE,
	    ),
	    'cookie' => array(
	        'name' => 'cookie_name',
	        'encrypted' => FALSE,
	        'lifetime' => 43200,
			'salt' => 'aoftj&#(^R(_@qgj3og uc 3um30c8*098*()3)*$)(T)',
	    ),
	    'database' => array(
	        'name' => 'cookie_name',
	        'encrypted' => FALSE,
	        'lifetime' => 43200,
	        'group' => 'default',
	        'table' => 'sessions',
	        'columns' => array(
	            'session_id'  => 'session_id',
	            'last_active' => 'last_active',
	            'contents'    => 'contents'
	        ),
	        'gc' => 500,
	    ),
  );