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
            'host'    => getenv('DB_HOST'),
            'name'    => getenv('DB_DATABASE'),
            'user'    => getenv('DB_USERNAME'),
            'pass'    => getenv('DB_PASSWORD'),
            'port'    => getenv('DB_PORT'),
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
