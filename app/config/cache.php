<?php
return array(
'memcache' => array
(
    'driver'             => 'memcache',
    'default_expire'     => 3600,
    'compression'        => FALSE,              // Use Zlib compression 
                                                (can cause issues with integers)
    'servers'            => array
    (
        array
        (
            'host'             => 'localhost',  // Memcache Server
            'port'             => 11211,        // Memcache port number
            'persistent'       => FALSE,        // Persistent connection
        ),
    ),
    'default_expire'     => 3600,
),
'memcachetag' => array
(
    'driver'             => 'memcachetag',
    'default_expire'     => 3600,
    'compression'        => FALSE,              // Use Zlib compression 
                                                (can cause issues with integers)
    'servers'            => array
    (
        array
        (
            'host'             => 'localhost',  // Memcache Server
            'port'             => 11211,        // Memcache port number
            'persistent'       => FALSE,        // Persistent connection
        ),
    ),
    'default_expire'     => 3600,
),);
