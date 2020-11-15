<?php
return [
    'paths'         => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments'  => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'production',
        'production'              => [
            'adapter' => 'mysql',
            'host'    => 'mysql',
            'name'    => 'geo',
            'user'    => 'geo',
            'pass'    => 'geo',
            'port'    => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
